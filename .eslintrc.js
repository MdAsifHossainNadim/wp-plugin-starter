module.exports = {
  extends: [
    'plugin:@wordpress/eslint-plugin/recommended',
    'plugin:@wordpress/eslint-plugin/esnext',
    'plugin:@wordpress/eslint-plugin/i18n',
    'plugin:@wordpress/eslint-plugin/react',
  ],
  settings: {
    'import/resolver': {
      node: {
        extensions: [ '.js', '.jsx', '.ts', '.tsx' ],
      },
      alias: {
        map: [
          [ '@', './src' ],
          [ '@admin', './src/admin' ],
          [ '@frontend', './src/frontend' ],
          [ '@scss', './src/scss' ],
        ],
        extensions: [ '.ts', '.tsx', '.js', '.jsx', '.json', '.css', '.scss' ],
      },
    },
    jsdoc: {
      mode: 'typescript',
      tagNamePreference: {
        returns: 'return',
      },
      validTypes: {
        React: true,
        JSX: true,
      },
    },
  },
  rules: {
    'prettier/prettier': 'off',
    '@wordpress/i18n-text-domain': [
      'error',
      {
        allowedTextDomain: [ 'wp-plugin-starter' ],
      },
    ],
    'import/order': [
      'error',
      {
        groups: [ 'builtin', 'external', 'internal', 'parent', 'sibling', 'index' ],
        pathGroups: [
          {
            pattern: '@wordpress/**',
            group: 'external',
            position: 'before',
          },
          {
            pattern: '@/**',
            group: 'internal',
            position: 'before',
          },
          {
            pattern: '@admin/**',
            group: 'internal',
            position: 'before',
          },
          {
            pattern: '@frontend/**',
            group: 'internal',
            position: 'before',
          },
          {
            pattern: '@scss/**',
            group: 'internal',
            position: 'before',
          },
          {
            pattern: './*.scss',
            group: 'sibling',
            position: 'after',
          },
        ],
        'newlines-between': 'always',
        alphabetize: {
          order: 'asc',
          caseInsensitive: true,
        },
        distinctGroup: true,
        warnOnUnassignedImports: true,
      },
    ],
    'no-unused-vars': [ 'error', { argsIgnorePattern: '^_', varsIgnorePattern: '^_' } ],
    'jsdoc/check-line-alignment': [ 'error', 'always' ],
    'no-console': 'error',
    'no-alert': 'error',
    'no-undef': 'error',
  },
};
