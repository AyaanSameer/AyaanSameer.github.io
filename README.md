# Ayaan Hydross Sameer — portfolio

**Live site: https://ayaansameer.github.io**

My portfolio site and the code behind its WordPress section.

- **`index.html`** — a one-page portfolio: Agricope POS (featured), internal systems at Noble House,
  three WordPress projects, earlier work, skills and contact. Plain HTML, CSS and JavaScript: no
  framework, no build step, no tracking. Light and dark themes, works on phones, keyboard friendly.
- **`wordpress/`** — a block theme, a plugin and a Gutenberg block, each with its own README. See
  [`wordpress/README.md`](wordpress/README.md).

```
index.html
css/styles.css           all styles; colours are tokens, dark mode is one block
js/main.js               theme switch, screenshot lightbox, code links (the page works without it)
assets/
  Ayaan_Hydross_Sameer_CV.pdf
  fonts/                 Manrope and Inter (SIL Open Font License)
  img/                   portrait, favicon, work/ screenshots (WebP)
wordpress/
  cardamom-cafe-theme/   block theme
  simple-enquiry-form/   plugin
  opening-hours-block/   block plugin, with tests
```

## Preview locally

Open `index.html` in a browser, or serve the folder:

```bash
python3 -m http.server 8000     # then open http://localhost:8000
```

## Publishing

This repository is named `AyaanSameer.github.io`, so GitHub Pages serves it at
https://ayaansameer.github.io (*Settings → Pages → Deploy from a branch → `main` / root*).
Every push to `main` updates the live site within a minute or two. The `.nojekyll` file tells
Pages to serve the files exactly as they are.

## Notes

- The Agricope POS screenshots show the system's demo business with generated sales data. The
  product's code is private and belongs to Agricope LLC.
- Cardamom Café, used by the WordPress projects, is a fictional business.

## Credits

Fonts: Manrope and Inter, SIL Open Font License 1.1. WordPress code is GPL-2.0-or-later, as
WordPress requires.
