# SmashOrPass - AI Copilot Instructions

## Project Overview

**SmashOrPass** is a Symfony 7.4-based PHP web application with a PostgreSQL backend (Doctrine ORM). It's a rating/voting platform where users vote on candidates using a binary "Smash or Pass" decision.

**Key Tech Stack:**
- **Framework:** Symfony 7.4 with MicroKernelTrait
- **Database:** Doctrine ORM with migrations (PostgreSQL)
- **Frontend:** Twig templates + Stimulus JS + UX Turbo (SPA-like interactions)
- **Testing:** PHPUnit with failOnDeprecation/Warning/Notice enabled

## Architecture & Core Concepts

### Entity Model
The app uses 5 core entities with specific naming conventions (PascalCase property names, not snake_case):

- **User**: Profile with FirstName, LastName, Email, NickName, Password, Gender (enum)
- **Candidate**: The subject being rated (Name, Bio, Birthdate, Height, Weight, HomeTown, Married, Income, PoliticalAffiliation, Interests, Lifestyle, AdditionalInformation)
- **Category**: Tags/classifications for candidates (many-to-many with Candidate)
- **UserVote**: Junction entity linking User→Candidate with boolean `Smash` decision and timestamps (CreatedOn, ModifiedOn)
- **Gender**: Enum config in `src/Config/GenderType.php` (Male, Female, Trans, Undecided, Other)

**Important Naming Pattern:** Properties use PascalCase (e.g., `$FirstName`, `$UserId`, `$CandidateId`), and relationship fields use Entity names (e.g., `$User` in UserVote points to User entity).

### Relationship Flow
```
User --< UserVote >-- Candidate
        ↑ timestamp & Smash bool

Candidate >-- Category (many-to-many)
```

### Key Files by Concern
- **Entities**: [src/Entity/](src/Entity/) - Doctrine mapping with attributes (not XML/YAML)
- **Repositories**: [src/Repository/](src/Repository/) - Auto-wired, accessed via dependency injection
- **Controllers**: [src/Controller/](src/Controller/) - Attribute-based routing (no manual route config needed)
- **Service Config**: [config/services.yaml](config/services.yaml) - Autowiring enabled for App\ namespace
- **Database**: [config/packages/doctrine.yaml](config/packages/doctrine.yaml) - Attribute mapping, underscore naming strategy

## Developer Workflows

### Setup & Running
```bash
# Install dependencies
composer install

# Run migrations (creates schema)
bin/console doctrine:migrations:migrate

# Start dev server
symfony serve          # or: php -S localhost:8000 -t public/

# Run tests
bin/phpunit            # or: ./bin/phpunit
```

### Adding Features
1. **New Entity**: Create in `src/Entity/`, use Doctrine attributes, add getter/setter
2. **Database Change**:
   - Modify entity → run `bin/console doctrine:migrations:diff` → review `.php` in [migrations/](migrations/) → migrate
3. **New Endpoint**: Create method in Controller with `#[Route('/path', 'route_name')]` attribute
4. **Service/Helper**: Define class in `src/` (autowired automatically via `App\` namespace)

### Debug & Inspection
```bash
# List all routes
bin/console debug:router

# SQL queries (in dev)
bin/console doctrine:query:sql "SELECT * FROM user_vote"

# Entity schema validation
bin/console doctrine:schema:validate
```

## Code Conventions

### Property Access Pattern
- Use **attributes** for Doctrine mapping (not XML/YAML)
- PascalCase for all entity properties (reflect DB column naming)
- Type-hint relationships: `?User $User`, `Collection<int, UserVote>`
- Always initialize Collections in `__construct()`: `$this->userVotes = new ArrayCollection()`

### Relationship Fields Naming
- **Foreign keys in entities use Entity class names**: `User $User`, `Candidate $Candidate` (not `user_id`, `candidate_id`)
- **Collections are plural of entity**: `$userVotes`, `$categories` (lowercase plural, even though properties are usually PascalCase elsewhere)

### Service Injection Pattern
```php
class SomeController {
    public function __construct(
        private UserRepository $userRepo,
        private CandidateRepository $candidateRepo
    ) {}
}
```
- Constructor injection via `#[AutowireAttribute]` is implicit for `Repository` classes and services in `src/`

### Timestamps
- UserVote enforces `CreatedOn` and `ModifiedOn` (DateTime objects, not strings)
- Use `new \DateTime()` or doctrine lifecycle callbacks to auto-set

### Environment & Configuration
- `.env` for defaults, `.env.local` for overrides (Git-ignored)
- `.env.test` for test database/settings
- APP_ENV: dev/test/prod controls bundle loading and caching behavior

## Testing

- Tests live in [tests/](tests/), PHPUnit configured in [phpunit.dist.xml](phpunit.dist.xml)
- Bootstrap via [tests/bootstrap.php](tests/bootstrap.php) for kernel/DB setup
- Strict: failOnDeprecation=true, failOnNotice=true, failOnWarning=true
- Test DB: uses `_test` suffix on DB name via `DBNameSuffix` config

## Frontend Integration

- Twig templates in [templates/](templates/) reference Stimulus controllers in [assets/controllers/](assets/controllers/)
- Turbo integration enables SPA-like navigation without full page reloads
- JavaScript imports via importmap ([importmap.php](importmap.php))
- Asset compilation auto-handled by AssetMapper

## AI Agent Integration

### Candidate Research Service
- **Service**: [src/Service/CandidateResearchService.php](src/Service/CandidateResearchService.php) uses OpenAI API to research candidates
- **Controller Endpoint**: `POST /candidate/search` accepts candidate name, returns structured biographical data
- **Stimulus Controller**: [assets/controllers/candidate-research_controller.js](assets/controllers/candidate-research_controller.js) handles form autofill
- **API Key**: Set `OPENAI_API_KEY` in `.env` (uses gpt-3.5-turbo by default)

### Usage
- On the candidate form, enter a name and click "Search & Auto-fill"
- AI agent researches the candidate and populates form fields (Bio, Interests, Lifestyle, etc.)
- Supports keyboard shortcut: press Enter to search
- Returns normalized data matching Candidate entity fields and enum constraints

## Common Pitfalls to Avoid

1. **Don't use snake_case property names** in entities; use PascalCase to match the established pattern
2. **Don't forget to initialize Collections** in entity constructors
3. **Doctrine attribute mapping is the standard**; don't create XML/YAML mappings
4. **Repository methods should be auto-wired**, not fetched via EntityManager directly
5. **MicroKernelTrait** handles routing/bundle config; no need to override configure() methods
6. **Strict test mode**: even deprecation warnings fail tests; avoid deprecated Symfony patterns
