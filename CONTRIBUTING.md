# Contributing to Admin Columns

Thanks for your interest in improving Admin Columns! This document explains how to set up a local development environment, report bugs, and suggest features.

Following these guidelines helps us respond to you faster and keeps the project moving cleanly.

## Development setup

### Requirements

- PHP 7.4 or higher
- WordPress 5.9 or higher
- Composer 2.x
- Node.js and npm (only needed for active frontend development)

### Quick install

From the `admin-columns/` folder:

```bash
./install.sh
```

This runs the full setup end-to-end: installs PHP dependencies (with vendor prefixing via the bundled `build/` tooling), installs frontend dependencies, and runs the one-time `setup:dev` hook. Compiled frontend assets are already committed to git, so the plugin works immediately after install. Run `npm run build` from `src/` only when producing a release. Re-running the script is safe.

After the script finishes, activate **Admin Columns** in WordPress → Plugins.

### Step-by-step installation

If you prefer to run the steps manually (or need to debug a failure), the flow below mirrors what `install.sh` does.

#### 1. Place the plugin in WordPress

Clone this repository into your WordPress plugins directory:

```bash
cd wp-content/plugins
git clone https://github.com/codepress/admin-columns.git
cd admin-columns
```

#### 2. Install PHP dependencies

```bash
composer install
```

This installs the plugin's Composer packages and then automatically:

1. Installs the build tooling in `build/vendor/`
2. Runs the prefixer, isolating vendor namespaces under `AC\Vendor\`

You can re-run the prefix cycle at any time with `composer prefix`.

#### 3. Install frontend dependencies (optional)

Compiled assets are committed to git, so this step is only needed if you plan to make frontend changes. From `admin-columns/src/`:

```bash
npm install
npm run setup:dev
```

See [`src/README.md`](src/README.md) for watch-mode, production build, and asset-release commands.

#### 4. Activate in WordPress

Go to **WordPress Admin → Plugins** and activate **Admin Columns**. Configure columns under **Settings → Admin Columns**.

### Project layout

```
admin-columns/
├── codepress-admin-columns.php   # plugin bootstrap
├── api.php                       # public PHP API
├── assets/                       # compiled frontend output (committed)
├── build/                        # prefixer tooling (shared with Admin Columns Pro)
├── classes/                      # PSR-4 `AC\` namespace
├── languages/                    # translations
├── settings/                     # settings screens
├── src/                          # TypeScript/Svelte frontend sources
└── templates/                    # PHP view templates
```

## Reporting bugs

The [issue tracker](https://github.com/codepress/admin-columns/issues) is the preferred channel for bug reports, but please respect the following:

- **Support questions belong on the [Plugin Support Forum](https://wordpress.org/support/plugin/codepress-admin-columns/)**, not the issue tracker.
- Keep discussion on topic and respectful of other contributors.
- Review the [issue labels](#issue-labels) before filing.

A good bug report is a *demonstrable problem* with enough detail that a maintainer can reproduce it. Before filing:

1. **Search existing issues** — someone may have already reported it.
2. **Isolate the problem** — the narrower the reproduction, the faster the fix.

Include in your report:

- Your WordPress version, PHP version, and active theme
- Steps to reproduce
- What you expected to happen
- What actually happened (include error messages, screenshots where useful)

Issues without enough information to act on will be closed; reopen them once you can add the missing details.

## Requesting features

Feature requests are welcome. Before filing, consider whether the idea fits the scope of the project — Admin Columns aims to be focused and maintainable, and features are much easier to add than to remove.

Make a concrete case: what problem does the feature solve, who does it help, and how would it work in practice? Mockups, example configurations, or references to similar plugins all help.

## Issue labels

Labels follow a `group:name` convention to keep the tracker tidy.

**Type**
- `type:addon`
- `type:bug`
- `type:enhancement`
- `type:feature`
- `type:invalid`
- `type:refactor`

**Status**
- `status:feedback`
- `status:in_progress`
- `status:on_hold`
- `status:rejected`
- `status:wontfix`
