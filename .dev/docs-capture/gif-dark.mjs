// Dark mode GIF from the published demo: where the switch is, and what it does.
import { browser, eager, watchdog } from './docs-lib.mjs';
watchdog(150000);
const { b, pg } = await browser({ width: 1200, height: 860, scale: 1 });
await pg.goto('https://colorlibhub.com/pato/menu/', { waitUntil: 'domcontentloaded' });
await eager(pg);
await pg.addStyleTag({ content: '.pato-ring{outline:3px solid #f0b849 !important;outline-offset:5px !important;border-radius:999px !important}' });
const toggle = pg.locator('.pato-scheme-toggle, [class*="scheme-toggle"] button').first();
const ring = on => toggle.evaluate((e, on) => (e.closest('.wp-block-button') || e).classList.toggle('pato-ring', on), on);
// Show the header and the first light section below the banner.
await pg.evaluate(() => { const h = document.querySelector('.pato-section__title, h2'); window.scrollTo(0, 0); });
await pg.waitForTimeout(600);
const shot = async n => { await pg.waitForTimeout(250); await pg.screenshot({ path: `docs/gif/dark-${n}.png` }); };
await shot(1);
await ring(true); await shot(2);
await toggle.click(); await pg.waitForTimeout(1100); await shot(3);
await ring(false); await shot(4);
await ring(true); await shot(5);
await toggle.click(); await pg.waitForTimeout(1100); await shot(6);
console.log('scheme after two clicks:', await pg.evaluate(() => document.documentElement.className || '(none)'));
await b.close();
