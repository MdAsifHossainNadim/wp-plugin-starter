/**
 * External dependencies
 */
const path = require( 'path' );

/**
 * WordPress dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Define entry points for each script/style file
const entries = {
	'admin/app': './src/admin/app.jsx',
	// 'frontend/frontend': './src/frontend/frontend.js',
	// blocks: './src/frontend/blocks/index.js',
};

module.exports = {
	...defaultConfig,
	entry: entries,
	optimization: {
		...defaultConfig.optimization,
		splitChunks: {
			cacheGroups: {
				style: false,
				default: false,
			},
		},
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name][ext]',
				},
			},
			{
				test: /\.(png|svg|jpg|jpeg|gif)$/,
				type: 'asset/resource',
				generator: {
					filename: 'images/[name][ext]',
				},
			},
		],
	},
	resolve: {
		...defaultConfig.resolve,
		extensions: [ '.tsx', '.ts', '.js', '.jsx', '.json' ],
		alias: {
			...defaultConfig.resolve.alias,
			'@': path.resolve( __dirname, 'src' ),
			'@admin': path.resolve( __dirname, 'src/admin' ),
			'@frontend': path.resolve( __dirname, 'src/frontend' ),
			'@scss': path.resolve( __dirname, 'src/scss' ),
		},
	},
};
