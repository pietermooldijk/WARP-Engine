# Gamet Studios WARP Engine (v2.2)

**WARP** — **W**eb **A**pplication **R**untime **P**latform.  
Vollständige **PHP 8.1–8.5 + Vanilla-JS Full-Stack-Engine** hinter dem Gamet Studios CMSS — ohne Build-Server, ohne Composer-Zwang und ohne MySQL (SQLite Vault).

> **Lizenz:** Source-Available — siehe [`LICENSE`](LICENSE).  
> **Core-Update-Kompatibilität:** Alle Engine-Dateien (`includes/`, `api/app.php`, `assets/js/core/`, Basis-CSS) sind fester Bestandteil des CMSS-Core-Updates (`export_core_zip`).

---

## 1. Enthaltene Backend-Module (`includes/engine.php` & `includes/db.php`)

- **Auth, RBAC & Bearer API-Tokens (`api_tokens`):**
  - `gs_boot_engine()`, `gs_require_auth($role)`, `gs_can($scope)`, `gs_require_perm($scope)`, `gs_require_csrf()`, `gs_rate_limit($bucket, $max, $windowSec)`
  - API-Tokens: `gs_token_create($userId, $name, $scopes, $ttlDays)`, `gs_token_verify($token)`, `gs_token_list($userId)`, `gs_token_revoke($id, $userId)`
- **Queryable App-Store (`app_records`):**
  - `gs_store_put($ns, $key, $data, $ownerId)`, `gs_store_patch($ns, $key, $patch, $ownerId)`, `gs_store_get($ns, $key)`, `gs_store_list($ns)`, `gs_store_delete($ns, $key)`
  - **Bulk, Count, Export/Import & Namespaces:** `gs_store_bulk_put($ns, $items, $ownerId)`, `gs_store_count($ns, $where)`, `gs_store_namespaces()`, `gs_store_export($ns)`, `gs_store_import($ns, $items)`, `gs_csv_download($filename, $rows)`
  - **Erweiterte Suche & Pagination:** `gs_store_query($ns, ['q' => ..., 'where' => [...], 'sortBy' => ..., 'sortDir' => 'asc'|'desc', 'page' => 1, 'perPage' => 50])`
- **TTL-Cache (`app_cache`):** `gs_cache_set()`, `gs_cache_get()`, `gs_cache_forget()`, `gs_cache_remember()`, `gs_cache_prune()`, `gs_cache_flush($prefix)`
- **Background-Job-Queue (`app_jobs`):** `gs_job_register($handler, $fn)`, `gs_job_dispatch($handler, $payload, $delaySec)`, `gs_job_run_pending($limit)`, `gs_job_stats($queue)`, `gs_job_retry_failed($queue)`, `gs_job_prune($days)`
- **Validierung & Krypto:**
  - `gs_validate($data, ['email' => 'required|email', 'age' => 'int|min:18', 'slug' => 'slug'])` (unterstützt `required`, `string`, `int`, `float`, `bool`, `array`, `email`, `url`, `slug`, `min:`, `max:`, `in:`, `regex:`)
  - **AES-256-GCM:** `gs_encrypt($plain)` & `gs_decrypt($cipher)`
  - **Signierte Tokens:** `gs_hmac_sign($payload, $secret, $ttlSec)` & `gs_hmac_verify($token)`
- **HTTP-Client, Webhooks & Mail:** `gs_http($method, $url, $opts)`, `gs_webhook_send($url, $payload, $secret)`, `gs_webhook_verify($secret)`, `gs_mail($to, $subject, $html)`
- **Echtzeit-SSE, Feature-Flags & App-Settings:** `gs_sse_headers()`, `gs_sse_send($event, $data)`, `gs_flag($name)`, `gs_flag_set($name, $bool)`, `gs_app_setting_get($slug, $key)`, `gs_app_setting_set($slug, $key, $val)`
- **Uploads (`app_uploads`), Audit & Health:** `gs_upload_handle($field, $ns)` (inkl. automatischer WebP-Konvertierung & Skalierung), `gs_uploads_list($ns)`, `gs_audit($action, $summary)`, `gs_health()`, `gs_on()` / `gs_emit()`

---

## 2. Einheitliche API (`api/app.php`)

- `GET /api/app.php?action=engine_info`
- `GET /api/app.php?action=store_list&ns=...` & `GET /api/app.php?action=store_query&ns=...&q=...&page=1`
- `GET /api/app.php?action=store_get&ns=...&key=...`, `store_count&ns=...`, `store_namespaces`, `store_export&ns=...`
- `POST /api/app.php` (`store_put`, `store_patch`, `store_import`, `store_delete`, `token_create`, `token_revoke`, `upload`, `jobs_run`, `jobs_retry`, `cache_flush`, `flag_set`, `app_setting_set`)
- `GET /api/app.php?action=uploads_list`, `tokens_list`, `flags_list`, `app_settings_get`, `jobs_stats`, `audit_list`
- **Eigene Module:** `/api/app.php?app=<slug>&action=...` → lädt `apps/<slug>/api.php`

---

## 3. Frontend App-Framework (`assets/js/core/engine.js`)

```js
import { warp } from '/assets/js/core/engine.js'; // Alias für gsEngine

const info = await warp.auth();
const state = warp.createStore({ items: [] });
const events = warp.createEvents();
const router = warp.createRouter();

warp.ui.palette([
  { label: 'Neu laden', shortcut: 'R', run: () => location.reload() },
]);

router.add('/', async () => {
  const res = await warp.store.query('mein-ns', { q: 'test', page: 1, perPage: 20 });
  state.set({ items: res.items });
  warp.ui.table('#list', {
    columns: [{ key: 'key', label: 'ID' }],
    rows: res.items,
  });
});
router.start();

warp.hotkey('mod+s', () => warp.ui.toast('Gespeichert!', 'success'));
```

---

## 4. WARP CLI & Lokal starten

```bash
php -S localhost:8080 tools/router.php
php tools/warp.php new-app mein-projekt "Mein Projekt"
php tools/warp.php jobs:run
php tools/warp.php health
```
Beispiel-App: **`http://localhost:8080/apps/example-notes/`**
