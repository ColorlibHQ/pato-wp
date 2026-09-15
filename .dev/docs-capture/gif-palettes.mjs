// Browse styles GIF over the Menu page (the front page here), scrolled to the
// price list so each palette shows in text, prices and grounds. Nothing is saved.
import { openEditor, BASE, watchdog } from './docs-lib.mjs';
watchdog(300000);
const { b, pg } = await openEditor(BASE + '/wp-admin/site-editor.php', { width: 1280, height: 800, scale: 1 });
const nav = label => pg.locator('.edit-site-sidebar-navigation-item, [class*="sidebar-navigation-item"], a, button').filter({ hasText: new RegExp('^\\s*' + label + '\\s*$') }).first();
await nav('Styles').click();
await pg.waitForTimeout(3000);
await pg.getByText('Browse styles', { exact: true }).first().click();
await pg.waitForTimeout(3000);
const cards = pg.locator('.edit-site-global-styles-variations_item, [class*="variations"] [role="button"]');
const labels = await cards.evaluateAll(els => els.map(e => e.getAttribute('aria-label')));
const canvas = () => pg.frames().find(fr => fr.name() === 'editor-canvas');
async function settle() {
  await pg.waitForTimeout(2200);
  const f = canvas();
  if (!f) return 'no canvas';
  return f.evaluate(async () => {
    const dish = document.querySelector('.pato-dish');
    if (!dish) return 'no dish';
    const cols = dish.closest('.wp-block-columns');
    const title = cols?.parentElement?.querySelector('.pato-section__title');
    const t = title || cols || dish;
    window.scrollTo(0, t.getBoundingClientRect().top + window.scrollY - 90);
    await new Promise(r => setTimeout(r, 500));
    if (document.fonts) await document.fonts.ready;
    return Math.round(window.scrollY);
  });
}
let k = 0;
const shot = async () => { await pg.mouse.move(1270, 790); await pg.waitForTimeout(300); await pg.screenshot({ path: `docs/gif/pal-${++k}.png` }); };
await cards.nth(0).click();
console.log('frame 1 Default scrollY', await settle()); await shot();
for (const i of [2, 4, 6, 7, 8, 0]) {
  await cards.nth(i).click();
  const y = await settle(); await shot();
  console.log('frame', k, labels[i], 'scrollY', y);
}
await b.close();
