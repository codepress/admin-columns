/** @type {import('tailwindcss').Config} */
const path = require('path');

module.exports = {
	prefix : 'acu-',
	content : [
		"./../../templates/**/*.php",
		"./../../src/core/**/*.{js,ts,svelte}",
		"./../../src/editing/**/*.{js,ts,svelte}",
		"./js/**/*.{js,ts}",
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
		sceens: {
			'sm': '640px',
			'md' : '783px',
			'lg' : '1024px',
			'xl' : '1280px'
		},
		colors: {
			'notification-red': 'var(--ac-notification-red)',
			'notification-blue': 'var(--ac-notification-blue)'
		},
		extend : {},
	},
	plugins : [],
}
