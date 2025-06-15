#!/bin/bash

# Exit if any command fails
set -e

# Check if version is set
if [ -z "$PLUGIN_VERSION" ]; then
    echo "Error: PLUGIN_VERSION is not set"
    exit 1
fi

# Plugin name
PLUGIN_NAME="wp-plugin-starter"
PLUGIN_SLUG="wp-plugin-starter"

# Main directories
MAIN_DIR=$(pwd)
BUILD_DIR="$MAIN_DIR/build"
RELEASE_DIR="$MAIN_DIR/release"
DIST_DIR="$RELEASE_DIR/dist"
SVN_DIR="$RELEASE_DIR/svn"
SVN_REPO="https://plugins.svn.wordpress.org/$PLUGIN_SLUG"

# Ensure the release directory exists
mkdir -p "$RELEASE_DIR"
mkdir -p "$DIST_DIR"

echo "Preparing release for version $PLUGIN_VERSION"

# Clean up any previous builds
rm -rf "$DIST_DIR/$PLUGIN_SLUG"
mkdir -p "$DIST_DIR/$PLUGIN_SLUG"

# Copy all necessary files to the distribution directory
echo "Copying plugin files..."
rsync -av --exclude-from='.distignore' --exclude='.*/' . "$DIST_DIR/$PLUGIN_SLUG/" --delete

# Create a zip file
echo "Creating zip file..."
cd "$DIST_DIR" || exit
zip -r "$PLUGIN_SLUG-$PLUGIN_VERSION.zip" "$PLUGIN_SLUG"
cd "$MAIN_DIR" || exit

echo "WordPress plugin zip created: $DIST_DIR/$PLUGIN_SLUG-$PLUGIN_VERSION.zip"

# SVN update/deploy (if needed)
if [ "$1" == "--deploy" ]; then
    echo "Preparing for SVN deployment..."

    # Check if SVN directory exists
    if [ -d "$SVN_DIR" ]; then
        echo "Updating SVN repository..."
        cd "$SVN_DIR" || exit
        svn update
    else
        echo "Checking out SVN repository..."
        mkdir -p "$SVN_DIR"
        svn checkout "$SVN_REPO" "$SVN_DIR"
        cd "$SVN_DIR" || exit
    fi

    # Clean SVN trunk
    echo "Cleaning SVN trunk..."
    rm -rf "$SVN_DIR/trunk/*"

    # Copy files to SVN trunk
    echo "Copying files to SVN trunk..."
    rsync -av "$DIST_DIR/$PLUGIN_SLUG/" "$SVN_DIR/trunk/" --delete

    # Create SVN tag if it doesn't exist
    if [ ! -d "$SVN_DIR/tags/$PLUGIN_VERSION" ]; then
        echo "Creating SVN tag $PLUGIN_VERSION..."
        mkdir -p "$SVN_DIR/tags/$PLUGIN_VERSION"
        rsync -av "$DIST_DIR/$PLUGIN_SLUG/" "$SVN_DIR/tags/$PLUGIN_VERSION/" --delete
        svn add --force "$SVN_DIR/tags/$PLUGIN_VERSION"
    fi

    # Add new files to SVN
    svn add --force "$SVN_DIR/trunk"

    # Ask for confirmation before committing
    echo "Ready to commit to WordPress.org SVN repository."
    read -p "Do you want to proceed with the commit? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        svn ci -m "Release $PLUGIN_VERSION"
        echo "SVN commit completed successfully!"
    else
        echo "SVN commit aborted."
    fi
fi

echo "Release process completed!"
