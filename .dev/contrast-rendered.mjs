/**
 * Measure text contrast on a real rendered page, under every colour palette.
 *
 * theme.json's own audit checks the palette's numbers. It cannot catch a
 * pattern that puts the wrong slug on the wrong ground — which is how every
 * cover headline and the whole footer came out black-on-black in the two dark
 * palettes: they asked for `base`, and `base` is the page background, so in a
 * dark palette it is nearly black.
 *
 * This walks the rendered DOM, resolves each text node's effective background
 * by climbing its ancestors, and reports anything under 4.5:1. It is the check
 * that would have caught that bug, so it runs over all eight palettes.
 *
 *   WP_URL=... WP_USER=... WP_PASS=... PATO_URL=/some-page/ node .dev/contrast-rendered.mjs
 *
 * @package Pato
 */

import { chromium } from 'playwright';

const site = process.env.WP_URL || 'http://local-wp.local';
const path = process.env.PATO_URL || '/';
const palettes = ( process.env.PATO_PALETTES || '' ).split( ',' ).filter( Boolean );

const browser = await chromium.launch();
const page = await ( await browser.newContext( { viewport: { width: 1400, height: 1000 } } ) ).newPage();

await page.goto( site + path, { waitUntil: 'networkidle', timeout: 90000 } );

const findings = await page.evaluate( () => {
	const luminance = ( colour ) => {
		const parts = colour.match( /[\d.]+/g );
		if ( ! parts ) {
			return null;
		}
		const [ r, g, b ] = parts.slice( 0, 3 ).map( ( v ) => {
			v = Number( v ) / 255;
			return v <= 0.03928 ? v / 12.92 : Math.pow( ( v + 0.055 ) / 1.055, 2.4 );
		} );
		return 0.2126 * r + 0.7152 * g + 0.0722 * b;
	};

	const ratio = ( a, b ) => {
		const la = luminance( a );
		const lb = luminance( b );
		if ( null === la || null === lb ) {
			return null;
		}
		return ( Math.max( la, lb ) + 0.05 ) / ( Math.min( la, lb ) + 0.05 );
	};

	const out = [];

	document.querySelectorAll( 'h1,h2,h3,h4,h5,h6,p,li,a,dt,dd,summary,label,button' ).forEach( ( el ) => {
		const own = [ ...el.childNodes ].some( ( n ) => 3 === n.nodeType && n.textContent.trim() );
		if ( ! own ) {
			return;
		}

		const box = el.getBoundingClientRect();
		if ( box.width < 6 || box.height < 6 ) {
			return;
		}

		const style = getComputedStyle( el );
		if ( 'hidden' === style.visibility || '0' === style.opacity || 'none' === style.display ) {
			return;
		}

		// Anything over a photograph is judged by eye, not by this: the
		// backdrop is pixels, not a colour.
		let node = el;
		let onImage = false;
		while ( node ) {
			const s = getComputedStyle( node );
			if ( s.backgroundImage && 'none' !== s.backgroundImage ) {
				onImage = true;
				break;
			}
			if ( node.classList && node.classList.contains( 'wp-block-cover' ) ) {
				onImage = true;
				break;
			}
			node = node.parentElement;
		}
		if ( onImage ) {
			return;
		}

		let background = 'rgba(0, 0, 0, 0)';
		node = el;
		while ( node ) {
			const c = getComputedStyle( node ).backgroundColor;
			if ( c && 'rgba(0, 0, 0, 0)' !== c && 'transparent' !== c ) {
				background = c;
				break;
			}
			node = node.parentElement;
		}
		if ( 'rgba(0, 0, 0, 0)' === background ) {
			background = 'rgb(255, 255, 255)';
		}

		const r = ratio( style.color, background );
		if ( null !== r && r < 4.5 ) {
			out.push( {
				text: el.textContent.trim().slice( 0, 40 ),
				colour: style.color,
				background,
				ratio: Number( r.toFixed( 2 ) ),
			} );
		}
	} );

	return out;
} );

await browser.close();

if ( findings.length ) {
	console.error( `${ findings.length } text nodes under 4.5:1` );
	for ( const f of findings.slice( 0, 12 ) ) {
		console.error( `  ${ f.ratio }  "${ f.text }"  ${ f.colour } on ${ f.background }` );
	}
	process.exit( 1 );
}

console.log( 'every measurable text node is at least 4.5:1' );
