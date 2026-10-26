/**
 * Extensive VC assets build
 *
 * npm run build - builds minified js/css files and copies 3rd party libraries into assets/plugins
 * npm run watch - rebuilds files on every change
 *
 * JS sources: assets/js/modules/*.js (global plugin scripts) and shortcodes/<shortcode>/assets/js/*.js
 * Output: main.min.js (all scripts), core.min.js (global scripts) and assets/js/elements/<shortcode>.min.js
 * CSS sources: assets/css/main.css and assets/css/admin/main-admin.css, main.css is split per element with bin/split-css.js
 */
const path = require( 'path' );
const glob = require( 'fs' ).globSync;
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const CopyPlugin = require( 'copy-webpack-plugin' );
const TerserPlugin = require( 'terser-webpack-plugin' );

// Global modules are loaded first, then shortcodes modules ordered by shortcode name
const mainScripts = [].concat(
	glob( 'assets/js/modules/*.js' ).sort( ( a, b ) => ( path.basename( a ) === 'global.js' ? -1 : path.basename( b ) === 'global.js' ? 1 : a.localeCompare( b ) ) ),
	glob( '{shortcodes,post-types,plugins}/*/assets/js/*.js' ).sort(),
	glob( 'plugins/*/shortcodes/*/assets/js/*.js' ).sort()
).map( ( file ) => './' + file.split( path.sep ).join( '/' ) );

// Per element scripts, loaded only on pages where element is used (element slug is shortcode folder name)
const elementScripts = {};

glob( '{shortcodes,post-types,plugins}/*/assets/js/*.js' )
	.concat( glob( 'plugins/*/shortcodes/*/assets/js/*.js' ) )
	.map( ( file ) => file.split( path.sep ).join( '/' ) )
	.sort()
	.forEach( ( file ) => {
		const slug = file.split( '/assets/js/' )[ 0 ].split( '/' ).pop();

		elementScripts[ 'assets/js/elements/' + slug + '.min' ] = ( elementScripts[ 'assets/js/elements/' + slug + '.min' ] || [] ).concat( './' + file );
	} );

// Remove source map references from 3rd party libraries, map files are not distributed
const removeSourceMap = ( content ) => content.toString().replace( /\n?\/\/# sourceMappingURL=.*$/m, '' );

const common = {
	context: __dirname,
	devtool: false,
	output: {
		path: __dirname,
		filename: '[name].js',
		clean: false,
	},
	externals: {
		jquery: 'jQuery',
	},
	stats: 'minimal',
	performance: {
		hints: false,
	},
};

const terser = new TerserPlugin( {
	extractComments: false,
	terserOptions: {
		format: {
			comments: false,
		},
	},
} );

module.exports = [
	// Minified files which are loaded by the plugin
	{
		...common,
		name: 'minified',
		entry: {
			'assets/js/main.min': mainScripts,
			'assets/js/core.min': mainScripts.filter( ( file ) => file.startsWith( './assets/js/modules/' ) ),
			...elementScripts,
			'assets/js/admin/main-admin.min': './assets/js/admin/main-admin.js',
			'assets/css/main.min': './assets/css/main.css',
			'assets/css/admin/main-admin.min': './assets/css/admin/main-admin.css',
		},
		module: {
			rules: [
				{
					test: /\.css$/,
					use: [ MiniCssExtractPlugin.loader, { loader: 'css-loader', options: { url: false, import: false } } ],
				},
			],
		},
		optimization: {
			minimize: true,
			minimizer: [ terser, new CssMinimizerPlugin() ],
		},
		plugins: [
			new RemoveEmptyScriptsPlugin(),
			new MiniCssExtractPlugin( { filename: '[name].css' } ),
			new CopyPlugin( {
				patterns: [
					{ from: 'node_modules/chart.js/dist/chart.umd.min.js', to: 'assets/plugins/chart/chart.umd.min.js', transform: removeSourceMap },
					{ from: 'node_modules/lightbox2/dist/js/lightbox.min.js', to: 'assets/plugins/lightbox/lightbox.min.js', transform: removeSourceMap },
					{ from: 'node_modules/lightbox2/dist/css/lightbox.min.css', to: 'assets/plugins/lightbox/lightbox.min.css' },
					{ from: 'node_modules/lightbox2/dist/images', to: 'assets/plugins/lightbox/images' },
				],
			} ),
		],
	},
	// Readable version of the main script, loaded when SCRIPT_DEBUG is enabled
	{
		...common,
		name: 'readable',
		entry: {
			'assets/js/main': mainScripts,
		},
		optimization: {
			minimize: false,
		},
	},
];
