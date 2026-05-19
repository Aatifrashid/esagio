<style>
    /* ═══════════════════════════════════════════════════════════
       ESAGIO — Light Theme / Orange Brand / Thin Sophisticated Font
       ═══════════════════════════════════════════════════════════ */

    @import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;400;500;600;700&display=swap');

    /* ── Force light mode ── */
    .dark { color-scheme: light !important; }
    .fi-body { color-scheme: light !important; }
    html.dark, html.dark body { background-color: #ffffff !important; }

    /* ── Font Override ── */
    body, [class*="fi-"] {
        font-family: 'Lexend Deca', -apple-system, BlinkMacSystemFont, sans-serif !important;
        -webkit-font-smoothing: antialiased;
    }

    /* ── Body ── */
    .fi-body, body {
        background-color: #f8f9fa !important;
        color: #1a1a2e !important;
    }

    /* ── Sidebar — Narrow, white, clean ── */
    .fi-sidebar {
        background-color: #ffffff !important;
        border-right: 1px solid #e9ecef !important;
        width: 220px !important;
        min-width: 220px !important;
    }
    .fi-sidebar-nav { padding: 8px !important; }

    .fi-sidebar-header {
        padding: 14px 16px 10px !important;
        border-bottom: 1px solid #e9ecef !important;
    }

    /* Brand */
    .fi-sidebar-header span,
    .fi-sidebar-header a {
        font-size: 17px !important;
        font-weight: 700 !important;
        letter-spacing: -0.03em !important;
        color: #1a1a2e !important;
    }

    /* Nav items */
    .fi-sidebar-item a,
    .fi-sidebar-item button {
        border-radius: 6px !important;
        padding: 6px 10px !important;
        margin: 1px 0 !important;
        font-size: 13px !important;
        font-weight: 400 !important;
        color: #5a6474 !important;
        transition: all 0.12s ease !important;
    }
    .fi-sidebar-item a:hover,
    .fi-sidebar-item button:hover {
        background-color: #fff5f0 !important;
        color: #e8663d !important;
    }
    .fi-sidebar-item-active a,
    .fi-sidebar-item-active button,
    .fi-sidebar-item a.fi-active,
    .fi-sidebar-item [aria-current="page"] {
        background-color: #fff5f0 !important;
        color: #e8663d !important;
        font-weight: 600 !important;
    }

    /* Sidebar icons */
    .fi-sidebar-item svg {
        width: 17px !important;
        height: 17px !important;
        color: inherit !important;
    }

    /* Sidebar group labels */
    .fi-sidebar-group-label {
        font-size: 10px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        color: #adb5bd !important;
        padding: 14px 12px 4px !important;
    }

    /* Sidebar group collapse icon */
    .fi-sidebar-group-collapse-button {
        color: #adb5bd !important;
    }

    /* ── Top Bar ── */
    .fi-topbar {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e9ecef !important;
        box-shadow: none !important;
    }
    .fi-topbar nav, .fi-topbar > div {
        background-color: #ffffff !important;
    }

    /* ── Page Header ── */
    .fi-header-heading {
        font-size: 20px !important;
        font-weight: 600 !important;
        letter-spacing: -0.02em !important;
        color: #1a1a2e !important;
    }
    .fi-header-subheading {
        font-size: 13px !important;
        color: #868e96 !important;
        font-weight: 400 !important;
    }

    /* ── Breadcrumbs ── */
    .fi-breadcrumbs { font-size: 12px !important; }
    .fi-breadcrumbs li, .fi-breadcrumbs a { color: #868e96 !important; font-weight: 400 !important; }
    .fi-breadcrumbs a:hover { color: #e8663d !important; }

    /* ── Cards / Sections ── */
    .fi-section, .fi-card {
        background-color: #ffffff !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
    }
    .fi-section-header {
        padding: 12px 16px !important;
        border-bottom: 1px solid #f1f3f5 !important;
        background-color: #ffffff !important;
    }
    .fi-section-header-heading {
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #1a1a2e !important;
    }
    .fi-section-content {
        padding: 14px 16px !important;
        background-color: #ffffff !important;
    }

    /* ── Tables ── */
    .fi-ta-ctn {
        background-color: #ffffff !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
    }
    .fi-ta-header-cell {
        background-color: #f8f9fa !important;
        padding: 9px 16px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        color: #868e96 !important;
        border-bottom: 1px solid #e9ecef !important;
    }
    .fi-ta-row {
        border-bottom: 1px solid #f1f3f5 !important;
        background-color: #ffffff !important;
    }
    .fi-ta-row:hover { background-color: #fff9f6 !important; }
    .fi-ta-cell {
        padding: 10px 16px !important;
        font-size: 13px !important;
        font-weight: 400 !important;
        color: #343a40 !important;
    }

    /* Table search */
    .fi-ta-search-field input {
        background-color: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        color: #343a40 !important;
    }
    .fi-ta-search-field input:focus {
        border-color: #e8663d !important;
        box-shadow: 0 0 0 2px rgba(232,102,61,0.12) !important;
    }
    .fi-ta-search-field input::placeholder { color: #adb5bd !important; }

    /* ── Buttons — Orange primary ── */
    .fi-btn {
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        padding: 7px 14px !important;
        transition: all 0.12s !important;
    }
    .fi-btn-primary,
    .fi-btn[style*="background"],
    button.fi-btn:not(.fi-btn-color-gray):not(.fi-btn-color-danger) {
        background-color: #e8663d !important;
        border-color: #e8663d !important;
        color: #ffffff !important;
    }
    .fi-btn-primary:hover {
        background-color: #d4572e !important;
        border-color: #d4572e !important;
    }

    /* Ghost / secondary buttons */
    .fi-btn-color-gray {
        background-color: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
    }
    .fi-btn-color-gray:hover {
        background-color: #e9ecef !important;
    }

    /* ── Forms / Inputs ── */
    .fi-input input, .fi-input select, .fi-input textarea,
    .fi-fo-field-wrp input, .fi-fo-field-wrp select, .fi-fo-field-wrp textarea {
        background-color: #ffffff !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        font-weight: 400 !important;
        color: #343a40 !important;
        padding: 8px 12px !important;
    }
    .fi-input input:focus, .fi-input select:focus, .fi-input textarea:focus,
    .fi-fo-field-wrp input:focus, .fi-fo-field-wrp select:focus, .fi-fo-field-wrp textarea:focus {
        border-color: #e8663d !important;
        box-shadow: 0 0 0 2px rgba(232,102,61,0.1) !important;
        outline: none !important;
    }
    .fi-fo-field-wrp label {
        font-size: 12px !important;
        font-weight: 500 !important;
        color: #495057 !important;
    }

    /* ── Badges ── */
    .fi-badge {
        font-size: 11px !important;
        font-weight: 500 !important;
        padding: 3px 10px !important;
        border-radius: 12px !important;
    }

    /* ── Modals ── */
    .fi-modal-content {
        background-color: #ffffff !important;
        border: 1px solid #e9ecef !important;
        border-radius: 10px !important;
        box-shadow: 0 20px 60px rgba(0,0,0,0.12) !important;
    }
    .fi-modal-header {
        border-bottom: 1px solid #f1f3f5 !important;
        padding: 16px 20px !important;
    }
    .fi-modal-heading {
        font-size: 16px !important;
        font-weight: 600 !important;
        color: #1a1a2e !important;
    }
    .fi-modal-footer {
        border-top: 1px solid #f1f3f5 !important;
        padding: 14px 20px !important;
        background-color: #f8f9fa !important;
    }

    /* ── Widgets / Stats ── */
    .fi-wi-stats-overview-stat {
        background-color: #ffffff !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
    }
    .fi-wi-stats-overview-stat-label {
        font-size: 12px !important;
        font-weight: 400 !important;
        color: #868e96 !important;
    }
    .fi-wi-stats-overview-stat-value {
        font-size: 26px !important;
        font-weight: 600 !important;
        color: #1a1a2e !important;
        letter-spacing: -0.02em !important;
    }
    .fi-wi-stats-overview-stat-description {
        color: #e8663d !important;
    }

    /* ── Dropdown Menus ── */
    .fi-dropdown-panel {
        background-color: #ffffff !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
    }
    .fi-dropdown-list-item button,
    .fi-dropdown-list-item a {
        font-size: 13px !important;
        padding: 7px 12px !important;
        color: #495057 !important;
        border-radius: 4px !important;
    }
    .fi-dropdown-list-item button:hover,
    .fi-dropdown-list-item a:hover {
        background-color: #fff5f0 !important;
        color: #e8663d !important;
    }

    /* ── Tabs ── */
    .fi-tabs-tab {
        font-size: 13px !important;
        font-weight: 400 !important;
        color: #868e96 !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
    }
    .fi-tabs-tab:hover {
        color: #343a40 !important;
        background-color: #f8f9fa !important;
    }
    .fi-tabs-tab-active {
        color: #e8663d !important;
        background-color: #fff5f0 !important;
        font-weight: 500 !important;
    }

    /* ── Empty States ── */
    .fi-ta-empty-state-heading {
        font-size: 14px !important;
        font-weight: 500 !important;
        color: #868e96 !important;
    }

    /* ── Pagination ── */
    .fi-pagination { font-size: 12px !important; }

    /* ── Notifications ── */
    .fi-notification { border-radius: 8px !important; }

    /* ── Action icons ── */
    .fi-ta-actions button:hover,
    .fi-ta-actions a:hover {
        background-color: #fff5f0 !important;
        color: #e8663d !important;
        border-radius: 6px !important;
    }

    /* ── Compact spacing ── */
    .fi-page > .fi-page-content-ctn > div { gap: 14px !important; }
    .fi-body-content { padding: 14px 20px !important; }

    /* ── Force all dark-mode overrides to light ── */
    .dark .fi-sidebar,
    .dark .fi-topbar,
    .dark .fi-body,
    .dark .fi-ta-ctn,
    .dark .fi-section,
    .dark .fi-card,
    .dark .fi-modal-content,
    .dark .fi-dropdown-panel,
    .dark .fi-ta-row,
    .dark .fi-section-header,
    .dark .fi-section-content,
    .dark .fi-modal-footer,
    .dark .fi-wi-stats-overview-stat {
        background-color: inherit !important;
        color: inherit !important;
    }
    .dark .fi-ta-header-cell { background-color: #f8f9fa !important; }
    .dark .fi-sidebar { background-color: #ffffff !important; }
    .dark .fi-topbar, .dark .fi-topbar nav, .dark .fi-topbar > div { background-color: #ffffff !important; }
    .dark .fi-body, .dark body { background-color: #f8f9fa !important; }
    .dark .fi-ta-row { background-color: #ffffff !important; }
    .dark .fi-section, .dark .fi-card { background-color: #ffffff !important; }
    .dark .fi-section-header { background-color: #ffffff !important; }
    .dark .fi-section-content { background-color: #ffffff !important; }
    .dark .fi-modal-content { background-color: #ffffff !important; }
    .dark .fi-modal-footer { background-color: #f8f9fa !important; }
    .dark .fi-wi-stats-overview-stat { background-color: #ffffff !important; }
    .dark .fi-dropdown-panel { background-color: #ffffff !important; }

    /* Force text colors in dark mode */
    .dark .fi-header-heading { color: #1a1a2e !important; }
    .dark .fi-section-header-heading { color: #1a1a2e !important; }
    .dark .fi-ta-cell { color: #343a40 !important; }
    .dark .fi-ta-header-cell { color: #868e96 !important; }
    .dark .fi-sidebar-item a, .dark .fi-sidebar-item button { color: #5a6474 !important; }
    .dark .fi-sidebar-item-active a, .dark .fi-sidebar-item-active button,
    .dark .fi-sidebar-item [aria-current="page"] {
        color: #e8663d !important;
        background-color: #fff5f0 !important;
    }
    .dark .fi-sidebar-group-label { color: #adb5bd !important; }
    .dark .fi-sidebar-header span, .dark .fi-sidebar-header a { color: #1a1a2e !important; }
    .dark .fi-breadcrumbs li, .dark .fi-breadcrumbs a { color: #868e96 !important; }
    .dark .fi-wi-stats-overview-stat-value { color: #1a1a2e !important; }
    .dark .fi-wi-stats-overview-stat-label { color: #868e96 !important; }
    .dark .fi-fo-field-wrp label { color: #495057 !important; }
    .dark .fi-modal-heading { color: #1a1a2e !important; }
    .dark .fi-dropdown-list-item button, .dark .fi-dropdown-list-item a { color: #495057 !important; }
    .dark .fi-btn-color-gray { background-color: #f8f9fa !important; border-color: #dee2e6 !important; color: #495057 !important; }

    /* Dark mode inputs */
    .dark .fi-input input, .dark .fi-input select, .dark .fi-input textarea,
    .dark .fi-fo-field-wrp input, .dark .fi-fo-field-wrp select, .dark .fi-fo-field-wrp textarea {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
        color: #343a40 !important;
        color-scheme: light !important;
    }
    .dark .fi-ta-search-field input {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #343a40 !important;
    }

    /* ── Scrollbar (light) ── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #adb5bd; }

    /* ── Links ── */
    a.fi-link { color: #e8663d !important; }
    a.fi-link:hover { color: #d4572e !important; }

    /* ── Checkbox/Toggle orange accent ── */
    .fi-checkbox-input:checked { background-color: #e8663d !important; border-color: #e8663d !important; }
    .fi-toggle-input:checked { background-color: #e8663d !important; }

    /* ── Global text color fix for dark class ── */
    .dark, .dark * { --tw-text-opacity: 1; }
</style>
