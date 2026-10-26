/**
 * Split main stylesheet into core and per element stylesheets
 *
 * Source: assets/css/main.css, element styles are wrapped with banner comments:
 * "<Element Name> shortcode style - begin/end" and "<Element Name> shortcode responsive style - begin/end"
 *
 * Output: assets/css/core.min.css (common styles) and assets/css/elements/<element-slug>.min.css,
 * plugin enqueues element stylesheet only on pages where element is used
 */
const fs = require( 'fs' );
const path = require( 'path' );
const postcss = require( 'postcss' );
const cssnano = require( 'cssnano' );

const ROOT = path.resolve( __dirname, '..' );
const SOURCE = path.join( ROOT, 'assets/css/main.css' );
const OUTPUT_DIR = path.join( ROOT, 'assets/css/elements' );

// Global sections which belong to one element only
const SECTION_OWNERS = [ { pattern: /^WooCommerce .+ global styles$/, slug: 'product-list' } ];

const getSectionSlug = ( name ) => {
	const element = name.match( /^(.+?) shortcode (?:responsive )?styles?$/ );

	if ( element ) {
		return element[ 1 ].trim().toLowerCase().replace( /\s+/g, '-' );
	}

	const owner = SECTION_OWNERS.find( ( item ) => item.pattern.test( name ) );

	return owner ? owner.slug : '';
};

const banner = '\\/\\* =+\\s*\\n\\s*(.+?) - begin\\s*\\n\\s*=+ \\*\\/';

const split = ( css ) => {
	const elements = {};
	const sections = [];
	const beginRegex = new RegExp( banner, 'g' );
	let match;

	while ( ( match = beginRegex.exec( css ) ) ) {
		const name = match[ 1 ].trim();
		const slug = getSectionSlug( name );

		if ( ! slug ) {
			continue;
		}

		const endRegex = new RegExp( '\\/\\* =+\\s*\\n\\s*' + name.replace( /[.*+?^${}()|[\]\\]/g, '\\$&' ) + ' - end\\s*\\n\\s*=+ \\*\\/', 'g' );

		endRegex.lastIndex = match.index;

		const end = endRegex.exec( css );

		if ( ! end ) {
			throw new Error( 'Missing end banner for section "' + name + '"' );
		}

		sections.push( { slug, start: match.index, end: end.index + end[ 0 ].length } );
		beginRegex.lastIndex = end.index + end[ 0 ].length;
	}

	let core = '';
	let position = 0;

	sections.forEach( ( section ) => {
		core += css.slice( position, section.start );
		elements[ section.slug ] = ( elements[ section.slug ] || '' ) + css.slice( section.start, section.end ) + '\n';
		position = section.end;
	} );

	core += css.slice( position );

	return { core, elements };
};

const minify = async ( css, from ) => ( await postcss( [ cssnano( { preset: 'default' } ) ] ).process( css, { from } ) ).css;

( async () => {
	const { core, elements } = split( fs.readFileSync( SOURCE, 'utf8' ) );

	fs.rmSync( OUTPUT_DIR, { recursive: true, force: true } );
	fs.mkdirSync( OUTPUT_DIR, { recursive: true } );

	const coreFile = path.join( ROOT, 'assets/css/core.min.css' );

	fs.writeFileSync( coreFile, await minify( core, SOURCE ) );

	for ( const [ slug, css ] of Object.entries( elements ) ) {
		fs.writeFileSync( path.join( OUTPUT_DIR, slug + '.min.css' ), await minify( css, SOURCE ) );
	}

	const size = ( file ) => ( fs.statSync( file ).size / 1024 ).toFixed( 1 ) + ' KB';

	console.log( '[split-css] core.min.css ' + size( coreFile ) + ', ' + Object.keys( elements ).length + ' element stylesheets in assets/css/elements' );
} )().catch( ( error ) => {
	console.error( '[split-css] ' + error.message );
	process.exit( 1 );
} );
