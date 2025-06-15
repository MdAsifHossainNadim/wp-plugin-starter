# Working with React Components in WP Plugin Starter

> **Documentation Version**: This documentation is based on WP Plugin Starter version 3.0.0. Features and functionality may vary in other versions.

## Introduction

WP Plugin Starter uses React for its admin interface, providing a modern, component-based architecture that ensures a responsive and user-friendly experience. This guide covers how to work with the React components in WP Plugin Starter, including architecture, best practices, and the component system.

## Architecture Overview

The React architecture in WP Plugin Starter follows modern best practices:

```
src/admin/
├── components/       # Reusable UI components
│   ├── common/       # Shared UI components
│   ├── debug/        # Development-only components
│   ├── features/     # Feature-specific components
│   ├── fields/       # Form input fields
│   └── layout/       # Layout components
├── context/          # React contexts for state management
├── hooks/            # Custom React hooks
├── pages/            # Page components
│   ├── dashboard/    # Dashboard page and sub-components
│   └── features/     # Features page and sub-components
├── utils/            # Utility functions
└── app.jsx           # Main application entry point
```

The frontend folder also contains React components:

```
src/frontend/
├── components/       # Frontend UI components
│   ├── product/      # Product-related components
│   └── vendor/       # Vendor-related components
└── blocks/           # Gutenberg blocks
```

## Key Technologies and Patterns

WP Plugin Starter React codebase leverages:

1. **Functional Components**: All components are function-based with hooks
2. **React Hooks**: Used for state management and side effects
3. **Context API**: Used for global state management
4. **React Router**: Handles routing within the admin interface
5. **WordPress Components**: Integration with `@wordpress/components`
6. **Tailwind CSS**: Used for styling with utility classes
7. **Modern JavaScript**: ES6+ features, async/await, and destructuring

## Component System

### Common UI Components

WP Plugin Starter includes a rich component library that follows consistent patterns:

- **Card Components**: Building blocks for content sections
- **Button Components**: Various button styles and variants
- **Form Fields**: Standardized form elements
- **Notices**: Feedback components for success, error, and info messages
- **Badges**: Label and status indicators
- **Animated Components**: Components with animation capabilities
- **Tables**: Data display components with sorting and filtering
- **Modals**: Dialog components for focused interactions
- **Tabs**: Content organization components

### Using Built-in Components

Here's an example of using the built-in Card components:

```jsx
import { 
  Card, 
  CardHeader, 
  CardTitle, 
  CardDescription, 
  CardContent, 
  CardFooter 
} from '@admin/components/common/card';
import { Button } from '@admin/components/common/button';

const MyComponent = () => {
  return (
    <Card>
      <CardHeader>
        <CardTitle>Card Title</CardTitle>
        <CardDescription>This is a description of the card content.</CardDescription>
      </CardHeader>
      <CardContent>
        <p>Main content goes here...</p>
      </CardContent>
      <CardFooter>
        <Button variant="primary">Save</Button>
        <Button variant="secondary">Cancel</Button>
      </CardFooter>
    </Card>
  );
};
```

### Form Fields

WP Plugin Starter provides a comprehensive set of form field components:

```jsx
import TextField from '@admin/components/fields/text';
import SelectField from '@admin/components/fields/select';
import ToggleField from '@admin/components/fields/toggle';

const MyFormComponent = ({ values, onChange }) => {
  return (
    <>
      <TextField
        label="Title"
        name="title"
        value={values.title}
        onChange={(value) => onChange('title', value)}
        help="Enter the title for this item"
      />
      
      <SelectField
        label="Category"
        name="category"
        value={values.category}
        options={[
          { value: 'cat1', label: 'Category 1' },
          { value: 'cat2', label: 'Category 2' }
        ]}
        onChange={(value) => onChange('category', value)}
      />
      
      <ToggleField
        label="Enable Feature"
        name="enableFeature"
        checked={values.enableFeature}
        onChange={(value) => onChange('enableFeature', value)}
      />
    </>
  );
};
```

### Creating a Data Table

Here's an example of creating a data table with sorting and filtering:

```jsx
import { DataTable } from '@admin/components/common/data-table';
import { Badge } from '@admin/components/common/badge';
import { formatDate, formatCurrency } from '@admin/utils/formatters';

const VendorList = () => {
  const columns = [
    {
      id: 'name',
      header: 'Vendor Name',
      cell: (row) => row.name,
      sortable: true,
    },
    {
      id: 'status',
      header: 'Status',
      cell: (row) => (
        <Badge 
          variant={row.status === 'active' ? 'success' : 'warning'}
        >
          {row.status}
        </Badge>
      ),
      filterable: true,
      filterOptions: [
        { value: 'active', label: 'Active' },
        { value: 'pending', label: 'Pending' },
        { value: 'inactive', label: 'Inactive' }
      ]
    },
    {
      id: 'sales',
      header: 'Total Sales',
      cell: (row) => formatCurrency(row.sales),
      sortable: true,
    },
    {
      id: 'registered',
      header: 'Registered',
      cell: (row) => formatDate(row.registered),
      sortable: true,
    },
    {
      id: 'actions',
      header: 'Actions',
      cell: (row) => (
        <div className="dk-flex dk-space-x-2">
          <Button size="sm" variant="primary" onClick={() => viewVendor(row.id)}>
            View
          </Button>
          <Button size="sm" variant="secondary" onClick={() => editVendor(row.id)}>
            Edit
          </Button>
        </div>
      ),
    },
  ];

  const { data, isLoading, error } = useVendors();

  if (isLoading) return <LoadingSpinner />;
  if (error) return <ErrorMessage message={error.message} />;

  return (
    <Card>
      <CardHeader>
        <CardTitle>Vendor List</CardTitle>
      </CardHeader>
      <CardContent>
        <DataTable 
          columns={columns} 
          data={data} 
          initialSort={{ column: 'sales', direction: 'desc' }}
          searchable={true}
          pagination={true}
          itemsPerPage={10}
        />
      </CardContent>
    </Card>
  );
};
```

## State Management

### Using Context API

WP Plugin Starter uses React's Context API for state management. Here's how to use the provided contexts:

```jsx
import { useContext } from '@wordpress/element';
import { NoticesContext } from '@admin/context/notices-context';

const MyComponent = () => {
  const { addNotice, removeNotice } = useContext(NoticesContext);
  
  const handleAction = async () => {
    try {
      // Perform some action
      addNotice({
        status: 'success',
        message: 'Action completed successfully!'
      });
    } catch (error) {
      addNotice({
        status: 'error',
        message: error.message
      });
    }
  };
  
  return (
    <button onClick={handleAction}>Perform Action</button>
  );
};
```

### Using Settings Context

The SettingsContext is specifically designed for managing plugin settings:

```jsx
import { useContext } from '@wordpress/element';
import { SettingsContext } from '@admin/context/settings-context';

const FeatureComponent = () => {
  const { settings, updateSettings, isSaving } = useContext(SettingsContext);
  
  return (
    <>
      <ToggleField
        label="Enable Feature"
        name="enable_feature"
        checked={settings.enable_feature}
        onChange={(value) => updateSettings({ enable_feature: value })}
        disabled={isSaving}
      />
      
      {settings.enable_feature && (
        <TextField
          label="Feature Title"
          name="feature_title"
          value={settings.feature_title}
          onChange={(value) => updateSettings({ feature_title: value })}
          disabled={isSaving}
        />
      )}
    </>
  );
};
```

### Creating a Feature Tab

Here's an example of creating a feature tab in the WP Plugin Starter admin interface:

```jsx
import { useContext } from '@wordpress/element';
import { SettingsContext } from '@admin/context/settings-context';
import { Card, CardContent, CardHeader, CardTitle } from '@admin/components/common/card';
import { TextField, SelectField, ToggleField } from '@admin/components/fields';
import { Button } from '@admin/components/common/button';

// Tab component that will be registered with the tab system
const MyFeatureTab = () => {
  const { settings, updateSettings, saveSettings, isSaving } = useContext(SettingsContext);
  
  // Extract feature-specific settings
  const featureSettings = settings.my_feature || {};
  
  const handleChange = (key, value) => {
    updateSettings({
      my_feature: {
        ...featureSettings,
        [key]: value
      }
    });
  };
  
  return (
    <div>
      <Card>
        <CardHeader>
          <CardTitle>My Feature Settings</CardTitle>
        </CardHeader>
        <CardContent>
          <ToggleField
            label="Enable My Feature"
            name="enabled"
            checked={featureSettings.enabled}
            onChange={(value) => handleChange('enabled', value)}
            help="Turn this feature on or off"
          />
          
          {featureSettings.enabled && (
            <>
              <TextField
                label="Feature Title"
                name="title"
                value={featureSettings.title}
                onChange={(value) => handleChange('title', value)}
              />
              
              <SelectField
                label="Display Mode"
                name="display_mode"
                value={featureSettings.display_mode}
                options={[
                  { value: 'grid', label: 'Grid View' },
                  { value: 'list', label: 'List View' },
                  { value: 'compact', label: 'Compact View' }
                ]}
                onChange={(value) => handleChange('display_mode', value)}
              />
            </>
          )}
          
          <div className="dk-mt-6">
            <Button 
              variant="primary" 
              onClick={saveSettings}
              disabled={isSaving}
            >
              {isSaving ? 'Saving...' : 'Save Changes'}
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  );
};

// Register the tab with the tab system
import { registerTab } from '@admin/utils/tabs';

registerTab('features', {
  id: 'my-feature',
  title: 'My Feature',
  component: MyFeatureTab,
  priority: 20 // Controls the order of tabs
});
```

## Custom Hooks

WP Plugin Starter provides several custom hooks to encapsulate common functionality:

### useAnimations

```jsx
import { useAnimations } from '@admin/hooks/use-animations';

const MyComponent = () => {
  const { getAnimationClasses } = useAnimations();
  
  const classes = getAnimationClasses('element', { 
    state: 'active', 
    isError: false 
  });
  
  return <div className={classes}>Animated content</div>;
};
```

### useAPI Hook

For interacting with the WP Plugin Starter REST API:

```jsx
import { useAPI } from '@admin/hooks/use-api';

const ProductList = () => {
  const { get, post, isLoading, error } = useAPI();
  const [products, setProducts] = useState([]);
  
  useEffect(() => {
    const fetchProducts = async () => {
      const response = await get('/wp-plugin-starter/v1/products');
      if (response.success) {
        setProducts(response.data);
      }
    };
    
    fetchProducts();
  }, []);
  
  const handleUpdateProduct = async (id, data) => {
    const response = await post(`/wp-plugin-starter/v1/products/${id}`, data);
    if (response.success) {
      // Update local state or show success message
    }
  };
  
  if (isLoading) return <LoadingSpinner />;
  if (error) return <ErrorMessage message={error.message} />;
  
  return (
    <div>
      {products.map(product => (
        <ProductCard 
          key={product.id} 
          product={product} 
          onUpdate={handleUpdateProduct} 
        />
      ))}
    </div>
  );
};
```

### useDokanIntegration Hook

For integrating with Dokan core functionality:

```jsx
import { useDokanIntegration } from '@admin/hooks/use-dokan-integration';

const VendorSummary = ({ vendorId }) => {
  const { getVendorData, getVendorProducts, isLoading } = useDokanIntegration();
  const [vendor, setVendor] = useState(null);
  
  useEffect(() => {
    const loadVendorData = async () => {
      const data = await getVendorData(vendorId);
      setVendor(data);
    };
    
    loadVendorData();
  }, [vendorId]);
  
  if (isLoading || !vendor) return <LoadingSpinner />;
  
  return (
    <Card>
      <CardHeader>
        <CardTitle>{vendor.store_name}</CardTitle>
      </CardHeader>
      <CardContent>
        <p>Total Products: {vendor.product_count}</p>
        <p>Total Sales: {vendor.sales_count}</p>
        <p>Rating: {vendor.rating}</p>
      </CardContent>
    </Card>
  );
};
```

## Styling Components

WP Plugin Starter uses Tailwind CSS for styling. The project includes a utility function called `cn()` that leverages `tailwind-merge` to safely combine Tailwind classes:

```jsx
import { cn } from '@admin/utils/tailwind-utils';

const MyComponent = ({ className, variant = 'default' }) => {
  const baseClasses = 'dk-p-wp-4 dk-rounded';
  const variantClasses = {
    default: 'dk-bg-white dk-text-gray-900',
    primary: 'dk-bg-primary-600 dk-text-white',
    danger: 'dk-bg-red-600 dk-text-white'
  };
  
  const combinedClasses = cn(
    baseClasses,
    variantClasses[variant],
    className // Allow consumers to override/extend styles
  );
  
  return <div className={combinedClasses}>Content</div>;
};
```

### Tailwind Prefix

Note that WP Plugin Starter uses a `dk-` prefix for all Tailwind classes to prevent conflicts with other plugins or themes:

```jsx
// Correct usage with prefix
<div className="dk-flex dk-items-center dk-p-4">Content</div>

// Incorrect usage without prefix
<div className="flex items-center p-4">Content</div>
```

## Creating Custom Components

When creating custom components, follow these best practices:

### Component Structure

```jsx
// MyCustomComponent.jsx
import { useState } from '@wordpress/element';
import PropTypes from 'prop-types';
import { cn } from '@admin/utils/tailwind-utils';

const MyCustomComponent = ({ 
  title, 
  description, 
  onAction, 
  className,
  disabled = false
}) => {
  const [isActive, setIsActive] = useState(false);
  
  const handleClick = () => {
    if (!disabled) {
      setIsActive(true);
      onAction();
    }
  };
  
  return (
    <div 
      className={cn(
        'dk-border dk-rounded dk-p-4', 
        isActive ? 'dk-border-primary-500' : 'dk-border-gray-300',
        disabled ? 'dk-opacity-50 dk-cursor-not-allowed' : 'dk-cursor-pointer',
        className
      )}
      onClick={handleClick}
    >
      <h3 className="dk-text-lg dk-font-medium">{title}</h3>
      {description && (
        <p className="dk-text-sm dk-text-gray-600">{description}</p>
      )}
    </div>
  );
};

MyCustomComponent.propTypes = {
  title: PropTypes.string.isRequired,
  description: PropTypes.string,
  onAction: PropTypes.func.isRequired,
  className: PropTypes.string,
  disabled: PropTypes.bool
};

export default MyCustomComponent;
```

## Integration with Dokan

### Using Dokan Data in React Components

```jsx
import { useState, useEffect } from '@wordpress/element';
import { useAPI } from '@admin/hooks/use-api';

const DokanVendorsList = () => {
  const [vendors, setVendors] = useState([]);
  const { get, isLoading } = useAPI();
  
  useEffect(() => {
    const fetchVendors = async () => {
      // First check if this is from WP Plugin Starter API
      const response = await get('/wp-plugin-starter/v1/vendors');
      if (response.success) {
        setVendors(response.data);
        return;
      }
      
      // Fallback to Dokan API if needed
      const dokanResponse = await get('/dokan/v1/stores');
      if (dokanResponse.success) {
        // Transform Dokan data structure to match our component needs
        const transformedVendors = dokanResponse.data.map(store => ({
          id: store.id,
          name: store.store_name,
          status: store.status,
          sales: store.sales?.total || 0,
          registered: store.registered
        }));
        setVendors(transformedVendors);
      }
    };
    
    fetchVendors();
  }, []);
  
  if (isLoading) return <LoadingSpinner />;
  
  return (
    <VendorList vendors={vendors} />
  );
};
```

### Extending Dokan Admin Pages

```jsx
import { addFilter } from '@wordpress/hooks';

// Add a tab to Dokan Vendor Dashboard
const addCustomVendorTab = (tabs) => {
  return {
    ...tabs,
    my_custom_tab: {
      title: 'Custom Features',
      icon: 'chart-bar',
      content: MyCustomTabContent,
    }
  };
};

addFilter(
  'dokan_vendor_tabs', 
  'wp-plugin-starter/add-custom-vendor-tab', 
  addCustomVendorTab
);
```

## Performance Optimization

### Memoization

Use React.memo and useCallback to optimize performance:

```jsx
import { memo, useCallback } from '@wordpress/element';

// Memoize a component to prevent unnecessary re-renders
const VendorCard = memo(({ vendor, onSelect }) => {
  // Component implementation
});

// In parent component
const VendorList = ({ vendors }) => {
  // Memoize callback functions
  const handleSelect = useCallback((id) => {
    // Handle selection logic
  }, []);
  
  return (
    <div>
      {vendors.map(vendor => (
        <VendorCard 
          key={vendor.id} 
          vendor={vendor} 
          onSelect={handleSelect} 
        />
      ))}
    </div>
  );
};
```

### Lazy Loading

Implement lazy loading for components that aren't immediately needed:

```jsx
import { lazy, Suspense } from '@wordpress/element';

// Lazy load a component
const LazyComponent = lazy(() => import('./HeavyComponent'));

const MyApp = () => {
  return (
    <div>
      <AlwaysVisibleComponent />
      <Suspense fallback={<LoadingSpinner />}>
        <LazyComponent />
      </Suspense>
    </div>
  );
};
```

## Testing React Components

WP Plugin Starter includes a testing setup for React components:

```jsx
// MyComponent.test.js
import { render, screen, fireEvent } from '@testing-library/react';
import MyComponent from './MyComponent';

describe('MyComponent', () => {
  test('renders correctly', () => {
    render(<MyComponent title="Test Title" />);
    expect(screen.getByText('Test Title')).toBeInTheDocument();
  });
  
  test('calls onAction when clicked', () => {
    const handleAction = jest.fn();
    render(<MyComponent title="Test" onAction={handleAction} />);
    fireEvent.click(screen.getByText('Test'));
    expect(handleAction).toHaveBeenCalledTimes(1);
  });
});
```

## Conclusion

Working with React components in Dokan Kits provides a powerful way to create interactive and user-friendly interfaces. By following the patterns and practices described in this guide, you can create maintainable, performant, and consistent React components that integrate seamlessly with the Dokan Kits architecture.

For more information, see the [Technical Architecture](architecture.md) documentation and explore our component examples in the codebase. 
