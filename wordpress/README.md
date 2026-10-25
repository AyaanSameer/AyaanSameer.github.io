# WordPress projects

Three small, self-contained WordPress projects. Each folder drops straight into a WordPress
install — no build step, no Composer, no npm. They were built and tested together on
WordPress 7.1.3 / PHP 8.3 as one demo site for a fictional café.

| Project | Type | What it shows |
|---|---|---|
| [`cardamom-cafe-theme/`](cardamom-cafe-theme/) | Block theme | `theme.json` design system, templates, template parts and PHP patterns; fully editable in the Site Editor |
| [`simple-enquiry-form/`](simple-enquiry-form/) | Plugin | Shortcode form, private custom post type, Settings API, nonces, sanitising and escaping, spam protection |
| [`opening-hours-block/`](opening-hours-block/) | Plugin (block) | A dynamic Gutenberg block with `block.json`, a server render and a no-build editor UI; time-zone-aware "Open now" logic with tests |

## Try them

1. Copy `cardamom-cafe-theme` into `wp-content/themes/`, and the two plugin folders into
   `wp-content/plugins/`.
2. Activate the theme under **Appearance → Themes** and both plugins under **Plugins**.
3. Set **Settings → General → Timezone** to your city (the demo used Doha).
4. Create a page called *Visit us* and insert the **Opening Hours** block; create *Contact* and add
   `[simple_enquiry_form]` in a Shortcode block.
5. Under **Settings → Permalinks**, choose *Post name*, so `/visit/` and `/contact/` work as in the
   header's links.

Cardamom Café is invented for this portfolio; its name, address and menu are not a real business.
