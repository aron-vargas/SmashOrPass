<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImageSearchService {
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $openaiApiKey
    ) {
    }

    /**
     * Generate or find an image for a person using OpenAI Images API.
     * Returns an image URL or data URL (base64) or null on failure.
     */
    public function findImageUrl(string $query): ?string
    {
        // Build a safe prompt for generating a headshot-like image
        $prompt = sprintf('Headshot portrait of %s, medium close-up, recent, flattering', $query);

        // 1) First, try Wikimedia Commons (no API key required)
        try
        {
            $resp = $this->httpClient->request('GET', 'https://commons.wikimedia.org/w/api.php', [
                'query' => [
                    'action' => 'query',
                    'generator' => 'search',
                    'gsrsearch' => $query,
                    'gsrnamespace' => 6, // File namespace (images)
                    'gsrlimit' => 10,
                    'prop' => 'imageinfo',
                    'iiprop' => 'url',
                    'format' => 'json',
                ],
                'timeout' => 15,
            ]);

            $wm = $resp->toArray(false);
            if (!empty($wm['query']['pages']) && is_array($wm['query']['pages']))
            {
                $images = [];
                foreach ($wm['query']['pages'] as $page)
                {
                    if (!empty($page['imageinfo'][0]['url']))
                    {
                        $images[] = $page['imageinfo'][0]['url'];
                    }
                }

                if (!empty($images))
                {
                    // randomize among results
                    return $images[array_rand($images)];
                }
            }
        }
        catch (\Exception $e)
        {
            // fall through to OpenAI generation
        }

        // 3) Fall back to OpenAI image generation (if available)
        try
        {
            $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/images/generations', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->openaiApiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'prompt' => $prompt,
                    'n' => 1,
                    'size' => '1024x1024',
                ],
                'timeout' => 30,
            ]);

            $data = $response->toArray(false);

            if (!empty($data['data'][0]['url']))
            {
                return $data['data'][0]['url'];
            }

            if (!empty($data['data'][0]['b64_json']))
            {
                $b64 = $data['data'][0]['b64_json'];
                return 'data:image/png;base64,' . $b64;
            }

            return null;
        }
        catch (\Exception $e)
        {
            return null;
        }
    }
}
