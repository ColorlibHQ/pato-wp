import { openEditor, BASE, watchdog } from './docs-lib.mjs';
watchdog(420000);
async function step(name, fn) { try { await fn(); console.log('ok  ', name); } catch (e) { console.log('FAIL', name, e.message.split('\n')[0]); } }
async function dismissGuide(pg) {
  for (let i = 0; i < 3; i++) {
    await pg.waitForTimeout(1200);
    if (!(await pg.$('.components-guide, .components-modal__frame'))) return;
    await pg.keyboard.press('Escape');
  }
}
const nav = (pg, label) => pg.locator('.edit-site-sidebar-navigation-item, [class*="sidebar-navigation-item"], a, button').filter({ hasText: new RegExp('^\\s*' + label + '\\s*$') }).first();

await step('30-styles', async () => {
  const { b, pg } = await openEditor(BASE + '/wp-admin/site-editor.php');
  try {
    await nav(pg, 'Styles').click();
    await pg.waitForTimeout(3500);
    await pg.screenshot({ path: 'docs/30-styles.png' });
    console.log('   url', pg.url());
    const browse = pg.getByText('Browse styles', { exact: true }).first();
    if (await browse.count()) { await browse.click(); await pg.waitForTimeout(4000); }
    else console.log('   no "Browse styles" — variations may be inline');
    await pg.evaluate(() => document.fonts && document.fonts.ready);
    await pg.waitForTimeout(1500);
    await pg.screenshot({ path: 'docs/31-browse-styles.png' });
  } finally { await b.close(); }
});

await step('34-navigation', async () => {
  const { b, pg } = await openEditor(BASE + '/wp-admin/site-editor.php');
  try {
    await nav(pg, 'Navigation').click();
    await pg.waitForTimeout(3500);
    await pg.screenshot({ path: 'docs/34-navigation.png' });
    console.log('   url', pg.url());
  } finally { await b.close(); }
});

await step('35-edit-header', async () => {
  const { b, pg, f } = await openEditor(BASE + '/wp-admin/site-editor.php?postType=wp_template_part&postId=pato%2F%2Fheader&canvas=edit');
  try {
    await dismissGuide(pg);
    await pg.waitForTimeout(1500);
    console.log('   guide still open:', !!(await pg.$('.components-guide')));
    await pg.screenshot({ path: 'docs/35-edit-header.png' });
    console.log('   url', pg.url(), 'blocks', f ? await f.evaluate(() => document.querySelectorAll('.wp-block').length) : 'no canvas');
  } finally { await b.close(); }
});
