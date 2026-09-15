import { admin, openEditor, BASE, watchdog } from './docs-lib.mjs';
watchdog(420000);
async function step(name, fn) { try { await fn(); console.log('ok  ', name); } catch (e) { console.log('FAIL', name, e.message.split('\n')[0]); } }

let ids;
{
  const { b, pg } = await admin();
  await pg.goto(BASE + '/wp-admin/edit.php?post_type=page', { waitUntil: 'domcontentloaded' });
  ids = await pg.$$eval('#the-list tr', rows => Object.fromEntries(rows.map(r => [r.querySelector('.row-title')?.textContent.trim(), r.id.replace('post-', '')])));
  console.log('ids', JSON.stringify(ids));
  await b.close();
}
const edit = id => BASE + `/wp-admin/post.php?post=${id}&action=edit`;

await step('20-edit-price', async () => {
  const { b, pg, f } = await openEditor(edit(ids.Menu));
  try {
    const price = f.locator('.pato-dish__price').nth(2);
    await price.scrollIntoViewIfNeeded();
    await price.click();
    await f.evaluate(() => document.querySelectorAll('.pato-dish')[2]?.scrollIntoView({ block: 'center' }));
    await pg.waitForTimeout(1000);
    await pg.screenshot({ path: 'docs/20-edit-price.png' });
  } finally { await b.close(); }
});

await step('21-list-view', async () => {
  const { b, pg } = await openEditor(edit(ids.Home));
  try {
    await pg.locator('button[aria-label="Document Overview"], button[aria-label="List View"]').first().click();
    await pg.waitForTimeout(1500);
    for (const i of [0, 1]) {
      const ex = pg.locator('.block-editor-list-view-leaf .block-editor-list-view__expander').nth(i);
      if (await ex.count()) { await ex.click({ force: true }); await pg.waitForTimeout(800); }
    }
    const row = pg.locator('.block-editor-list-view-leaf a.block-editor-list-view-block-select-button').nth(3);
    if (await row.count()) { await row.click(); await pg.waitForTimeout(1000); }
    await pg.mouse.move(1200, 880);
    await pg.waitForTimeout(500);
    await pg.screenshot({ path: 'docs/21-list-view.png' });
  } finally { await b.close(); }
});

await step('22-pattern-inserter', async () => {
  const { b, pg } = await openEditor(edit(ids.About));
  try {
    await pg.locator('button[aria-label="Block Inserter"], button[aria-label="Toggle block inserter"]').first().click();
    await pg.waitForTimeout(1500);
    await pg.getByRole('tab', { name: 'Patterns' }).first().click();
    await pg.waitForTimeout(1500);
    await pg.getByText('Pato: menus', { exact: true }).first().click();
    await pg.waitForTimeout(4000);
    for (const fr of pg.frames()) {
      await fr.evaluate(async () => {
        document.querySelectorAll('img').forEach(i => { i.loading = 'eager'; });
        await Promise.all([...document.images].map(i => i.decode().catch(() => {})));
      }).catch(() => {});
    }
    await pg.waitForTimeout(1500);
    await pg.screenshot({ path: 'docs/22-pattern-inserter.png' });
  } finally { await b.close(); }
});

