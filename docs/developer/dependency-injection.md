# Dependency Injection

> **Documentation Version**: This documentation is based on WP Plugin Starter version 3.0.0.

## What is Dependency Injection?

Dependency Injection (DI) is a design pattern that implements Inversion of Control for resolving dependencies. In WP Plugin Starter, we use a dependency injection container to make code more maintainable, testable, and modular.

## Benefits in WP Plugin Starter

- **Decoupling**: Reduces tight coupling between components
- **Testability**: Makes components easier to test in isolation
- **Maintainability**: Simplifies code by centralizing dependencies
- **Extensibility**: Allows easy replacement of services
- **Consistency**: Creates a standardized approach to service usage

## The Container System

WP Plugin Starter uses the League Container library, a lightweight but powerful PSR-11 compatible dependency injection container.

### Core Components

1. **Container** (`League\Container\Container`)
   The main container that manages service registration and resolution

2. **Service Providers** (implementing `League\Container\ServiceProvider\ServiceProviderInterface`)
   Classes that register related services with the container

3. **Definitions**
   Service definitions that control how services are instantiated

## Using the Container

### Accessing the Container

The container can be accessed through the main plugin instance:

```php
// Get the container from the main plugin instance
$container = dokan_kits()->get_container();
```

### Registering Services

Services can be registered directly with the container:

```php
// Register a shared (singleton) service
$container->add('service_id', SomeClass::class);

// Register a non-shared service (new instance each time)
$container->add('factory_service', SomeClass::class, false);

// Register a concrete implementation for an interface
$container->add(SomeInterface::class, ConcreteImplementation::class);
```

### Using Service Providers

For organization, related services should be grouped into service providers:

```php
namespace Dokan_Kits\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;

class FeatureServiceProvider extends AbstractServiceProvider
{
    /**
     * Services provided by this provider.
     * 
     * @var string[]
     */
    protected $provides = [
        'feature.manager',
        'feature.registry',
        SomeInterface::class,
    ];

    /**
     * Register services with the container.
     *
     * @return void
     */
    public function register(): void
    {
        $this->getContainer()->add('feature.manager', FeatureManager::class);
        
        $this->getContainer()
            ->add('feature.registry', FeatureRegistry::class)
            ->addArgument('feature.manager');
            
        $this->getContainer()
            ->add(SomeInterface::class, ConcreteImplementation::class)
            ->addArgument(AnotherService::class);
    }
}

// Register the service provider with the container
$container->addServiceProvider(new FeatureServiceProvider());
```

### Resolving Services

To get a service from the container:

```php
// Get a service by ID
$service = $container->get('service_id');

// Resolve a class with its dependencies
$instance = $container->get(SomeClass::class);
```

### Constructor Injection

Services can be injected into class constructors:

```php
namespace Dokan_Kits\Features\SomeFeature;

use Dokan_Kits\Core\Interfaces\Hookable;

class SomeFeature implements Hookable
{
    /**
     * @var SomeService
     */
    private $service;
    
    /**
     * @var AnotherService
     */
    private $another;
    
    /**
     * Constructor
     *
     * @param SomeService $service
     * @param AnotherService $another
     */
    public function __construct(SomeService $service, AnotherService $another)
    {
        $this->service = $service;
        $this->another = $another;
    }
    
    /**
     * {@inheritdoc}
     */
    public function register_hooks(): void
    {
        // Use injected services
    }
}
```

## Service Lifetimes

The container supports different service lifetimes:

1. **Shared** (Singleton): One instance shared throughout the application
   ```php
   $container->add('logger', Logger::class, true); // Default is true
   ```

2. **Non-Shared** (Transient): New instance created each time
   ```php
   $container->add('factory', Factory::class, false);
   ```

3. **Delegated**: Custom factory function
   ```php
   $container->add('complex_service', function() {
       return new ComplexService(new Dependency());
   });
   ```

## Best Practices

1. **Use interfaces** where appropriate to make services replaceable
2. **Group related services** in service providers
3. **Avoid the service locator pattern** in favor of constructor injection
4. **Keep services focused** on a single responsibility
5. **Use shared services** for stateless services, non-shared for stateful ones

## Common Pitfalls

1. **Circular dependencies**: A depends on B, B depends on A
2. **Service locator anti-pattern**: Injecting the container itself
3. **Over-abstraction**: Creating interfaces for everything
4. **Under-abstraction**: Hard-coding dependencies

## Example Service Provider

Here's a complete example of a service provider in WP Plugin Starter:

```php
namespace Dokan_Kits\Core\Providers;

use Dokan_Kits\Core\Assets\AssetManager;
use Dokan_Kits\Core\Assets\ScriptRegistry;
use Dokan_Kits\Core\Assets\StyleRegistry;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AssetServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'assets.manager',
        'assets.scripts',
        'assets.styles',
    ];

    public function register(): void
    {
        // Register style registry
        $this->getContainer()
            ->add('assets.styles', StyleRegistry::class);
            
        // Register script registry
        $this->getContainer()
            ->add('assets.scripts', ScriptRegistry::class);
            
        // Register asset manager with dependencies
        $this->getContainer()
            ->add('assets.manager', AssetManager::class)
            ->addArgument('assets.scripts')
            ->addArgument('assets.styles');
    }
}
```
