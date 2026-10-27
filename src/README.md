# Admin Columns — Frontend

TypeScript/Svelte frontend for the Admin Columns free plugin. All commands run from this directory (`admin-columns/src/`).

## First-time setup

```bash
npm install
npm run setup
```

`setup` configures three things locally (once per developer, not synced via git):
- A git merge driver that prevents asset conflicts on merge
- `assume-unchanged` on all compiled assets, so watch-mode rebuilds stay out of your git client
- A `post-checkout` hook that automatically restores `assume-unchanged` after every branch switch

## Development

```bash
npm run dev   # Watch mode: JS + CSS + Tailwind together
```

## Production build

```bash
npm run build   # Full build (JS, CSS, Tailwind)
```

## Releasing assets

Compiled assets are committed to git so all developers (including backend) always have a working build. They are intentionally hidden from your git client during development via `assume-unchanged`.

To commit updated assets:

```bash
npm run release:assets   # Runs production build + unlocks assets in git client
# → stage and commit assets in your git client
npm run release:lock     # Re-hides assets from git client
```

If your git client blocks a branch switch due to locally modified assets:

```bash
npm run release:discard  # Discards watch-built changes, allows branch switching
```

## Other

```bash
npm run webfont   # Regenerate the icon font from SVG sources
```

## Translations

```bash
npm run languages   # Regenerate the .pot and pull translations from translate.wordpress.org
```

Not part of `npm run build`, run it before a release. Only the .po files are downloaded; the .mo files are compiled
locally with `msgfmt` (`brew install gettext`). A language that fails to download or validate keeps its previous files,
and the script exits with 1 and lists what failed.

## Running things directly

The npm scripts are thin wrappers around nps:

```bash
npx nps build              # full production build
npx nps build.development  # same as `npm run dev`
npx nps languages          # translations only
```
