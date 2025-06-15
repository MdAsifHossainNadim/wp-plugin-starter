# Coding Standards

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This document outlines the coding standards and conventions used in the WP Plugin Starter plugin. Following these standards ensures consistent, maintainable, and high-quality code.

## 📋 Table of Contents

- [General Guidelines](#-general-guidelines)
- [PHP Coding Standards](#-php-coding-standards)
- [JavaScript Coding Standards](#javascript-coding-standards)
- [SCSS/CSS Standards](#scsscss-standards)
- [Documentation Standards](#documentation-standards)
- [Localization](#localization)
- [Accessibility Standards](#accessibility-standards)
- [Testing Standards](#testing-standards)
- [Version Control](#version-control)

## 📝 General Guidelines

### Principles

1. **Readability**: Write code that is easy to read and understand
2. **Maintainability**: Design for future maintenance and changes
3. **Performance**: Optimize for performance where appropriate
4. **Security**: Follow security best practices
5. **Compatibility**: Maintain compatibility with WordPress ecosystem

### File Organization

- One primary class or function per file
- Logical directory structure following the architecture
- Consistent file naming conventions
- Clear separation of concerns

## 🐘 PHP Coding Standards

WP Plugin Starter follows the [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/) with some additional requirements:

### Namespace and Class Structure

- Use PSR-4 autoloading standard
- Namespace structure should match directory structure
- Class names should be in StudlyCaps (PascalCase)
- Interface names should end with `Interface`
- Abstract class names should start with `Abstract`
- Trait names should be descriptive of their function

```php
namespace WP_Plugin_Starter\Features\Product;

use WP_Plugin_Starter\Core\Container;
use WP_Plugin_Starter\Features\Base\AbstractFeature;

class ImageRestrictions extends AbstractFeature {
    // Class implementation
}
```

### Method and Function Naming

- Method and function names should be in camelCase
- Method names should be descriptive of their purpose
- Getter methods should start with `get_`
- Setter methods should start with `set_`
- Boolean methods should start with `is_`, `has_`, or `can_`

```php
public function getProductTypes() {} // Not WordPress standard
public function get_product_types() {} // Correct: WordPress standard
```

### Variable Naming

- Variable names should be in snake_case
- Private/protected properties should have descriptive names
- Constants should be in UPPER_CASE with underscores

```php
private $product_types; // Correct
const MAX_IMAGE_SIZE = 2048; // Correct
```

### Spacing and Indentation

- Use 4 spaces for indentation (not tabs)
- Line length should not exceed 100 characters where possible
- Add spaces around operators and after commas
- Control structure keywords should have one space after them
- Opening braces for classes and functions should be on the same line

```php
if ( $condition ) { // Correct: space after if and inside parentheses
    $this->do_something();
}

function example_function( $param1, $param2 ) { // Correct: spaces after commas
    // Function implementation
}
```

### Comments

- Use DocBlock comments for classes, methods, and functions
- Include parameter types, return types, and descriptions
- Add inline comments for complex logic
- Use `@since` annotations for versioning

```php
/**
 * Validates image dimensions against restrictions.
 *
 * @since 1.0.0
 *
 * @param int   $image_id The attachment ID of the image.
 * @param array $restrictions The image restrictions to validate against.
 * @return array|WP_Error Validation result or error.
 */
public function validate_image_dimensions( $image_id, $restrictions ) {
    // Method implementation
}
```

## 📜 JavaScript Coding Standards

WP Plugin Starter follows the [WordPress JavaScript Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/javascript/) with these additions for React and modern JS:

### ES6+ Features

- Use `const` and `let` instead of `var`
- Use arrow functions for callbacks and anonymous functions
- Use template literals for string concatenation
- Use destructuring for objects and arrays
- Use spread/rest operators where appropriate
- Use async/await for asynchronous operations

```javascript
// Good
const { id, title } = item;
const newItems = [...items, newItem];

// Avoid
var id = item.id;
var title = item.title;
var newItems = items.concat([newItem]);
```

### React Coding Standards

- Use functional components with hooks
- Component names should be in PascalCase
- Prop types should be clearly defined
- Use destructuring for props
- Use controlled components for forms
- Separate UI components from container components

```jsx
// Functional component with props destructuring
const ProductCard = ({ product, onSelect }) => {
	return (
		<div className="product-card">
			<h3>{product.title}</h3>
			<button onClick={() => onSelect(product.id)}>Select</button>
		</div>
	);
};

// PropTypes definition
ProductCard.propTypes = {
	product: PropTypes.shape({
		id: PropTypes.number.isRequired,
		title: PropTypes.string.isRequired,
	}).isRequired,
	onSelect: PropTypes.func.isRequired,
};
```

### State Management

- Use React Context for application-wide state
- Use `useState` for component-specific state
- Use `useReducer` for complex state logic
- Keep state as close as possible to where it's used
- Use custom hooks to encapsulate state logic

```jsx
// Custom hook for features
export const useSettings = () => {
	const [settings, setSettings] = useState({});
	const [loading, setLoading] = useState(true);

	useEffect(() => {
		const fetchSettings = async () => {
			try {
				setLoading(true);
				const response = await apiFetch({ path: '/wp-plugin-starter/v1/features' });
				setSettings(response);
			} catch (error) {
				console.error('Failed to fetch features:', error);
			} finally {
				setLoading(false);
			}
		};

		fetchSettings();
	}, []);

	const updateSetting = useCallback((key, value) => {
		setSettings((prev) => ({ ...prev, [key]: value }));
	}, []);

	return { settings, loading, updateSetting };
};
```

### File Organization

- One component per file
- Group related components in directories
- Index files for exporting components
- Separate business logic from presentation
- Keep files focused on a single responsibility

```
components/
  fields/
    toggle/
      index.js        # Main component
      style.scss      # Component styles
    select/
      index.js
      style.scss
    index.js          # Exports all field components
```

## 🎨 SCSS/CSS Standards

WP Plugin Starter uses SCSS with Tailwind CSS and follows these standards:

### SCSS Organization

- Use BEM (Block Element Modifier) naming convention
- Group related styles in partial files
- Use variables for colors, spacing, and typography
- Follow a logical ordering of properties
- Use nesting judiciously (max 3 levels)

```scss
// Variables
$primary-color: #f86e01;
$border-radius: 4px;

// Component styles
.wp-plugin-starter-card {
	background-color: white;
	border-radius: $border-radius;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

	&__header {
		padding: 16px;
		border-bottom: 1px solid #eee;

		&--large {
			padding: 24px;
		}
	}

	&__body {
		padding: 16px;
	}
}
```

### Tailwind CSS Usage

- Use Tailwind utility classes for standard styling
- Use custom CSS only for complex components
- Maintain prefix (`wps-`) to avoid conflicts
- Group related utility classes

```jsx
// Good: Grouped related Tailwind classes
<div className="wps-bg-white wps-rounded-md wps-shadow-sm wps-p-4 wps-mb-4">
	<h3 className="wps-text-lg wps-font-medium wps-text-gray-900 wps-mb-2">Card Title</h3>
	<p className="wps-text-sm wps-text-gray-500">Card content</p>
</div>
```

### Responsive Design

- Use mobile-first approach
- Use Tailwind's responsive prefixes
- Define breakpoints in the Tailwind config
- Test all components across breakpoints

```jsx
// Mobile-first approach
<div className="wps-w-full wps-md:w-1/2 wps-lg:w-1/3">{/* Content */}</div>
```

## 📚 Documentation Standards

### Code Documentation

- Use PHPDoc blocks for PHP classes, methods, and functions
- Use JSDoc for JavaScript functions and components
- Document parameters, return values, and exceptions
- Include real-world examples where helpful
- Keep documentation updated with code changes

```javascript
/**
 * Fetches features from the API.
 *
 * @param {Object} options - Fetch options
 * @param {boolean} options.forceRefresh - Whether to force a refresh
 * @returns {Promise<Object>} The features object
 * @throws {Error} When network request fails
 *
 * @example
 * // Fetch with cache
 * const features = await fetchSettings({ forceRefresh: false });
 */
const fetchSettings = async ({ forceRefresh = false } = {}) => {
	// Implementation
};
```

### README and External Documentation

- Provide clear installation instructions
- Include usage examples
- Document APIs and extension points
- Update documentation with each release
- Use Markdown for formatting

## 🌐 Localization

All user-facing strings should be properly localized:

### PHP Localization

- Use `__()`, `_e()`, `esc_html__()`, etc. for static strings
- Use `sprintf()` for strings with variables
- Always include text domain

```php
// Correct
$label = esc_html__( 'Product SettingsModel', 'wp-plugin-starter' );

// Correct: With variables
$message = sprintf(
    esc_html__( 'Product %s has been updated.', 'wp-plugin-starter' ),
    esc_html( $product_name )
);
```

### JavaScript Localization

- Use `__()` function from `@wordpress/i18n`
- Pass text domain as second parameter
- Use string literals for variables

```javascript
import { __ } from '@wordpress/i18n';

// Correct
const label = __('Product SettingsModel', 'wp-plugin-starter');

// Correct: With variables
const message = __('Product ${productName} has been updated.', 'wp-plugin-starter').replace('${productName}', productName);
```

## ♿ Accessibility Standards

WP Plugin Starter aims to meet WCAG 2.1 AA standards:

### General Guidelines

- Use semantic HTML elements
- Ensure proper heading hierarchy
- Provide alternative text for images
- Ensure sufficient color contrast
- Support keyboard navigation
- Use ARIA attributes appropriately

### React Component Accessibility

- Add appropriate ARIA roles and attributes
- Ensure focus management in modals and dialogs
- Use `onKeyDown` handlers for keyboard support
- Test with screen readers

```jsx
// Accessible toggle
const AccessibleToggle = ({ id, label, checked, onChange }) => {
	return (
		<div className="wps-toggle">
			<input
				id={id}
				type="checkbox"
				checked={checked}
				onChange={onChange}
				className="wps-toggle-input"
				aria-checked={checked}
			/>
			<label htmlFor={id} className="wps-toggle-label">
				{label}
			</label>
		</div>
	);
};
```

## 🧪 Testing Standards

### Unit Testing

- Write tests for all new features
- Follow the Arrange-Act-Assert pattern
- Use descriptive test names
- Mock dependencies and external services
- Aim for high code coverage

```php
// PHP unit test example
public function test_image_validation_rejects_oversized_images() {
    // Arrange
    $image_id = $this->create_test_image(2000, 2000);
    $restrictions = [
        'max_width' => 1000,
        'max_height' => 1000,
    ];

    // Act
    $result = $this->validator->validate_image($image_id, $restrictions);

    // Assert
    $this->assertInstanceOf(WP_Error::class, $result);
    $this->assertEquals('image_too_large', $result->get_error_code());
}
```

```javascript
// JavaScript test example
describe('useSettings hook', () => {
	it('should update a setting value', () => {
		// Arrange
		const { result } = renderHook(() => useSettings());

		// Act
		act(() => {
			result.current.updateSetting('test_key', 'test_value');
		});

		// Assert
		expect(result.current.settings.test_key).toBe('test_value');
	});
});
```

### Integration Testing

- Test feature integrations
- Test with WordPress, WooCommerce, and Dokan
- Verify correct database interactions
- Test admin and frontend interfaces

### End-to-End Testing

- Test critical user flows
- Verify multi-step processes work correctly
- Test across different browsers
- Include mobile testing

## 📝 Version Control

### Git Workflow

- Follow the Gitflow workflow
- Create feature branches from `develop`
- Use descriptive branch names
- Create pull requests for code review
- Squash commits when merging to develop

### Commit Messages

WP Plugin Starter uses [Conventional Commits](https://www.conventionalcommits.org/) format:

```
type(scope): subject

body

footer
```

Types:

- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code changes that neither fix bugs nor add features
- `perf`: Performance improvements
- `test`: Adding or fixing tests
- `chore`: Changes to the build process, tooling, etc.

Examples:

```
feat(product): add image dimension restrictions

Implements image dimension validation to ensure product images meet
size requirements.

Closes #123
```

```
fix(vendor): correct capability assignment for new vendors

The capability 'manage_product' wasn't being properly assigned to
new vendors, preventing them from creating products.

Fixes #456
```

### Pull Requests

- Provide a clear description of changes
- Reference related issues
- Include screenshots for UI changes
- Update documentation if necessary
- Ensure all tests pass
- Request review from relevant team members

## 📋 Code Review Checklist

When reviewing code, consider the following:

1. **Functionality**: Does the code work as intended?
2. **Security**: Are there potential security issues?
3. **Performance**: Could there be performance problems?
4. **Readability**: Is the code easy to understand?
5. **Maintainability**: Will the code be easy to maintain?
6. **Standards**: Does the code follow our coding standards?
7. **Documentation**: Is the code properly documented?
8. **Testing**: Are there sufficient tests?
9. **Accessibility**: Does the code meet accessibility standards?
10. **Compatibility**: Is the code compatible with our minimum requirements?

## 🛠️ Tools and Configuration

### PHP Tools

#### PHPCS Configuration

The `.phpcs.xml.dist` file configures PHP CodeSniffer:

```xml
<?xml version="1.0"?>
<ruleset name="WP Plugin Starter">
    <description>WP Plugin Starter Coding Standards</description>

    <!-- What to scan -->
    <file>.</file>
    <exclude-pattern>/vendor/</exclude-pattern>
    <exclude-pattern>/node_modules/</exclude-pattern>
    <exclude-pattern>/build/</exclude-pattern>
    <exclude-pattern>/assets/</exclude-pattern>
    <exclude-pattern>/tests/</exclude-pattern>

    <!-- How to scan -->
    <arg value="sp"/> <!-- Show sniff and progress -->
    <arg name="colors"/>
    <arg name="extensions" value="php"/>
    <arg name="parallel" value="8"/>

    <!-- Rules -->
    <rule ref="WordPress">
        <!-- Exclude rules incompatible with PSR-4 -->
        <exclude name="WordPress.Files.FileName"/>
    </rule>

    <!-- Additional rules -->
    <rule ref="PSR1.Methods.CamelCapsMethodName.NotCamelCaps">
        <severity>0</severity>
    </rule>

    <config name="minimum_supported_wp_version" value="6.0"/>
    <config name="testVersion" value="7.4-"/>
</ruleset>
```

#### PHPStan Configuration

The `phpstan.neon` file configures static analysis:

```yaml
parameters:
    level: 5
    paths:
        - includes
    excludes_analyse:
        - vendor
        - node_modules
        - build
    bootstrapFiles:
        - vendor/php-stubs/wordpress-stubs/wordpress-stubs.php
        - vendor/php-stubs/woocommerce-stubs/woocommerce-stubs.php
    ignoreErrors:
        # Add specific errors to ignore
```

### JavaScript Tools

#### ESLint Configuration

The `.eslintrc.js` file configures JavaScript linting:

```javascript
module.exports = {
	root: true,
	extends: ['plugin:@wordpress/eslint-plugin/recommended', 'plugin:react-hooks/recommended'],
	parserOptions: {
		ecmaVersion: 2021,
		sourceType: 'module',
		ecmaFeatures: {
			jsx: true,
		},
	},
	env: {
		browser: true,
		es6: true,
		node: true,
	},
	rules: {
		// Custom rules
		'react/react-in-jsx-scope': 'off',
		camelcase: 'off',
		'no-console': ['warn', { allow: ['warn', 'error'] }],
		'react-hooks/rules-of-hooks': 'error',
		'react-hooks/exhaustive-deps': 'warn',
	},
};
```

#### Prettier Configuration

The `.prettierrc` file configures code formatting:

```json
{
	"singleQuote": true,
	"tabWidth": 4,
	"trailingComma": "es5",
	"printWidth": 100,
	"semi": true,
	"bracketSpacing": true,
	"jsxBracketSameLine": false,
	"arrowParens": "always",
	"endOfLine": "lf"
}
```

### SCSS/CSS Tools

#### Stylelint Configuration

The `.stylelintrc` file configures CSS/SCSS linting:

```json
{
	"extends": ["stylelint-config-standard-scss", "stylelint-config-recommended"],
	"rules": {
		"indentation": 4,
		"max-nesting-depth": 3,
		"selector-class-pattern": "^([a-z][a-z0-9]*)(-[a-z0-9]+)*(__[a-z0-9]+)?(--[a-z0-9]+)?$",
		"selector-max-id": 0,
		"selector-no-qualifying-type": [
			true,
			{
				"ignore": ["attribute", "class"]
			}
		],
		"at-rule-no-unknown": null,
		"scss/at-rule-no-unknown": true
	}
}
```

### Editor Configuration

The `.editorconfig` file ensures consistent coding style across editors:

```ini
root = true

[*]
charset = utf-8
end_of_line = lf
insert_final_newline = true
trim_trailing_whitespace = true
indent_style = space

[*.{php,js,jsx,ts,tsx,css,scss,json,yml,yaml}]
indent_size = 4

[*.md]
trim_trailing_whitespace = false
```

## 📏 Enforcing Standards

### Pre-commit Hooks

Use Husky and lint-staged to enforce standards on commit:

```json
// package.json excerpt
{
	"husky": {
		"hooks": {
			"pre-commit": "lint-staged"
		}
	},
	"lint-staged": {
		"*.php": ["vendor/bin/phpcs --standard=.phpcs.xml.dist"],
		"*.{js,jsx}": ["eslint --fix", "prettier --write"],
		"*.{scss,css}": ["stylelint --fix", "prettier --write"]
	}
}
```

### CI Integration

Configure GitHub Actions to check code standards:

```yaml
# .github/workflows/code-quality.yml
name: Code Quality

on:
    push:
        branches: [main, develop]
    pull_request:
        branches: [main, develop]

jobs:
    php-code-quality:
        runs-on: ubuntu-latest
        steps:
            - uses: actions/checkout@v2

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: '7.4'
                  tools: composer, phpcs, phpstan

            - name: Install dependencies
              run: composer install --prefer-dist --no-progress

            - name: Run PHPCS
              run: vendor/bin/phpcs --standard=.phpcs.xml.dist

            - name: Run PHPStan
              run: vendor/bin/phpstan analyse

    js-code-quality:
        runs-on: ubuntu-latest
        steps:
            - uses: actions/checkout@v2

            - name: Setup Node.js
              uses: actions/setup-node@v2
              with:
                  node-version: '16'

            - name: Install dependencies
              run: npm ci

            - name: Run ESLint
              run: npm run lint:js

            - name: Run Stylelint
              run: npm run lint:style
```

## ✅ Compliance Verification

To verify code complies with standards:

### PHP Compliance

```bash
# Check PHP coding standards
composer run phpcs

# Run static analysis
composer run phpstan

# Fix automatically fixable issues
composer run phpcbf
```

### JavaScript/CSS Compliance

```bash
# Check JavaScript/React code
npm run lint:js

# Check SCSS/CSS code
npm run lint:style

# Format code
npm run format
```

## 🎓 Learning Resources

To learn more about the coding standards:

- [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/)
- [WordPress JavaScript Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/javascript/)
- [WordPress CSS Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/css/)
- [React Best Practices](https://reactjs.org/docs/thinking-in-react.html)
- [BEM Methodology](http://getbem.com/introduction/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Conventional Commits](https://www.conventionalcommits.org/)

## 🔄 Updates and Revisions

This coding standards document is subject to updates. Any significant changes will be communicated to the development team and documented in the version history below.

### Version History

| Version | Date       | Changes                         |
| ------- | ---------- | ------------------------------- |
| 1.0     | 2025-05-01 | Initial release                 |
| 1.1     | 2025-05-15 | Added React component standards |
| 1.2     | 2025-06-01 | Updated Tailwind CSS guidelines |
