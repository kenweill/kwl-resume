# 📄 KWL Resume — WordPress Theme

> A fully customizable single-page resume/CV WordPress theme with 8 color schemes, live Customizer preview, a tabbed admin settings page, sortable repeater fields, and flexible optional sections.

![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-purple?logo=php)
![License](https://img.shields.io/badge/License-GPL--2.0%2B-green)
[![Documentation](https://img.shields.io/badge/docs-online-0D9488)](https://kenweill.github.io/kwl-resume/)

---

## ✨ Features

- 🎨 **8 Preset Color Schemes** — Growth & Trust (default), Midnight, Crimson, Forest, Slate, Amber, Rose, Indigo
- 🖌️ **Individual color overrides** — override any palette color via the Customizer
- 🔤 **6 Font Pairings** — swap display + body fonts with one click
- 📐 **Sidebar position** — left or right, with adjustable width
- 👁️ **Section visibility toggles** — show or hide any section without losing data
- 🛠️ **Custom admin settings page** — tabbed interface for all resume content
- 🔀 **Auto-sorted experience** — entries sort automatically by date on save (active jobs first, newest to oldest); drag-and-drop reordering for Education, Certifications, and Projects
- ➕ **Custom Sections** — add any section not covered by defaults (Awards, Languages, Publications, etc.)
- 💼 **Projects section** — generic, not limited to open source; each project has a Type field
- 🖨️ **Print / Save as PDF** button — fixed floating button, togglable
- 🌟 **Page load animation** — togglable fade-in
- 📱 **Fully responsive** — stacks gracefully on mobile across all screen sizes
- 🖨️ **Print stylesheet** — clean output when printing or saving as PDF
- 🌐 **Translation ready** — fully i18n with `kwl-resume` text domain

---

## 📦 Installation

### Option A — Upload ZIP (recommended)

1. Download the latest release ZIP from the [Releases](../../releases) page
2. In your WordPress admin go to **Appearance → Themes → Add New → Upload Theme**
3. Upload the ZIP file and click **Activate**

### Option B — Clone from GitHub

```bash
git clone https://github.com/kenweill/kwl-resume.git
```

Copy the `kwl-resume` folder to your WordPress `wp-content/themes/` directory, then activate via **Appearance → Themes**.

---

## ⚙️ Usage

### Resume Content

Go to **Appearance → Resume Content** to edit all resume data across these tabs:

| Tab | What you can edit |
|-----|-------------------|
| 👤 **Profile** | Name, title, summary, photo, initials, open-to-work toggle |
| 📬 **Contact** | Location, email, LinkedIn, GitHub, website, custom links |
| 💼 **Experience** | Job entries (drag to reorder), role, company, date, bullet points |
| 🛠️ **Skills** | Comma-separated skill tags with live preview |
| 🎓 **Education** | Institution, degree, date range |
| 📜 **Certifications** | Name, issuer, date |
| 🗂️ **Projects** | Name, type, description, URL — any kind of project |
| ➕ **Custom Sections** | Create entirely new sections with any title and entries |
| ⚙️ **Section Settings** | Enable/disable sections and rename their headings |
| 🔍 **System Info** | Troubleshoot custom section data and environment |
| 💾 **Backup & Restore** | Export all data to .json; restore on any WP install |

### Appearance & Layout

Go to **Appearance → Customize → KWL Resume** to control:

| Section | What you can customize |
|---------|------------------------|
| **Color Scheme** | Choose a preset + optionally override individual colors |
| **Typography** | Choose a font pairing (all served from Google Fonts) |
| **Layout** | Sidebar position (left/right) and sidebar width |
| **Section Visibility** | Toggle sections on/off with live preview |
| **Extras** | Page load animation, print button, open-to-work message |

---

## 🎨 Color Schemes

| Scheme | Sidebar | Accent | Highlight |
|--------|---------|--------|-----------|
| **Growth & Trust** *(default)* | `#0A1929` | `#0D9488` | `#EAB308` |
| **Midnight** | `#0D1B2A` | `#F59E0B` | `#FBBF24` |
| **Crimson** | `#1A0A0A` | `#DC2626` | `#FBBF24` |
| **Forest** | `#0A1A0D` | `#16A34A` | `#BEF264` |
| **Slate** | `#1E293B` | `#8B5CF6` | `#C4B5FD` |
| **Amber** | `#1C1207` | `#D97706` | `#FCD34D` |
| **Rose** | `#1A0812` | `#E11D48` | `#FDA4AF` |
| **Indigo** | `#0F0A2E` | `#06B6D4` | `#A5F3FC` |

---

## 🔤 Font Pairings

| Pairing | Display Font | Body Font |
|---------|-------------|-----------|
| **Roboto Slab + Roboto** *(default)* | Roboto Slab | Roboto |
| **Playfair Display + Source Sans 3** | Playfair Display | Source Sans 3 |
| **Merriweather + Open Sans** | Merriweather | Open Sans |
| **DM Serif Display + DM Sans** | DM Serif Display | DM Sans |
| **Libre Baskerville + Lato** | Libre Baskerville | Lato |
| **Josefin Slab + Josefin Sans** | Josefin Slab | Josefin Sans |

---

## 🗂️ File Structure

```
kwl-resume/
├── style.css                  # Theme header & metadata
├── functions.php              # Bootstrap, enqueue, dynamic CSS
├── index.php                  # WordPress fallback template
├── front-page.php             # Main resume output template
├── screenshot.png             # Theme browser preview (1200×900)
├── README.md                  # This file
├── CHANGELOG.md               # Version history
├── docs/
│   └── index.html             # Full usage documentation (GitHub Pages)
├── .gitignore
├── inc/
│   ├── color-schemes.php      # 8 color schemes + 6 font pairs
│   ├── template-functions.php # Data getters & render helpers
│   ├── customizer.php         # WordPress Customizer panels
│   ├── admin-settings.php     # Tabbed admin settings page
│   └── backup-restore.php     # Export/import backup feature
└── assets/
    ├── css/
    │   ├── resume.css         # Front-end styles
    │   └── admin.css          # Admin page styles
    └── js/
        └── admin.js           # Repeater, sortable, skills preview
```

---

## 🗒️ Defaults

The theme ships pre-populated with Ken Weill's original resume data. Simply go to **Appearance → Resume Content** and replace the content with your own.

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

1. Fork the repo
2. Create a feature branch (`git checkout -b feature/my-feature`)
3. Commit your changes (`git commit -m 'Add some feature'`)
4. Push to the branch (`git push origin feature/my-feature`)
5. Open a Pull Request

---

## 📄 License

Distributed under the **GPL-2.0+** license. See [`LICENSE`](LICENSE) for more information.

Anyone can use, modify, and distribute this theme freely — as long as they keep the same license.

---

## 📋 Changelog

See [CHANGELOG.md](CHANGELOG.md) for full version history.

---

## 📖 Documentation

Full usage documentation is available at **[kenweill.github.io/kwl-resume](https://kenweill.github.io/kwl-resume/)**.

---

> Built with ❤️ as a free, fully featured resume theme. No page builders, no premium add-ons — just a clean, customizable CV that looks great and prints even better.
