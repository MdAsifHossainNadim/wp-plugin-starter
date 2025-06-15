# Structure System

> Current Version: 3.0.0

## Overview

The Structure System in WP Plugin Starter provides a flexible, hierarchical configuration framework for managing settings and building dynamic UI components. It follows a standardized three-level hierarchy:

1. **Tabs** - Top-level navigation elements
2. **Sections** - Groupings of related fields within a tab
3. **Fields** - Individual settings with various types and behaviors

## Data Structure

The Structure System uses a standardized JSON structure:

```javascript
{
  "tab_id": {
    "id": "tab_id",
    "title": "Tab Title",
    "icon": "dashicons-icon",
    "description": "Tab description text",
    "sections": {
      "section_id": {
        "id": "section_id",
        "title": "Section Title",
        "description": "Section description text",
        "fields": {
          "field_id": {
            "id": "field_id",
            "title": "Field Title",
            "description": "Field description text",
            "variant": "text|toggle|select|number|etc",
            "value": "current value",
            "default": "default value",
            "placeholder": "Placeholder text",
            "readonly": false,
            "disabled": false,
            "dependencies": [
              {
                "key": "other_field_id",
                "comparison": "=",
                "value": true
              }
            ],
            "options": [
              { "label": "Option 1", "value": "option1" },
              { "label": "Option 2", "value": "option2" }
            ],
            "minimum": 0,
            "maximum": 100,
            "step": 1
          }
        }
      }
    }
  }
}
```

## Core Components

### 1. React Hook: `useStructure`

Located in `src/admin/hooks/useStructure.js`, this hook provides the main API for working with structures:

```javascript
const {
  structure,      // The parsed structure object
  settings,       // Flat key-value pairs of all settings
  isLoading,      // Loading state indicator
  updateSetting,  // Function to update a setting
  saveSettings,   // Function to save all settings
  reloadStructure // Function to refresh the structure
} = useStructure();
```

### 2. Helper Utilities

Located in `src/admin/utils/structure.js`, these utilities provide tools for working with structures:

- `getFieldByKey(structure, key)` - Find a specific field by its key
- `isFieldVisible(field, settings)` - Check if a field should be visible
- `getVisibleFields(structure, settings)` - Get all visible fields
- `validateFieldValue(field, value)` - Validate a field value
- `validateSettings(structure, settings)` - Validate all settings

### 3. React Components

The component hierarchy:

- `SettingsLayout` (in `src/admin/components/layout`) - Main container for settings
- `FeatureTab` (in `src/admin/pages/features/tabs`) - Renders tab content
- `FeatureSection` (in `src/admin/components/features`) - Renders a section
- `Field` (in `src/admin/components/fields`) - Renders field components

## Field Types

The system supports many field types through the `variant` property:

| Field Type | Description |
|------------|-------------|
| `text` | Single-line text input |
| `textarea` | Multi-line text input |
| `select` | Dropdown select field |
| `number` | Numeric input with min/max |
| `checkbox` | Boolean checkbox |
| `radio` | Radio button group |
| `color` | Color picker |
| `media` | Media uploader |
| `toggle` | On/Off toggle switch |
| `code` | Code editor with syntax highlighting |

## Conditional Logic

Fields can include dependencies to show/hide based on other field values:

```javascript
"dependencies": [
  {
    "key": "other_field_id",    // The field to depend on
    "comparison": "=",          // Comparison operator (=, !=, >, <, >=, <=)
    "value": true               // Value to compare against
  },
  // Multiple conditions use AND logic
  {
    "key": "another_field",
    "comparison": ">",
    "value": 10
  }
]
```

## Usage Example

```javascript
// In a React component
import { useStructure } from '../../hooks/useStructure';
import { validateSettings } from '../../utils/structure';

const SettingsComponent = () => {
  const { 
    structure, 
    settings, 
    updateSetting, 
    saveSettings,
    isLoading 
  } = useStructure();

  const handleChange = (key, value) => {
    updateSetting(key, value);
  };
  
  const handleSave = async () => {
    const { isValid, errors } = validateSettings(structure, settings);
    
    if (isValid) {
      try {
        await saveSettings();
        // Show success message
      } catch (error) {
        // Handle error
      }
    } else {
      // Display validation errors
      console.error(errors);
    }
  };

  return (
    <div>
      {/* Your UI components using structure data */}
      <button onClick={handleSave} disabled={isLoading}>
        {isLoading ? 'Saving...' : 'Save SettingsModel'}
      </button>
    </div>
  );
};
```

## Extending the System

### Adding Custom Field Types

1. Create a new field component in `src/admin/components/fields/`
2. Add your component to the exports in `src/admin/components/fields/index.js`
3. Update field rendering in `src/admin/components/features/FeatureSection.js`

Example custom field:

```javascript
// src/admin/components/fields/RangeField.js
import React from 'react';

const RangeField = ({ field, value, onChange }) => {
  return (
    <div className="wp-plugin-starter-range-field">
      <input
        type="range"
        min={field.minimum || 0}
        max={field.maximum || 100}
        step={field.step || 1}
        value={value}
        onChange={(e) => onChange(field.id, parseInt(e.target.value, 10))}
        disabled={field.disabled}
      />
      <span className="value">{value}</span>
    </div>
  );
};

export default RangeField;
```

### Custom Tab Components

Register custom tabs by adding your components to the features system:

```javascript
// Example custom tab registration
import MyCustomTab from './components/MyCustomTab';

// In your initialization code
const customTabs = {
  'my-custom-tab': MyCustomTab
};

// Make it available to the structure system
```

## PHP Integration

The structure system is powered by several backend components:

- `Core\Data\Models\Settings` - Settings data model
- `Core\Data\Stores\SettingsDataStore` - Database interaction for settings
- `Admin\Dashboard\Components\Fields` - Field registration

The system exposes settings via REST API endpoints that the React frontend consumes.

### Registering Structures in PHP

```php
// Example: Registering a structure in PHP
function register_my_structure($structures) {
    $structures['my_feature'] = [
        'id' => 'my_feature',
        'title' => __('My Feature', 'wp-plugin-starter'),
        'icon' => 'dashicons-admin-settings',
        'sections' => [
            'general' => [
                'id' => 'general',
                'title' => __('General SettingsModel', 'wp-plugin-starter'),
                'fields' => [
                    'enable_feature' => [
                        'id' => 'enable_feature',
                        'title' => __('Enable Feature', 'wp-plugin-starter'),
                        'description' => __('Turn this feature on or off', 'wp-plugin-starter'),
                        'variant' => 'toggle',
                        'default' => false
                    ],
                    // More fields...
                ]
            ]
        ]
    ];
    
    return $structures;
}
add_filter('dokan_kits_feature_structures', 'register_my_structure');
```

## Best Practices

1. **Use Consistent Keys**: Create predictable key structures for fields
2. **Group Related Fields**: Use sections to organize related settings
3. **Provide Defaults**: Always set default values for fields
4. **Use Dependencies Wisely**: Don't create overly complex dependency chains
5. **Validate User Input**: Add proper validation for user-entered data
