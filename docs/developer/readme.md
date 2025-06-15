# WP Plugin Starter Developer Documentation

This directory contains documentation for developers who want to extend, customize, or contribute to WP Plugin Starter.

## Table of Contents

- [Developer Guide](developer-guide.md) - Start here for an overview of the development environment
- [Architecture](architecture.md) - Object-oriented architecture and design patterns
- [Dependency Injection](dependency-injection.md) - Using the League Container implementation
- [Data Models](data-models.md) - Core data structures and database schema
- [REST API](rest-api.md) - Custom API endpoints and extensions
- [Hooks Reference](hooks-reference.md) - Complete list of actions and filters
- [Extending](extending.md) - Guidelines for extending WP Plugin Starter
- [Structure System](structure-system.md) - Understanding the namespaced file organization
- [API Reference](api-reference.md) - Detailed class and method documentation
- [Building Extensions](building-extensions.md) - How to build add-ons for WP Plugin Starter
- [React Components](react-components.md) - Documentation for the admin dashboard React components
- [Contributing](contributing.md) - Guidelines for contributing to WP Plugin Starter
- [Migration Guide](migration-guide.md) - Migrating from previous versions

## Core Architecture

WP Plugin Starter follows modern PHP practices with:

- Namespaced classes under the `Dokan_Kits` namespace
- Interface-based design with `Hookable` and `Initable` interfaces
- PSR-4 autoloading via Composer
- Dependency injection using League Container
- WordPress coding standards compliance

## Key Directories

- `includes/` - Contains PHP classes organized by namespace
- `src/` - Contains JavaScript and SCSS source files
- `build/` - Compiled assets (JS/CSS)
- `templates/` - HTML templates used by the plugin
- `assets/` - Static assets like images and third-party libraries

## Getting Started with Development

For developers new to WP Plugin Starter, we recommend:

1. Start with the [Developer Guide](developer-guide.md) for environment setup
2. Review the [Architecture](architecture.md) to understand the system design
3. Check the [Hooks Reference](hooks-reference.md) for extension points
4. Follow the [Contributing](contributing.md) guide if you want to submit code

## Key Concepts

- **Hooks System**: WP Plugin Starter provides numerous actions and filters for extending functionality
- **Service Container**: Dependency management through the League Container implementation
- **REST API**: Custom endpoints extending WP REST API for front and backend operations
- **React Admin**: Modern admin interfaces built with React

## Need More Help?

If you need further assistance with development, consider:

1. Checking our [technical documentation](../technical/) for build processes and standards
2. Reviewing the [main documentation](../readme.md) for more resources
3. Examining the codebase structure through your IDE - classes are well documented with DocBlocks
