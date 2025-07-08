/** @type {import('tailwindcss').Config} */
const path = require('path');

module.exports = {
	corePlugins: {
		preflight: false,
	},
	prefix : 'acu-',
	content : [
		"./../**/*.php",
		"./../../src/core/**/*.{js,ts,svelte}",
		"./../../src/conditional-format/**/*.{js,ts,svelte}",
		"./../../src/editing/**/*.{js,ts,svelte}",
		"./../../templates/**/*.php",
		"./../templates/**/*.php",
		"./js/**/*.{js,ts,svelte}",
		"./ui/**/*.{js,ts,svelte}",
	],
	theme : {
		// Custom Spacing
		spacing : {
			'0.5' : '3px',
			'1' : '5px',
			'1.5' : '8px',
			'2' : '10px',
			'2.5' : '13px',
			'3' : '15px',
			'3.5' : '18px',
			'4' : '20px',
			'5' : '25px',
			'6' : '30px',
			'7' : '35px',
			'8' : '40px',
			'8.5' : '43px',
			'9' : '45px',
			'10' : '50px',
		},
		screens: {
			'sm': '640px',
			'md' : '782px', // WordPress default mobile breakpoint
			'lg' : '960px', // WordPress menu bar breakpoint
			'xl' : '1280px',
			'2xl' : '1450px',
			'3xl' : '1600px'
		},
		colors: {
			'pink' : '#e9426e',
			'gray-dark' : '#3D4350',
			'acbase': 'var(--ac-text-color)',
			'ui-border': 'var(--acui-border)',
			'link': 'var(--ac-link)',
			'link-hover': 'var(--ac-link-hover)',
			'notification-red': 'var(--ac-notification-red)',
			'notification-blue': 'var(--ac-notification-blue)'
		},
		extend : {},
	},
	plugins : [
		require('@tailwindcss/container-queries'),
	],
}
