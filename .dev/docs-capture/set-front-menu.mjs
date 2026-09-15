// Disposable Playground only: open the site on the Menu page so the Styles preview shows prices.
import { admin, BASE, watchdog } from './docs-lib.mjs';
watchdog(90000);
const { b, pg } = await admin();
await pg.goto(BASE + '/wp-admin/options-reading.php', { waitUntil: 'domcontentloaded' });
await pg.check('input[name="show_on_front"][value="page"]');
await pg.selectOption('#page_on_front', { label: 'Menu' });
await Promise.all([pg.waitForNavigation({ waitUntil: 'domcontentloaded' }), pg.click('#submit')]);
console.log('front page now:', await pg.$eval('#page_on_front option:checked', o => o.textContent.trim()));
await b.close();
