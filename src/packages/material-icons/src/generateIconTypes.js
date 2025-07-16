import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function generateIconTypes() {
	try {
		// Lees het icons.js bestand
		const iconsPath = path.join(__dirname, 'icons.js');
		const iconsContent = await fs.readFile(iconsPath, 'utf8');

		const iconMatch = iconsContent.match(/\[([\s\S]*?)\]/);
		if (!iconMatch) {
			console.error('Kon de icon array niet vinden in icons.js');
			process.exit(1);
		}

		const icons = iconMatch[1]
			.split(',')
			.map(icon => icon.trim().replace(/'/g, ''))
			.filter(icon => icon);

		// Genereer de TypeScript declaration content
		const tsContent = `export declare const icons: readonly [
${icons.map(icon => `    '${icon}'`).join(',\n')}
];

export type IconName = typeof icons[number];
`;

		const dtsPath = path.join(__dirname, 'icons.d.ts');
		await fs.writeFile(dtsPath, tsContent);

		console.log('icons.d.ts is succesvol gegenereerd.');
	} catch (error) {
		console.error('Er is een fout opgetreden:', error);
		process.exit(1);
	}
}

generateIconTypes();