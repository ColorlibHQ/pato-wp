import { chromium } from 'playwright';

// Isolated Playground by default: local-wp is shared with other sessions.
export const BASE = process.env.WPBASE || 'http://127.0.0.1:9471';
const USER = process.env.WPUSER || 'admin';
const PASS = process.env.WPPASS || 'password';

/** Kill the process if a run hangs — a stuck editor must never stall the session. */
export function watchdog(ms = 100000) {
  const t = setTimeout(() => { console.error('WATCHDOG: exceeded ' + ms + 'ms'); process.exit(2); }, ms);
  t.unref();
}

export async function browser({ width = 1440, height = 900, scale = 2 } = {}) {
  const b = await chromium.launch();
  const c = await b.newContext({ viewport: { width, height }, deviceScaleFactor: scale });
  const pg = await c.newPage();
  pg.setDefaultTimeout(20000);
  return { b, c, pg };
}

export async function admin(opts = {}) {
  const r = await browser(opts);
  await r.pg.goto(BASE + '/wp-admin/', { waitUntil: 'domcontentloaded' });
  if (await r.pg.$('#user_login')) {
    await r.pg.fill('#user_login', USER);
    await r.pg.fill('#user_pass', PASS);
    await Promise.all([r.pg.waitForNavigation({ waitUntil: 'domcontentloaded' }), r.pg.click('#wp-submit')]);
  }
  return r;
}

/** Strip admin chrome that is noise in a documentation shot. */
export async function declutter(pg, { keepMenu = false } = {}) {
  await pg.evaluate((keepMenu) => {
    document.querySelectorAll('.notice, .update-nag, #screen-meta, #screen-meta-links, #wpfooter, #wpadminbar').forEach(e => e.remove());
    if (!keepMenu) {
      document.querySelectorAll('#adminmenuback, #adminmenuwrap').forEach(e => e.remove());
      const w = document.querySelector('#wpcontent');
      if (w) { w.style.marginLeft = '0'; w.style.paddingLeft = '20px'; }
    }
    document.documentElement.style.setProperty('margin-top', '0', 'important');
    document.querySelector('#wpbody-content')?.style.setProperty('padding-bottom', '0');
  }, keepMenu);
  await pg.waitForTimeout(250);
}

/** Make every image eager and decoded — full-page shots photograph lazy images as blank. */
export async function eager(pg) {
  await pg.evaluate(async () => {
    document.querySelectorAll('img').forEach(i => { i.loading = 'eager'; i.setAttribute('decoding', 'sync'); });
    document.querySelector('#wpadminbar')?.remove();
    document.documentElement.style.setProperty('margin-top', '0', 'important');
    window.scrollTo(0, document.body.scrollHeight); await new Promise(r => setTimeout(r, 900));
    window.scrollTo(0, 0); await new Promise(r => setTimeout(r, 400));
    await Promise.all([...document.images].map(i => i.decode().catch(() => {})));
    if (document.fonts) await document.fonts.ready;
  });
  await pg.waitForTimeout(500);
}

/** Open the block editor or Site Editor and wait until its canvas has painted. */
export async function openEditor(url, opts = {}) {
  const r = await admin(opts);
  const { pg } = r;
  await pg.goto(url, { waitUntil: 'domcontentloaded' });
  await pg.waitForSelector('iframe[name="editor-canvas"]', { timeout: 45000 }).catch(() => {});
  // Close only real modals (welcome guide) — a bare "Close" selector also matches the sidebar.
  for (let i = 0; i < 3; i++) {
    await pg.waitForTimeout(900);
    const close = await pg.$('.components-modal__frame button[aria-label="Close"]');
    if (!close) break;
    await close.click().catch(() => {});
  }
  const f = pg.frames().find(fr => fr.name() === 'editor-canvas');
  if (f) {
    await f.waitForSelector('.wp-block', { timeout: 30000 }).catch(() => {});
    await f.evaluate(async () => {
      document.querySelectorAll('img').forEach(i => { i.loading = 'eager'; });
      await Promise.all([...document.images].map(i => i.decode().catch(() => {})));
    }).catch(() => {});
  }
  await pg.evaluate(() => {
    document.querySelector('#wpadminbar')?.remove();
    document.documentElement.style.setProperty('margin-top', '0', 'important');
    document.body.classList.remove('admin-bar');
  });
  await pg.waitForTimeout(1500);
  return { ...r, f };
}

/** Thumbnail an image for visual review. */
export async function preview() {}
