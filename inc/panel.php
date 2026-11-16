<?php
/* Shared sidebar helpers for the user panel and the admin panel. */
function cwp_icon($name) {
    $p = array(
        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'wallet'   => '<path d="M3 7a2 2 0 0 1 2-2h13v4"/><path d="M3 7v11a2 2 0 0 0 2 2h14a1 1 0 0 0 1-1v-9a1 1 0 0 0-1-1H5a2 2 0 0 1-2-2z"/><circle cx="16.5" cy="14.5" r="1.2"/>',
        'report'   => '<path d="M6 3h9l4 4v14H6z"/><path d="M14 3v5h5M9 13h7M9 17h7"/>',
        'sliders'  => '<path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0"/><circle cx="16" cy="6" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="18" cy="18" r="2"/>',
        'qr'       => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM20 14v0M14 20h3M20 17v4"/>',
        'phone'    => '<rect x="7" y="2.5" width="10" height="19" rx="2.5"/><path d="M11 18.5h2"/>',
        'at'       => '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/>',
        'list'     => '<path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
        'bank'     => '<path d="M3 10l9-6 9 6M5 10v8M9.5 10v8M14.5 10v8M19 10v8M3 21h18"/>',
        'code'     => '<path d="M8 8l-5 4 5 4M16 8l5 4-5 4M14 5l-4 14"/>',
        'cog'      => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1L7 17M17 7l2.1-2.1"/>',
        'tag'      => '<path d="M3 12V4h8l10 10-8 8z"/><circle cx="7.5" cy="8.5" r="1.2"/>',
        'users'    => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.6a3.5 3.5 0 0 1 0 6.8M18 14.2a6.5 6.5 0 0 1 3.5 5.8"/>',
        'logout'   => '<path d="M9 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h4M16 8l4 4-4 4M20 12H9"/>',
        'menu'     => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'sun'      => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon'     => '<path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5z"/>',
        'close'    => '<path d="M6 6l12 12M18 6L6 18"/>',
    );
    $d = isset($p[$name]) ? $p[$name] : '';
    return '<svg class="ico" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $d . '</svg>';
}

/* $items: array of array(href, label, icon, optional tag) */
function cwp_nav($items) {
    $page = basename($_SERVER['SCRIPT_NAME'], '.php');
    $out = '<nav class="p-nav" aria-label="Main">';
    foreach ($items as $it) {
        $on = (strcasecmp($page, $it[0]) === 0);
        $tag = isset($it[3]) ? '<span class="p-tag">' . htmlspecialchars($it[3]) . '</span>' : '';
        $out .= '<a href="' . htmlspecialchars($it[0]) . '" class="p-link' . ($on ? ' is-on' : '') . '"' . ($on ? ' aria-current="page"' : '') . '>'
              . cwp_icon($it[2]) . '<span>' . htmlspecialchars($it[1]) . '</span>' . $tag . '</a>';
    }
    return $out . '</nav>';
}
