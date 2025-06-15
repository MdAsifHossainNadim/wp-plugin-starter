# WP Plugin Starter - Build & Development Scripts

This directory contains scripts for building, releasing, and customizing the WP Plugin Starter plugin.

## 🔧 Plugin Customization

### Transform WP Plugin Starter to Your Plugin

Use the `update-pluginname.sh` script to transform this boilerplate into your custom plugin:

```bash
# Basic usage
./bin/update-pluginname.sh "My Awesome Plugin"

# With author information
./bin/update-pluginname.sh "My Awesome Plugin" "John Doe" "john@example.com" "https://johndoe.com"

# Full example
./bin/update-pluginname.sh "E-Commerce Toolkit" "Jane Smith" "jane@example.com" "https://janesmith.dev"
```

#### What the script does:

✅ **Updates all naming conventions:**
- Plugin Name: `WP Plugin Starter` → `Your Plugin Name`
- Slug: `wp-plugin-starter` → `your-plugin-name`
- Function Prefix: `wp_plugin_starter` → `your_function_prefix`
- Class Prefix: `WP_Plugin_Starter` → `Your_Plugin_Name`
- Namespace: `WPPluginStarter` → `YourPluginName`
- Constants: `WP_PLUGIN_STARTER` → `YOUR_PLUGIN_NAME`

✅ **Updates file contents:**
- PHP files (classes, functions, hooks, constants)
- JavaScript/React files (global variables, API calls)
- CSS/SCSS files (class names, IDs)
- Configuration files (composer.json, package.json, phpcs.xml)
- Documentation files
- Template files

✅ **Renames files:**
- `wp-plugin-starter.php` → `your-plugin-name.php`
- `class-wp-plugin-starter.php` → `class-your-plugin-name.php`
- `languages/wp-plugin-starter.pot` → `languages/your-plugin-name.pot`

✅ **Updates author information:**
- Author name, email, and URLs throughout the codebase
- Package names in composer.json and package.json
- GitHub repository URLs

✅ **Creates automatic backup:**
- Full project backup before making any changes
- Timestamped backup directory

#### Generated Naming Examples:

| Plugin Name | Slug | Function Prefix | Class Prefix | Namespace | Constants |
|-------------|------|-----------------|--------------|-----------|-----------|
| "My Awesome Plugin" | `my-awesome-plugin` | `map_plugin` | `My_Awesome_Plugin` | `MyAwesomePlugin` | `MY_AWESOME_PLUGIN` |
| "E-Commerce Toolkit" | `e-commerce-toolkit` | `ect_plugin` | `E_Commerce_Toolkit` | `ECommerceToolkit` | `E_COMMERCE_TOOLKIT` |
| "SEO Optimizer Pro" | `seo-optimizer-pro` | `sop_plugin` | `SEO_Optimizer_Pro` | `SEOOptimizerPro` | `SEO_OPTIMIZER_PRO` |

#### After running the script:

1. **Review the changes**
2. **Install dependencies:**
   ```bash
   npm install
   composer install
   ```
3. **Build assets:**
   ```bash
   npm run build
   ```
4. **Generate language files:**
   ```bash
   npm run makepot
   ```
5. **Test your plugin**

## 🚀 Building & Releasing

### Building a production zip file

To build a production-ready zip file of the plugin:

```bash
# Using npm/yarn
yarn release
npm run release

# Manual build
npm run build
npm run makepot
yarn zip
```

This command will:
1. Build the JavaScript and CSS assets
2. Generate the translation template file
3. Create a zip file in the `release/dist` directory

### Release Process

The release process includes:
1. Building production assets with webpack
2. Generating the translation template file
3. Creating a zip file with all production files (excluding development files)

### Manual Release

You can also run the release script directly:

```bash
PLUGIN_VERSION=1.0.0 ./bin/release.sh
```

## 📁 Files in this directory

| File | Purpose |
|------|---------|
| `update-pluginname.sh` | **Plugin name transformer script** - Converts WP Plugin Starter to your custom plugin |
| `release.sh` | Creates production-ready zip files |
| `update-filename.sh` | ⚠️ **Deprecated** - Use `update-pluginname.sh` instead |

## 🛡️ Safety Features

- **Automatic backup creation** before making changes
- **Input validation** for plugin names, emails, and URLs
- **Preview mode** - Shows what will be changed before proceeding
- **Error handling** with colored output for easy reading
- **WordPress coding standards** compliance

## 💡 Tips

- **Use descriptive plugin names** that clearly indicate your plugin's purpose
- **Follow WordPress plugin naming conventions** (avoid trademarked terms)
- **Test thoroughly** after renaming to ensure all functionality works
- **Update documentation** to match your new plugin name
- **Consider SEO** when choosing your plugin name and slug

## 🔧 Troubleshooting

If you encounter issues:

1. **Check file permissions** - ensure scripts are executable:
   ```bash
   chmod +x bin/update-pluginname.sh
   ```

2. **Restore from backup** if something goes wrong:
   ```bash
   # Backups are created in ../wp-plugin-starter-backup-TIMESTAMP/
   ```

3. **Run the script again** - it's safe to run multiple times

4. **Check excluded directories** - vendor/, node_modules/, build/, .git/ are automatically excluded

## 📚 Further Documentation

- [Developer Guide](../docs/developer/developer-guide.md)
- [Architecture Overview](../docs/developer/architecture.md)
- [WordPress Coding Standards](../docs/technical/coding-standards.md)
