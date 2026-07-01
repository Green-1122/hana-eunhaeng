# Contributing to Hana-Eunhaeng

We love your input! We want to make contributing to Hana-Eunhaeng as easy and transparent as possible.

## Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, check the issue list as you might find out that you don't need to create one. When you are creating a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps which reproduce the problem**
- **Provide specific examples to demonstrate the steps**
- **Describe the behavior you observed after following the steps**
- **Explain which behavior you expected to see instead and why**
- **Include screenshots and animated GIFs if possible**
- **Include your environment details** (OS, PHP version, MySQL version, etc.)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, include:

- **Use a clear and descriptive title**
- **Provide a step-by-step description of the suggested enhancement**
- **Provide specific examples to demonstrate the steps**
- **Describe the current behavior and the expected behavior**
- **Explain why this enhancement would be useful**

### Pull Requests

- Fill in the required template
- Follow the PHP coding standards (PSR-12)
- Include appropriate test cases
- Update documentation if needed
- End all files with a newline

## Development Setup

```bash
# Clone the repository
git clone https://github.com/Green-1122/hana-eunhaeng.git
cd hana-eunhaeng

# Create a feature branch
git checkout -b feature/your-feature-name

# Make your changes
# Test your changes

# Commit your changes
git commit -m "feat: description of your changes"

# Push to your fork
git push origin feature/your-feature-name

# Create a pull request
```

## Coding Standards

### PHP Code Style

- Follow PSR-12 coding standards
- Use 4 spaces for indentation
- Use single quotes for strings unless interpolation is needed
- Include type hints for all functions
- Document public methods with PHPDoc comments

```php
/**
 * Description of what this method does
 *
 * @param string $name The user's name
 * @param int $age The user's age
 * @return bool True on success, false on failure
 */
public function example(string $name, int $age): bool
{
    // Implementation
}
```

### Commit Messages

- Use the present tense ("add feature" not "added feature")
- Use the imperative mood ("move cursor to..." not "moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests liberally after the first line

Examples:
- `feat: add two-factor authentication`
- `fix: resolve account balance calculation bug`
- `docs: update installation guide`
- `refactor: simplify transaction processing`

### Commit Types

- `feat`: A new feature
- `fix`: A bug fix
- `docs`: Documentation only changes
- `style`: Changes that don't affect code meaning
- `refactor`: Code change that neither fixes a bug nor adds a feature
- `perf`: Code change that improves performance
- `test`: Adding missing tests or correcting existing tests
- `chore`: Changes to build process, dependencies, etc.

## Testing

- Write tests for new functionality
- Ensure all tests pass before submitting a pull request
- Test in multiple browsers if UI changes
- Test on mobile devices if responsive design is affected

## Documentation

- Update README.md if you add new features
- Update relevant documentation files
- Include inline comments for complex logic
- Add examples for new features or APIs

## Security

- **Do not** commit sensitive information (passwords, API keys, etc.)
- Report security vulnerabilities privately to security@hana-eunhaeng.local
- Do not open GitHub issues for security vulnerabilities

## Review Process

Pull requests are reviewed by maintainers. We'll provide feedback or merge your changes. All contributions will be reviewed for:

- Code quality
- Security implications
- Test coverage
- Documentation completeness
- Backward compatibility

## License

By contributing to Hana-Eunhaeng, you agree that your contributions will be licensed under the MIT License.

## Questions?

Feel free to open an issue with the `question` label or contact the maintainers.

Thank you for contributing! 🎉
