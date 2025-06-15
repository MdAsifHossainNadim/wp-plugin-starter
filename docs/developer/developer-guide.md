# WP Plugin Starter Developer Guide

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This guide provides comprehensive information for developers who want to extend, customize, or contribute to WP Plugin Starter.

## 📋 Table of Contents

- [Development Environment Setup](#-development-environment-setup)
- [Project Structure](#-project-structure)
- [Development Workflow](#-development-workflow)
- [Extension Development](#-extension-development)
- [Contributing Guidelines](#-contributing-guidelines)
- [Testing](#-testing)
- [Common Development Tasks](#-common-development-tasks)
- [Best Practices](#-best-practices)

## 🛠 Development Environment Setup

### Prerequisites

To set up a development environment for WP Plugin Starter, you'll need:

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Node.js 16.x or higher
- npm 8.x or higher
- Composer
- WordPress 6.4.2+
- WooCommerce 7.9+ (optional)
- Dokan Lite/Pro 3.9.7+ (optional)

### Local Development Setup

1. **Clone the Repository**
   ```bash
   git clone [repository-url] wp-plugin-starter
   cd wp-plugin-starter
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript Dependencies**
   ```bash
   npm install
   ```

4. **Set Up WordPress**
   - Install WordPress locally
   - Clone or symlink the WP Plugin Starter directory to `/wp-content/plugins/wp-plugin-starter`
   - Activate WP Plugin Starter

### Development Commands

- **Build assets once:**
  ```bash
  npm run build
  ```

- **Watch and build assets during development:**
  ```bash
  npm run start
  ```

- **Run PHP tests:**
  ```bash
  composer test
  ```

- **Run PHP linting:**
  ```bash
  composer lint
  ```

- **Fix PHP code style issues:**
  ```bash
  composer fix
  ```

## 📁 Project Structure

WP Plugin Starter follows a structured organization to make development more manageable:

```
wp-plugin-starter/
├── assets/                  # Static assets (images, compiled CSS/JS)
├── bin/                     # Command line scripts
├── build/                   # Compiled assets (generated)
├── dependencies/            # Third-party dependencies (prefixed)
├── docs/                    # Documentation
├── includes/                # PHP classes
│   ├── Admin/               # Admin-related functionality
│   ├── Core/                # Core plugin functionality
│   ├── Features/            # Individual features
│   ├── Frontend/            # Frontend components
│   ├── REST/                # REST API endpoints
│   ├── Setup/               # Installation and upgrades
│   └── Utils/               # Utility classes
├── languages/               # Translation files
├── src/                     # Source files for JS/CSS
│   ├── admin/               # Admin JavaScript/React
│   ├── frontend/            # Frontend JavaScript
│   └── scss/                # SCSS source files
├── templates/               # Template files
└── tests/                   # Test files
```

### Key Files

- `wp-plugin-starter.php` - Main plugin file
- `class-wp-plugin-starter.php` - Main plugin class
- `includes/functions.php` - Global functions
- `composer.json` - PHP dependencies and autoloading
- `package.json` - JavaScript dependencies and scripts
- `webpack.config.js` - Asset build configuration

## 🔄 Development Workflow

### Feature Development

When developing new features for WP Plugin Starter:

1. **Create Feature Branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Implement Feature**
   - Add new classes in the appropriate directories
   - Follow the existing architectural patterns
   - Register new services in Service Providers

3. **Build Assets**
   ```bash
   npm run build
   ```

4. **Test Feature**
   - Manual testing in WordPress
   - Write unit tests for new functionality
   - Run the test suite

5. **Create Pull Request**
   - Include a detailed description of the feature
   - Reference any related issues
   - Ensure all tests pass

### Code Standards

WP Plugin Starter follows WordPress coding standards with some modern PHP practices:

- PSR-4 autoloading
- PHP 7.4+ features (typed properties, return type declarations)
- WordPress coding style for PHP
- ESLint and Prettier for JavaScript

## 🧩 Extension Development

### Extension Types

You can extend WP Plugin Starter in several ways:

1. **Standalone Plugin**
   - Create a separate plugin that hooks into WP Plugin Starter
   - Use the provided hooks and filters

2. **Feature Integration**
   - Register a custom feature with the Feature Registry
   - Implement the required interfaces

3. **Service Extension**
   - Register custom services in the container
   - Extend existing services with new functionality

### Extension Boilerplate

A basic extension structure should look like:

```php
<?php
/**
 * Plugin Name: WP Plugin Starter Extension
 * Description: Extends WP Plugin Starter with additional features
 * Version: 1.0.0
 * Author: Your Name
 * Requires Plugins: wp-plugin-starter
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_Plugin_Starter_Extension {
    public function __construct() {
        add_action('wp_plugin_starter_loaded', [$this, 'init']);
    }

    public function init() {
        // Register hooks, features, or services
    }
}

new WP_Plugin_Starter_Extension();
```

### Accessing Core Services

You can access WP Plugin Starter services through the service container:

```php
$container = wp_plugin_starter_get_container();
$service = $container->get('service.name');
```

### Registering Custom Features

To add a custom feature:

```php
add_action('wp_plugin_starter_before_bootstrap', function($bootstrap) {
    $registry = wp_plugin_starter_get_container()->get('feature-registry');
    $registry->register('my-feature', [
        'name' => 'My Feature',
        'description' => 'Description of my feature',
        'callback' => function() {
            // Initialize your feature
            return new My_Feature();
        }
    ]);
});
```

## 👥 Contributing Guidelines

### Contribution Process

1. **Fork the Repository**
   - Create a fork on GitHub
   - Clone your fork locally

2. **Create a Branch**
   - Use a descriptive branch name
   - Base your branch on `develop`

3. **Make Your Changes**
   - Follow the coding standards
   - Add tests for new functionality
   - Update documentation as needed

4. **Create a Pull Request**
   - Provide a clear description of changes
   - Reference any related issues
   - Ensure all tests pass

### Commit Messages

Follow the conventional commit format:

```
type(scope): description

[optional body]

[optional footer]
```

Types include:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (no functionality change)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

## 🧪 Testing

### Test Environment

WP Plugin Starter uses PHPUnit for PHP tests:

```bash
composer test
```

### Writing Tests

Tests are located in the `tests/` directory:

1. **Unit Tests**
   - Test individual classes and methods
   - Located in `tests/php/src/Unit`

2. **Integration Tests**
   - Test components working together
   - Located in `tests/php/src/Integration`

3. **Feature Tests**
   - Test entire features end-to-end
   - Located in `tests/php/src/Feature`

### Example Test

```php
namespace WPPluginStarter\Tests\Unit;

class My_Feature_Test extends \WP_UnitTestCase {
    public function test_something() {
        $feature = new \WPPluginStarter\Features\My_Feature\My_Feature();
        $result = $feature->do_something();
        $this->assertEquals('expected', $result);
    }
}
```

## 🛠 Common Development Tasks

### Adding a New Setting

1. Register the setting in the appropriate Settings Provider:

```php
add_filter('wp_plugin_starter_settings_fields', function($fields) {
    $fields['section']['my_setting'] = [
        'name' => 'my_setting',
        'label' => __('My Setting', 'wp-plugin-starter'),
        'type' => 'text',
        'default' => '',
    ];
    return $fields;
});
```

2. Access the setting value:

```php
$value = wp_plugin_starter_service('settings')->get('section.my_setting');
```

### Adding a REST Endpoint

1. Create a controller class:

```php
namespace WPPluginStarter\REST;

class My_REST_Controller extends \WP_REST_Controller {
    public function register_routes() {
        register_rest_route('wp-plugin-starter/v1', '/my-endpoint', [
            'methods' => 'GET',
            'callback' => [$this, 'get_items'],
            'permission_callback' => [$this, 'get_items_permissions_check'],
        ]);
    }
    
    // Implement other required methods
}
```

2. Register the controller:

```php
add_action('rest_api_init', function() {
    $controller = new \WPPluginStarter\REST\My_REST_Controller();
    $controller->register_routes();
});
```

### Adding a React Component

1. Create a component file in `src/admin/components/`:

```jsx
import React from 'react';

const MyComponent = (props) => {
    return (
        <div className="my-component">
            {props.content}
        </div>
    );
};

export default MyComponent;
```

2. Import and use in your admin page:

```jsx
import MyComponent from './components/MyComponent';

const AdminPage = () => {
    return (
        <div>
            <MyComponent content="Hello World" />
        </div>
    );
};
```

## 📋 Best Practices

### Code Quality

- Use type hints and return types
- Document your code with DocBlocks
- Follow SOLID principles
- Write unit tests for new functionality

### WordPress Integration

- Use WordPress hooks and filters
- Follow WordPress security practices
- Properly sanitize and validate data
- Use WordPress translation functions

### Performance

- Cache expensive operations
- Optimize database queries
- Minimize asset sizes
- Use proper hook priorities

### Security

- Validate and sanitize all inputs
- Check capabilities before actions
- Use nonces for AJAX requests
- Escape output properly

## 📚 Additional Resources

- [Architecture Documentation](architecture.md) - Detailed architecture overview
- [Hooks Reference](hooks-reference.md) - Available actions and filters
- [REST API](rest-api.md) - API endpoints reference
- [Data Models](data-models.md) - Core data structures
