# Dark/Light Mode Toggle

The design system already has full `.dark` token coverage in `src/styles.css`. We just need a theme provider, persistence, and a header toggle.

## Scope

1. **Theme provider** — `src/components/theme-provider.tsx`
   - React context exposing `theme` (`"light" | "dark" | "system"`) and `setTheme`.
   - Persists to `localStorage` under key `hana-theme` (default `"system"`).
   - Applies `.dark` class to `document.documentElement` based on resolved theme.
   - Listens to `prefers-color-scheme` changes when in system mode.
   - SSR-safe: reads localStorage inside `useEffect`, no hydration mismatch (initial render uses default, then syncs).

2. **Mount provider** — wrap children in `src/routes/__root.tsx` so it covers every route.

3. **Toggle component** — `src/components/theme-toggle.tsx`
   - Uses existing `DropdownMenu` + `Button` (icon variant) from shadcn.
   - Sun/Moon icons from `lucide-react` with smooth swap.
   - Three options: Light, Dark, System.

4. **Place toggle in header** — `src/components/site-header.tsx`
   - Insert next to the Sign in / Open an account buttons (desktop).
   - Also include in the mobile `Sheet` menu.

5. **Auth shell** — quick check that `src/components/auth-shell.tsx` doesn't hard-code background/foreground; if it does, swap to semantic tokens so dark mode renders correctly.

## Out of scope

- No new tokens needed (already defined for both modes).
- No backend/database changes.
- No per-user preference sync (localStorage only).

## Files

- add `src/components/theme-provider.tsx`
- add `src/components/theme-toggle.tsx`
- edit `src/routes/__root.tsx` (wrap with ThemeProvider)
- edit `src/components/site-header.tsx` (mount toggle, desktop + mobile)
- possibly edit `src/components/auth-shell.tsx` (token audit only)
