# Testing Guide

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This document provides comprehensive guidelines for testing WP Plugin Starter, including testing strategies, tools, and best practices.

## 📋 Table of Contents

- [Testing Strategy](#-testing-strategy)
- [Unit Testing](#-unit-testing)
- [Integration Testing](#-integration-testing)
- [End-to-End Testing](#-end-to-end-testing)
- [Manual Testing](#-manual-testing)
- [Accessibility Testing](#-accessibility-testing)
- [Performance Testing](#-performance-testing)
- [Security Testing](#-security-testing)
- [Continuous Integration](#-continuous-integration)
- [Test Environment Setup](#-test-environment-setup)

## 🎯 Testing Strategy

WP Plugin Starter follows a comprehensive testing strategy that combines various testing methodologies:

### Testing Pyramid

1. **Unit Tests**: Fast, isolated tests for individual components and functions
2. **Integration Tests**: Testing how components work together
3. **End-to-End Tests**: Testing complete user flows
4. **Manual Testing**: Human verification of functionality and usability

### Test Coverage Goals

- **PHP Code**: Aim for 80%+ unit test coverage
- **JavaScript Code**: Aim for 70%+ unit test coverage
- **Critical Paths**: 100% coverage for critical functionality
- **API Endpoints**: 100% testing of all endpoints

### Testing Workflow

1. **Developers**: Write unit tests for new code
2. **QA Team**: Perform integration and end-to-end testing
3. **Release Manager**: Verify all tests pass before release
4. **Community**: Provide feedback and bug reports

## 🧪 Unit Testing

### PHP Unit Testing

WP Plugin Starter uses PHPUnit for PHP unit testing.

#### Setup

1. Install dependencies:

    ```bash
    composer install
    ```

2. Run tests:
    ```bash
    composer run test
    ```

#### Writing PHP Unit Tests

1. Create test files in the `tests/php/unit/` directory
2. Name test files with the `Test` suffix (e.g., `ImageValidatorTest.php`)
3. Extend the `WP_UnitTestCase` class
4. Use descriptive test method names

```php
<?php
namespace WP_Plugin_Starter\Tests\Unit;

use WP_Plugin_Starter\Features\Product\Image_Restrictions;
use WP_UnitTestCase;
use WP_Error;

class ImageRestrictionsTest extends WP_UnitTestCase {
    private $image_restrictions;

    public function setUp(): void {
        parent::setUp();

        // Create mock container
        $container = $this->createMock(\WP_Plugin_Starter\Core\Container::class);

        // Initialize the feature
        $this->image_restrictions = new Image_Restrictions($container);
    }

    public function test_validate_image_dimensions_with_valid_image() {
        // Arrange
        $image_id = $this->create_test_image(800, 600);
        $restrictions = [
            'max_width' => 1000,
            'max_height' => 1000,
            'min_width' => 500,
            'min_height' => 400,
        ];

        // Act
        $result = $this->image_restrictions->validate_image_dimensions($image_id, $restrictions);

        // Assert
        $this->assertTrue($result);
    }

    public function test_validate_image_dimensions_with_oversized_image() {
        // Arrange
        $image_id = $this->create_test_image(1200, 1000);
        $restrictions = [
            'max_width' => 1000,
            'max_height' => 800,
        ];

        // Act
        $result = $this->image_restrictions->validate_image_dimensions($image_id, $restrictions);

        // Assert
        $this->assertInstanceOf(WP_Error::class, $result);
        $this->assertEquals('image_too_large', $result->get_error_code());
    }

    private function create_test_image($width, $height) {
        // Create a test image attachment and return its ID
        // Implementation details...
    }
}
```

#### Mocking in PHP Tests

Use PHPUnit's mocking capabilities for dependencies:

```php
// Create a mock
$options_manager = $this->createMock(\WP_Plugin_Starter\Core\OptionsManager::class);

// Configure the mock
$options_manager->method('get_option')
    ->with('feature_enabled', false)
    ->willReturn(true);

// Inject the mock
$feature->set_options_manager($options_manager);
```

#### Test Data Providers

Use data providers for testing multiple scenarios:

```php
/**
 * @dataProvider dimension_provider
 */
public function test_image_dimensions_validation($width, $height, $restrictions, $expected) {
    $image_id = $this->create_test_image($width, $height);
    $result = $this->image_restrictions->validate_image_dimensions($image_id, $restrictions);

    if ($expected === true) {
        $this->assertTrue($result);
    } else {
        $this->assertInstanceOf(WP_Error::class, $result);
        $this->assertEquals($expected, $result->get_error_code());
    }
}

public function dimension_provider() {
    return [
        // width, height, restrictions, expected result
        [800, 600, ['max_width' => 1000, 'max_height' => 1000], true],
        [1200, 1000, ['max_width' => 1000, 'max_height' => 1000], 'image_too_large'],
        [300, 200, ['min_width' => 400, 'min_height' => 300], 'image_too_small'],
        [800, 400, ['aspect_ratio' => '1:1', 'aspect_ratio_tolerance' => 0.1], 'invalid_aspect_ratio'],
    ];
}
```

### JavaScript Unit Testing

WP Plugin Starter uses Jest for JavaScript unit testing.

#### Setup

1. Install dependencies:

    ```bash
    npm install
    ```

2. Run tests:

    ```bash
    npm run test
    ```

3. Run tests with coverage:
    ```bash
    npm run test:coverage
    ```

#### Writing JavaScript Unit Tests

1. Create test files next to the code they test
2. Name test files with `.test.js` suffix (e.g., `useSettings.test.js`)
3. Use Jest's testing functions
4. Write descriptive test names

```javascript
// src/admin/hooks/use-features.test.js
import { renderHook, act } from '@testing-library/react-hooks';
import apiFetch from '@wordpress/api-fetch';
import { useSettings } from './use-features';

// Mock apiFetch
jest.mock('@wordpress/api-fetch');

describe('useSettings hook', () => {
	beforeEach(() => {
		// Clear all mocks between tests
		jest.clearAllMocks();

		// Setup default mock response
		apiFetch.mockResolvedValue({
			structure: {
				vendor: {
					title: 'Vendor',
					sections: {},
				},
			},
			values: {
				test_setting: 'test_value',
			},
		});
	});

	it('should fetch features on mount', async () => {
		// Arrange & Act
		const { result, waitForNextUpdate } = renderHook(() => useSettings());

		// Wait for the async operation to complete
		await waitForNextUpdate();

		// Assert
		expect(apiFetch).toHaveBeenCalledWith({ path: '/wp-plugin-starter/v1/features' });
		expect(result.current.settings).toEqual({ test_setting: 'test_value' });
		expect(result.current.structure).toEqual({ vendor: { title: 'Vendor', sections: {} } });
		expect(result.current.isLoading).toBe(false);
	});

	it('should update a setting value', async () => {
		// Arrange
		const { result, waitForNextUpdate } = renderHook(() => useSettings());

		// Wait for the initial fetch
		await waitForNextUpdate();

		// Act
		act(() => {
			result.current.updateSetting('new_setting', 'new_value');
		});

		// Assert
		expect(result.current.settings).toEqual({
			test_setting: 'test_value',
			new_setting: 'new_value',
		});
	});

	it('should save features', async () => {
		// Arrange
		apiFetch.mockImplementation((options) => {
			if (options.method === 'POST') {
				return Promise.resolve({
					success: true,
					message: 'SettingsModel saved successfully.',
					values: { ...options.data },
				});
			}

			return Promise.resolve({
				structure: { vendor: { title: 'Vendor', sections: {} } },
				values: { test_setting: 'test_value' },
			});
		});

		const { result, waitForNextUpdate } = renderHook(() => useSettings());

		// Wait for the initial fetch
		await waitForNextUpdate();

		// Act
		let saveResult;
		await act(async () => {
			saveResult = await result.current.saveSettings();
		});

		// Assert
		expect(apiFetch).toHaveBeenCalledWith({
			path: '/wp-plugin-starter/v1/features',
			method: 'POST',
			data: { test_setting: 'test_value' },
		});
		expect(saveResult).toEqual({
			success: true,
			message: 'SettingsModel saved successfully.',
		});
	});

	it('should handle fetch errors', async () => {
		// Arrange
		apiFetch.mockRejectedValue(new Error('Failed to fetch'));

		// Act
		const { result, waitForNextUpdate } = renderHook(() => useSettings());

		// Wait for the async operation to complete
		await waitForNextUpdate();

		// Assert
		expect(result.current.settings).toEqual({});
		expect(result.current.isLoading).toBe(false);
		// console.error should have been called
	});
});
```

#### Testing React Components

For React components, use React Testing Library:

```javascript
// src/admin/components/fields/toggle/index.test.js
import { render, screen, fireEvent } from '@testing-library/react';
import ToggleField from './index';

describe('ToggleField', () => {
	it('renders correctly with provided props', () => {
		// Arrange
		const field = {
			id: 'test_toggle',
			label: 'Test Toggle',
			description: 'This is a test toggle',
		};
		const value = false;
		const onChange = jest.fn();

		// Act
		render(<ToggleField field={field} value={value} onChange={onChange} />);

		// Assert
		expect(screen.getByText('Test Toggle')).toBeInTheDocument();
		expect(screen.getByText('This is a test toggle')).toBeInTheDocument();
		const toggle = screen.getByRole('checkbox');
		expect(toggle).not.toBeChecked();
	});

	it('calls onChange when toggled', () => {
		// Arrange
		const field = { id: 'test_toggle', label: 'Test Toggle' };
		const value = false;
		const onChange = jest.fn();

		// Act
		render(<ToggleField field={field} value={value} onChange={onChange} />);

		fireEvent.click(screen.getByRole('checkbox'));

		// Assert
		expect(onChange).toHaveBeenCalledWith(true);
	});

	it('renders checked when value is true', () => {
		// Arrange
		const field = { id: 'test_toggle', label: 'Test Toggle' };

		// Act
		render(<ToggleField field={field} value={true} onChange={() => {}} />);

		// Assert
		expect(screen.getByRole('checkbox')).toBeChecked();
	});
});
```

## 🔄 Integration Testing

Integration tests check how different components work together.

### PHP Integration Testing

Create PHP integration tests in the `tests/php/integration/` directory:

```php
<?php
namespace WP_Plugin_Starter\Tests\Integration;

use WP_Plugin_Starter\Core\Container;use WP_Plugin_Starter\Core\OptionsManager;use WP_Plugin_Starter\Features\Product\Image_Restrictions;use WP_UnitTestCase;

class ImageRestrictionsIntegrationTest extends WP_UnitTestCase {
    private $container;
    private $image_restrictions;
    private $options_manager;

    public function setUp(): void {
        parent::setUp();

        // Create a real container with real dependencies
        $this->container = new Container();

        // Register real services
        $this->options_manager = new OptionsManager();
        $this->container->singleton('options_manager', $this->options_manager);

        // Initialize the feature with real dependencies
        $this->image_restrictions = new Image_Restrictions($this->container);
        $this->image_restrictions->init();
    }

    public function test_image_validation_integration() {
        // Set up test features
        $this->options_manager->update_option('image_max_width', 1000);
        $this->options_manager->update_option('image_max_height', 1000);

        // Create a test product
        $product_id = $this->factory->post->create([
            'post_type' => 'product',
            'post_title' => 'Test Product',
        ]);

        // Generate a test image and attach it to the product
        $image_id = $this->create_test_attachment($product_id);

        // Test the entire validation flow
        $result = apply_filters('wp_plugin_starter_validate_product_image', true, $image_id, $product_id);

        // The result depends on the generated image dimensions
        // This is testing the real validation logic integrated with WordPress hooks
        $this->assertIsBool($result);
    }

    private function create_test_attachment($parent_post_id) {
        // Create a real attachment
        $filename = WP_PLUGIN_STARTER_PLUGIN_PATH . 'tests/fixtures/test-image.jpg';

        $attachment_id = $this->factory->attachment->create_upload_object([
            'file' => $filename,
            'post_parent' => $parent_post_id,
        ]);

        return $attachment_id;
    }
}
```

### JavaScript Integration Testing

Test how React components interact with each other:

```javascript
// src/admin/pages/features/index.test.js
import { render, screen, waitFor } from '@testing-library/react';
import { SettingsProvider } from '../../context/features-context';
import SettingsPage from './index';
import apiFetch from '@wordpress/api-fetch';

// Mock the API fetch
jest.mock('@wordpress/api-fetch');

// Mock child components
jest.mock('./tabs/vendor', () => () => <div data-testid="vendor-tab">Vendor Tab</div>);
jest.mock('./tabs/product', () => () => <div data-testid="product-tab">Product Tab</div>);

describe('SettingsPage', () => {
	beforeEach(() => {
		// Setup default mock response
		apiFetch.mockResolvedValue({
			structure: {
				vendor: {
					title: 'Vendor',
					icon: 'dashicons-businessman',
					sections: {},
				},
				product: {
					title: 'Product',
					icon: 'dashicons-products',
					sections: {},
				},
			},
			values: {},
		});
	});

	it('renders the features page with tabs', async () => {
		// Arrange & Act
		render(
			<SettingsProvider>
				<SettingsPage />
			</SettingsProvider>
		);

		// Initial loading state
		expect(screen.getByText('Loading features...')).toBeInTheDocument();

		// Wait for data to load
		await waitFor(() => {
			expect(screen.queryByText('Loading features...')).not.toBeInTheDocument();
		});

		// Assert tab navigation
		expect(screen.getByRole('tab', { name: 'Vendor' })).toBeInTheDocument();
		expect(screen.getByRole('tab', { name: 'Product' })).toBeInTheDocument();

		// Assert first tab is active by default
		expect(screen.getByTestId('vendor-tab')).toBeInTheDocument();
	});

	// More integration tests...
});
```

## 🌐 End-to-End Testing

End-to-end tests verify complete user workflows.

### Playwright Setup

WP Plugin Starter uses Playwright for E2E testing:

1. Install Playwright:

    ```bash
    npm install --save-dev @playwright/test
    npx playwright install
    ```

2. Run tests:
    ```bash
    npm run test:e2e
    ```

### Writing E2E Tests

Create tests in the `tests/e2e/` directory:

```javascript
// tests/e2e/features-page.spec.js
import { test, expect } from '@playwright/test';

test.describe('SettingsModel Page', () => {
	// Define variables accessible in all tests
	let page;

	test.beforeEach(async ({ browser }) => {
		// Create a new page for each test
		page = await browser.newPage();

		// Login to WordPress
		await page.goto('/wp-login.php');
		await page.fill('#user_login', 'admin');
		await page.fill('#user_pass', 'password');
		await page.click('#wp-submit');

		// Navigate to WP Plugin Starter features
		await page.goto('/wp-admin/admin.php?page=wp-plugin-starter-features');
	});

	test('should display features tabs', async () => {
		// Check page title
		await expect(page.locator('h1')).toContainText('WP Plugin Starter SettingsModel');

		// Check tabs are present
		await expect(page.locator('.wp-plugin-starter-features-tabs [role="tab"]')).toHaveCount.greaterThan(0);

		// Check first tab is selected by default
		const firstTab = page.locator('.wp-plugin-starter-features-tabs [role="tab"]').first();
		await expect(firstTab).toHaveClass(/is-active/);
	});

	test('should save features', async () => {
		// Find a toggle field
		const toggle = page.locator('.components-form-toggle__input').first();

		// Get current state
		const initialState = await toggle.isChecked();

		// Toggle the state
		await toggle.click();

		// Should now be in the opposite state
		await expect(toggle).toBeChecked(!initialState);

		// Click save button
		await page.click('button:has-text("Save Changes")');

		// Should show success message
		await expect(page.locator('.components-snackbar')).toBeVisible();
		await expect(page.locator('.components-snackbar')).toContainText('SettingsModel saved');

		// Reload the page to verify persistence
		await page.reload();

		// The toggle should maintain its new state
		await expect(toggle).toBeChecked(!initialState);
	});

	test('should switch between tabs', async () => {
		// Get tabs
		const tabs = page.locator('.wp-plugin-starter-features-tabs [role="tab"]');

		// Click second tab
		await tabs.nth(1).click();

		// Second tab should be active
		await expect(tabs.nth(1)).toHaveClass(/is-active/);

		// Content should update
		await expect(page.locator('.wp-plugin-starter-features-tabs__tabpanel')).toBeVisible();
	});
});
```

### Visual Regression Testing

Add visual comparison to E2E tests:

```javascript
test('features page visual regression', async () => {
	// Wait for page to stabilize
	await page.waitForTimeout(1000);

	// Take a screenshot
	const screenshot = await page.screenshot();

	// Compare with baseline
	expect(screenshot).toMatchSnapshot('features-page.png');
});
```

## 👐 Manual Testing

Some aspects of WP Plugin Starter require manual testing.

### Test Cases

Organize manual test cases in a structured format:

```markdown
# Manual Test Case: Product Image Validation

## Preconditions

- WordPress with WooCommerce and WP Plugin Starter installed
- WP Plugin Starter activated
- Logged in as vendor

## Test Steps

1. Navigate to Vendor Dashboard
2. Click "Add New Product"
3. Click "Add Product Images"
4. Try to upload an image that exceeds the configured maximum dimensions
5. Try to upload an image below the configured minimum dimensions
6. Try to upload an image with allowed dimensions

## Expected Results

- Step 4: System should show an error message about image being too large
- Step 5: System should show an error message about image being too small
- Step 6: Image should upload successfully
```

### Testing Checklist

Create checklists for regular testing:

#### Pre-Release Checklist

- [ ] Test on latest WordPress version
- [ ] Test on latest WooCommerce version
- [ ] Test on latest WP Plugin Starter version
- [ ] Test with popular themes (Astra, Storefront, etc.)
- [ ] Test all settings work correctly
- [ ] Test all features with various configurations
- [ ] Test uninstall and reinstall process
- [ ] Test upgrade from previous version

#### Cross-Browser Testing

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile browsers (iOS Safari, Chrome for Android)

#### User Role Testing

- [ ] Administrator
- [ ] Shop Manager
- [ ] Vendor
- [ ] Customer
- [ ] Guest

### User Acceptance Testing

For major features, create a User Acceptance Testing (UAT) document:

```markdown
# User Acceptance Testing: Image Restrictions Feature

## Feature Description

The Image Restrictions feature allows marketplace administrators to enforce specific dimensions and file size limits for product images.

## Test Scenarios

### Scenario 1: Administrator Configuration

1. Administrator logs in and navigates to WP Plugin Starter > Settings > Product
2. Administrator configures image restrictions with specific values
3. Administrator saves settings
4. Settings should be saved successfully

### Scenario 2: Vendor Experience

1. Vendor logs in to dashboard
2. Vendor attempts to upload images for products
3. System should enforce restrictions defined by administrator
4. Clear error messages should be displayed for non-compliant images

### Scenario 3: Customer Experience

1. Customer views products on frontend
2. Product images should be consistent in dimensions
3. Page loading times should be reasonable due to optimized images

## Feedback Collection

Please provide feedback on:

- Usability of the configuration interface
- Clarity of error messages for vendors
- Performance impact of image validation
- Any bugs or issues encountered
```

## ♿ Accessibility Testing

Ensure WP Plugin Starter is accessible to all users.

### Automated Accessibility Testing

Use axe-core for automated testing:

```javascript
// tests/e2e/accessibility.spec.js
import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';

test.describe('Accessibility', () => {
	test('features page should be accessible', async ({ page }) => {
		// Navigate to features page
		await page.goto('/wp-admin/admin.php?page=wp-plugin-starter-features');

		// Run accessibility tests
		const accessibilityScanResults = await new AxeBuilder({ page }).analyze();

		// Should have no violations
		expect(accessibilityScanResults.violations).toEqual([]);
	});
});
```

### Manual Accessibility Checklist

- [ ] All interactive elements are keyboard accessible
- [ ] Focus states are clearly visible
- [ ] Color contrast meets WCAG AA standards
- [ ] Form inputs have associated labels
- [ ] Error messages are clear and accessible
- [ ] Page structure uses proper heading hierarchy
- [ ] Images have alt text
- [ ] ARIA attributes are correctly implemented
- [ ] Screen reader testing completed

## 🚀 Performance Testing

Evaluate WP Plugin Starter's performance impact.

### Frontend Performance

Measure key metrics using Lighthouse:

```javascript
// tests/e2e/performance.spec.js
import { test, expect } from '@playwright/test';
import { playAudit } from 'playwright-lighthouse';

test.describe('Performance', () => {
	test('product page performance', async ({ browser }) => {
		const page = await browser.newPage();

		// Navigate to a product page
		await page.goto('/product/sample-product/');

		// Run Lighthouse audit
		const audit = await playAudit(page, {
			categories: ['performance'],
			thresholds: {
				'first-contentful-paint': 2000,
				'largest-contentful-paint': 2500,
				'cumulative-layout-shift': 0.1,
				'total-blocking-time': 300,
			},
		});

		// Check results
		expect(audit.scores.performance).toBeGreaterThanOrEqual(0.9);
	});
});
```

### Backend Performance

Test admin page loading times:

```php
<?php
namespace WP_Plugin_Starter\Tests\Performance;

use WP_UnitTestCase;

class AdminPerformanceTest extends WP_UnitTestCase {
    public function test_settings_page_query_count() {
        global $wpdb;

        // Start query count
        $initial_query_count = get_num_queries();

        // Simulate loading the features page
        do_action('wp_plugin_starter_load_settings_page');

        // Get final query count
        $final_query_count = get_num_queries();
        $query_difference = $final_query_count - $initial_query_count;

        // Assert reasonable query count
        $this->assertLessThanOrEqual(20, $query_difference, 'Too many queries on features page load');
    }
}
```

## 🔒 Security Testing

Ensure WP Plugin Starter is secure against common vulnerabilities.

### OWASP Top 10 Testing

1. **Injection**:

    - Test all input fields with SQL injection attempts
    - Verify input sanitization and validation

2. **Broken Authentication**:

    - Test permission checks
    - Verify capability validation

3. **Sensitive Data Exposure**:

    - Ensure sensitive data is properly secured
    - Check for exposure in API responses

4. **XML External Entities (XXE)**:

    - Test XML parsing if applicable

5. **Broken Access Control**:

    - Verify user role restrictions
    - Test direct access to admin functionality

6. **Security Misconfiguration**:

    - Check for default credentials
    - Verify error handling doesn't expose sensitive information

7. **Cross-Site Scripting (XSS)**:

    - Test all output for proper escaping
    - Verify form inputs are sanitized

8. **Insecure Deserialization**:

    - Test serialized data handling

9. **Using Components with Known Vulnerabilities**:

    - Audit dependencies

10. **Insufficient Logging & Monitoring**:
    - Verify critical actions are logged

### WordPress-Specific Security Testing

- Test nonce verification
- Check capability checks
- Verify data sanitization and validation
- Test AJAX endpoints
- Verify REST API authentication

## 🔄 Continuous Integration

Automate testing in the CI/CD pipeline.

### GitHub Actions Configuration

The CI workflow in `.github/workflows/test.yml`:

```yaml
name: Test

on:
    push:
        branches: [main, develop]
    pull_request:
        branches: [main, develop]

jobs:
    php-tests:
        runs-on: ubuntu-latest
        strategy:
            matrix:
                php-versions: ['7.4', '8.0', '8.1']
                wp-versions: ['latest', '6.4', '6.3']

        steps:
            - uses: actions/checkout@v2

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: ${{ matrix.php-versions }}
                  tools: composer

            - name: Setup WordPress
              uses: wp-cli/setup-wordpress@v1
              with:
                  version: ${{ matrix.wp-versions }}

            - name: Install dependencies
              run: composer install

            - name: Run PHP tests
              run: composer run test

    js-tests:
        runs-on: ubuntu-latest

        steps:
            - uses: actions/checkout@v2

            - name: Setup Node.js
              uses: actions/setup-node@v2
              with:
                  node-version: '16'

            - name: Install dependencies
              run: npm ci

            - name: Run JS tests
              run: npm run test

    e2e-tests:
        runs-on: ubuntu-latest

        steps:
            - uses: actions/checkout@v2

            - name: Setup Node.js
              uses: actions/setup-node@v2
              with:
                  node-version: '16'

            - name: Install dependencies
              run: npm ci

            - name: Install Playwright
              run: npx playwright install --with-deps

            - name: Setup WordPress
              uses: wp-cli/setup-wordpress@v1
              with:
                  core: latest
                  plugins: woocommerce,dokan-lite
                  theme: storefront

            - name: Install plugin
              run: |
                  npm run build
                  wp plugin install --activate ./build/wp-plugin-starter.zip

            - name: Run E2E tests
              run: npm run test:e2e
```

## 🏗️ Test Environment Setup

### Local Testing Environment

1. **Install WordPress Test Suite**:

    ```bash
    bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
    ```

2. **Configure PHPUnit**:
   Create a `phpunit.xml.dist` file:

    ```xml
    <?xml version="1.0"?>
    <phpunit
        bootstrap="tests/bootstrap.php"
        backupGlobals="false"
        colors="true"
        convertErrorsToExceptions="true"
        convertNoticesToExceptions="true"
        convertWarningsToExceptions="true"
    >
        <testsuites>
            <testsuite name="unit">
                <directory prefix="test-" suffix=".php">./tests/php/unit</directory>
            </testsuite>
            <testsuite name="integration">
                <directory prefix="test-" suffix=".php">./tests/php/integration</directory>
            </testsuite>
        </testsuites>
    </phpunit>
    ```

3. **Create Bootstrap File**:
   Create `tests/bootstrap.php`:

    ```php
    <?php
    // Load WordPress test suite
    $_tests_dir = getenv('WP_TESTS_DIR');
    if (!$_tests_dir) {
        $_tests_dir = '/tmp/wordpress-tests-lib';
    }

    // Load WordPress
    require_once $_tests_dir . '/includes/functions.php';

    // Load Composer autoloader
    require_once dirname(__DIR__) . '/vendor/autoload.php';

    // Load tests
    require_once $_tests_dir . '/includes/bootstrap.php';
    ```

### Docker Test Environment

Use Docker for isolated testing:

1. **Create docker-compose.yml**:

    ```yaml
    version: '3'

    services:
        wordpress:
            image: wordpress:latest
            ports:
                - '8080:80'
            environment:
                WORDPRESS_DB_HOST: db
                WORDPRESS_DB_NAME: wordpress
                WORDPRESS_DB_USER: wordpress
                WORDPRESS_DB_PASSWORD: wordpress
            volumes:
                - ./:/var/www/html/wp-content/plugins/wp-plugin-starter
                - wordpress:/var/www/html

        db:
            image: mysql:5.7
            environment:
                MYSQL_ROOT_PASSWORD: root
                MYSQL_DATABASE: wordpress
                MYSQL_USER: wordpress
                MYSQL_PASSWORD: wordpress
            volumes:
                - db:/var/lib/mysql

        wordpress-tests:
            image: wordpress:cli
            environment:
                WORDPRESS_DB_HOST: db-tests
                WORDPRESS_DB_NAME: wordpress_tests
                WORDPRESS_DB_USER: wordpress
                WORDPRESS_DB_PASSWORD: wordpress
            volumes:
                - ./:/var/www/html/wp-content/plugins/wp-plugin-starter
                - wordpress-tests:/var/www/html
            depends_on:
                - db-tests

        db-tests:
            image: mysql:5.7
            environment:
                MYSQL_ROOT_PASSWORD: root
                MYSQL_DATABASE: wordpress_tests
                MYSQL_USER: wordpress
                MYSQL_PASSWORD: wordpress
            volumes:
                - db-tests:/var/lib/mysql

    volumes:
        wordpress:
        db:
        wordpress-tests:
        db-tests:
    ```

2. **Start environment**:

    ```bash
    docker-compose up -d
    ```

3. **Run tests in container**:
    ```bash
    docker-compose run wordpress-tests wp scaffold plugin-tests wp-plugin-starter
    docker-compose run wordpress-tests bash -c "cd /var/www/html/wp-content/plugins/wp-plugin-starter && bin/install-wp-tests.sh wordpress_tests root '' db-tests latest"
    docker-compose run wordpress-tests bash -c "cd /var/www/html/wp-content/plugins/wp-plugin-starter && composer install && vendor/bin/phpunit"
    ```

By implementing this comprehensive testing strategy, you can ensure that WP Plugin Starter maintains high quality, reliability, and security as it evolves.
