# WP Plugin Starter

[![License](https://img.shields.io/badge/license-GPL--2.0%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net/)
[![WordPress Version](https://img.shields.io/badge/WordPress-6.4.2%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-7.9%2B-purple.svg)](https://woocommerce.com/)
[![Build Status](https://img.shields.io/github/workflow/status/MdAsifHossainNadim/wp-plugin-starter/Build)](https://github.com/MdAsifHossainNadim/wp-plugin-starter/actions)
[![Code Quality](https://img.shields.io/scrutinizer/quality/g/MdAsifHossainNadim/wp-plugin-starter)](https://scrutinizer-ci.com/g/MdAsifHossainNadim/wp-plugin-starter/)

A modern, extensible WordPress plugin boilerplate that provides a robust foundation for building advanced plugins with enterprise-grade architecture, React-powered admin interface, and WordPress/WooCommerce best practices.

**Current Version:** 1.0.0  
**Author:** [Md. Asif Hossain Nadim](https://profiles.wordpress.org/devianadim9/)  
**Tested up to:** WordPress 6.8, WooCommerce 9.8.3

## 📋 Table of Contents

- [🔍 Overview](#-overview)
- [✨ Features](#-features)
- [🎯 Use Cases](#-use-cases)
- [📋 Requirements](#-requirements)
- [🚀 Quick Start](#-quick-start)
- [💻 Development Setup](#-development-setup)
- [🏗️ Architecture](#️-architecture)
- [📁 Project Structure](#-project-structure)
- [🔧 Configuration](#-configuration)
- [🎨 Customization](#-customization)
- [📚 Documentation](#-documentation)
- [🤝 Contributing](#-contributing)
- [🐛 Support](#-support)
- [📄 License](#-license)

## 🔍 Overview

WP Plugin Starter is a comprehensive WordPress plugin boilerplate designed for professional developers who need to build modern, scalable plugins. It combines enterprise-grade architecture with WordPress best practices to provide a solid foundation for any plugin project.

### Why Choose WP Plugin Starter?

- **🏗️ Modern Architecture** - Built with dependency injection, service containers, and SOLID principles
- **⚡ Performance First** - Optimized for speed with lazy loading and efficient asset management
- **🛡️ Security Focused** - Implements WordPress security best practices and input validation
- **🧪 Testing Ready** - Includes comprehensive PHPUnit test suite and CI/CD workflows
- **🎨 React Admin Interface** - Modern, responsive admin dashboard built with React and WordPress components
- **🔌 Extensible by Design** - Feature-based architecture with hooks and filters for easy customization
- **📱 Mobile Responsive** - Admin interface works seamlessly across all devices

## ✨ Features

### 🏗️ **Enterprise Architecture**
- **Dependency Injection Container** - Clean, testable code with automatic service resolution
- **Service Provider Pattern** - Organized service registration and bootstrapping
- **Feature-Based Organization** - Modular features that can be enabled/disabled independently
- **Data Store Abstraction** - Custom data stores for complex data management
- **Template System** - Flexible template management with override capabilities

### 🎨 **Modern Admin Interface**
- **React-Powered Dashboard** - Fast, interactive admin interface built with React
- **WordPress Components** - Consistent UI using official WordPress design system
- **Real-Time Updates** - Dynamic content updates without page refreshes
- **Mobile-First Design** - Responsive admin interface for all screen sizes
- **Dark Mode Support** - Automatic theme adaptation for better user experience

### 🔌 **Developer Experience**
- **Hot Module Replacement** - Fast development with instant code reloading
- **TypeScript Support** - Type-safe JavaScript development
- **SCSS Processing** - Advanced styling with variables and mixins
- **Webpack Integration** - Modern asset bundling and optimization
- **ESLint & Prettier** - Automated code formatting and quality checks

### 🛡️ **Security & Performance**
- **Input Validation** - Comprehensive sanitization and validation system
- **Capability Checks** - Proper permission handling throughout the plugin
- **Nonce Verification** - CSRF protection for all forms and AJAX requests
- **Asset Optimization** - Minified, compressed assets for production
- **Lazy Loading** - Services and features loaded only when needed

### 🧪 **Testing & Quality Assurance**
- **PHPUnit Integration** - Comprehensive unit and integration testing
- **WordPress Test Suite** - Tests run against actual WordPress environment
- **Code Coverage** - Track test coverage and maintain quality
- **CI/CD Workflows** - Automated testing and deployment pipelines
- **Code Standards** - WordPress Coding Standards (WPCS) enforcement

### 🌐 **REST API & Integration**
- **Custom REST Endpoints** - Extensible API for frontend integrations
- **Authentication Middleware** - Secure API access with proper authentication
- **Response Formatting** - Consistent, standardized API responses
- **Rate Limiting** - Protection against API abuse
- **Documentation Generation** - Auto-generated API documentation

## 🎯 Use Cases

WP Plugin Starter is perfect for:

- **🏪 E-commerce Plugins** - WooCommerce extensions and marketplace tools
- **📊 Business Applications** - CRM, analytics, and management systems
- **🎓 Learning Management** - Educational platforms and course management
- **📰 Content Management** - Advanced publishing and content tools
- **🔧 Utility Plugins** - Performance, security, and optimization tools
- **🎨 Theme Companions** - Feature-rich theme enhancements
- **📱 API-First Applications** - Headless WordPress solutions

## 📋 Requirements

### Minimum Requirements
- **WordPress:** 6.4.2 or higher
- **PHP:** 7.4 or higher
- **MySQL:** 5.7 or higher
- **Memory:** 128MB (256MB recommended)

### Optional Dependencies
- **WooCommerce:** 7.9+ (for e-commerce features)
- **Dokan Lite/Pro:** 3.9.7+ (for marketplace features)

### Development Requirements
- **Node.js:** 16.x or higher
- **npm:** 8.x or higher
- **Composer:** Latest stable version
- **Git:** For version control

## 🚀 Quick Start

### Transform into Your Plugin

**🔄 Rename WP Plugin Starter to your custom plugin name:**

```bash
# Quick rename
./bin/update-pluginname.sh "My Awesome Plugin"

# With author details
./bin/update-pluginname.sh "My Awesome Plugin" "John Doe" "john@example.com" "https://johndoe.com"
```

The script will automatically:
- ✅ Update all naming conventions (functions, classes, constants, namespaces)
- ✅ Rename files and update file contents
- ✅ Update package.json and composer.json
- ✅ Create a backup before making changes
- ✅ Follow WordPress coding standards

**See [bin/README.md](bin/README.md) for detailed documentation.**

### Installation

1. **Download the Plugin**
   ```bash
   git clone https://github.com/MdAsifHossainNadim/wp-plugin-starter.git
   cd wp-plugin-starter
   ```

2. **Install Dependencies**
   ```bash
   composer install --no-dev
   npm install --production
   ```

3. **Build Assets**
   ```bash
   npm run build
   ```

4. **Upload to WordPress**
   - Upload the entire folder to `/wp-content/plugins/`
   - Activate through WordPress admin dashboard

### First Steps

1. **Access the Dashboard**
   - Navigate to `WP Admin → WP Plugin Starter`
   - Explore the modern React-based interface

2. **Configure Settings**
   - Review general settings in the admin panel
   - Enable/disable features as needed

3. **Check System Status**
   - Verify all requirements are met
   - Review compatibility status

## 💻 Development Setup

### Local Development Environment

1. **Clone and Setup**
   ```bash
   git clone https://github.com/MdAsifHossainNadim/wp-plugin-starter.git
   cd wp-plugin-starter
   composer install
   npm install
   ```

2. **Development Commands**
   ```bash
   # Start development server with hot reloading
   npm run start
   
   # Build for production
   npm run build
   
   # Run PHP tests
   composer test
   
   # Check code standards
   composer lint
   
   # Fix code style issues
   composer fix
   ```

3. **Development Workflow**
   ```bash
   # Create feature branch
   git checkout -b feature/amazing-feature
   
   # Make changes and test
   npm run start
   composer test
   
   # Build and commit
   npm run build
   git commit -m "feat: add amazing feature"
   ```

## 🏗️ Architecture

### Core Principles

WP Plugin Starter follows modern software architecture principles:

- **🎯 Single Responsibility** - Each class has one clear purpose
- **🔓 Open/Closed Principle** - Open for extension, closed for modification
- **🔄 Dependency Inversion** - Depend on abstractions, not concretions
- **📦 Composition over Inheritance** - Flexible object composition
- **🧪 Test-Driven Development** - Comprehensive test coverage

### System Components

```
┌─────────────────────────────────────────────────┐
│                 WP Plugin Starter               │
├─────────────────────────────────────────────────┤
│  🎨 Admin Interface (React)                     │
│  ├── Dashboard Components                       │
│  ├── Settings Management                        │
│  └── Real-time Updates                          │
├─────────────────────────────────────────────────┤
│  🔌 Core Services                               │
│  ├── Dependency Injection Container             │
│  ├── Service Providers                          │
│  ├── Feature Registry                           │
│  └── Event System                               │
├─────────────────────────────────────────────────┤
│  💾 Data Layer                                  │
│  ├── Custom Data Stores                         │
│  ├── Model Abstractions                         │
│  ├── Migration System                           │
│  └── Validation Engine                          │
├─────────────────────────────────────────────────┤
│  🌐 API Layer                                   │
│  ├── REST Controllers                           │
│  ├── Authentication Middleware                  │
│  ├── Response Formatting                        │
│  └── Rate Limiting                              │
├─────────────────────────────────────────────────┤
│  🎨 Frontend Integration                        │
│  ├── Asset Management                           │
│  ├── Template System                            │
│  ├── Block Editor Support                       │
│  └── Theme Integration                          │
└─────────────────────────────────────────────────┘
```

## 📁 Project Structure

```
wp-plugin-starter/
├── 📄 wp-plugin-starter.php        # Main plugin file
├── 📄 class-wp-plugin-starter.php  # Main plugin class
├── 📁 includes/                    # PHP source code
│   ├── 📁 Admin/                   # Admin interface
│   │   ├── Assets.php              # Admin asset management
│   │   ├── Menu.php                # Admin menu system
│   │   └── Dashboard/              # Dashboard components
│   ├── 📁 Core/                    # Core functionality
│   │   ├── Bootstrap.php           # Plugin bootstrap
│   │   ├── DI/                     # Dependency injection
│   │   ├── Data/                   # Data stores and models
│   │   └── Interfaces/             # Core interfaces
│   ├── 📁 Features/                # Plugin features
│   │   ├── Abstract_Feature.php    # Feature base class
│   │   └── Product/                # Example feature
│   ├── 📁 REST/                    # REST API
│   │   ├── Controllers/            # API controllers
│   │   └── Middleware/             # API middleware
│   ├── 📁 Frontend/                # Frontend components
│   │   ├── Assets.php              # Frontend assets
│   │   └── Frontend.php            # Frontend hooks
│   ├── 📁 Setup/                   # Installation & updates
│   │   ├── Activator.php           # Plugin activation
│   │   ├── Migrator.php            # Database migrations
│   │   └── System_Check.php        # System requirements
│   └── 📁 Utils/                   # Utility classes
│       ├── Helpers.php             # Helper functions
│       └── Logger.php              # Logging system
├── 📁 src/                         # Frontend source code
│   ├── 📁 admin/                   # React admin interface
│   │   ├── app.jsx                 # Main admin app
│   │   ├── components/             # Reusable components
│   │   ├── pages/                  # Admin pages
│   │   └── hooks/                  # Custom React hooks
│   ├── 📁 frontend/                # Frontend JavaScript
│   │   ├── frontend.js             # Main frontend script
│   │   ├── blocks/                 # Gutenberg blocks
│   │   └── components/             # Frontend components
│   └── 📁 scss/                    # Stylesheet source
│       ├── admin/                  # Admin styles
│       ├── frontend/               # Frontend styles
│       └── shared/                 # Shared styles
├── 📁 assets/                      # Compiled assets
│   ├── css/                        # Compiled CSS
│   ├── js/                         # Compiled JavaScript
│   └── images/                     # Static images
├── 📁 templates/                   # Template files
│   └── admin/                      # Admin templates
├── 📁 languages/                   # Translation files
├── 📁 tests/                       # Test suite
│   └── php/                        # PHPUnit tests
├── 📁 docs/                        # Documentation
│   ├── developer/                  # Developer guides
│   ├── user/                       # User documentation
│   └── technical/                  # Technical reference
├── 📁 .github/                     # GitHub workflows
├── 📄 composer.json                # PHP dependencies
├── 📄 package.json                 # Node.js dependencies
├── 📄 webpack.config.js            # Build configuration
└── 📄 phpcs.xml.dist              # Code standards config
```

## 🔧 Configuration

### Environment Setup

Create a `.env` file for local development:

```env
# WordPress Configuration
WP_DEBUG=true
WP_DEBUG_LOG=true
WP_DEBUG_DISPLAY=false

# Plugin Configuration
WP_PLUGIN_STARTER_DEBUG=true
WP_PLUGIN_STARTER_LOG_LEVEL=debug

# Build Configuration
NODE_ENV=development
```

### Plugin Constants

Key constants available for configuration:

```php
// Plugin paths and URLs
WP_PLUGIN_STARTER_FILE          // Main plugin file
WP_PLUGIN_STARTER_PLUGIN_PATH   // Plugin directory path
WP_PLUGIN_STARTER_PLUGIN_URL    // Plugin URL
WP_PLUGIN_STARTER_ASSETS_URL    // Assets URL
WP_PLUGIN_STARTER_BUILD_URL     // Build assets URL

// Plugin metadata
WP_PLUGIN_STARTER_VERSION       // Current version
WP_PLUGIN_STARTER_BASENAME      // Plugin basename
```

### Build Configuration

Customize the build process in `webpack.config.js`:

```javascript
// Custom entry points
entry: {
  admin: './src/admin/app.jsx',
  frontend: './src/frontend/frontend.js',
  blocks: './src/frontend/blocks/index.js'
}

// Environment-specific builds
if (process.env.NODE_ENV === 'production') {
  // Production optimizations
}
```

## 🎨 Customization

### Adding Custom Features

1. **Create Feature Class**
   ```php
   namespace WPPluginStarter\Features\MyFeature;
   
   use WPPluginStarter\Features\Abstract_Feature;
   
   class My_Feature extends Abstract_Feature {
       public function init(): void {
           // Initialize your feature
       }
   }
   ```

2. **Register with Container**
   ```php
   add_action('wp_plugin_starter_before_bootstrap', function($bootstrap) {
       $container = wp_plugin_starter_get_container();
       $container->add('my-feature', My_Feature::class);
   });
   ```

### Extending the Admin Interface

1. **Add React Component**
   ```jsx
   // src/admin/components/MyComponent.jsx
   import React from 'react';
   
   const MyComponent = () => {
       return <div>My Custom Component</div>;
   };
   
   export default MyComponent;
   ```

2. **Register Admin Page**
   ```php
   add_action('wp_plugin_starter_admin_menu', function($capability, $position) {
       add_submenu_page(
           'wp-plugin-starter',
           'My Page',
           'My Page',
           $capability,
           'my-custom-page',
           'render_my_page'
       );
   });
   ```

### Creating REST Endpoints

```php
namespace WPPluginStarter\REST\Controllers\V1;

class My_Controller extends \WP_REST_Controller {
    public function register_routes() {
        register_rest_route('wp-plugin-starter/v1', '/my-endpoint', [
            'methods' => 'GET',
            'callback' => [$this, 'get_items'],
            'permission_callback' => [$this, 'get_items_permissions_check'],
        ]);
    }
}
```

## 📚 Documentation

### Complete Documentation Suite

- **📖 [Getting Started](docs/getting-started/)** - Installation and basic setup
- **👥 [User Guide](docs/user/)** - End-user documentation
- **🔧 [Developer Guide](docs/developer/)** - Technical documentation
- **🏗️ [Architecture](docs/developer/architecture.md)** - System design
- **🔌 [Hooks Reference](docs/developer/hooks-reference.md)** - Available hooks
- **🌐 [REST API](docs/developer/rest-api.md)** - API documentation
- **🧪 [Testing](docs/technical/testing.md)** - Testing guidelines

### Quick Reference

#### Essential Hooks
```php
// Core plugin hooks
wp_plugin_starter_loaded                 // Plugin fully loaded
wp_plugin_starter_before_bootstrap      // Before bootstrap
wp_plugin_starter_admin_menu            // Admin menu registration

// Feature hooks
wp_plugin_starter_hookable_services     // Register services
wp_plugin_starter_data_stores           // Register data stores
wp_plugin_starter_get_container         // Container access
```

#### Helper Functions
```php
// Service access
wp_plugin_starter_service('service-name')
wp_plugin_starter_get_container()
wp_plugin_starter_template_manager()
wp_plugin_starter_logger()

// Template functions
wp_plugin_starter_get_template('template-name', $args)
wp_plugin_starter_get_template_part('slug', 'name', $args)

// Utility functions
wp_plugin_starter_is_woocommerce_active()
wp_plugin_starter_is_dokan_active()
```

## 🤝 Contributing

We welcome contributions from the WordPress community! Here's how you can help:

### How to Contribute

1. **🍴 Fork the Repository**
   ```bash
   git clone https://github.com/yourusername/wp-plugin-starter.git
   ```

2. **🌿 Create Feature Branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```

3. **✨ Make Your Changes**
   - Follow WordPress Coding Standards
   - Add tests for new functionality
   - Update documentation

4. **🧪 Test Your Changes**
   ```bash
   composer test
   npm run build
   ```

5. **📤 Submit Pull Request**
   - Provide clear description
   - Reference related issues
   - Ensure all checks pass

### Development Guidelines

- **📋 Follow WordPress Coding Standards**
- **🧪 Write Tests** for new functionality
- **📝 Document Your Code** with PHPDoc
- **🔄 Use Semantic Versioning** for releases
- **💬 Use Conventional Commits** for messages

### Areas for Contribution

- **🐛 Bug Fixes** - Help improve stability
- **✨ New Features** - Extend functionality
- **📚 Documentation** - Improve guides and examples
- **🧪 Testing** - Increase test coverage
- **🌐 Translations** - Add language support
- **🎨 UI/UX** - Enhance admin interface

## 🐛 Support

### Getting Help

- **📖 [Documentation](docs/)** - Comprehensive guides and references
- **🐛 [Issue Tracker](https://github.com/MdAsifHossainNadim/wp-plugin-starter/issues)** - Report bugs and request features
- **💬 [Discussions](https://github.com/MdAsifHossainNadim/wp-plugin-starter/discussions)** - Community support and questions
- **📧 [Email Support](mailto:devianadim@gmail.com)** - Direct developer contact

### Reporting Issues

When reporting issues, please include:

- WordPress version
- PHP version
- Plugin version
- Steps to reproduce
- Expected vs actual behavior
- Any error messages

### Feature Requests

We love hearing your ideas! Please use the issue tracker to:

- Describe the feature clearly
- Explain the use case
- Provide examples if possible
- Consider implementation complexity

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

### What This Means

- ✅ **Free to Use** - Personal and commercial projects
- ✅ **Free to Modify** - Customize for your needs
- ✅ **Free to Distribute** - Share with others
- ⚠️ **Copyleft License** - Derivatives must be GPL licensed
- 📋 **Attribution Required** - Maintain copyright notices

---

## 🏆 Credits

**Developed by:** [Md. Asif Hossain Nadim](https://github.com/MdAsifHossainNadim)  
**Contributors:** [View all contributors](https://github.com/MdAsifHossainNadim/wp-plugin-starter/contributors)

### Technologies Used

- **WordPress** - Content management platform
- **React** - User interface library
- **PHP** - Server-side scripting
- **Node.js** - Build tools and asset compilation
- **Webpack** - Module bundling
- **SCSS** - CSS preprocessing
- **PHPUnit** - Testing framework

### Inspiration

Built on the shoulders of giants and inspired by:
- WordPress Plugin Boilerplate
- Modern PHP practices
- React best practices
- WordPress Coding Standards

---

**⭐ Star this repository if you find it helpful!**

**🤝 Contributing makes the WordPress community stronger - join us!**

---

*Made with ❤️ for the WordPress community*
