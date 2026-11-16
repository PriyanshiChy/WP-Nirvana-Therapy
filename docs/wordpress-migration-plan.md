# WordPress migration plan: Nirvana Therapy theme

Goal: move all content out of `assets/content.json` into WordPress so the client can edit it, and let them **reorder** the landing page sections in the block editor, without breaking the design.

Decisions:

- **Classic PHP theme + Secure Custom Fields (SCF).** SCF is the free WordPress.org fork of ACF. It includes repeaters, options pages and ACF Blocks with no licence cost.
- **Every landing page section becomes an SCF block.** The existing section markup moves almost unchanged into each block's `render.php`.
- **Icons are dropdowns** of the SVGs that ship with the theme. There are no SVG uploads.
- **All output is escaped.**
- **Styling is fixed in two places:** the editor preview, and normal page content.
- **`content.json` is retired** once its content has been seeded into WordPress.

---

## 0. LocalWP environment

| | |
|---|---|
| Site | `~/Local Sites/nirvana-therapy`, served at `http://nirvana-therapy.local` |
| Theme | symlinked: `app/public/wp-content/themes/nirvana-therapy` → this repo (done) |
| Stack | PHP 8.2, MySQL 8.4, nginx; Mailpit at `localhost:10000` |

One-time setup:

1. Activate **Nirvana Therapy Painted**. This runs `nirvana_create_core_pages()`, which creates the Landing and Terms pages.
2. Install **Secure Custom Fields** and an SEO plugin (Yoast or Rank Math).
3. Turn on debugging in `app/public/wp-config.php`:
   ```php
   define( 'WP_DEBUG', true );
   define( 'WP_DEBUG_LOG', true );        // → wp-content/debug.log
   define( 'WP_DEBUG_DISPLAY', false );
   define( 'WP_ENVIRONMENT_TYPE', 'local' );
   ```
4. Create `acf-json/` in the theme. SCF then saves every field group there as JSON, so the definitions are versioned in git and deploy with the theme.

Daily loop:

- Start the site in Local, run `npm run dev` here, then refresh `nirvana-therapy.local`.
- Run WP-CLI from Local's **Open Site Shell** (right-click the site).
- Run `wp db export ~/Desktop/nirvana-before-<step>.sql` before every step below. Git versions the code but not the content in the database.

---

## 1. Global content → Site Settings and menus

| From `content.json` | To |
|---|---|
| `navbar.links`, footer "Explore" and "Legal" | `register_nav_menus()` (`primary`, `footer-explore`, `footer-legal`) + `wp_nav_menu()` with a custom walker that keeps the current classes |
| `logo.svg` | Stays inline in the theme. Its markup is styled by CSS, so `custom-logo` would lose that. |
| Phone, email, WhatsApp, address + map link, socials (repeater), booking form URL, reviews URL, copyright | SCF **options page** "Site Settings" (`acf_add_options_page()`) |
| `head` title, description and keywords | Removed. The `title-tag` support plus the SEO plugin replace them. Also delete `nirvana_get_head_meta()`. |

Menu items keep anchor links such as `/#faq`. The anchor IDs are fixed in the blocks (see section 2), so these links keep working after the client reorders sections.

## 2. Landing page → reorderable SCF blocks

### Structure

```
blocks/
  hero/            block.json  render.php
  about/
  founder/
  testimonials/
  why-choose-us/
  expertise/
  how-it-works/
  faq/
inc/blocks.php     registration, allowed blocks, default template, editor assets
```

Each `block.json`:

```json
{
  "apiVersion": 3,
  "name": "nirvana/faq",
  "title": "FAQ",
  "category": "nirvana",
  "icon": "editor-help",
  "acf": { "mode": "preview", "renderTemplate": "render.php" },
  "supports": {
    "multiple": false,
    "html": false,
    "align": false,
    "customClassName": false,
    "reusable": false
  }
}
```

The JSON settings and the PHP helpers in `inc/blocks.php` keep the client from breaking the design while still letting them reorder:

- **`"multiple": false`**: each section can appear only once.
- **`allowed_block_types_all`**: the landing page offers only `nirvana/*` blocks. There are no stray paragraphs or columns in between.
- **Default template**: a new landing page opens with all eight blocks in the current order, so it never starts empty.
- **Reordering** uses the editor's up/down arrows or drag handles. A section is removed with "Delete" and comes back from the inserter.
- **Fixed anchor IDs**: `render.php` hard-codes each section's `id` (`hero`, `about-us`, `meet-the-founder`, `testimonials`, `choose-us`, `area-of-expertise`, `how-it-works`, `faq`). The CSS in `entry.css` (`#hero`, `#faq h2 em`…) and the nav anchors keep working. The client can't rename them.

`page-landing.php` shrinks to `get_header()`, then `<main id="main">`, `the_content()`, `</main>`, then `get_footer()`.

### Fields per block

| Block | Fields |
|---|---|
| Hero | title, subtitle, background image (optional; falls back to `bg/hero.webp`) |
| About | title, body (WYSIWYG, basic toolbar) |
| Meet the Founder | title, name, role, qualification, bio (WYSIWYG), photo, card text, **stats** repeater: number, suffix, label |
| Testimonials | title, **testimonials** repeater: quote |
| Why Choose Us | title, **items** repeater: icon (dropdown), title, body |
| Areas of Expertise | title, **items** repeater: icon (dropdown), title, body; CTA label |
| How It Works | title, **modes** repeater: icon (dropdown), title, text; opening hours; steps heading; **steps** repeater: icon (dropdown), title, text; closing line |
| FAQ | heading, subheading, **items** repeater: question, answer; closing line |

Each stat is three fields (number, suffix, label) instead of the current `"title": "1000+"`. That removes the `preg_match` in the template.

The FAQ block also prints `FAQPage` JSON-LD from the same repeater, built with `wp_json_encode()`.

The buttons (Book a session, Contact us, Read reviews) take their URLs from **Site Settings** and aren't repeated in each block.

### Buttons and icons helpers

- **`nirvana_button( array $args )`** replaces `components/book-session.php`, `contact-us.php` and `link-button.php`. It takes `variant` (`primary`, `outlined`, `glow`, `underlined`), `label`, `href`, `target` and `icon`. It escapes every argument.
- **`nirvana_icon( string $slug )`** wraps the existing `nirvana_svg()` cache.

### Icons as dropdowns

- Every icon field is an SCF **Select** field (stylised UI, searchable).
- Its choices are filled at load time by an `acf/load_field/name=icon` filter that scans `assets/images/areas-of-expertise/*.svg`, `assets/images/icons/*.svg` and `assets/images/therapy/*.svg`. The value is the relative path; the label is the filename in words, e.g. `anxiety-overthinking.svg` → "Anxiety / Overthinking".
- A new icon becomes available by dropping an SVG into the folder. No code change is needed.
- **Safety:** on output, `nirvana_icon()` accepts the value only if it is in that scanned list. A tampered value can't read arbitrary files, and nothing is ever uploaded through the Media Library. That matters because SVGs can contain scripts.
- Rename `friendship&peer.svg` → `friendship-peer.svg`. A `&` in a path value is fragile.

## 3. Escaping

Today the landing and terms templates print raw values with `echo`. That was safe while the theme owned `content.json`, but not once the content is editable in wp-admin.

| Value | Escape with |
|---|---|
| Plain text (titles, labels, stats, questions) | `esc_html()` |
| HTML attributes (`aria-label`, `data-target`, `alt`) | `esc_attr()` |
| URLs (`href`, `src`) | `esc_url()` |
| Rich text (bios, answers, body copy) | `wp_kses( $value, nirvana_allowed_html() )` |
| Images | `wp_get_attachment_image()`, which handles escaping, `srcset` and lazy-loading |
| JSON-LD | `wp_json_encode()` |
| Stat number | `absint()` |

`nirvana_allowed_html()` allows only what the content uses today: `a[href|target|rel]`, `br`, `em`, `i`, `strong`, `u`, `p`, `ul`, `ol`, `li`, and the custom **`semibold`** tag.

**Keeping `<semibold>`.** It is used 15 times and keeps its existing CSS rule. WordPress and the editor drop unknown tags in three places, so each one must allow it:

1. **Output:** it is in `nirvana_allowed_html()`.
2. **Saving:** add it to the `wp_kses_allowed_html` filter for the `post` context. Without this, WordPress strips it from block data whenever a user without the `unfiltered_html` capability saves, such as an Editor-role client or anyone on multisite.
3. **WYSIWYG fields:** TinyMCE removes tags it doesn't know when the field loads. Register it through `tiny_mce_before_init` (`custom_elements: '~semibold'`, plus `extended_valid_elements`), and add a "Semibold" toolbar button so the client can apply it. `~` makes it an inline element.

Test after step 3: wrap text in `<semibold>` in a block, save as an Editor-role user, reload, and confirm the tag is still there.

Any `target="_blank"` link gets `rel="noopener noreferrer"`.

## 4. Fixing styling

**A. Block editor preview.** Without this, the client edits unstyled blocks.

- In `inc/blocks.php`, enqueue `style.css` for the editor with `enqueue_block_assets`, scoped by `is_admin()` checks as needed.
- The fade-in hides content under `.js` until JavaScript runs. Inside the editor, add a rule that always shows `section` (`.editor-styles-wrapper section { opacity: 1; transform: none; }`).
- Interactive pieces (carousel, FAQ accordion, flip cards, count-up) don't run in the preview. That's acceptable: the preview shows the static layout. Optionally hook `acf.addAction('render_block_preview/type=nirvana/faq', …)` to initialise them.
- Tailwind already scans `./**/*.php` (`entry.css:14`), so classes in `blocks/*/render.php` are picked up. No configuration change is needed.

**B. Normal page content.** This covers terms and any future pages.

- Tailwind's base styles strip heading, list and paragraph spacing, so `the_content()` would render as flat text.
- Add `@plugin "@tailwindcss/typography";` to `entry.css` and wrap the content in `prose` with brand colours and fonts set through the typography variables. Or write a small `.entry-content` stylesheet.
- `page.php` keeps the existing terms look: `tnc.webp` background, serif title. The body becomes `<div class="prose …"><?php the_content(); ?></div>`.
- Delete `page-terms.php`. The client writes the terms in the normal editor.

**C. Content-driven layout risks** to check once the client can type anything:

- Long titles or long stat numbers (e.g. "1000+" in the 3-column stats grid on mobile).
- Fixed heights such as the testimonial card's `h-96` and the step descriptions' `h-12`.
- Fewer or more items than today in the grids (the borders rely on `nth-child`, e.g. Areas of Expertise's 3-column grid).

## 5. Moving content out of `content.json`

1. **Seed script `tools/seed-content.php`**, run once with `wp eval-file tools/seed-content.php` from Local's site shell:
   - Fills **Site Settings** from `components.*` with `update_field( …, 'option' )`.
   - Creates the menus from `navbar.links` and the footer link lists.
   - Builds the landing page's `post_content` as block markup from `index.*`. Each block gets its `data` attributes, with repeaters in SCF's flattened `items_0_title` form and field keys taken from `acf-json/`.
   - Converts `terms-and-conditions.*` into paragraph blocks in the Terms page content.
   - Cleans the content on the way in: stats split into number, suffix and label, icon paths matched to the dropdown values. `<semibold>` is copied unchanged.
   - Is **idempotent**: it does nothing if the content is already seeded, unless run with `--force`.
2. **Verify** on `nirvana-therapy.local`: every section matches the current site visually, and nav anchors, buttons and WhatsApp links all work.
3. **Cut over:** change the header, footer and templates to read from SCF and menus.
4. **Delete** `assets/content.json` and the content functions in `inc/json.php` (`nirvana_get_content`, `nirvana_get_page_content`, `nirvana_get_component_content`). Keep `nirvana_asset_uri()` and `nirvana_svg()` (move them to `inc/assets.php`).
5. Change `nirvana_create_core_pages()` so a fresh install also gets seeded content, not the placeholder "Replace this with your real landing content".

## 6. Deploying

1. `npm run build`.
2. Zip the theme without `node_modules/`, `tools/`, `docs/`, `entry.css` or `package*.json`. Upload it to the live site.
3. Field groups arrive with the theme through `acf-json/`.
4. For content, either run the seed script on the live site, or push the Local database with a migration plugin (All-in-One WP Migration or WP Migrate). Local Connect also works if the host is Flywheel or WP Engine.

---

## Build order

| # | Step | Done when |
|---|---|---|
| 0 | LocalWP setup | Theme active, SCF installed, `acf-json/` present |
| 1 | Site Settings and menus | Header and footer read from wp-admin |
| 2 | `nirvana_button()`, `nirvana_icon()`, icon dropdown, `nirvana_allowed_html()` | Old `components/*.php` deleted |
| 3 | Blocks, one per section, escaped | Landing page builds from blocks and can be reordered in the editor. **Done.** The landing content import moved here from step 5, so the page could be verified. |
| 4 | Editor styles and `prose` | Editor preview matches the front end; terms page styled. **Done.** See "Step 4 notes" below. The terms import moved here from step 5. |
| 5 | Stop reading `content.json` (kept in `tools/` for the seed), seed on fresh install | No reads from `content.json` remain. **Done.** See "Step 5 notes" below. |
| 6 | Deploy | Live site matches Local. **Zip ready** (`npm run zip`). Uploading it and moving the content are manual; see "Step 6: deploy checklist". |
| 7 | Therapists page, profiles and Book button actions | Listing matches the Figma frame; each card opens its profile; Book buttons run a link or a script. **Done.** See "7. Therapists and booking actions" below. |

Take a `wp db export` snapshot before each step, and make one git commit per step.

### Step 4 notes (what changed from the plan)

- **Editor canvas CSS** (`nirvana_editor_canvas_assets()` in `inc/blocks.php`). `enqueue_block_assets` fires three times: on the front end, in the wp-admin page (inside `admin_enqueue_scripts`), and while WordPress collects the iframe's assets. The theme CSS loads only in the iframe pass. In that pass, WordPress also adds `common.css`, `forms.css` and `wp-reset-editor-styles` for classic themes. They're unlayered, so they beat Tailwind's `@layer` rules (they hid the expertise grid and changed fonts and margins). The callback marks them as done, which removes them from the iframe only. Verified by diffing computed styles of all 559 section elements, editor against front end: 1 difference remains (the hero's `26vh` margin, which follows the canvas height).
- **Editor overrides** in `entry.css` under `.editor-styles-wrapper`: full-width landing sections (classic layout caps `.wp-block` at 840px), no fade-in hiding, editable text selectable despite `select-none`.
- **Page content**: kept the `.entry-content` stylesheet instead of `@tailwindcss/typography`. The same rules style the editor canvas for non-landing pages. Paragraph gap is `1lh`, matching the old `<br />`.
- **Terms page**: `page-terms.php` deleted. The terms are paragraph blocks; "Last Updated…" is a new optional **Subtitle** field (`acf-json/group_nirvana_page.json`, default-template pages); the closing line is a centered paragraph with font size "Small". The seed script imports it (`tools/seed-content.php`).
- **Semibold in the block editor**: `assets/js/editor-semibold.js` registers a `<semibold>` rich-text format, so paragraphs get the same button as the WYSIWYG fields.
- **Head meta**: page.php pages no longer read `content.json` head data (the terms entry had the landing page's title copied in). Empty description tags aren't printed.

### Layout risks found (4C), not fixed: design decisions

Tested by injecting long content at 390px and 1440px:

- Stat labels longer than about two words overflow their `h-10` box (both widths). A five-digit stat number ("10000+") overflows its column at desktop size (`xl:text-7xl`).
- Testimonials longer than about 10 lines are silently cut off by `line-clamp-10` inside `h-96`.
- How It Works step text longer than about 2 lines overflows `h-12` on mobile.
- Areas of Expertise with a count that isn't a multiple of 3: `nth-last-child(-n+3)` removes the bottom border from the last 3 items, which isn't the last row.
- A long hero title is fine (no horizontal scroll).

### Step 5 notes

- `assets/content.json` moved to `tools/content.json`. Only the seed script reads it, and it isn't deployed. **Kept on purpose** (decision, 2026-11-16): the theme never reads it; it stays as the original-content record and the seed script's source. Checked against the Local database: every piece of its text is in WordPress except the head keywords and the terms head title/description, dropped on purpose (see Head meta below). The five footer section headings (EXPLORE, LEGAL, ADDRESS, FIND US, CONTACT INFORMATION), which were typed into `parts/footer.php`, are now Site Settings → Footer fields, saved on Local. `inc/json.php` became `inc/assets.php` with just `nirvana_asset_uri()` and `nirvana_svg()`.
- **Fresh install** (`nirvana_create_core_pages()`): the landing page is created empty with the Landing template, so the editor opens it with all eight sections. Terms gets one placeholder paragraph. To get the original content, run the seed script. The theme doesn't seed by itself, because the content source isn't shipped.
- **Head meta without an SEO plugin**: `content.json` no longer supplies titles or descriptions. The title is WordPress's own, with `|` as the separator. The seed sets the tagline to "Online Therapy & Mental Wellness in India", so the front page title is unchanged. The description is the page's **Excerpt** (now enabled for pages; the seed fills the landing page's), else the start of the page text, else the tagline. The keywords tag is gone. With an SEO plugin active, the theme prints only the creator and author tags.

### Content backup

`tools/export-content.php` copies everything edited in wp-admin into `tools/content.json` under a `wordpress` key: Site Settings and Booking Popup, every page (block markup with its fields), therapists (fields, languages, expertise, photo), posts, menus with their locations, the Languages/Expertise lists and the media library's URLs and details. The original keys stay untouched for `seed-content.php`. Field values are raw (image fields are media IDs), so they can be written back with `update_field()`. Image files aren't in the JSON; they stay in `wp-content/uploads`. Re-run it after content changes:

    wp eval-file wp-content/themes/nirvana-therapy/tools/export-content.php

Licensing was removed (2026-11-16): `inc/license.php`, `tools/license.php` and the commented-out require in `functions.php` are gone.

### Step 6: deploy checklist

1. `npm run zip` builds `style.css`, then `dist/nirvana-therapy.zip`, excluding `node_modules/`, `tools/`, `docs/`, `entry.css` and `package*.json`. The folder inside is `nirvana-therapy/`, the same slug as on Local, so theme settings and menu locations carry over in a database migration.
2. On the live site: install and activate **Secure Custom Fields** (and the SEO plugin) first, then upload the zip under Appearance → Themes → Add New → Upload, and activate it.
3. Content, one of:
   - **Migrate the Local database** (recommended): All-in-One WP Migration or WP Migrate. This brings the pages, Site Settings, menus, media, tagline and excerpts as they are on Local.
   - **Or seed it**: copy `tools/` to the server, then run `wp eval-file path/to/tools/seed-content.php` there (requires WP-CLI on the host). Delete `tools/` afterwards.
4. Check on the live site: every section, nav anchors, the Book / WhatsApp / Reviews buttons, the terms page, and the editor preview.

## 7. Therapists and booking actions

### Therapists

| What | Where |
|---|---|
| Therapist (photo, name, role, bio) | Post type `therapist` (wp-admin → Therapists), at `/therapists/<name>/`. Photo = featured image; name = title; role = SCF field; bio = editor content; card order = "Order" box. `inc/therapists.php`, `acf-json/group_nirvana_therapist.json` |
| Language tags | Taxonomy `therapist_language`. Tag colours alternate mint / peach (the only new colours, `--color-mint`, `--color-peach`) |
| Expertise line | Taxonomy `therapist_expertise`, joined with `・` (terms list alphabetically) |
| Listing page | A page using the "Therapists" template (`page-therapists.php`): Heading field (inline `<i>` for "*for you.*"), else the page title; the page content is the intro; then a card per therapist (`parts/therapist-card.php`). Created on theme activation. |
| Profile page | `single-therapist.php`, per the Figma "Therapist Profile" frame. Left panel: photo and language tags (the same featured image and tags as the card), name, role, then the Profile fields: Experience, Education, Days & timing, Mode of work, the line above the Book button (default "Book a session with <first name>!"), and the Book button. Right: the editor content: each Heading ("About me", "Approach"…) starts a section, divided by a line; paragraphs run on with no blank line (`.therapist-bio`). Top banner: each therapist's **Banner image** (Profile box), else Site Settings → Therapists → "Default profile banner", else `bg/tnc.webp`. The card's expertise line isn't on the profile (not in the design). Fields: `acf-json/group_nirvana_therapist_profile.json` (below the editor) and `group_nirvana_therapist.json` (Booking, sidebar). New token: `--shadow-panel`. |

Styling reuses the theme's colours (`text`, `primary`, `foreground`; the role's grey is `text` at 70%) and `nirvana_button()` (new `size: small`, 48px / 16px). New tokens: the two tag colours and `--shadow-card` (`0 0 18.3px -4px rgb(0 0 0 / .25)`). The Figma frame sets the expertise line in Inter; it uses Golos Text like the rest of the site.

Sample data on Local: `wp eval-file wp-content/themes/nirvana-therapy/tools/seed-therapists.php [photo-id]`.

### Book button actions (decision)

A Book button does one of two things, nothing else lives in the theme:

1. **Link**: opens the Booking URL (as before).
2. **Custom script**: a file in `assets/js/actions/` (e.g. `calendly.js` → "Script: Calendly" in the dropdown). It registers one handler with the registry in `assets/js/actions.js`:
   ```js
   nirvanaActions.register("calendly", (button, context) => { … });
   // context: { bookingUrl, therapistId, therapistName, profileUrl }
   ```

- **Where it's set:** Site Settings → "Booking action" (all Book buttons: header, hero, founder, footer) and each therapist's "Booking action" (default "Same as Site Settings"), with an optional per-therapist Booking URL.
- **Fallback:** the button stays a real link to the Booking URL (for a therapist: own URL, else the site's, else the profile). Script not loaded → the link works; handler throws → the link is followed.
- **Loading:** an action's script (plus the registry) loads only on pages showing a button that uses it.
- **Events:** each handled click fires a bubbling `nirvana:action` event (`{ name, context }`), e.g. for analytics.
- **Not chosen:** pasting JS into a wp-admin field (unversioned, one typo breaks every button, an injection route) and a popup built into the theme outside the action system (the step-wise popup below is just another script).

`inc/actions.php` holds the PHP side; `assets/js/actions/README.md` documents writing an action.

### Booking popup (step-wise)

The step-wise popup from the Figma "Booking" frames is the first custom script: `assets/js/actions/booking-popup.js` ("Script: Booking Popup"), selected in Site Settings → Booking action, so every Book button that follows Site Settings opens it.

- **Content:** wp-admin → Site Settings → **Booking Popup** (`acf-json/group_nirvana_booking_popup.json`). Steps in order, each with an ID, a question, optional text under it, a look (tiles with icons, or a list of buttons), its choices, and an optional bottom line + button.
- **Each choice, "When clicked":** go to the next step · go to a step (by ID, for branches, e.g. separate plan steps for Individual and Couple) · open a link · run a script from `assets/js/actions/`. A link left empty uses the Book button's own link (the therapist's, else Site Settings → Booking URL). A script gets the Book button's context plus `answers` (`{ "who": "Couple", … }`); if it fails, its link (or the booking link) opens.
- **Per therapist:** each therapist has a "Booking popup" box (below the editor) with the same fields, generated in PHP from `group_nirvana_booking_popup.json` (keys `field_nv_tbp_…`), so it always matches. Left empty, the therapist uses Site Settings → Booking Popup. Every Book button carries `context.popup` (`booking-popup` or `booking-popup-<therapist ID>`), and the footer prints one `<dialog>` per popup used on the page, so a listing page can open a different popup per card. On Local the 3/6 packages are hidden for Couple and the combo package for Individual.
- **Hiding choices:** "Hide when the visitor picked" (earlier answers, comma-separated, e.g. `Couple`) hides a choice. On Local the 3 and 6 session packages are hidden for couples.
- **Markup:** `parts/booking-popup.php`, printed in the footer only on pages with a button that opens it (`inc/booking-popup.php`). A `<dialog>`: Esc, the X and a click on the blurred area close it; the header stays visible above the blur; focus moves to each step's question.
- **Progress bar:** steps passed ÷ steps on the longest way through (0, ⅓, ⅔ for the design's three steps).
- **No steps saved:** Book buttons work as plain links.
- **Events:** each final choice fires a bubbling `nirvana:booking` event (`{ choice, answers, context }`).
- On Local: snapshot `before-booking-popup.sql`; the design's three steps were added with `tools/seed-booking-popup.php` (only runs when the popup has no steps); Site Settings → Booking action set to "Script: Booking Popup". The plan links are empty, so they open the Google Form for now.

### Step 7 notes
- On Local: DB snapshot `before-therapists.sql`; sample therapists seeded; rewrites flushed (`after_switch_theme` also flushes them on a fresh install).
- Verified: listing and profile at 1440px and 390px against the Figma screenshot; the landing page's Book buttons render as before with Action = Link; a test script ran with the therapist context instead of following the link; a throwing handler fell back to the link; a deleted script fell back to a plain link.
- Not done: the therapists page isn't in the header menu (add it in Appearance → Menus); the Therapists page intro isn't centred in the editor canvas (it is on the site).

