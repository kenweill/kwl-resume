# 📋 Changelog — KWL Resume

All notable changes to this project are documented here.

---

## [1.1.2] — 2026-05-13

### 🚀 New
- Experience entries are now sorted automatically on every save — no manual reordering needed.
  - Active jobs (date contains "Present") always appear first, sorted by start date newest first
  - Ended jobs follow, sorted by end date newest to oldest, then start date as a tiebreaker
  - Supports en-dash (`–`), em-dash (`—`), and hyphen as date separators
- Drag-to-reorder handle removed from the Experience tab since ordering is now automatic

### 📁 Files Changed
`inc/template-functions.php` `inc/admin-settings.php` `functions.php` `style.css` `CHANGELOG.md` `README.md`

---

## [1.1.1] — 2026-05-08

### 🔧 Fixed
- Backslashes multiplying in text on every save — apostrophes in bullet points (e.g. `annotators' outputs`, `Carter's`) were being double-escaped each time the form was saved, producing `\'` then `\\\'` and so on. Fixed by running `wp_unslash()` on `$_POST` data before sanitizing, as required for all WordPress form handlers.
- Automatically cleans up already-corrupted data on first load after updating — strips all accumulated backslash layers from existing saved content so no manual re-entry is needed.

### 📁 Files Changed
`functions.php` `inc/admin-settings.php` `style.css` `changelog.txt` `README.md`

---

## [1.1.0] — 2026-05-04

### 🚀 New
- Added Backup & Restore tab (💾) to the admin settings page:
  - **Export** — downloads a `.json` file containing all resume content (profile, contact, experience, skills, education, certifications, projects, custom sections, section settings) and all Customizer settings (color scheme, font pair, sidebar position/width, animations, print button). Always exports a complete snapshot — includes sections still on defaults, not just ones explicitly saved.
  - **Import** — upload a previously exported `.json` file to restore all data. Works across domains and WordPress installs.
  - Friendly error messages for missing file, upload errors, oversized files, and invalid format.
  - Built-in migration guide with step-by-step instructions.

### 📁 Files Changed
`inc/backup-restore.php` *(new file)* `inc/admin-settings.php` `functions.php` `assets/css/admin.css` `style.css` `changelog.txt` `README.md`

---

## [1.0.3] — 2026-05-03

### ✨ Improved
- Renamed Debug tab to System Info — better reflects its purpose as a troubleshooting and support tool rather than a dev tool.
- Added environment info to System Info tab: theme version, WordPress version, PHP version, site URL, multisite status, WP debug mode, and active plugin count — standard information needed when reporting issues.
- Improved System Info tab layout with clearer section headings, common fixes reference, and better instructions for including the data in support reports.

### 📁 Files Changed
`inc/admin-settings.php` `style.css` `functions.php`

---

## [1.0.2] — 2026-05-03

### 🔧 Fixed
- Custom sections not appearing on the resume — rendering logic was too strict in checking the enabled state, causing sections to be silently skipped even when enabled.
- Custom sections not appearing in Section Settings tab — only built-in sections were listed, making it impossible to toggle custom sections from that tab.
- Enabled/disabled state not saving correctly for custom sections — checkbox used an indexed key that browsers don't submit when unchecked, causing PHP to always default to disabled. Fixed using a hidden field + checkbox pattern.

### ✨ Improved
- Moved custom sections to render after Experience and before Projects — better visual flow since custom sections share the same layout as experience entries.
- Fixed section label hierarchy — section labels (Professional Experience, etc.) are now larger than their entries (job titles, etc.), restoring correct visual hierarchy.
- Added Debug tab in admin settings for troubleshooting custom section data.

### 📁 Files Changed
`front-page.php` `inc/admin-settings.php` `assets/js/admin.js` `assets/css/resume.css`

---

## [1.0.1] — 2026-05-02

### 🔧 Fixed
- Mobile layout not stacking to single column — dynamic CSS was setting `grid-template-columns` unconditionally, overriding the mobile media query in `resume.css`. Now wrapped in `@media (min-width: 681px)` so the two-column layout only applies on tablet and wider screens.
- Color scheme switcher having no visible effect — individual color override settings were always taking precedence over the selected preset scheme. Now uses `get_theme_mods()` so overrides only apply when explicitly saved by the user.

### ✨ Improved
- Expanded mobile styles with three proper breakpoints:
  - `max-width: 860px` — narrow tablet adjustments
  - `max-width: 680px` — full single-column stack with scaled fonts, padding, avatar, skill tags, experience, projects, footer
  - `max-width: 360px` — additional tweaks for very small screens

### 📁 Files Changed
`functions.php` `assets/css/resume.css`

---

## [1.0.0] — 2026-05-02

### 🚀 New
- 8 preset color schemes: Growth & Trust (default), Midnight, Crimson, Forest, Slate, Amber, Rose, Indigo
- 6 Google Font pairings selectable from the Customizer
- Individual color overrides for advanced customization
- Sidebar position toggle (left / right) with adjustable width
- Section visibility toggles — show/hide without losing data
- Page load animation (togglable)
- Print / Save as PDF floating button (togglable)
- Tabbed admin settings page (**Appearance → Resume Content**):
  - **Profile** — name, title, summary, photo, initials
  - **Contact** — location, email, LinkedIn, GitHub, website, unlimited custom links
  - **Experience** — sortable job entries with bullet points
  - **Skills** — comma-separated tags with live preview
  - **Education** — sortable institution/degree entries
  - **Certifications** — name, issuer, date
  - **Projects** — generic project entries with type field (open source, client work, personal, etc.)
  - **Custom Sections** — create unlimited custom sections with any title and entry structure
  - **Section Settings** — rename section headings, enable/disable per section
- Drag-and-drop reordering for Experience, Education, Certifications, and Projects entries
- Responsive layout — stacks cleanly on mobile
- Print stylesheet for clean PDF output
- Translation ready (text domain: `kwl-resume`)
- Pre-populated with sample resume data (easily replaced)
