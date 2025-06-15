#!/bin/bash

# Test Plugin Naming Generation
# This script tests the naming generation functions used in update-pluginname.sh

# Source the main script functions
source "$(dirname "${BASH_SOURCE[0]}")/update-pluginname.sh"

# Test function
test_naming() {
    local plugin_name="$1"
    
    echo "Testing plugin name: '$plugin_name'"
    echo "=================================="
    echo "Slug: $(generate_slug "$plugin_name")"
    echo "Function Prefix: $(generate_function_prefix "$plugin_name")"
    echo "Class Prefix: $(generate_class_prefix "$plugin_name")"
    echo "Namespace: $(generate_namespace "$plugin_name")"
    echo "Constants: $(generate_constant_prefix "$plugin_name")"
    echo ""
}

# Test cases
echo "🧪 Plugin Naming Convention Test"
echo "================================"
echo ""

test_naming "My Awesome Plugin"
test_naming "E-Commerce Toolkit"
test_naming "SEO Optimizer Pro"
test_naming "WooCommerce Analytics Dashboard"
test_naming "Simple Contact Form"
test_naming "Advanced Custom Fields Pro"
test_naming "Yoast SEO"
test_naming "Elementor Pro"

echo "✅ All tests completed!"
echo ""
echo "💡 To rename your plugin, use:"
echo './bin/update-pluginname.sh "Your Plugin Name"'