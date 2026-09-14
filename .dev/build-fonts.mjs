/**
 * Downloads Pato's webfonts into assets/fonts/ and prints the theme.json
 * fontFace entries for .dev/build_theme.py.
 *
 * Montserrat is taken as a variable font: one file per subset covering the
 * whole 100-900 range, so the Site Editor can offer any weight without the
 * theme shipping a file per step. Poppins has no variable release, so its four
 * weights are static. Courgette has only one weight by design.
 *
 * Two faces per family per weight -- latin and latin-ext -- each carrying its
 * own unicode-range, so a page with no latin-ext character never fetches that
 * file. The ranges are read from Fontsource rather than hardcoded.
 *
 * Usage:  node .dev/build-fonts.mjs
 */

import { writeFileSync, mkdirSync, rmSync, existsSync } from 'node:fs';
import { join, dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname( fileURLToPath( import.meta.url ) );
const root = resolve( here, '..' );

const V = '5.3.0';
const VV = '5.2.6'; // @fontsource-variable packages version independently.

const FAMILIES = [
	{ pkg: 'montserrat', dir: 'montserrat', family: 'Montserrat', variable: true,
	  faces: [ { weight: '100 900', stem: 'montserrat-normal' } ] },
	{ pkg: 'poppins', dir: 'poppins', family: 'Poppins',
	  faces: [ 300, 400, 500, 700 ].map( w => ( { weight: String( w ), stem: `poppins-${ w }` } ) ) },
	{ pkg: 'courgette', dir: 'courgette', family: 'Courgette',
	  faces: [ { weight: '400', stem: 'courgette-400' } ] },
];

const SUBSETS = [ [ 'latin', '' ], [ 'latin-ext', '-ext' ] ];

async function get( url, text ) {
	const res = await fetch( url );
	if ( ! res.ok ) throw new Error( `${ res.status } ${ url }` );
	return text ? res.text() : Buffer.from( await res.arrayBuffer() );
}

/**
 * Only the combined per-weight stylesheet declares unicode-range; the
 * per-subset one omits it by design. Two rangeless faces for the same family
 * and weight collide, and the browser keeps the last -- which means every page
 * downloads latin-ext to draw ASCII.
 */
function rangeFor( css, base ) {
	const i = css.indexOf( `/* ${ base } */` );
	if ( i === -1 ) return null;
	const block = css.slice( i, css.indexOf( '}', i ) );
	const m = block.match( /unicode-range:\s*([^;]+);/ );
	return m ? m[ 1 ].trim() : null;
}

const out = {};
let total = 0;

for ( const f of FAMILIES ) {
	const dir = join( root, 'assets/fonts', f.dir );
	if ( existsSync( dir ) ) rmSync( dir, { recursive: true } );
	mkdirSync( dir, { recursive: true } );

	const pkg = f.variable ? `@fontsource-variable/${ f.pkg }` : `@fontsource/${ f.pkg }`;
	const ver = f.variable ? VV : V;
	const cdn = `https://cdn.jsdelivr.net/npm/${ pkg }@${ ver }`;

	out[ f.family ] = [];

	for ( const face of f.faces ) {
		for ( const [ subset, suffix ] of SUBSETS ) {
			const src = f.variable
				? `${ f.pkg }-${ subset }-wght-normal`
				: `${ f.pkg }-${ subset }-${ face.stem.split( '-' ).pop() }-normal`;

			const cssUrl = f.variable
				? `${ cdn }/index.css`
				: `${ cdn }/${ face.weight }.css`;
			const css = await get( cssUrl, true );
			const range = rangeFor( css, src );
			if ( ! range ) throw new Error( `no unicode-range for ${ src }` );

			const buf = await get( `${ cdn }/files/${ src }.woff2`, false );
			const name = `${ face.stem }-latin${ suffix }.woff2`;
			writeFileSync( join( dir, name ), buf );
			total += buf.length;

			out[ f.family ].push( { weight: face.weight, subset, range, file: `${ f.dir }/${ name }` } );
			console.log( `  ${ f.dir }/${ name }  ${ ( buf.length / 1024 ).toFixed( 1 ) } KB` );
		}
	}

	// The OFL text has to travel with the fonts.
	const ofl = await get( `${ cdn }/LICENSE`, true ).catch( () => null );
	if ( ofl ) writeFileSync( join( dir, 'OFL.txt' ), ofl );
}

writeFileSync( join( root, '.dev/font-faces.json' ), JSON.stringify( out, null, '\t' ) + '\n' );
console.log( `\n${ ( total / 1024 ).toFixed( 1 ) } KB total -> .dev/font-faces.json` );
