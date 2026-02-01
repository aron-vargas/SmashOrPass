<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Config\GenderType;

class CandidateResearchService {
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $openaiApiKey,
    ) {
    }

    /**
     * Research a candidate using AI and return structured data
     */
    public function researchCandidate(string $candidateName): array
    {
        $systemPrompt = <<<'PROMPT'
You are a research assistant. When given a person's name, research and provide biographical information in JSON format.
Return ONLY valid JSON with these fields (use null for unknown values):
{
  "Name": "full name",
  "Birthdate": "YYYY-MM-DD or null",
  "Gender": "Male|Female|Transgender|Undecided|Other",
  "Height": "height description or null",
  "Weight": "weight description or null",
  "HomeTown": "hometown or null",
  "Married": true/false,
  "Income": "income description or null",
  "PoliticalAffiliation": "Undecided|Republican|Democrat|Independant or null",
  "Bio": "brief biography",
  "Interests": "comma-separated interests or null",
  "Lifestyle": "lifestyle description or null",
  "AdditionalInformation": "any other relevant info or null",
  "ImgUrl": "URL to a headshot image or null"
}
PROMPT;

        try
        {
            $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->openaiApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => "Research this person: {$candidateName}"],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                ],
                'timeout' => 30,
            ]);

            $data = $response->toArray();

            if (!isset($data['choices'][0]['message']['content']))
            {
                throw new \RuntimeException('Invalid API response');
            }

            $content = $data['choices'][0]['message']['content'];
            $candidateData = json_decode($content, associative: true);

            if (!is_array($candidateData))
            {
                throw new \RuntimeException('Failed to parse AI response as JSON');
            }

            return $this->normalizeCandidateData($candidateData);
        }
        catch (\Exception $e)
        {
            return [
                'error' => 'Failed to research candidate: ' . $e->getMessage(),
                'Name' => $candidateName,
            ];
        }
    }

    /**
     * Normalize and validate candidate data from AI response
     */
    private function normalizeCandidateData(array $data): array
    {
        $normalized = [];

        // Name
        $normalized['Name'] = $data['Name'] ?? null;

        // Gender - validate enum
        if (isset($data['Gender']))
        {
            try
            {
                GenderType::from($data['Gender']);
                $normalized['Gender'] = $data['Gender'];
            }
            catch (\ValueError)
            {
                $normalized['Gender'] = null;
            }
        }

        // Birthdate - parse date
        if (isset($data['Birthdate']) && $data['Birthdate'])
        {
            try
            {
                $date = new \DateTime($data['Birthdate']);
                $normalized['Birthdate'] = $date->format('Y-m-d');
            }
            catch (\Exception)
            {
                $normalized['Birthdate'] = null;
            }
        }

        // Simple string fields
        foreach (['Height', 'Weight', 'HomeTown', 'Income', 'Bio', 'Interests', 'Lifestyle', 'AdditionalInformation'] as $field)
        {
            $normalized[$field] = $data[$field] ?? null;
        }

        // Boolean field
        if (isset($data['Married']))
        {
            $normalized['Married'] = (bool) $data['Married'];
        }

        // PoliticalAffiliation - validate choices
        if (isset($data['PoliticalAffiliation']))
        {
            $valid = ['Undecided', 'Republican', 'Democrat', 'Independant'];
            $normalized['PoliticalAffiliation'] = in_array($data['PoliticalAffiliation'], $valid)
                ? $data['PoliticalAffiliation']
                : null;
        }

        // Image URL - accept multiple possible field names from the AI and validate
        $imgKeys = ['ImgUrl', 'imageUrl', 'imgUrl', 'imgageUrl'];
        $foundImg = null;
        foreach ($imgKeys as $k)
        {
            if (!empty($data[$k]))
            {
                $foundImg = $data[$k];
                break;
            }
        }
        $normalized['ImgUrl'] = $foundImg && filter_var($foundImg, FILTER_VALIDATE_URL)
            ? $foundImg
            : null;

        return $normalized;
    }
}
