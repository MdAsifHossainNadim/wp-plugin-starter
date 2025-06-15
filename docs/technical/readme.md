# WP Plugin Starter Technical Documentation

This directory contains technical documentation focused on development processes, standards, and release management for the WP Plugin Starter plugin.

## Table of Contents

- [Build Process](build-process.md) - Webpack, asset compilation and build workflow
- [Coding Standards](coding-standards.md) - PHP, JS coding conventions and linting
- [Testing](testing.md) - PHPUnit, automated testing and QA procedures
- [Release Process](release-process.md) - Version management and deployment
- [Changelog](changelog.md) - Version history and release notes
- [Roadmap](roadmap.md) - Future feature plans and timeline

## Build System

WP Plugin Starter uses modern front-end build tools:

- **Webpack** for JavaScript bundling and optimization
- **SCSS** for stylesheet preprocessing
- **PostCSS** for CSS optimization and transformations
- **Tailwind CSS** for utility-first styling
- **NPM scripts** for task automation

To understand the complete build workflow, see the [Build Process](build-process.md) documentation.

## Code Quality Tools

The project enforces code quality through:

- **PHPCS** with WordPress coding standards
- **PHPStan** for static analysis
- **ESLint** for JavaScript linting
- **PHPMD** for PHP mess detection
- **PHPUnit** for unit and integration testing

For detailed code quality standards, check the [Coding Standards](coding-standards.md) guide.

## Release Workflow

The release process follows semantic versioning (SEMVER) principles with:

1. Version bump in plugin headers
2. Asset compilation and optimization
3. Changelog updates
4. Tag creation and GitHub release
5. WordPress.org SVN deployment

For the full release workflow, see the [Release Process](release-process.md) documentation.

## For Technical Teams

This technical documentation is intended for:

- Core contributors to WP Plugin Starter
- Technical leads implementing WP Plugin Starter in production
- Quality assurance testers
- Release managers

## Related Resources

- [Developer Documentation](../developer/) - For extending and customizing WP Plugin Starter
- [GitHub repository](https://github.com/wpintegrity/wp-plugin-starter) - Source code access
- [bin/README.md](/bin/README.md) - Command-line tools and scripts
