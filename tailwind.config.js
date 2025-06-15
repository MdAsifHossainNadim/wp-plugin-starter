/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [ './src/**/*.{js,jsx,ts,tsx}', './templates/**/*.php', './includes/**/*.php' ],
	// Prefix Tailwind classes to avoid conflicts with WordPress and other plugins
	prefix: 'dk-',
	theme: {
		extend: {
			colors: {
				// WP Plugin Starter branding colors (example)
				primary: {
					DEFAULT: '#1c9371',
					50: '#effef6',
					100: '#d7fceb',
					200: '#b2f4d9',
					300: '#79e9c1',
					400: '#40d4a3',
					500: '#1c9371',
					600: '#089270',
					700: '#07745d',
					800: '#075c4b',
					900: '#074c3f',
					950: '#042b23',
				},
				secondary: {
					DEFAULT: '#435c94',
					50: '#f5f7fb',
					100: '#eaeff6',
					200: '#d1dcec',
					300: '#abbfdc',
					400: '#7d9bca',
					500: '#5c7db5',
					600: '#435c94',
					700: '#364978',
					800: '#2e3f64',
					900: '#293755',
					950: '#1a2236',
				},
			},
			// Match WordPress admin spacing/sizes
			fontSize: {
				'admin-xs': [ '0.75rem', '1rem' ],
				'admin-sm': [ '0.875rem', '1.25rem' ],
				'admin-base': [ '1rem', '1.5rem' ],
				'admin-lg': [ '1.125rem', '1.75rem' ],
				'admin-xl': [ '1.25rem', '1.75rem' ],
				'admin-2xl': [ '1.5rem', '2rem' ],
			},
			spacing: {
				'wp-1': '4px',
				'wp-2': '8px',
				'wp-3': '12px',
				'wp-4': '16px',
				'wp-5': '20px',
				'wp-6': '24px',
				'wp-8': '32px',
				'wp-10': '40px',
				'wp-12': '48px',
			},
			// WordPress admin breakpoints
			screens: {
				'wp-sm': '600px',
				'wp-md': '782px',
				'wp-lg': '960px',
				'wp-xl': '1280px',
				'wp-2xl': '1440px',
			},
			// WordPress shadow styles
			boxShadow: {
				'wp-popover': '0 2px 6px rgba(0, 0, 0, 0.05)',
				'wp-card': '0 1px 3px rgba(0, 0, 0, 0.04)',
				'wp-dropdown': '0 2px 4px rgba(0, 0, 0, 0.08)',
				'wp-modal': '0 5px 15px rgba(0, 0, 0, 0.12)',
			},
		},
	},
	plugins: [ require( '@tailwindcss/forms' ) ],
};
