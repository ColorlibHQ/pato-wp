// Import each starter through Appearance → Starter sites, photograph the result
// logged out, and keep frames of the Pizzeria import for the GIF.
import { admin, browser, eager, BASE, watchdog } from './docs-lib.mjs';
watchdog(900000);

const starters = [['bistro', 'Bistro'], ['fine-dining', 'Fine dining'], ['cafe', 'Café'], ['pizzeria', 'Pizzeria'], ['bar', 'Bar'], ['bakery', 'Bakery']];
const adm = await admin({ width: 1280, height: 800, scale: 1 });
const pub = await browser({ width: 1400, height: 1000, scale: 2 });
await adm.pg.addInitScript(() => {});
const ring = '.pato-ring{outline:3px solid #f0b849 !important;outline-offset:3px !important}';
let g = 0;
const gshot = async (pg) => { await pg.waitForTimeout(300); await pg.screenshot({ path: `docs/gif/import-${++g}.png` }); };

for (const [slug, name] of starters) {
  const pg = adm.pg;
  await pg.goto(BASE + '/wp-admin/themes.php?page=pato-starters', { waitUntil: 'domcontentloaded' });
  await pg.evaluate(() => { document.querySelectorAll('.notice:not(.pato-notice), #wpfooter').forEach(e => e.remove()); });
  const card = pg.locator('div, li, article').filter({ has: pg.getByRole('heading', { name, exact: true }) }).filter({ has: pg.locator('button, input[type="submit"]') }).last();
  const btn = card.locator('button, input[type="submit"]').first();
  const gif = slug === 'pizzeria';
  if (gif) {
    await pg.addStyleTag({ content: ring });
    await gshot(pg);
    await btn.evaluate(e => e.classList.add('pato-ring'));
    await gshot(pg);
  }
  await Promise.all([pg.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 120000 }), btn.click()]);
  const notice = await pg.$eval('.notice, .updated', e => e.textContent.trim()).catch(() => '(no notice)');
  console.log(slug.padEnd(12), '→', notice.slice(0, 70));
  if (gif) {
    await pg.addStyleTag({ content: ring });
    await gshot(pg);
    await pg.locator('.notice a, .updated a').first().evaluate(e => e.classList.add('pato-ring')).catch(() => {});
    await gshot(pg);
  }

  await pub.pg.goto(BASE + '/', { waitUntil: 'domcontentloaded' });
  await eager(pub.pg);
  await pub.pg.screenshot({ path: `docs/starters/${slug}.png` });
  if (gif) {
    await pub.pg.setViewportSize({ width: 1280, height: 800 });
    await pub.pg.waitForTimeout(600);
    const tmp = await pub.pg.screenshot({ scale: 'css' });
    const fs = await import('node:fs');
    fs.writeFileSync(`docs/gif/import-${++g}.png`, tmp);
    await pub.pg.setViewportSize({ width: 1400, height: 1000 });
  }
  console.log('   h1:', await pub.pg.$eval('h1', e => e.textContent.trim()).catch(() => '?'));
}
await adm.b.close(); await pub.b.close();
console.log('gif frames:', g);
