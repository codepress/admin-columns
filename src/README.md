# Admin Columns — Frontend

TypeScript/Svelte frontend for the Admin Columns free plugin. All commands run from this directory (`admin-columns/src/`).

## First-time setup

```bash
npm install
npm run setup:dev
```

`setup:dev` configures three things locally (once per developer, not synced via git):
- A git merge driver that prevents asset conflicts on merge
- `assume-unchanged` on all compiled assets, so watch-mode rebuilds stay out of your git client
- A `post-checkout` hook that automatically restores `assume-unchanged` after every branch switch

## Development

```bash
npm run ac:build:development   # Watch mode (JS + CSS)
npm run ac:tailwind            # Watch Tailwind CSS separately
```

## Production build

```bash
npm run build                  # Full build (JS, CSS, languages)
npm run build-nolanguage       # Full build without languages
```

## Releasing assets

Compiled assets are committed to git so all developers (including backend) always have a working build. They are intentionally hidden from your git client during development via `assume-unchanged`.

To commit updated assets:

```bash
npm run assets:release   # Runs production build + unlocks assets in git client
# → stage and commit assets in your git client
npm run assets:lock      # Re-hides assets from git client
```

If your git client blocks a branch switch due to locally modified assets:

```bash
npm run assets:discard   # Discards watch-built changes, allows branch switching
```

## Other scripts

```bash
npm run ac:languages     # Build translation (.pot) file
npm run ac:webfont       # Regenerate icon font from SVG sources
npm run ac:svgsymbols    # Build SVG symbols sprite
```
