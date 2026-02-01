import { Controller } from '@hotwired/stimulus';

export default class extends Controller
{
    static targets = [
        'searchInput',
        'searchButton',
        'loadingMessage'
    ];

    connect()
    {
        console.log('CandidateResearch controller connected', this.element);
    }

    searchOnEnter(event)
    {
        if (event.key === 'Enter')
        {
            event.preventDefault();
            this.search();
        }
    }

    async search()
    {
        console.log('Search triggered');
        const name = this.searchInputTarget.value.trim();

        if (!name)
        {
            alert('Please enter a candidate name');
            return;
        }

        this.searchButtonTarget.disabled = true;
        this.loadingMessageTarget.classList.remove('d-none');

        try
        {
            const searchUrl = this.element.dataset.searchUrl;
            console.log('Fetching from:', searchUrl);

            const response = await fetch(searchUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ name }),
            });

            console.log('Response status:', response.status);

            if (!response.ok)
            {
                const error = await response.json();
                throw new Error(error.error || 'Search failed');
            }

            const data = await response.json();
            console.log('Received data:', data);

            if (data.error)
            {
                alert('Error: ' + data.error);
                return;
            }

            this.populateForm(data);
            alert('Form populated successfully!');
        } catch (error)
        {
            alert('Error searching candidate: ' + error.message);
            console.error('Search error:', error);
        } finally
        {
            this.searchButtonTarget.disabled = false;
            this.loadingMessageTarget.classList.add('d-none');
        }
    }

    populateForm(data)
    {
        const form = this.element.closest('form') || document.querySelector('form');

        const fields = {
            'Name': 'input[id*="Name"]',
            'ImgUrl': 'input[id*="ImgUrl"]',
            'Birthdate': 'input[id*="Birthdate"]',
            'Gender': 'select[id*="Gender"]',
            'Height': 'input[id*="Height"]',
            'Weight': 'input[id*="Weight"]',
            'HomeTown': 'input[id*="HomeTown"]',
            'Married': 'select[id*="Married"]',
            'Income': 'input[id*="Income"]',
            'PoliticalAffiliation': 'select[id*="PoliticalAffiliation"]',
            'Bio': 'textarea[id*="Bio"]',
            'Interests': 'textarea[id*="Interests"]',
            'Lifestyle': 'textarea[id*="Lifestyle"]',
            'AdditionalInformation': 'textarea[id*="AdditionalInformation"]'
        };

        for (const [key, selector] of Object.entries(fields))
        {
            if (data[key] !== null && data[key] !== undefined)
            {
                const element = form.querySelector(selector);
                if (element)
                {
                    if (key === 'Married')
                    {
                        element.value = data[key] ? '1' : '0';
                    } else
                    {
                        element.value = data[key];
                    }
                    console.log(`Populated ${key}:`, data[key]);
                }
            }
        }
    }
}
