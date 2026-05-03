# KWL Resume

A fully customizable single-page resume/CV WordPress theme with 8 color schemes, live Customizer preview, a tabbed admin settings page, sortable repeater fields, and flexible optional sections.

**Repository:** [github.com/kenweill/kwl-resume](https://github.com/kenweill/kwl-resume)  
**Author:** [Ken Weill](https://github.com/kenweill)  
**License:** GPL-2.0+

---

## Features

- **8 Preset Color Schemes** — Growth & Trust (default), Midnight, Crimson, Forest, Slate, Amber, Rose, Indigo
- **Individual color overrides** — override any palette color via the Customizer
- **6 Font Pairings** — swap display + body fonts with one click
- **Sidebar position** — left or right, with adjustable width
- **Section visibility toggles** — show or hide any section without losing data
- **Custom admin settings page** — tabbed interface for all resume content
- **Sortable/reorderable entries** — drag-and-drop experience and certifications
- **Custom Sections** — add any section not covered by defaults (Awards, Languages, Publications, etc.)
- **Projects section** — generic, not limited to open source; each project has a Type field
- **Print / Save as PDF** button — fixed floating button, togglable
- **Page load animation** — togglable fade-in
- **Responsive** — stacks gracefully on mobile across all screen sizes
- **Print stylesheet** — clean output when printing or saving as PDF
- **Translation ready** — fully i18n with `kwl-resume` text domain

---

## Installation

### From GitHub

1. [Download the latest release](https://github.com/kenweill/kwl-resume/releases) as a `.zip` file
2. In your WordPress dashboard go to **Appearance → Themes → Add New → Upload Theme**
3. Upload the zip and click **Activate**

### From source

```bash
git clone https://github.com/kenweill/kwl-resume.git
```

Copy the `kwl-resume` folder to your WordPress `wp-content/themes/` directory, then activate via **Appearance → Themes**.

---

## Usage

### Resume Content

Go to **Appearance → Resume Content** to edit all resume data across these tabs:

| Tab | What you can edit |
|---|---|
| 👤 Profile | Name, title, summary, photo, initials, open-to-work toggle |
| 📬 Contact | Location, email, LinkedIn, GitHub, website, custom links |
| 💼 Experience | Job entries (drag to reorder), role, company, date, bullet points |
| 🛠 Skills | Comma-separated skill tags with live preview |
| 🎓 Education | Institution, degree, date range |
| 📜 Certifications | Name, issuer, date |
| 🗂 Projects | Name, type, description, URL — any kind of project |
| ➕ Custom Sections | Create entirely new sections with any title and entries |
| ⚙️ Section Settings | Enable/disable sections and rename their headings |
| 🔍 Debug | Troubleshoot custom section data |

### Appearance & Layout

Go to **Appearance → Customize → KWL Resume** to control:

- **Color Scheme** — choose a preset + optionally override individual colors
- **Typography** — choose a font pairing (all served from Google Fonts)
- **Layout** — sidebar position (left/right) and sidebar width
- **Section Visibility** — toggle sections on/off with live preview
- **Extras** — page load animation, print button, open-to-work message

---

## Color Schemes

| Scheme | Sidebar | Accent | Highlight |
|---|---|---|---|
| Growth & Trust *(default)* | `#0A1929` | `#0D9488` | `#EAB308` |
| Midnight | `#0D1B2A` | `#F59E0B` | `#FBBF24` |
| Crimson | `#1A0A0A` | `#DC2626` | `#FBBF24` |
| Forest | `#0A1A0D` | `#16A34A` | `#BEF264` |
| Slate | `#1E293B` | `#8B5CF6` | `#C4B5FD` |
| Amber | `#1C1207` | `#D97706` | `#FCD34D` |
| Rose | `#1A0812` | `#E11D48` | `#FDA4AF` |
| Indigo | `#0F0A2E` | `#06B6D4` | `#A5F3FC` |

---

## Font Pairings

| Pairing | Display Font | Body Font |
|---|---|---|
| Roboto Slab + Roboto *(default)* | Roboto Slab | Roboto |
| Playfair Display + Source Sans 3 | Playfair Display | Source Sans 3 |
| Merriweather + Open Sans | Merriweather | Open Sans |
| DM Serif Display + DM Sans | DM Serif Display | DM Sans |
| Libre Baskerville + Lato | Libre Baskerville | Lato |
| Josefin Slab + Josefin Sans | Josefin Slab | Josefin Sans |

---

## File Structure

```
kwl-resume/
├── style.css                  # Theme header & metadata
├── functions.php              # Bootstrap, enqueue, dynamic CSS
├── index.php                  # WordPress fallback template
├── front-page.php             # Main resume output template
├── screenshot.png             # Theme browser preview (1200×900)
├── README.md                  # This file
├── changelog.txt              # Version history
├── documentation.html         # Full usage documentation
├── .gitignore
├── inc/
│   ├── color-schemes.php      # 8 color schemes + 6 font pairs
│   ├── template-functions.php # Data getters & render helpers
│   ├── customizer.php         # WordPress Customizer panels
│   └── admin-settings.php     # Tabbed admin settings page
└── assets/
    ├── css/
    │   ├── resume.css         # Front-end styles
    │   └── admin.css          # Admin page styles
    └── js/
        └── admin.js           # Repeater, sortable, skills preview
```

---

## Defaults

The theme ships pre-populated with Ken Weill's original resume data. Simply go to **Appearance → Resume Content** and replace the content with your own.

---

## Changelog

### 1.0.3
- Renamed Debug tab to System Info
- Added environment info: theme version, WordPress version, PHP version, site URL, multisite, WP debug mode, active plugin count
- Improved layout and instructions in System Info tab

### 1.0.2
- Fixed custom sections not appearing on the resume
- Fixed custom sections not showing in Section Settings tab
- Fixed enabled/disabled state not saving correctly for custom sections
- Moved custom sections to appear after Experience and before Projects
- Fixed section label hierarchy — labels are now larger than their entries
- Added Debug tab in admin for troubleshooting

### 1.0.1
- Fixed mobile layout not stacking to single column
- Fixed color scheme switcher having no visible effect
- Improved mobile styles with proper breakpoints for tablet, mobile, and small screens

### 1.0.0
- Initial release

See [changelog.txt](changelog.txt) for full details.

---

## License

[GPL-2.0+](https://www.gnu.org/licenses/gpl-2.0.html)

---

## Related Projects

- [kwl-coupon-wp](https://github.com/kenweill/kwl-coupon-wp) — WordPress theme for coupon/deals sites
- [kwl-maintenance-mode](https://github.com/kenweill/kwl-maintenance-mode) — Customizable maintenance mode plugin
- [mikhmon-ce](https://github.com/kenweill/mikhmon-ce) — MikroTik hotspot manager for PHP 8.x
