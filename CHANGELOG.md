# Changelog

All notable changes to WP Plugin Starter will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2025-05-10

### Added

- Complete rebuild with modern architecture:
    - Dependency injection container for better code organization
    - Service Provider pattern for modular service registration
    - Event Manager for centralized WordPress hooks management
    - Feature Registry for modular feature management
    - Settings Registry for structured settings management
- React-based admin interface:
    - Modular component architecture
    - Context API for state management
    - Reusable UI components
    - Dynamic tabs and fields rendering
- Advanced Product Features:
    - Image dimension restrictions with validation feedback
    - Image file size limitations and type checking
    - Granular product field visibility controls
    - Product type availability management
- Vendor Management Features:
    - Enhanced vendor registration process
    - Fine-grained vendor capability controls
    - Improved vendor account settings
- Cart and Checkout Enhancements:
    - Customizable cart buttons with styling options
    - "Buy Now" functionality for direct checkout
    - Order completion customization
- Shipping Improvements:
    - Lite shipping configuration for Dokan Lite
    - Pro shipping integration with Dokan Pro
    - Zone-based shipping controls
- Development Tooling:
    - Modern build system with Webpack
    - TypeScript support for type safety
    - Tailwind CSS with WordPress theme compatibility
    - Automated testing setup (PHPUnit, Jest)
- REST API Architecture:
    - Comprehensive endpoints for all features
    - Standard response format
    - Authentication and permission handling
    - Proper error reporting

### Changed

- Reorganized codebase for better maintainability:
    - PSR-4 autoloading standard
    - Namespaced PHP classes
    - Clear separation between frontend and backend
    - Module-based directory structure
- Improved templating system:
    - Template overrides for themes
    - Standardized template parts
    - Clear template hierarchy
- Enhanced compatibility:
    - Full support for latest WordPress (6.4+)
    - Improved Dokan compatibility (3.9.7+)
    - Better WooCommerce integration (6.0+)
    - Theme compatibility improvements
- Performance optimizations:
    - Reduced database queries
    - Improved asset loading
    - Better caching implementation
    - Conditional feature loading
- Developer experience improvements:
    - Better inline documentation
    - Standardized coding patterns
    - Semantic versioning
    - Comprehensive developer documentation

### Fixed

- Legacy compatibility issues:
    - Fixed template conflicts with older Dokan versions
    - Resolved settings conflicts with Dokan Lite and Pro
    - Fixed JavaScript conflicts with WooCommerce
- Product management issues:
    - Corrected product validation logic for special product types
    - Fixed image upload handling in various browsers
    - Resolved product filtering in vendor dashboard
    - Fixed product meta data handling
- Vendor management issues:
    - Corrected vendor registration form validation
    - Fixed capability assignment for new vendors
    - Resolved vendor profile update issues
    - Fixed vendor store settings conflicts
- Cart and checkout problems:
    - Fixed cart button display on various themes
    - Corrected AJAX cart handling
    - Resolved checkout field validation issues
    - Fixed order processing for multi-vendor orders
- Shipping calculation issues:
    - Corrected shipping zone handling
    - Fixed rate calculation for combined shipments
    - Resolved tax calculation with shipping
    - Fixed shipping method display on checkout

## [2.5.0] - 2024-12-15

### Added

- Compatibility with Dokan 3.14.3
- Product type restrictions feature with granular controls
- Vendor capability enhancements:
    - Custom capability groups
    - Role-based capability assignment
    - Capability presets for different vendor types
- Image handling improvements:
    - Basic image validation
    - Thumbnail generation options
    - Gallery enhancement features
- New vendor registration form customization options

### Changed

- Improved settings user interface:
    - Redesigned settings pages
    - Better organization of options
    - Enhanced form controls
- Enhanced WooCommerce 8.3 integration:
    - Updated hooks and filters
    - Improved template compatibility
    - Better checkout integration
- Updated language files:
    - Added 5 new languages
    - Improved translation strings
    - Better RTL support
- Optimized database queries:
    - Reduced number of queries
    - Improved caching
    - Better query structure

### Fixed

- Vendor product purchase restrictions:
    - Fixed self-purchase limitations
    - Corrected purchase validation
    - Improved error messaging
- Cart button display on various themes:
    - Fixed styling conflicts
    - Improved responsive design
    - Corrected button positioning
- Shipping calculation errors:
    - Fixed zone-based calculations
    - Corrected multi-vendor shipping
    - Resolved tax interactions

## [2.0.0] - 2024-06-20

### Added

- New settings interface:
    - Tab-based navigation
    - Improved settings organization
    - Better user experience
- Advanced vendor capabilities:
    - Product creation controls
    - Order management options
    - Commission handling features
    - Enhanced product management:
    - Basic product field controls
    - Simplified product form options
    - Category restrictions
- Better Dokan Pro integration:
    - Support for Dokan Pro features
    - Enhanced subscription handling
    - Improved vendor verification

### Changed

- Complete code restructuring:
    - Basic OOP architecture implementation
    - Improved function organization
    - Better file structure
- Improved Dokan core integration:
    - Updated hooks and filters
    - Better template handling
    - Enhanced API integration
- Updated dependencies:
    - Modern JavaScript libraries
    - Updated CSS frameworks
    - Compatible PHP libraries

### Fixed

- Compatibility issues with Dokan Lite:
    - Fixed template conflicts
    - Resolved hook priority issues
    - Corrected capability assignments
- Vendor registration problems:
    - Fixed form validation
    - Corrected email notifications
    - Resolved profile creation issues
- Product display inconsistencies:
    - Fixed grid layout issues
    - Corrected image display problems
    - Resolved responsive design issues

## [1.0.0] - 2024-01-10

### Added

- Initial release of WP Plugin Starter
- Basic vendor management features:
    - Simple registration customization
    - Basic vendor capability controls
    - Store settings options
- Product display enhancements:
    - Simple product layout options
    - Basic image controls
    - Product information display
- Simple shipping options:
    - Basic flat rate shipping
    - Free shipping thresholds
    - Local pickup options
- Cart and checkout modifications:
    - Basic cart button customization
    - Simple checkout field options
    - Order review customization

[3.0.0]: https://github.com/wpintegrity/wp-plugin-starter/compare/v2.5.0...v3.0.0
[2.5.0]: https://github.com/wpintegrity/wp-plugin-starter/compare/v2.0.0...v2.5.0
[2.0.0]: https://github.com/wpintegrity/wp-plugin-starter/compare/v1.0.0...v2.0.0
[1.0.0]: https://github.com/wpintegrity/wp-plugin-starter/releases/tag/v1.0.0

