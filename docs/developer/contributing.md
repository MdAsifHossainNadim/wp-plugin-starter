# Contributing to WP Plugin Starter

> **Documentation Version**: This documentation is based on Dokan Kits version 3.0.0. Features and functionality may vary in other versions.

Thank you for your interest in contributing to Dokan Kits! This guide will help you set up your development environment and understand the contribution workflow.

## 🚦 Code of Conduct

By participating in this project, you agree to abide by our Code of Conduct. We expect all contributors to be respectful, considerate, and collaborative.

## 🌟 How Can I Contribute?

There are many ways to contribute to Dokan Kits:

- **Bug Reports**: Help us identify and fix issues
- **Feature Requests**: Suggest improvements or new features
- **Documentation**: Improve or expand our documentation
- **Code Contributions**: Fix bugs or add new features
- **Testing**: Test the plugin and report issues
- **Translations**: Help translate the plugin into other languages

## 🛠️ Development Environment Setup

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.6 or higher
- WordPress 5.8 or higher
- Node.js 14 or higher
- npm 7 or higher
- Composer

### Local Development Setup

1. **Fork the Repository**

    Click the "Fork" button on the [Dokan Kits repository](https://github.com/wpintegrity/wp-plugin-starter) to create your own copy.

2. **Clone Your Fork**

    ```bash
    git clone https://github.com/wpintegrity/wp-plugin-starter
    cd wp-plugin-starter
    ```

3. **Install PHP Dependencies**

    ```bash
    composer install
    ```

4. **Install JavaScript Dependencies**

    ```bash
    npm install
    ```

5. **Set Up WordPress Environment**

    We recommend using [LocalWP](https://localwp.com/) or [DevKinsta](https://kinsta.com/devkinsta/) for local WordPress development.

    - Install WordPress
    - Install and activate WooCommerce
    - Install and activate Dokan Lite
    - Link your cloned repository to the WordPress plugins directory

6. **Start Development Server**

    ```bash
    # For frontend development with hot reload
    npm start

    # For building production assets
    npm run build
    ```

## 🧪 Testing

### PHP Testing

```bash
# Run PHP unit tests
composer run test

# Run PHP code standards checks
composer run phpcs
```

### JavaScript Testing

```bash
# Run JavaScript unit tests
npm run test

# Run JavaScript code standards checks
npm run lint
```

### End-to-End Testing

```bash
# Run end-to-end tests
npm run test:e2e
```

## 🔄 Development Workflow

### Branching Strategy

- `main`: The main development branch, containing the latest stable code
- `feature/feature-name`: Feature branches for new features
- `fix/issue-number`: Fix branches for bug fixes
- `release/version`: Release branches for version preparation

### Creating a New Feature

1. Create a new branch from `main`:

    ```bash
    git checkout main
    git pull origin main
    git checkout -b feature/your-feature-name
    ```

2. Develop your feature, following the coding standards and guidelines.

3. Write tests for your feature.

4. Build and test your changes:

    ```bash
    npm run build
    composer run test
    npm run test
    ```

5. Commit your changes with meaningful commit messages:

    ```bash
    git commit -m "feat: add new feature XYZ"
    ```

    We follow [Conventional Commits](https://www.conventionalcommits.org/) for commit messages.

6. Push your branch to your fork:

    ```bash
    git push origin feature/your-feature-name
    ```

7. Open a Pull Request against the `main` branch of the original repository.

### Fixing a Bug

1. Create a new branch from `main`:

    ```bash
    git checkout main
    git pull origin main
    git checkout -b fix/issue-number
    ```

2. Fix the bug, following the coding standards and guidelines.

3. Write or update tests to catch the bug.

4. Build and test your changes:

    ```bash
    npm run build
    composer run test
    npm run test
    ```

5. Commit your changes with meaningful commit messages:

    ```bash
    git commit -m "fix: correct issue with XYZ"
    ```

6. Push your branch to your fork:

    ```bash
    git push origin fix/issue-number
    ```

7. Open a Pull Request against the `main` branch of the original repository.

## 📋 Pull Request Process

1. Ensure your code follows the coding standards.
2. Include tests for your changes.
3. Update documentation if necessary.
4. Ensure all tests pass.
5. Fill out the Pull Request template completely.
6. Request review from maintainers.
7. Address any feedback from reviewers.

## 📝 Coding Standards

### PHP Coding Standards

- Follow [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/)
- Use namespaces and autoloading
- Document classes, methods, and functions with PHPDoc
- Keep methods small and focused
- Use meaningful variable and function names

### JavaScript Coding Standards

- Follow [WordPress JavaScript Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/javascript/)
- Use ES6+ features
- Document functions and components with JSDoc
- Use functional components and hooks for React
- Keep components small and focused

### CSS Coding Standards

- Follow [WordPress CSS Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/css/)
- Use Tailwind CSS utility classes when possible
- Use BEM naming convention for custom CSS classes
- Keep styling modular and responsive
- Consider RTL support

## 📚 Documentation

### Inline Documentation

- Document all classes, methods, and functions
- Explain complex logic with comments
- Document parameters, return values, and exceptions
- Use meaningful variable and function names

### External Documentation

- Update README.md with relevant information
- Update user documentation if your changes affect user experience
- Update developer documentation if your changes affect the API
- Include screenshots or diagrams for visual clarity

## 🌐 Translations

We use the WordPress translation system for internationalization.

1. Ensure all user-facing strings are translatable:

    ```php
    // PHP
    __('Translatable string', 'wp-plugin-starter')
    ```

    ```jsx
    // JavaScript
    __('Translatable string', 'wp-plugin-starter');
    ```

2. Run the pot file generation:

    ```bash
    npm run makepot
    ```

3. If you're adding a new translation, use the pot file to create a po file for your language.

## 🎯 Feature Requests

If you have an idea for a new feature, please follow these steps:

1. Check if the feature has already been requested or implemented.
2. Open a new issue using the Feature Request template.
3. Clearly describe the feature and its benefits.
4. Provide examples of how the feature would work.
5. If possible, include mockups or diagrams.

## 🐛 Bug Reports

If you find a bug, please follow these steps:

1. Check if the bug has already been reported.
2. Open a new issue using the Bug Report template.
3. Clearly describe the bug, including steps to reproduce.
4. Include relevant details such as WordPress version, Dokan version, etc.
5. If possible, include screenshots or screen recordings.

## 📅 Release Process

1. **Version Bump**

    - Update version number in main plugin file
    - Update changelog in readme.txt
    - Update version in package.json

2. **Build Assets**

    ```bash
    npm run build
    ```

3. **Final Testing**

    ```bash
    composer run test
    npm run test
    ```

4. **Create Release Branch**

    ```bash
    git checkout -b release/x.y.z
    git add .
    git commit -m "chore: prepare release x.y.z"
    git push origin release/x.y.z
    ```

5. **Create Pull Request**

    - Create a pull request from `release/x.y.z` to `main`
    - Get approval from maintainers

6. **Create Tag and Release**

    ```bash
    git checkout main
    git pull origin main
    git tag vx.y.z
    git push origin vx.y.z
    ```

7. **Create GitHub Release**
    - Go to GitHub releases page
    - Create a new release from the tag
    - Include changelog in the release notes

## 🙏 Thank You!

Your contributions help make Dokan Kits better for everyone. We appreciate your time and effort!

If you have any questions or need help, please reach out to the maintainers or open an issue.
