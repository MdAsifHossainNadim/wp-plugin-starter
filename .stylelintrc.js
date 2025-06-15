module.exports = {
    extends: [
        '@wordpress/stylelint-config/scss',
        'stylelint-config-prettier-scss',
        'stylelint-config-recess-order',
        'stylelint-config-tailwindcss'
    ],
    rules: {
        // Add any custom stylelint rules here
        'no-descending-specificity': null,
        'font-family-no-missing-generic-family-keyword': null,
        'selector-class-pattern': null,
        'at-rule-empty-line-before': null,
        'selector-pseudo-class-no-unknown': [
            true,
            {
                ignorePseudoClasses: [ 'global', 'local', 'export' ],
            },
        ],
    },
    ignoreFiles: [ 'build/**/*.css', 'node_modules/**/*.css', '**/*.min.css' ],
};
