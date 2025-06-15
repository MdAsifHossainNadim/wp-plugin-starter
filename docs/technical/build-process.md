# Build Process

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This document explains the build process for WP Plugin Starter, including development workflow, tools, and release procedures.

## 📋 Table of Contents

- [Development Environment](#-development-environment)
- [Build Tools](#-build-tools)
- [Development Workflow](#-development-workflow)
- [Build Configuration](#-build-configuration)
- [Asset Optimization](#-asset-optimization)
- [Release Process](#-release-process)
- [Continuous Integration](#-continuous-integration)
- [Troubleshooting](#-troubleshooting)

## 🧑‍💻 Development Environment

### Requirements

To work on WP Plugin Starter, you'll need:

- PHP 7.4 or higher
- MySQL 5.7 or higher (or MariaDB 10.2+)
- Node.js 16.x or higher
- npm 8.x or higher
- Composer
- WordPress 6.4.2+
- WooCommerce 7.9+
- (Optional) Dokan Lite/Pro 3.9.7+

### Setup

1. Clone the repository
2. Run `composer install` to install PHP dependencies
3. Run `npm install` to install JavaScript dependencies
4. Configure your local WordPress development environment

## 🛠 Build Tools

WP Plugin Starter uses the following build tools:

- **Webpack**: JavaScript bundling and module management via `webpack.config.js`
- **Babel**: JavaScript transpilation for browser compatibility
- **SCSS**: CSS preprocessing for maintainable stylesheets
- **PostCSS**: CSS transformations and optimizations via `postcss.config.js`
- **Tailwind CSS**: Utility-first CSS framework via `tailwind.config.js`
- **TypeScript**: Static typing for JavaScript via `tsconfig.json`
- **Composer**: PHP dependency management via `composer.json`
- **PHPUnit**: PHP testing framework via `phpunit.xml.dist`
- **PHPCS**: PHP code standards via `phpcs.xml.dist`

## 💻 Development Workflow

### Local Development

1. Start the development server with hot reloading:
   ```
   npm run start
   ```

2. Make changes to files in the `src/` directory
   - JavaScript/TypeScript files in `src/admin/` and `src/frontend/`
   - SCSS files in `src/scss/`

3. Changes will be automatically compiled into the `build/` directory

### Building for Production

To create optimized production assets:

```
npm run build
```

This creates minified files in the `build/` directory:
- `build/admin/app.js` - Admin JavaScript
- `build/admin/app.css` - Admin CSS
- `build/frontend/blocks/` - Frontend block assets

## ⚙️ Build Configuration

### Webpack Configuration

The `webpack.config.js` file in the root directory defines:
- Entry points for admin and frontend code
- Output paths and filenames
- Loaders for different file types (JS, SCSS, images)
- Optimization settings for production builds
- Development server configuration

### Tailwind Configuration

The `tailwind.config.js` file defines:
- Custom theme settings
- Plugins used
- Purge settings for production optimization

### TypeScript Configuration

The `tsconfig.json` file configures:
- JavaScript language features
- Module resolution
- Compilation options
- Type checking rules

## 🔧 Asset Optimization

For production builds, assets are optimized through:

1. JavaScript minification and tree-shaking
2. CSS purging and minification
3. Image optimization
4. Asset versioning for cache busting

## 📦 Release Process

The release process uses the `bin/release.sh` script to:

1. Bump version numbers in:
   - `wp-plugin-starter.php`
   - `readme.txt`
   - `package.json`
   - `composer.json`
2. Generate production assets
3. Update the changelog in `CHANGELOG.md`
4. Create a git tag
5. Package the release

See [Release Process](release-process.md) for detailed steps.

## 🔄 Continuous Integration

CI workflows run on every pull request to verify:
- PHP code standards compliance
- JavaScript linting
- Unit tests passing
- Build process succeeding

## ❓ Troubleshooting

### Common Build Issues

- **Missing dependencies**: Run `npm install` and `composer install`
- **Build failures**: Check for syntax errors in your JavaScript/SCSS
- **Webpack errors**: Verify entry points in webpack.config.js match your source files
- **Type errors**: Address TypeScript warnings in your code
