# Release Process

> **Documentation Version**: This documentation is based on WP Plugin Starter version 1.0.0. Features and functionality may vary in other versions.

This document outlines the detailed process for releasing new versions of WP Plugin Starter, from planning to post-release activities.

## 📋 Table of Contents

- [Release Planning](#-release-planning)
- [Version Numbering](#-version-numbering)
- [Pre-Release Checklist](#-pre-release-checklist)
- [Release Preparation](#-release-preparation)
- [Build Process](#-build-process)
- [Testing Process](#-testing-process)
- [Release Deployment](#-release-deployment)
- [Post-Release Activities](#-post-release-activities)
- [Hotfix Process](#-hotfix-process)
- [Release Schedule](#-release-schedule)

## 📅 Release Planning

### Release Types

WP Plugin Starter follows these release types:

1. **Major Releases** (X.0.0):

    - Significant new features
    - Potentially breaking changes
    - Major architectural improvements
    - Thoroughly tested before release

2. **Minor Releases** (x.Y.0):

    - New features that don't break backward compatibility
    - Enhancements to existing functionality
    - Non-breaking API changes
    - Performance improvements

3. **Patch Releases** (x.y.Z):

    - Bug fixes
    - Security updates
    - Small improvements
    - No breaking changes

4. **Release Candidates** (x.y.z-RC#):

    - Pre-release versions for testing
    - Feature complete for the target release
    - Released for community testing

5. **Beta Releases** (x.y.z-beta#):
    - Early testing versions
    - Feature incomplete
    - Known issues may exist
    - Released for early adopter testing

### Release Planning Process

1. **Feature Planning**:

    - Identify features for the next release
    - Document requirements and specifications
    - Prioritize features based on user needs

2. **Roadmap Creation**:

    - Create a release roadmap
    - Set timelines for feature development
    - Allocate resources

3. **Issue Collection**:

    - Gather bugs to be fixed
    - Review community feedback
    - Address security vulnerabilities

4. **Development Sprint Planning**:
    - Break down features into tasks
    - Assign tasks to developers
    - Set sprint goals and deadlines

## 🔢 Version Numbering

WP Plugin Starter follows [Semantic Versioning](https://semver.org/) (SemVer) for version numbers:

```
MAJOR.MINOR.PATCH[-PRERELEASE]
```

### Versioning Rules

1. **MAJOR version**: Incremented for incompatible API changes or significant feature additions
2. **MINOR version**: Incremented for backward-compatible new functionality
3. **PATCH version**: Incremented for backward-compatible bug fixes
4. **PRERELEASE**: Optional suffix for pre-release versions (e.g., `-beta.1`, `-rc.2`)

### Version Number Locations

When updating the version, ensure it's updated in all these locations:

- `wp-plugin-starter.php` (main plugin file header)
- `package.json`
- `composer.json`
- `CHANGELOG.md`
- Any hardcoded version constants (e.g., `WP_PLUGIN_STARTER_VERSION`)

## ✅ Pre-Release Checklist

Before beginning the release process, verify these items:

### Features and Fixes

- [ ] All planned features are completed and merged to the develop branch
- [ ] All targeted bug fixes are completed
- [ ] No blocking issues remain open for this release
- [ ] All pull requests for this release have been reviewed and merged

### Documentation

- [ ] All new features are documented
- [ ] README.md is updated
- [ ] CHANGELOG.md is complete and follows the standard format
- [ ] Developer documentation is updated
- [ ] Code is properly commented

### Quality Assurance

- [ ] Coding standards are met (run PHPCS and ESLint)
- [ ] All unit tests pass
- [ ] Integration tests pass
- [ ] End-to-end tests pass
- [ ] Manual testing completed according to test plan

### Compatibility

- [ ] Tested with the minimum required WordPress version
- [ ] Tested with the latest WordPress version
- [ ] Tested with the minimum required PHP version
- [ ] Tested with the minimum required WP Plugin Starter version
- [ ] Tested with the minimum required WooCommerce version
- [ ] Tested with popular themes (Storefront, Astra, etc.)

## 🛠️ Release Preparation

### 1. Create Release Branch

```bash
# Ensure you're on the latest develop branch
git checkout develop
git pull origin develop

# Create a release branch
git checkout -b release/x.y.z
```

### 2. Update Version Numbers

Update version numbers in all required files:

```bash
# Update manually in:
# - wp-plugin-starter.php
# - package.json
# - composer.json

# Verify changes
git diff
```

### 3. Update Changelog

Ensure `CHANGELOG.md` is complete and follows the [Keep a Changelog](https://keepachangelog.com/) format:

```markdown
## [x.y.z] - YYYY-MM-DD

### Added

- New feature 1
- New feature 2

### Changed

- Improvement 1
- Improvement 2

### Fixed

- Bug fix 1
- Bug fix 2

### Removed

- Deprecated feature 1
```

### 4. Update README.md

Ensure README.md reflects the latest features and requirements.

### 5. Update Translation Files

```bash
# Generate POT file
npm run makepot
```

### 6. Commit Changes

```bash
git add .
git commit -m "chore: prepare release x.y.z"
```

## 🔨 Build Process

### 1. Clean Environment

Start with a clean environment:

```bash
# Remove previous build files
rm -rf build/
rm -rf vendor/
rm -rf node_modules/

# Reinstall dependencies
composer install --no-dev --optimize-autoloader
yarn install
```

### 2. Run Final Tests

```bash
# PHP tests
composer run test

# JavaScript tests
yarn test

# Code standards checks
composer run phpcs
yarn lint:js
yarn lint:style
```

### 3. Build Production Package

The build and packaging process has been simplified with yarn scripts:

```bash
# Build production package (assets, translations, and zip)
yarn release
```

This single command performs the following steps:
1. Builds production assets with webpack (`yarn build`)
2. Generates translation files (`yarn makepot`)
3. Creates a distribution zip file (`yarn zip`)

The resulting package `wp-plugin-starter-x.y.z.zip` (where x.y.z is the version from package.json) will be available in the `release/dist` directory.

#### What's included in the package

The package includes:
- PHP files
- Built JavaScript and CSS files
- Templates
- Required text files (README, LICENSE, etc.)
- Translation files

#### What's excluded from the package

The `.distignore` file controls what's excluded from the package:
- Development files (.git, .github, etc.)
- Source files (src/)
- Configuration files (.eslintrc, .phpcs.xml, etc.)
- Tests
- Documentation
- Node modules
- Composer dev dependencies

### 4. Manual Package Creation (Alternative)

If needed, you can also create the package manually:

```bash
# Build assets only
yarn build

# Generate translations only
yarn makepot

# Create zip package only
yarn zip
```

## 🧪 Testing Process

### 1. Install on a Clean Site

Test the distribution package on a clean WordPress installation:

```bash
# Create a fresh WordPress site for testing
wp core download
wp core config --dbname=test_db --dbuser=root --dbpass=root
wp core install --url=localhost --title="Test Site" --admin_user=admin --admin_password=password --admin_email=test@example.com

# Install required plugins
wp plugin install woocommerce --activate
wp plugin install dokan-lite --activate

# Install WP Plugin Starter from the release package
wp plugin install ./release/dist/wp-plugin-starter-x.y.z.zip --activate
```

### 2. Test Critical Functionality

Test all critical functionality:

- Admin settings page
- Vendor features
- Product features
- Cart & checkout features
- Shipping features

### 3. Test Upgrade Process

Test upgrading from the previous version:

1. Install the previous version
2. Configure with some settings
3. Upgrade to the new version
4. Verify all settings and data are preserved

### 4. Beta/RC Release (Optional)

For major releases, consider releasing a beta or release candidate first:

```bash
# Tag as a pre-release
git tag vx.y.z-beta.1
git push origin vx.y.z-beta.1

# Create GitHub pre-release
gh release create vx.y.z-beta.1 --prerelease --title "WP Plugin Starter x.y.z Beta 1" --notes "This is a beta release for testing purposes."
```

## 🚀 Release Deployment

### 1. Merge Release Branch

After successful testing:

```bash
# Push release branch
git push origin release/x.y.z

# Create pull request from release/x.y.z to main
# Review and merge the pull request
```

### 2. Create Release Tag

```bash
# Checkout the main branch
git checkout main
git pull origin main

# Create and push tag
git tag vx.y.z
git push origin vx.y.z
```

### 3. Create GitHub Release

1. Go to GitHub Releases page
2. Create a new release from the tag
3. Upload the ZIP package from `release/dist/wp-plugin-starter-x.y.z.zip`
4. Include release notes from the changelog

### 4. Publish to Distribution Channels

Depending on your distribution channels:

- **Website**: Update download links to point to the new release
- **Marketplace**: Update listing with the new version

### 5. Merge Back to Develop

```bash
git checkout develop
git merge --no-ff main
git push origin develop
```

## 📢 Post-Release Activities

### 1. Announcement

Announce the release on:

- Official website
- Social media channels
- Email newsletter
- Community forums

### 2. Documentation Update

Ensure all public documentation is updated:

- Website documentation
- GitHub wiki
- Code references

### 3. Monitor Feedback

- Monitor support channels for issues
- Track usage analytics
- Collect user feedback

### 4. Release Retrospective

Hold a retrospective meeting to discuss:

- What went well
- What could be improved
- Lessons for future releases

## 🔧 Hotfix Process

For critical issues requiring immediate fixes:

### 1. Create Hotfix Branch

```bash
# Checkout from main
git checkout main
git pull origin main
git checkout -b hotfix/x.y.z+1
```

### 2. Fix the Issue

Implement and test the fix:

```bash
# Make changes
# Test thoroughly
git add .
git commit -m "fix: description of the fix"
```

### 3. Update Version and Changelog

```bash
# Update version to x.y.z+1
# Update CHANGELOG.md with the fix
git add .
git commit -m "chore: prepare hotfix x.y.z+1"
```

### 4. Build and Test

Build the hotfix package:

```bash
# Create the release package
yarn release
```

Test the package thoroughly before proceeding.

### 5. Release the Hotfix

```bash
# Push branch
git push origin hotfix/x.y.z+1

# Create PR to main
# After approval and merge:

git checkout main
git pull origin main
git tag vx.y.z+1
git push origin vx.y.z+1

# Create GitHub release for the hotfix
# Upload the ZIP package from release/dist/wp-plugin-starter-x.y.z+1.zip
```

### 6. Merge to Develop

```bash
git checkout develop
git pull origin develop
git merge --no-ff main
git push origin develop
```

## 📆 Release Schedule

WP Plugin Starter follows this release schedule:

### Major Releases (x.0.0)

- **Frequency**: 2-3 times per year
- **Planning Phase**: 4-6 weeks
- **Development Phase**: 8-12 weeks
- **Testing Phase**: 2-3 weeks
- **Release Candidates**: 1-2 weeks before final release

### Minor Releases (x.y.0)

- **Frequency**: Every 1-2 months
- **Planning Phase**: 1-2 weeks
- **Development Phase**: 2-4 weeks
- **Testing Phase**: 1 week

### Patch Releases (x.y.z)

- **Frequency**: As needed for bug fixes
- **Development Phase**: 1-3 days
- **Testing Phase**: 1 day

### Release Calendar

Maintain a public release calendar to communicate:

- Upcoming releases
- Target dates
- Feature freeze dates
- Code freeze dates
- Release candidate dates
- Final release dates

## 📋 Release Checklist Template

```markdown
# Release Checklist for WP Plugin Starter x.y.z

## Pre-Release

- [ ] All planned features completed
- [ ] All targeted bug fixes completed
- [ ] No blocking issues remain
- [ ] All PRs for this release merged
- [ ] CHANGELOG.md updated
- [ ] Documentation updated
- [ ] Version numbers updated in all locations
- [ ] Translation files updated

## Build and Test

- [ ] Clean environment setup
- [ ] Dependencies installed
- [ ] All tests pass
- [ ] Code standards checks pass
- [ ] Production package created using `yarn release`
- [ ] Package tested on clean WordPress installation
- [ ] Upgrade from previous version tested

## Release

- [ ] Release branch merged to main
- [ ] Tag created
- [ ] GitHub release published with release zip attached
- [ ] Package uploaded to distribution channels
- [ ] Main merged back to develop

## Post-Release

- [ ] Release announced
- [ ] Documentation published
- [ ] Feedback channels monitored
- [ ] Release retrospective scheduled
```

By following this structured release process, WP Plugin Starter can maintain high quality, minimize disruptions, and provide a reliable experience for users and developers.
