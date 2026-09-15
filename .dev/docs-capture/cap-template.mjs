import { openEditor, BASE, watchdog } from './docs-lib.mjs';
watchdog(150000);
const { b, pg } = await openEditor(BASE + '/wp-admin/post.php?post=7&action=edit');
const hdr = await pg.$$eval('.editor-header button, header button', bs => bs.map(x => x.getAttribute('aria-label') || x.textContent.trim()).filter(Boolean));
console.log('header buttons:', JSON.stringify(hdr));
if (!(await pg.$('.editor-sidebar, .interface-complementary-area'))) {
  await pg.locator('button[aria-label="Settings"]').first().click();
  await pg.waitForTimeout(1500);
}
const tab = pg.getByRole('tab', { name: 'Page', exact: true });
if (await tab.count()) { await tab.first().click(); await pg.waitForTimeout(800); }
console.log('rows:', JSON.stringify(await pg.$$eval('.editor-post-panel__row', r => r.map(x => x.innerText.replace(/\s+/g, ' ').trim()))));
// Pages created during activation by the blueprint have no author; a real activation sets one.
await pg.evaluate(() => document.querySelectorAll('.editor-post-panel__row').forEach(r => { if (/^Author/.test(r.innerText.trim())) r.remove(); }));
await pg.locator('.editor-post-panel__row button').filter({ hasText: 'Page without title' }).first().click();
await pg.waitForTimeout(1200);
await pg.screenshot({ path: 'docs/23a-template-menu.png' });
console.log('menuitems:', JSON.stringify(await pg.$$eval('[role="menuitem"]', m => m.map(x => x.textContent.trim()))));
const swap = pg.getByRole('menuitem', { name: /Change template|Swap template/ }).first();
if (await swap.count()) {
  await swap.click();
  await pg.waitForTimeout(4500);
  await pg.screenshot({ path: 'docs/23b-swap-template.png' });
  console.log('swap captured');
}
await b.close();
