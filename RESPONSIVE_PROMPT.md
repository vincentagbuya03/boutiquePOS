# Prompt — Make Boutique POS Fully Responsive

Copy/paste this prompt into GitHub Copilot Chat / ChatGPT.

```text
You are a senior frontend engineer working inside an existing Laravel Blade web app. This repo builds frontend assets with Vite and Tailwind CSS v4, but many screens currently use large per-view <style> blocks in Blade templates and a global layout stylesheet inside the layout.

Goal: make the entire system fully responsive on mobile/tablet/desktop (including the web app views) without changing the overall visual design.

Non-negotiable constraints:
- UI/layout/CSS only. Do NOT change backend logic (routes/controllers/models/migrations/policies/services).
- Do NOT add new frontend dependencies/frameworks.
- Preserve the existing brand look (fonts, spacing, component shapes). Reuse existing CSS variables already used across the app (e.g., the editorial maroon, border variables, etc.). Do not introduce random new colors.
- Do NOT “fix” layout issues by hiding overflow globally. Fix the actual sizing/wrapping/scrolling.

Where to start (must do in this order):
1) Global authenticated layout responsiveness:
   - Make the sidebar + top navbar + main content responsive from 320px up.
   - Fix any fixed widths that break small screens.
   - Ensure flex children can shrink (min-width: 0) and long content doesn’t force horizontal scrolling.
   - Ensure the mobile layout does not keep desktop offsets (e.g., desktop sidebar spacing should not apply on mobile).

   Primary file: resources/views/layouts/app.blade.php

2) High-impact screens (make each usable on mobile, not just “fits”):
   - Login: collapse the 2-column layout to 1 column on small screens; reduce huge paddings; use fluid typography (e.g., clamp) for oversized headings.
     File: resources/views/auth/login.blade.php

   - Dashboard: grids must collapse 4 → 2 → 1 across breakpoints; prevent cards/charts from overflowing.
     File: resources/views/dashboard.blade.php

   - POS Terminal: product grid + receipt panel must stack/adapt on mobile; nothing should be cut off; scrolling must work (especially with the on-screen keyboard). Avoid hard “100vh” traps on mobile browsers.
     File: resources/views/sales/create.blade.php

   - Table-heavy pages: keep desktop tables, but on small screens wrap tables in a horizontal scroll container and/or provide a stacked alternative so columns don’t clip and actions remain reachable.
     Example file: resources/views/inventory/index.blade.php

   - Product catalog: header + action buttons must wrap/stack; grids must scale cleanly.
     File: resources/views/products/index.blade.php

Implementation rules:
- Prefer Tailwind responsive utilities (sm, md, lg) where it reduces custom CSS, but keep changes minimal and consistent with existing style.
- It’s acceptable to keep existing CSS and add targeted @media rules when that’s the smallest safe change.
- Replace rigid sizes with responsive patterns (flex-wrap, max-width: 100%, width: min(...), clamp(...)).
- For tables: do not let the page overflow; use a wrapper with overflow-x: auto on small screens.

Acceptance criteria (must verify):
- At 320px, 375px, 768px, 1024px widths: no clipped content, no inaccessible buttons, no broken navigation.
- No reliance on overflow-x: hidden to mask layout bugs.
- POS flow remains functional and readable on mobile.

Deliverables:
- Apply changes directly in the repo.
- List the files modified and what each change fixes (problem + breakpoint behavior).
- Keep the design/UX the same—only make it responsive and robust.
```
