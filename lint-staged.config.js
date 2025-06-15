module.exports = {
  '*.{js,jsx,ts,tsx}': ['eslint --fix', 'prettier --write'],
  '*.{php}': ['phpcs --standard=phpcs.xml.dist --report=full'],
  '*.{css,scss}': ['prettier --write'],
};

