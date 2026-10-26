/**
 * Build local fonts used by predefined style (GDPR friendly alternative to Google Fonts CDN)
 *
 * Copies woff2 files from @fontsource packages into assets/fonts and generates assets/css/fonts.min.css
 */
const fs = require( 'fs' );
const path = require( 'path' );

const ROOT = path.resolve( __dirname, '..' );
const FONTS_DIR = path.join( ROOT, 'assets/fonts' );
const FONTS = {
	raleway: [ 400, 500, 600, 700, 800, 900 ],
	poppins: [ 400, 700 ],
};
const SUBSETS = [ 'latin-ext', 'latin' ];

fs.rmSync( FONTS_DIR, { recursive: true, force: true } );
fs.mkdirSync( FONTS_DIR, { recursive: true } );

let css = '';

for ( const [ font, weights ] of Object.entries( FONTS ) ) {
	const packageDir = path.join( ROOT, 'node_modules/@fontsource', font );

	for ( const weight of weights ) {
		const source = fs.readFileSync( path.join( packageDir, weight + '.css' ), 'utf8' );

		for ( const subset of SUBSETS ) {
			const name = font + '-' + subset + '-' + weight + '-normal';
			const block = source.match( new RegExp( '/\\* ' + name + ' \\*/\\s*(@font-face\\s*\\{[^}]*\\})' ) );

			if ( ! block ) {
				throw new Error( 'Font face ' + name + ' not found' );
			}

			fs.copyFileSync( path.join( packageDir, 'files', name + '.woff2' ), path.join( FONTS_DIR, name + '.woff2' ) );

			// Only woff2 format is used, it is supported by all modern browsers
			css += block[ 1 ]
				.replace( /src:[^;]+;/, "src:url(../fonts/" + name + ".woff2) format('woff2');" )
				.replace( /\s*\n\s*/g, '' ) + '\n';
		}
	}
}

// Fonts license
fs.copyFileSync( path.join( ROOT, 'node_modules/@fontsource/raleway/LICENSE' ), path.join( FONTS_DIR, 'OFL.txt' ) );

fs.writeFileSync( path.join( ROOT, 'assets/css/fonts.min.css' ), '/* Raleway and Poppins fonts, SIL Open Font License 1.1 */\n' + css );

console.log( '[build-fonts] ' + fs.readdirSync( FONTS_DIR ).length + ' font files copied into assets/fonts' );
