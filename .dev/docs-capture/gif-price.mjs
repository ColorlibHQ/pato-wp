// Editing a price GIF: click, select, type. Autosave is pushed out and nothing is saved.
import { openEditor, BASE, watchdog } from './docs-lib.mjs';
watchdog(300000);
const { b, pg, f } = await openEditor(BASE + '/wp-admin/post.php?post=5&action=edit', { width: 1100, height: 720, scale: 1 });
await pg.evaluate(() => wp.data.dispatch('core/editor').updateEditorSettings({ autosaveInterval: 100000 }));
// Close the settings sidebar so the menu has the width it has on the site.
const settingsOpen = await pg.$('.interface-complementary-area, .editor-sidebar');
if (settingsOpen) { await pg.locator('button[aria-label="Settings"]').first().click(); await pg.waitForTimeout(800); }
const price = f.locator('.pato-dish__price').nth(1);
await f.evaluate(() => document.querySelectorAll('.pato-dish')[1]?.scrollIntoView({ block: 'center' }));
await pg.waitForTimeout(900);
let k = 0;
const shot = async (ms = 250) => { await pg.waitForTimeout(ms); await pg.screenshot({ path: `docs/gif/price-${++k}.png` }); };
console.log('price before:', await price.textContent());
await pg.mouse.move(1090, 710); await shot(400);                 // 1: the menu as it is
await price.click(); await shot(500);                            // 2: block selected, toolbar shows
await pg.keyboard.press('ControlOrMeta+A'); await shot(300);      // 3: text selected
for (const ch of '$22') { await pg.keyboard.type(ch, { delay: 40 }); await shot(150); } // 4-6: typing
await pg.mouse.move(1090, 710); await shot(500);                 // 7: new price, leader re-flows
// 8: point at Save.
await pg.addStyleTag({ content: '.pato-ring{outline:3px solid #f0b849 !important;outline-offset:3px !important}' });
await pg.locator('.editor-post-publish-button, .editor-post-save-draft, button:has-text("Save")').first().evaluate(e => e.classList.add('pato-ring'));
await shot(300);
console.log('price after:', await price.textContent(), '| frames:', k);
// Leave without saving; a beforeunload prompt would block, so detach it first.
await pg.evaluate(() => { window.onbeforeunload = null; });
await b.close();
