import { admin, declutter, BASE, watchdog } from './docs-lib.mjs';
watchdog(200000);
const { b, pg } = await admin();
async function step(name, fn) { try { await fn(); console.log('ok  ', name); } catch (e) { console.log('FAIL', name, e.message.split('\n')[0]); } }
const pad = () => pg.evaluate(() => { const w = document.querySelector('.wrap'); if (w) { w.style.padding = '18px 24px 28px'; w.style.margin = '0'; } });

await step('01-upload-theme', async () => {
  await pg.goto(BASE + '/wp-admin/theme-install.php?browse=upload', { waitUntil: 'domcontentloaded' });
  await pg.waitForTimeout(1500);
  await declutter(pg);
  await pg.evaluate(() => {
    const u = document.querySelector('.upload-theme'); if (u) u.style.display = 'block';
    document.querySelectorAll('.wp-filter, .theme-browser, .no-themes, .theme-install-overlay, .error, .notice').forEach(e => e.remove());
  });
  await pad(); await pg.waitForTimeout(300);
  await (await pg.$('.wrap')).screenshot({ path: 'docs/01-upload-theme.png' });
});

await step('02-pages-created', async () => {
  await pg.goto(BASE + '/wp-admin/edit.php?post_type=page', { waitUntil: 'domcontentloaded' });
  await pg.waitForTimeout(1000);
  await declutter(pg);
  await pg.evaluate(() => document.querySelectorAll('.tablenav.bottom, #screen-options-link-wrap, .search-box').forEach(e => e.remove()));
  await pad(); await pg.waitForTimeout(300);
  await (await pg.$('.wrap')).screenshot({ path: 'docs/02-pages-created.png' });
});

await step('03-appearance-menu', async () => {
  await pg.goto(BASE + '/wp-admin/themes.php', { waitUntil: 'domcontentloaded' });
  await pg.waitForTimeout(1000);
  await declutter(pg, { keepMenu: true });
  await pg.evaluate(() => {
    const li = document.querySelector('#menu-appearance');
    li.classList.add('wp-has-current-submenu', 'wp-menu-open'); li.classList.remove('wp-not-current-submenu');
    document.querySelector('#collapse-menu')?.remove();
    document.querySelectorAll('#adminmenu .awaiting-mod, #adminmenu .update-plugins').forEach(e => e.remove());
    // Highlight the item this section is about.
    const s = [...li.querySelectorAll('.wp-submenu a')].find(a => a.textContent.trim() === 'Starter sites');
    if (s) { s.style.outline = '2px solid #f0b849'; s.style.outlineOffset = '-2px'; s.style.color = '#fff'; }
  });
  await pg.waitForTimeout(300);
  await (await pg.$('#adminmenu')).screenshot({ path: 'docs/03-appearance-menu.png' });
});

await step('04-starter-sites', async () => {
  await pg.goto(BASE + '/wp-admin/themes.php?page=pato-starters', { waitUntil: 'domcontentloaded' });
  await pg.waitForTimeout(1200);
  await declutter(pg);
  await pad(); await pg.waitForTimeout(300);
  await (await pg.$('.wrap')).screenshot({ path: 'docs/04-starter-sites.png' });
});

await step('05-reading-settings', async () => {
  await pg.goto(BASE + '/wp-admin/options-reading.php', { waitUntil: 'domcontentloaded' });
  await pg.waitForTimeout(900);
  await declutter(pg);
  await pad(); await pg.waitForTimeout(300);
  const t = await pg.$('.form-table');
  await t.screenshot({ path: 'docs/05-reading-settings.png' });
});

await b.close();
