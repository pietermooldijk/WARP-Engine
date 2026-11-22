<?php
/**
 * Beispiel-Backend für eine eigene App auf der Gamet Studios WARP Engine.
 * Aufruf: /api/app.php?app=example-notes&action=ping
 */

declare(strict_types=1);

$in = gs_input();
$action = (string) ($_GET['action'] ?? ($in['action'] ?? 'ping'));

if ($action === 'ping') {
    gs_json([
        'ok'      => true,
        'app'     => 'example-notes',
        'engine'  => $engine['version'],
        'message' => 'Gamet Studios WARP Engine App-Modul aktiv.',
    ]);
}

gs_json(['ok' => false, 'error' => 'Unbekannte App-Aktion.'], 404);
