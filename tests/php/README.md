# Dokan Kits - Testing Guide

This document provides a comprehensive guide to the testing framework used in Dokan Kits.

## Table of Contents

- [Overview](#overview)
- [Setup](#setup)
- [Running Tests](#running-tests)
- [Test Structure](#test-structure)
- [Writing Tests](#writing-tests)
    - [Base Test Case](#base-test-case)
    - [Data Providers](#data-providers)
    - [Mocking](#mocking)
- [Test Helpers](#test-helpers)
    - [WooCommerce Test Helper](#woocommerce-test-helper)
    - [Dokan Test Helper](#dokan-test-helper)
    - [Feature Test Helper](#feature-test-helper)
    - [Hooks Test Helper](#hooks-test-helper)
- [Mock Data Stores](#mock-data-stores)
- [Test Fixtures](#test-fixtures)
- [Test Bootstrap](#test-bootstrap)
- [Best Practices](#best-practices)

## Overview

Dokan Kits uses PHPUnit for unit and integration testing. The testing framework is designed to test individual components in isolation as well as their integration with WordPress, WooCommerce, and Dokan.

## Setup

To set up the testing environment:

1. Install the PHP dependencies:

    ```bash
    composer install
    ```

2. Set up the WordPress test environment:

    ```bash
    bin/install-wp-tests.sh wp_phpunit_tests root '' localhost latest
    ```

    The script takes the following parameters:

    - Database name (for testing)
    - Database user
    - Database password
    - Database host
    - WordPress version

## Running Tests

To run all tests:

```bash
./vendor/bin/phpunit
```

To run a specific test file:

```bash
./vendor/bin/phpunit tests/php/test-feature-registry.php
```

To run a specific test method:

```bash
./vendor/bin/phpunit --filter=test_register_feature
```

To generate a coverage report:

```bash
./vendor/bin/phpunit --coverage-html=coverage
```

## Test Structure

The test directory structure is organized as follows:

```
tests/php/
├── bin/                         # Test setup scripts
├── src/                         # Test support classes
│   ├── TestHelpers/             # Test helper utilities
│   │   ├── Data/                # Data-related test helpers
│   │   │   ├── Fixtures/        # Test data factories
│   │   │   └── Mocks/           # Mock implementations
│   │   ├── Bootstrap.php        # Test environment bootstrap
│   │   ├── DokanTestHelper.php  # Dokan-specific test utilities
│   │   ├── FeatureTestHelper.php # Feature testing utilities
│   │   ├── HooksTestHelper.php  # WordPress hooks testing utilities
│   │   ├── TestHelper.php       # General test utilities
│   │   └── WooCommerceTestHelper.php # WooCommerce utilities
│   └── Dokan_Kits_UnitTestCase.php # Base test case class
├── bootstrap.php                # PHPUnit bootstrap file
└── test-*.php                   # Individual test files
```

## Writing Tests

### Base Test Case

All test classes should extend `Dokan_Kits\Tests\Dokan_Kits_UnitTestCase` which provides access to common testing utilities:

```php
<?php

use Dokan_Kits\Tests\Dokan_KitsUnitTestCase;

class Test_Feature_Registry extends Dokan_KitsUnitTestCase {
    public function test_register_feature() {
        $registry = dokan_kits_feature_registry();
        $registry->register('test_feature', 'TestFeature');

        $this->assertTrue($registry->has('test_feature'));
    }
}
```

### Data Providers

For tests that need to be run with multiple data sets, use data providers:

```php
public function data_provider_for_slug_validation() {
    return [
        'valid slug' => ['valid-slug', true],
        'invalid slug with spaces' => ['invalid slug', false],
        'invalid slug with special chars' => ['invalid@slug', false],
    ];
}

/**
 * @dataProvider data_provider_for_slug_validation
 */
public function test_validate_slug($slug, $expected) {
    $is_valid = dokan_kits_validate_slug($slug);
    $this->assertEquals($expected, $is_valid);
}
```

### Mocking

You can create mocks for dependencies using PHPUnit's built-in mocking capabilities:

```php
public function test_with_mock_feature() {
    // Create a mock
    $mock = $this->createMock(\Dokan_Kits\Features\Feature_Interface::class);

    // Set up expectations
    $mock->method('is_enabled')->willReturn(true);
    $mock->method('get_name')->willReturn('Test Feature');

    // Test with the mock
    $this->assertTrue($mock->is_enabled());
    $this->assertEquals('Test Feature', $mock->get_name());
}
```

## Test Helpers

### WooCommerce Test Helper

Utilities for WooCommerce-specific testing:

```php
// Create a test product
$product = $this->wc->create_product([
    'name' => 'Test Product',
    'regular_price' => 20,
]);

// Check if WooCommerce is active
if ($this->wc->is_woocommerce_active()) {
    // WooCommerce-specific tests
}
```

### Dokan Test Helper

Utilities for Dokan-specific testing:

```php
// Create a vendor
$vendor_id = $this->dokan->create_vendor([
    'user_login' => 'test_vendor',
    'user_email' => 'vendor@example.com',
]);

// Check if Dokan is active
if ($this->dokan->is_dokan_active()) {
    // Dokan-specific tests
}
```

### Feature Test Helper

Utilities for testing features:

```php
// Register test features
$registry = new FeatureRegistry();
$this->feature_helper->register_test_features($registry, ['test1', 'test2']);

// Create a registry with initialized features
$registry = $this->feature_helper->create_registry_with_features(['test1', 'test2']);
```

### Hooks Test Helper

Utilities for testing WordPress hooks:

```php
// Create an action spy to verify a hook was called
[$callback, $calls] = HooksTestHelper::create_action_spy();
add_action('my_action', $callback);

// Trigger the action
do_action('my_action', 'arg1', 'arg2');

// Verify the action was called with expected args
$this->assertCount(1, $calls);
$this->assertEquals('arg1', $calls[0][0]);
$this->assertEquals('arg2', $calls[0][1]);

// Create a filter spy that modifies return values
[$callback, $calls, $return] = HooksTestHelper::create_filter_spy('modified');
add_filter('my_filter', $callback);

// Call the filter
$result = apply_filters('my_filter', 'original');

// Verify the filter was called and returned the modified value
$this->assertCount(1, $calls);
$this->assertEquals('modified', $result);
```

## Mock Data Stores

For unit tests that shouldn't interact with the database, use the mock data stores:

```php
// The base test case automatically sets up mock data stores
$feature = new Feature();
$feature->set_slug('test_feature');
$feature->set_name('Test Feature');
$feature->save();

// Retrieve using the mock store
$retrieved = dokan_kits_feature('test_feature');
$this->assertEquals('Test Feature', $retrieved->get_name());
```

## Test Fixtures

Use the fixtures factory to generate test data:

```php
// Create a feature
$feature = $this->fixtures->feature->create([
    'slug' => 'test-feature',
    'name' => 'Test Feature',
    'enabled' => true,
]);

// Create multiple features
$features = $this->fixtures->feature->create_many(3);
```

## Test Bootstrap

The test bootstrap process performs several important setup tasks:

1. Loads WordPress test environment
2. Loads WooCommerce and Dokan if available
3. Installs required database tables
4. Sets up mock data stores
5. Initializes test fixtures

The `Bootstrap` class (`Dokan_Kits\Tests\TestHelpers\Bootstrap`) handles most of this setup automatically. It provides methods for:

- Initializing the test environment
- Creating test data
- Truncating database tables between tests
- Managing test fixtures

## Best Practices

1. **Test in isolation**: Each test should be independent and not rely on the state from other tests.

2. **Use meaningful test names**: Name your tests clearly to describe what they're testing.

3. **One assertion per test**: Where possible, keep tests focused on a single assertion.

4. **Use data providers**: For tests that need to run with multiple inputs.

5. **Clean up after tests**: Use the `setUp` and `tearDown` methods to ensure each test starts with a clean state.

6. **Mock external dependencies**: Use mocks for external systems like the database or API calls.

7. **Test edge cases**: Test both the happy path and error conditions.

8. **Keep tests fast**: Unit tests should run quickly to encourage developers to run them often.

9. **Organize related tests**: Keep related tests in the same file and use descriptive grouping.

10. **Documentation**: Document complex test setups or any non-obvious testing approaches.
