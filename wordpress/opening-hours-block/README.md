# Opening Hours Block

A Gutenberg block for weekly opening hours: today highlighted, and a live **Open now · closes at
11:00 pm** or **Closed now · opens tomorrow at 6:30 am** badge, worked out in the site's own time
zone. Late nights that run past midnight (1:00 pm – 12:30 am) are handled properly.

## Files

```
opening-hours-block.php   registers the block; OHB_Hours holds the open/closed arithmetic
block/block.json          the block's metadata, attributes (with a schema) and supports
block/index.js            the editor: sidebar controls + live server preview — plain JS, no build step
block/index.asset.php     the editor script's dependencies, written by hand
block/render.php          the server render: badge, the week, the note
block/style.css           front-end styles; colours follow the theme
block/editor.css          sidebar layout
tests/hours-test.php      the arithmetic, checked without WordPress
```

## Why a dynamic block

The badge depends on the time a visitor opens the page, so the block saves nothing to the post
(`save` returns `null`) and renders on the server every time. The editor shows the same server
render through `ServerSideRender`, so the preview always matches the live page.

## Details

- **Week order** follows **Settings → General → Week starts on**; day names and the time format come
  from the site's language and settings.
- **Past midnight**: a closing time earlier than the opening time means the next morning. Last
  night's late opening is checked first, so 12:15 am on Friday is still "open" from Thursday.
- **Next opening** looks up to a week ahead and skips closed days ("opens Tuesday at 6:30 am").
- **Supports** colour, padding, margin, font size, border and wide alignment from the editor.
- **Caching note**: a full-page cache will freeze the badge at the moment the page was cached. Exclude
  the page or keep the cache short where the badge matters.

## Tests

```bash
php tests/hours-test.php
```

Nine cases: normal hours, the minute of closing, late nights on both sides of midnight, a closed
day, the next opening after a closed day, and the editor sending `"false"` as a string. That last one
was a real bug, caught while testing the editor preview: the REST request carried the booleans as
strings, and `! empty( "false" )` is true, so every day showed as closed. It is fixed in two places:
a schema on the `hours` attribute, and a strict read of the flag in PHP.

## Install

Copy this folder into `wp-content/plugins/`, activate **Opening Hours Block**, and add the block
from the inserter (search "hours"). Requires WordPress 6.5+ and PHP 7.4+; tested on 7.1.3.
