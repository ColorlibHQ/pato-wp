// Proves the wp_slash() fix on its own: repair disabled, fresh activation, then a UI starter import.
import { execFileSync } from 'node:child_process';
import { admin, BASE, watchdog } from './docs-lib.mjs';
watchdog(400000);

function validate(label) {
  try {
    const out = execFileSync('node', ['docs/validate-blocks.mjs'], { env: { ...process.env, WP_URL: BASE, WP_USER: 'admin', WP_PASS: 'password' }, encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] });
    console.log(`[${label}] PASS — ${out.trim()}`);
  } catch (e) {
    console.log(`[${label}] FAIL — ${(e.stderr || e.message).split('\n').slice(0, 8).join(' | ')}`);
  }
}

const { b, pg } = await admin();
await pg.goto(BASE + '/wp-admin/edit.php?post_type=page', { waitUntil: 'domcontentloaded' });
console.log('pages after activation:', JSON.stringify(await pg.$$eval('#the-list .row-title', a => a.map(x => x.textContent.trim()))));
const muActive = await pg.goto(BASE + '/wp-admin/plugins.php?plugin_status=mustuse', { waitUntil: 'domcontentloaded' }).then(() => pg.content()).then(h => h.includes('pato-no-repair'));
console.log('repair disabled by mu-plugin:', muActive);
validate('activation');

await pg.goto(BASE + '/wp-admin/themes.php?page=pato-starters', { waitUntil: 'domcontentloaded' });
await Promise.all([pg.waitForNavigation({ waitUntil: 'domcontentloaded', timeout: 60000 }), pg.locator('button, input[type="submit"]').filter({ hasText: 'Import this' }).first().click()]).catch(async () => {
  await pg.locator('input[type="submit"][value="Import this"]').first().click();
  await pg.waitForLoadState('domcontentloaded');
});
console.log('after import notice:', (await pg.$eval('.notice, .updated', e => e.textContent.trim()).catch(() => '(none)')).slice(0, 160));
await b.close();
validate('starter import');
