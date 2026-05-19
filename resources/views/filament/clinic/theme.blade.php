<style>
    /* ═══════════════════════════════════════════════════════════
       ESAGIO — HubSpot-Inspired Global Theme
       Injected via Filament renderHook (no build step needed)
       ═══════════════════════════════════════════════════════════ */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    /* ── Font Override ── */
    body,
    .fi-body,
    .fi-sidebar,
    .fi-topbar,
    .fi-header,
    .fi-main,
    .fi-page,
    .fi-ta-text,
    .fi-btn,
    .fi-input,
    .fi-modal,
    .fi-notification,
    [class*="fi-"] {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
    }

    /* ── Root Color Overrides (Dark Mode) ── */
    :root {
        --c-50: 224 242 254;
        --c-100: 186 230 253;
        --c-200: 125 211 252;
        --c-300: 56 189 248;
        --c-400: 14 165 233;
        --c-500: 2 132 199;
        --c-600: 3 105 161;
        --c-700: 7 89 133;
        --c-800: 12 74 110;
        --c-900: 12 55 80;
        --c-950: 8 47 73;
    }

    /* ── Body / Background ── */
    .fi-body {
        background-color: #111827 !important;
    }

    /* ── Sidebar ── */
    .fi-sidebar {
        background-color: #111827 !important;
        border-right: 1px solid #1f2937 !important;
    }

    .fi-sidebar-header {
        padding: 16px 16px 12px !important;
        border-bottom: 1px solid #1f2937 !important;
    }

    /* Brand name */
    .fi-sidebar-header span,
    .fi-sidebar-header a {
        font-size: 18px !important;
        font-weight: 700 !important;
        letter-spacing: -0.02em !important;
        color: #f9fafb !important;
    }

    /* Sidebar nav items */
    .fi-sidebar-item a,
    .fi-sidebar-item button {
        border-radius: 6px !important;
        padding: 7px 12px !important;
        margin: 1px 8px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #9ca3af !important;
        transition: all 0.15s ease !important;
    }

    .fi-sidebar-item a:hover,
    .fi-sidebar-item button:hover {
        background-color: #1f2937 !important;
        color: #f9fafb !important;
    }

    .fi-sidebar-item-active a,
    .fi-sidebar-item-active button,
    .fi-sidebar-item a.fi-active,
    .fi-sidebar-item [aria-current="page"] {
        background-color: rgba(59, 130, 246, 0.12) !important;
        color: #60a5fa !important;
        font-weight: 600 !important;
    }

    /* Sidebar icons */
    .fi-sidebar-item svg {
        width: 18px !important;
        height: 18px !important;
        opacity: 0.7;
    }
    .fi-sidebar-item-active svg,
    .fi-sidebar-item a:hover svg {
        opacity: 1;
    }

    /* Sidebar group labels */
    .fi-sidebar-group-label {
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        color: #6b7280 !important;
        padding: 16px 20px 4px !important;
    }

    /* ── Top Bar ── */
    .fi-topbar {
        background-color: #111827 !important;
        border-bottom: 1px solid #1f2937 !important;
        height: 56px !important;
    }

    /* ── Page Header ── */
    .fi-header-heading {
        font-size: 22px !important;
        font-weight: 700 !important;
        letter-spacing: -0.02em !important;
        color: #f9fafb !important;
    }

    .fi-header-subheading {
        font-size: 13px !important;
        color: #9ca3af !important;
    }

    /* ── Breadcrumbs ── */
    .fi-breadcrumbs {
        font-size: 12px !important;
    }
    .fi-breadcrumbs a {
        color: #9ca3af !important;
    }
    .fi-breadcrumbs a:hover {
        color: #60a5fa !important;
    }

    /* ── Cards / Sections ── */
    .fi-section,
    .fi-card {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
        border-radius: 8px !important;
        box-shadow: none !important;
    }

    .fi-section-header {
        padding: 14px 18px !important;
        border-bottom: 1px solid #374151 !important;
    }

    .fi-section-header-heading {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #f9fafb !important;
    }

    .fi-section-content {
        padding: 16px 18px !important;
    }

    /* ── Tables ── */
    .fi-ta-header-cell {
        background-color: #1f2937 !important;
        padding: 10px 16px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #9ca3af !important;
        border-bottom: 1px solid #374151 !important;
    }

    .fi-ta-row {
        border-bottom: 1px solid #1f2937 !important;
        transition: background-color 0.1s !important;
    }

    .fi-ta-row:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }

    .fi-ta-cell {
        padding: 10px 16px !important;
        font-size: 13px !important;
        color: #e5e7eb !important;
    }

    /* Table wrapper */
    .fi-ta-ctn {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
        border-radius: 8px !important;
        overflow: hidden !important;
    }

    /* Table search */
    .fi-ta-search-field input {
        background-color: #111827 !important;
        border: 1px solid #374151 !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        color: #e5e7eb !important;
        padding: 8px 12px 8px 36px !important;
    }
    .fi-ta-search-field input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15) !important;
    }

    /* ── Buttons ── */
    .fi-btn {
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        padding: 8px 16px !important;
        transition: all 0.15s !important;
    }

    .fi-btn-primary,
    .fi-btn[wire\\:click*="create"] {
        background-color: #3b82f6 !important;
        border: none !important;
    }
    .fi-btn-primary:hover {
        background-color: #2563eb !important;
    }

    /* ── Forms / Inputs ── */
    .fi-input input,
    .fi-input select,
    .fi-input textarea,
    .fi-fo-field-wrp input,
    .fi-fo-field-wrp select,
    .fi-fo-field-wrp textarea {
        background-color: #111827 !important;
        border: 1px solid #374151 !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        color: #e5e7eb !important;
        padding: 8px 12px !important;
        transition: border-color 0.15s !important;
    }

    .fi-input input:focus,
    .fi-input select:focus,
    .fi-input textarea:focus,
    .fi-fo-field-wrp input:focus,
    .fi-fo-field-wrp select:focus,
    .fi-fo-field-wrp textarea:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15) !important;
        outline: none !important;
    }

    .fi-fo-field-wrp label {
        font-size: 12px !important;
        font-weight: 600 !important;
        color: #d1d5db !important;
        margin-bottom: 4px !important;
    }

    /* ── Badges / Status pills ── */
    .fi-badge {
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 3px 10px !important;
        border-radius: 12px !important;
        letter-spacing: 0.01em !important;
    }

    /* ── Modals ── */
    .fi-modal-content {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
        border-radius: 10px !important;
    }

    .fi-modal-header {
        border-bottom: 1px solid #374151 !important;
        padding: 16px 20px !important;
    }

    .fi-modal-heading {
        font-size: 16px !important;
        font-weight: 700 !important;
    }

    .fi-modal-footer {
        border-top: 1px solid #374151 !important;
        padding: 14px 20px !important;
    }

    /* ── Notifications ── */
    .fi-notification {
        border-radius: 8px !important;
        font-size: 13px !important;
    }

    /* ── Widgets / Stats ── */
    .fi-wi-stats-overview-stat {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
        border-radius: 8px !important;
        padding: 16px 20px !important;
    }

    .fi-wi-stats-overview-stat-label {
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #9ca3af !important;
    }

    .fi-wi-stats-overview-stat-value {
        font-size: 28px !important;
        font-weight: 700 !important;
        color: #f9fafb !important;
        letter-spacing: -0.02em !important;
    }

    /* ── Pagination ── */
    .fi-pagination {
        font-size: 12px !important;
    }

    /* ── Tabs ── */
    .fi-tabs-tab {
        font-size: 13px !important;
        font-weight: 500 !important;
        padding: 8px 14px !important;
        border-radius: 6px !important;
        color: #9ca3af !important;
        transition: all 0.15s !important;
    }
    .fi-tabs-tab:hover {
        color: #f9fafb !important;
        background-color: #1f2937 !important;
    }
    .fi-tabs-tab-active {
        color: #60a5fa !important;
        background-color: rgba(59, 130, 246, 0.12) !important;
        font-weight: 600 !important;
    }

    /* ── Empty States ── */
    .fi-ta-empty-state {
        padding: 48px 16px !important;
    }
    .fi-ta-empty-state-heading {
        font-size: 15px !important;
        font-weight: 600 !important;
        color: #9ca3af !important;
    }

    /* ── Dropdown Menus ── */
    .fi-dropdown-panel {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.3) !important;
    }

    .fi-dropdown-list-item button,
    .fi-dropdown-list-item a {
        font-size: 13px !important;
        padding: 8px 14px !important;
        color: #d1d5db !important;
        border-radius: 4px !important;
    }
    .fi-dropdown-list-item button:hover,
    .fi-dropdown-list-item a:hover {
        background-color: rgba(59, 130, 246, 0.08) !important;
        color: #60a5fa !important;
    }

    /* ── Tooltip ── */
    [x-tooltip] {
        font-size: 12px !important;
        font-family: 'Inter', sans-serif !important;
    }

    /* ── Action icons in tables ── */
    .fi-ta-actions {
        gap: 4px !important;
    }
    .fi-ta-actions button,
    .fi-ta-actions a {
        border-radius: 6px !important;
        padding: 6px !important;
        transition: all 0.15s !important;
    }
    .fi-ta-actions button:hover,
    .fi-ta-actions a:hover {
        background-color: rgba(59, 130, 246, 0.1) !important;
    }

    /* ── Compact: reduce excessive padding ── */
    .fi-page > .fi-page-content-ctn > div {
        gap: 16px !important;
    }

    .fi-body-content {
        padding: 16px 24px !important;
    }

    /* ── Global transitions ── */
    * {
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    /* ── Scrollbar ── */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #374151; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #4b5563; }
</style>
