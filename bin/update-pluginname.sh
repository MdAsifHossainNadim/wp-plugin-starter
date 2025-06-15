#!/bin/bash

# WordPress Plugin Name Updater Script
# ==================================
# This script transforms WP Plugin Starter into any custom plugin name
# following WordPress coding standards and best practices.
#
# Usage: ./bin/update-pluginname.sh "Your Plugin Name" [author] [email] [url]
# Example: ./bin/update-pluginname.sh "My Awesome Plugin" "John Doe" "john@example.com" "https://example.com"
#
# Author: Al Amin Ahamed
# Version: 1.0.0

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_step() {
    echo -e "${BLUE}📝 Step $1: $2${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Function to generate slug from plugin name
generate_slug() {
    echo "$1" | tr '[:upper:]' '[:lower:]' | sed 's/[^a-z0-9]/-/g' | sed 's/--*/-/g' | sed 's/^-\|-$//g'
}

# Function to generate function prefix from plugin name
generate_function_prefix() {
    local words=($1)
    local prefix=""
    for word in "${words[@]}"; do
        if [[ ${#word} -gt 0 ]]; then
            prefix+=$(echo "${word:0:1}" | tr '[:upper:]' '[:lower:]')
        fi
    done
    echo "${prefix}_plugin"
}

# Function to generate class prefix from plugin name
generate_class_prefix() {
    echo "$1" | sed 's/[^a-zA-Z0-9 ]//g' | sed 's/ /_/g'
}

# Function to generate namespace from plugin name
generate_namespace() {
    echo "$1" | sed 's/[^a-zA-Z0-9 ]//g' | sed 's/ //g'
}

# Function to generate constant prefix from plugin name
generate_constant_prefix() {
    echo "$1" | tr '[:lower:]' '[:upper:]' | sed 's/[^A-Z0-9]/_/g' | sed 's/__*/_/g' | sed 's/^_\|_$//g'
}

# Function to backup project
backup_project() {
    local backup_dir="../wp-plugin-starter-backup-$(date +%Y%m%d_%H%M%S)"
    print_step "0" "Creating backup at $backup_dir"
    cp -r . "$backup_dir"
    if [[ $? -eq 0 ]]; then
        print_success "Backup created successfully"
    else
        print_error "Failed to create backup"
        exit 1
    fi
}

# Function to validate inputs
validate_inputs() {
    if [[ -z "$NEW_PLUGIN_NAME" ]]; then
        print_error "Plugin name is required!"
        echo "Usage: $0 \"Your Plugin Name\" [author] [email] [url]"
        exit 1
    fi

    if [[ ${#NEW_PLUGIN_NAME} -lt 3 ]]; then
        print_error "Plugin name must be at least 3 characters long"
        exit 1
    fi

    # Validate email format if provided
    if [[ -n "$NEW_AUTHOR_EMAIL" ]]; then
        if [[ ! "$NEW_AUTHOR_EMAIL" =~ ^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$ ]]; then
            print_error "Invalid email format: $NEW_AUTHOR_EMAIL"
            exit 1
        fi
    fi

    # Validate URL format if provided
    if [[ -n "$NEW_AUTHOR_URL" ]]; then
        if [[ ! "$NEW_AUTHOR_URL" =~ ^https?:// ]]; then
            print_error "URL must start with http:// or https://"
            exit 1
        fi
    fi
}

# Function to update file contents
update_file_contents() {
    local file_pattern="$1"
    local exclude_paths="$2"
    local description="$3"
    
    print_step "$current_step" "$description"
    
    local find_cmd="find . -name \"$file_pattern\""
    
    # Add exclude patterns
    for exclude in $exclude_paths; do
        find_cmd+=" -not -path \"$exclude\""
    done
    
    # Execute the find command and update files
    eval "$find_cmd" | while read -r file; do
        if [[ -f "$file" ]]; then
            # Plugin Name
            sed -i "s/WP Plugin Starter/$NEW_PLUGIN_NAME/g" "$file"
            
            # Slug/Text Domain
            sed -i "s/wp-plugin-starter/$NEW_SLUG/g" "$file"
            
            # Function Prefix
            sed -i "s/wp_plugin_starter/$NEW_FUNCTION_PREFIX/g" "$file"
            
            # Class Prefix
            sed -i "s/WP_Plugin_Starter/$NEW_CLASS_PREFIX/g" "$file"
            
            # Namespace
            sed -i "s/WPPluginStarter/$NEW_NAMESPACE/g" "$file"
            
            # Constants
            sed -i "s/WP_PLUGIN_STARTER/$NEW_CONSTANT_PREFIX/g" "$file"
            
            # Description
            if [[ -n "$NEW_DESCRIPTION" ]]; then
                sed -i "s/WP Plugin Starter is a modern, extensible WordPress plugin boilerplate.*/$NEW_DESCRIPTION/g" "$file"
            fi
            
            # Author
            if [[ -n "$NEW_AUTHOR" ]]; then
                sed -i "s/Al Amin Ahamed/$NEW_AUTHOR/g" "$file"
                sed -i "s/WPIntegrity/$NEW_AUTHOR/g" "$file"
                sed -i "s/WP Integrity/$NEW_AUTHOR/g" "$file"
            fi
            
            # Email
            if [[ -n "$NEW_AUTHOR_EMAIL" ]]; then
                sed -i "s/asifsgo007@gmail.com/$NEW_AUTHOR_EMAIL/g" "$file"
                sed -i "s/devianadim@gmail.com/$NEW_AUTHOR_EMAIL/g" "$file"
            fi
            
            # URL
            if [[ -n "$NEW_AUTHOR_URL" ]]; then
                sed -i "s|https://profiles.wordpress.org/devianadim9/|$NEW_AUTHOR_URL|g" "$file"
            fi
            
            # GitHub URLs
            if [[ -n "$NEW_GITHUB_REPO" ]]; then
                sed -i "s|https://github.com/MdAsifHossainNadim/wp-plugin-starter|$NEW_GITHUB_REPO|g" "$file"
            fi
            
            # WordPress.org URLs
            sed -i "s|https://wordpress.org/plugins/wp-plugin-starter/|https://wordpress.org/plugins/$NEW_SLUG/|g" "$file"
        fi
    done
    
    ((current_step++))
}

# Function to rename files
rename_files() {
    print_step "$current_step" "Renaming files"
    
    # Rename main plugin file
    if [[ -f "wp-plugin-starter.php" ]]; then
        mv "wp-plugin-starter.php" "$NEW_SLUG.php"
        print_success "Renamed main plugin file"
    fi
    
    # Rename main class file
    if [[ -f "class-wp-plugin-starter.php" ]]; then
        mv "class-wp-plugin-starter.php" "class-$NEW_SLUG.php"
        print_success "Renamed main class file"
    fi
    
    # Rename language file
    if [[ -f "languages/wp-plugin-starter.pot" ]]; then
        mv "languages/wp-plugin-starter.pot" "languages/$NEW_SLUG.pot"
        print_success "Renamed language file"
    fi
    
    # Rename image files if they exist
    if [[ -f "assets/images/wp-plugin-starter-logo.png" ]]; then
        mv "assets/images/wp-plugin-starter-logo.png" "assets/images/$NEW_SLUG-logo.png"
        print_success "Renamed logo file"
    fi
    
    if [[ -f "assets/images/wp-plugin-starter-banner.png" ]]; then
        mv "assets/images/wp-plugin-starter-banner.png" "assets/images/$NEW_SLUG-banner.png"
        print_success "Renamed banner file"
    fi
    
    ((current_step++))
}

# Function to update package.json scripts
update_package_scripts() {
    print_step "$current_step" "Updating package.json scripts"
    
    if [[ -f "package.json" ]]; then
        # Update makepot script
        sed -i "s/languages\/wp-plugin-starter\.pot/languages\/$NEW_SLUG.pot/g" package.json
        
        # Update package name if needed
        sed -i "s/@MdAsifHossainNadim\/wp-plugin-starter/@${NEW_AUTHOR_SLUG:-author}\/$NEW_SLUG/g" package.json
        
        print_success "Updated package.json"
    fi
    
    ((current_step++))
}

# Function to update composer.json
update_composer() {
    print_step "$current_step" "Updating composer.json"
    
    if [[ -f "composer.json" ]]; then
        # Update package name
        sed -i "s/MdAsifHossainNadim\/wp-plugin-starter/${NEW_AUTHOR_SLUG:-author}\/$NEW_SLUG/g" composer.json
        
        print_success "Updated composer.json"
    fi
    
    ((current_step++))
}

# Function to update README files
update_readme_files() {
    print_step "$current_step" "Updating README files"
    
    # Update README.md title
    if [[ -f "README.md" ]]; then
        sed -i "1s/.*/# $NEW_PLUGIN_NAME/" README.md
        print_success "Updated README.md title"
    fi
    
    # Update readme.txt title
    if [[ -f "readme.txt" ]]; then
        sed -i "1s/.*/=== $NEW_PLUGIN_NAME ===/" readme.txt
        print_success "Updated readme.txt title"
    fi
    
    ((current_step++))
}

# Function to show summary
show_summary() {
    echo ""
    echo "🎉 Plugin Rename Complete!"
    echo "========================="
    echo -e "${GREEN}Original:${NC} WP Plugin Starter"
    echo -e "${GREEN}New Name:${NC} $NEW_PLUGIN_NAME"
    echo -e "${GREEN}Slug:${NC} $NEW_SLUG"
    echo -e "${GREEN}Function Prefix:${NC} $NEW_FUNCTION_PREFIX"
    echo -e "${GREEN}Class Prefix:${NC} $NEW_CLASS_PREFIX"
    echo -e "${GREEN}Namespace:${NC} $NEW_NAMESPACE"
    echo -e "${GREEN}Constants:${NC} $NEW_CONSTANT_PREFIX"
    
    if [[ -n "$NEW_AUTHOR" ]]; then
        echo -e "${GREEN}Author:${NC} $NEW_AUTHOR"
    fi
    
    if [[ -n "$NEW_AUTHOR_EMAIL" ]]; then
        echo -e "${GREEN}Email:${NC} $NEW_AUTHOR_EMAIL"
    fi
    
    if [[ -n "$NEW_AUTHOR_URL" ]]; then
        echo -e "${GREEN}URL:${NC} $NEW_AUTHOR_URL"
    fi
    
    echo ""
    echo "📋 Next Steps:"
    echo "1. Review the changes"
    echo "2. Run: npm install"
    echo "3. Run: composer install"
    echo "4. Run: npm run build"
    echo "5. Run: npm run makepot"
    echo "6. Test your plugin functionality"
    echo ""
    echo "💡 Tip: A backup was created before making changes."
}

# Main execution starts here
main() {
    echo "🚀 WordPress Plugin Name Updater"
    echo "================================="
    echo ""

    # Get input parameters
    NEW_PLUGIN_NAME="$1"
    NEW_AUTHOR="${2:-}"
    NEW_AUTHOR_EMAIL="${3:-}"
    NEW_AUTHOR_URL="${4:-}"
    NEW_DESCRIPTION="${5:-}"
    NEW_GITHUB_REPO="${6:-}"

    # Generate derived values
    NEW_SLUG=$(generate_slug "$NEW_PLUGIN_NAME")
    NEW_FUNCTION_PREFIX=$(generate_function_prefix "$NEW_PLUGIN_NAME")
    NEW_CLASS_PREFIX=$(generate_class_prefix "$NEW_PLUGIN_NAME")
    NEW_NAMESPACE=$(generate_namespace "$NEW_PLUGIN_NAME")
    NEW_CONSTANT_PREFIX=$(generate_constant_prefix "$NEW_PLUGIN_NAME")
    
    # Generate author slug for packages
    if [[ -n "$NEW_AUTHOR" ]]; then
        NEW_AUTHOR_SLUG=$(generate_slug "$NEW_AUTHOR")
    fi

    # Validate inputs
    validate_inputs

    # Show what will be changed
    echo "Preview of changes:"
    echo "=================="
    echo "Plugin Name: WP Plugin Starter → $NEW_PLUGIN_NAME"
    echo "Slug: wp-plugin-starter → $NEW_SLUG"
    echo "Function Prefix: wp_plugin_starter → $NEW_FUNCTION_PREFIX"
    echo "Class Prefix: WP_Plugin_Starter → $NEW_CLASS_PREFIX"
    echo "Namespace: WPPluginStarter → $NEW_NAMESPACE"
    echo "Constants: WP_PLUGIN_STARTER → $NEW_CONSTANT_PREFIX"
    
    if [[ -n "$NEW_AUTHOR" ]]; then
        echo "Author: Al Amin Ahamed → $NEW_AUTHOR"
    fi
    
    echo ""
    read -p "Do you want to proceed? (y/N): " -n 1 -r
    echo ""
    
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Operation cancelled."
        exit 1
    fi

    # Create backup
    backup_project

    # Initialize step counter
    current_step=1

    # Common exclude paths
    EXCLUDE_PATHS="./vendor/* ./node_modules/* ./build/* ./.git/* ./coverage/*"

    # Update different file types
    update_file_contents "*.php" "$EXCLUDE_PATHS" "Updating PHP files"
    update_file_contents "*.js" "$EXCLUDE_PATHS" "Updating JavaScript files"
    update_file_contents "*.jsx" "$EXCLUDE_PATHS" "Updating React files"
    update_file_contents "*.ts" "$EXCLUDE_PATHS" "Updating TypeScript files"
    update_file_contents "*.tsx" "$EXCLUDE_PATHS" "Updating TypeScript React files"
    update_file_contents "*.css" "$EXCLUDE_PATHS" "Updating CSS files"
    update_file_contents "*.scss" "$EXCLUDE_PATHS" "Updating SCSS files"
    update_file_contents "*.json" "$EXCLUDE_PATHS ./composer.json ./package.json" "Updating JSON files"
    update_file_contents "*.md" "$EXCLUDE_PATHS" "Updating Markdown files"
    update_file_contents "*.txt" "$EXCLUDE_PATHS" "Updating text files"
    update_file_contents "*.xml" "$EXCLUDE_PATHS" "Updating XML files"
    update_file_contents "*.yml" "$EXCLUDE_PATHS" "Updating YAML files"
    update_file_contents "*.yaml" "$EXCLUDE_PATHS" "Updating YAML files"

    # Update specific files
    update_package_scripts
    update_composer
    update_readme_files

    # Rename files
    rename_files

    # Show summary
    show_summary
}

# Check if script is being run directly
if [[ "${BASH_SOURCE[0]}" == "${0}" ]]; then
    main "$@"
fi