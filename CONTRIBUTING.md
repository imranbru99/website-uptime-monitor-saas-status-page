# Contributing to Website Uptime Monitor

Thank you for contributing to the **Website Uptime Monitor** SaaS platform!

**Lead Architect & Developer:** Imran Ahmed  
**Email:** me@imrandev.bd  
**Official Website:** [https://imrandev.bd](https://imrandev.bd)  

---

## 1. Mandatory Git Author & Committer Rules

To preserve documentation integrity and licensing compliance, all Git commits must strictly use the following author and committer configuration:

```bash
git config --global user.name "Imran Ahmed"
git config --global user.email "me@imrandev.bd"
```

Verify your configuration before committing:
```bash
git config user.name
git config user.email
```

Expected output:
```
Imran Ahmed
me@imrandev.bd
```

Never commit with generic agent identities or mismatched email addresses.

---

## 2. Coding Standards & Conventions

1. **PHP Version**: Target PHP 8.3 and PHP 8.5+.
2. **Framework Guidelines**: Follow the conventions established by Laravel 13 and Filament v5.
3. **Pint Code Formatting**:
   Before finalizing changes, format all modified PHP files using Laravel Pint:
   ```bash
   vendor/bin/pint --dirty --format agent
   ```
4. **Strict Typing**:
   - Always declare explicit return types and parameter type hints.
   - Use PHP 8 constructor property promotion.
   - Use curly braces for all control structures.

---

## 3. Testing Requirements

- Any pull request modifying business logic, models, services, or APIs must include corresponding automated tests.
- Run the test suite before submitting:
  ```bash
  php artisan test
  ```
- Ensure all tests pass with zero failures and zero warnings.

---

## 4. Submitting Pull Requests

1. Fork the repository and create your branch from `main`:
   ```bash
   git checkout -b feature/amazing-feature
   ```
2. Write clean code and verify tests.
3. Commit your changes with clear, descriptive commit messages under `Imran Ahmed <me@imrandev.bd>`.
4. Push to your branch and open a Pull Request.

