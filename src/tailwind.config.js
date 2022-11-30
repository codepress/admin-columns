/** @type {import('tailwindcss').Config} */
const path = require('path');

module.exports = {
	prefix : 'acu-',
	content : [
		"./../../src/core/**/*.{js,ts,svelte}",
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
		},
		sceens: {
			'sm': '640px',
			'md' : '783px',
			'lg' : '1024px',
			'xl' : '1280px'
		},
		extend : {},
	},
	plugins : [],
}
