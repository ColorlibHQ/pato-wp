// Front-end documentation shots from the published demo — no sandbox needed.
import { browser, eager, watchdog } from './docs-lib.mjs';
watchdog(110000);
const D = 'https://colorlibhub.com/pato';
const { b, pg } = await browser({ width: 1280, height: 860 });

for (const path of ['/reservation/', '/contact/']) {
  const r = await pg.goto(D + path, { waitUntil: 'domcontentloaded' });
  const form = await pg.$('#pato-reservation form, form.pato-reservation, .pato-reservation form');
  console.log(path, r.status(), 'form:', !!form);
  if (form) {
    await eager(pg);
    await form.scrollIntoViewIfNeeded();
    await pg.waitForTimeout(500);
    await form.screenshot({ path: 'docs/09-reservation-form.png' });
    break;
  }
}

const r = await pg.goto(D + '/menu/', { waitUntil: 'domcontentloaded' });
console.log('/menu/', r.status(), 'dishes:', await pg.locator('.pato-dish').count());
await eager(pg);
const centre = () => pg.evaluate(() => document.querySelector('.pato-dish')?.scrollIntoView({ block: 'center' }));
await centre(); await pg.waitForTimeout(700);
await pg.screenshot({ path: 'docs/10a-light.png' });
const toggle = pg.locator('.pato-scheme-toggle, [class*="scheme-toggle"] button').first();
console.log('toggle:', await toggle.count());
if (await toggle.count()) {
  await toggle.click();
  await pg.waitForTimeout(1200);
  await centre(); await pg.waitForTimeout(600);
  await pg.screenshot({ path: 'docs/10b-dark.png' });
  console.log('scheme:', await pg.evaluate(() => document.documentElement.className));
}
await b.close();
