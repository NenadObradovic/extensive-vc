/**
 * Extensive VC release build
 *
 * Builds clean plugin version (without development files) and copies it into WordPress.org SVN working copy.
 * Nothing is committed, after the script is done review changes and commit them manually.
 *
 * Usage:
 * npm run release                                   - build assets and update SVN trunk (default SVN path is ../extensive-vc-addon)
 * npm run release -- --svn=E:/projects/extensive-vc-addon
 * npm run release -- --tag                          - also create tags/<version> from trunk
 * npm run release -- --zip                          - also create dist/extensive-vc-addon-<version>.zip
 * npm run release -- --no-svn --zip                 - only create zip file
 */
const fs = require( 'fs' );
const path = require( 'path' );
const { execFileSync } = require( 'child_process' );

const ROOT = path.resolve( __dirname, '..' );
const SLUG = 'extensive-vc-addon';

const args = process.argv.slice( 2 );
const hasArg = ( name ) => args.includes( '--' + name );
const getArg = ( name, fallback ) => {
	const arg = args.find( ( item ) => item.startsWith( '--' + name + '=' ) );

	return arg ? arg.slice( name.length + 3 ) : fallback;
};

const log = ( message ) => console.log( '\x1b[36m[release]\x1b[0m ' + message );
const fail = ( message ) => {
	console.error( '\x1b[31m[release] ' + message + '\x1b[0m' );
	process.exit( 1 );
};

/**
 * Files and folders which are not part of the plugin package. Patterns are relative to the plugin root,
 * same syntax as .distignore file (* matches inside one folder, ** matches any folders depth).
 */
const readDistIgnore = () => {
	const file = path.join( ROOT, '.distignore' );

	if ( ! fs.existsSync( file ) ) {
		fail( '.distignore file is missing' );
	}

	return fs
		.readFileSync( file, 'utf8' )
		.split( /\r?\n/ )
		.map( ( line ) => line.trim() )
		.filter( ( line ) => line && ! line.startsWith( '#' ) );
};

const globToRegExp = ( pattern ) => {
	const anchored = pattern.startsWith( '/' );
	const clean = pattern.replace( /^\//, '' ).replace( /\/$/, '' );
	const source = clean
		.replace( /[.+?^${}()|[\]\\]/g, '\\$&' )
		.replace( /\*\*\//g, '\u0000' )
		.replace( /\*\*/g, '\u0001' )
		.replace( /\*/g, '[^/]*' )
		.replace( /\u0000/g, '(?:.*/)?' )
		.replace( /\u0001/g, '.*' );

	// Pattern matches the path itself and everything inside it
	return new RegExp( ( anchored ? '^' : '(^|/)' ) + source + '(/|$)' );
};

const copyPlugin = ( target, ignored ) => {
	const matchers = ignored.map( globToRegExp );
	const isIgnored = ( relative ) => matchers.some( ( matcher ) => matcher.test( relative ) );
	let count = 0;

	const walk = ( relativeDir ) => {
		for ( const entry of fs.readdirSync( path.join( ROOT, relativeDir ), { withFileTypes: true } ) ) {
			const relative = path.posix.join( relativeDir.split( path.sep ).join( '/' ), entry.name );

			if ( isIgnored( relative ) ) {
				continue;
			}

			if ( entry.isDirectory() ) {
				walk( relative );
			} else if ( entry.isFile() ) {
				const destination = path.join( target, relative );

				fs.mkdirSync( path.dirname( destination ), { recursive: true } );
				fs.copyFileSync( path.join( ROOT, relative ), destination );
				count++;
			}
		}
	};

	walk( '' );

	return count;
};

// Remove everything inside the folder except SVN metadata
const emptyFolder = ( folder ) => {
	fs.mkdirSync( folder, { recursive: true } );

	for ( const entry of fs.readdirSync( folder ) ) {
		if ( '.svn' !== entry ) {
			fs.rmSync( path.join( folder, entry ), { recursive: true, force: true } );
		}
	}
};

const getVersions = () => {
	const read = ( file ) => fs.readFileSync( path.join( ROOT, file ), 'utf8' );
	const match = ( content, regex ) => ( content.match( regex ) || [] )[ 1 ];

	return {
		'extensive-vc.php header': match( read( 'extensive-vc.php' ), /^Version:\s*(.+)$/m ),
		'constants.php EXTENSIVE_VC_VERSION': match( read( 'constants.php' ), /'EXTENSIVE_VC_VERSION',\s*'([^']+)'/ ),
		'readme.txt Stable tag': match( read( 'readme.txt' ), /^Stable tag:\s*(.+)$/m ),
	};
};

const svn = ( svnArgs, cwd ) => execFileSync( 'svn', svnArgs, { cwd, encoding: 'utf8', stdio: [ 'ignore', 'pipe', 'inherit' ] } );

const zipFolder = ( sourceParent, folderName, zipFile ) => {
	fs.rmSync( zipFile, { force: true } );

	// Windows 10+ ships bsdtar which can create zip archives, other systems use zip command
	const windowsTar = path.join( process.env.SystemRoot || 'C:\\Windows', 'System32', 'tar.exe' );

	if ( 'win32' === process.platform && fs.existsSync( windowsTar ) ) {
		execFileSync( windowsTar, [ '-a', '-c', '-f', zipFile, folderName ], { cwd: sourceParent, stdio: 'inherit' } );
	} else {
		execFileSync( 'zip', [ '-r', '-q', zipFile, folderName ], { cwd: sourceParent, stdio: 'inherit' } );
	}
};

// 1. Versions check
const versions = getVersions();
const version = versions[ 'extensive-vc.php header' ];

for ( const [ name, value ] of Object.entries( versions ) ) {
	log( name + ': ' + value );
}

if ( Object.values( versions ).some( ( value ) => value !== version ) ) {
	fail( 'Plugin versions are not the same, please update all version values' );
}

// 2. Assets build
if ( ! hasArg( 'no-build' ) ) {
	log( 'Building assets...' );
	execFileSync( process.platform === 'win32' ? 'npm.cmd' : 'npm', [ 'run', 'build' ], { cwd: ROOT, stdio: 'inherit', shell: process.platform === 'win32' } );
}

// 3. Clean plugin package
const ignored = readDistIgnore();
const buildRoot = path.join( ROOT, 'dist' );
const buildDir = path.join( buildRoot, SLUG );

emptyFolder( buildDir );
log( 'Copied ' + copyPlugin( buildDir, ignored ) + ' files into dist/' + SLUG );

if ( hasArg( 'zip' ) ) {
	const zipFile = path.join( buildRoot, SLUG + '-' + version + '.zip' );

	zipFolder( buildRoot, SLUG, zipFile );
	log( 'Created ' + path.relative( ROOT, zipFile ) );
}

// 4. SVN working copy
if ( ! hasArg( 'no-svn' ) ) {
	const svnRoot = path.resolve( ROOT, getArg( 'svn', '../' + SLUG ) );
	const trunk = path.join( svnRoot, 'trunk' );

	if ( ! fs.existsSync( path.join( svnRoot, '.svn' ) ) ) {
		fail( 'SVN working copy not found in ' + svnRoot + ', use --svn=<path> argument' );
	}

	log( 'Updating ' + trunk );
	emptyFolder( trunk );
	copyPlugin( trunk, ignored );

	// Schedule new files for addition and removed files for deletion
	svn( [ 'add', '--force', '--quiet', 'trunk' ], svnRoot );

	const missing = svn( [ 'status', 'trunk' ], svnRoot )
		.split( /\r?\n/ )
		.filter( ( line ) => line.startsWith( '!' ) )
		.map( ( line ) => line.slice( 8 ).trim() );

	if ( missing.length ) {
		svn( [ 'rm', '--quiet', ...missing ], svnRoot );
	}

	if ( hasArg( 'tag' ) ) {
		const tag = path.join( svnRoot, 'tags', version );

		if ( fs.existsSync( tag ) ) {
			// Tag which is only scheduled for addition (not committed yet) can be recreated from the new trunk
			const tagStatus = svn( [ 'status', '--depth=empty', 'tags/' + version ], svnRoot );

			if ( ! tagStatus.startsWith( 'A' ) ) {
				fail( 'Tag ' + version + ' already exists in SVN repository, increase plugin version' );
			}

			svn( [ 'revert', '--quiet', '--recursive', 'tags/' + version ], svnRoot );
			fs.rmSync( tag, { recursive: true, force: true } );
		}

		svn( [ 'copy', '--quiet', 'trunk', 'tags/' + version ], svnRoot );
		log( 'Created tags/' + version + ' from trunk' );
	}

	const status = svn( [ 'status' ], svnRoot ).split( /\r?\n/ ).filter( Boolean );
	const summary = status.reduce( ( result, line ) => {
		result[ line[ 0 ] ] = ( result[ line[ 0 ] ] || 0 ) + 1;

		return result;
	}, {} );

	log( 'SVN changes: ' + JSON.stringify( summary ) + ' (A = added, M = modified, D = deleted)' );
	log( 'Review changes and commit them with: svn ci -m "Version ' + version + '" (in ' + svnRoot + ')' );
}

log( 'Done' );
