# Gamet Studios WARP Engine

> **WARP** — **W**eb **A**pplication **R**untime **P**latform (`v2.2.0`)

Die modulare **Full-Stack Web-App-Engine** von Gamet Studios — entwickelt mit **PHP 8.1–8.5**, **Vanilla-JS (ES-Module)** und einem geschützten **SQLite Vault**.  
Kein Build-Server, kein Node.js-Zwang im Hosting, kein Composer-Ballast und kein MySQL erforderlich.

---

## Highlights

- **Zero-Build & Hosting-Ready:** Läuft direkt auf klassischem Shared-Hosting (Plesk, Apache, nginx) sowie lokal über den eingebauten PHP-Server.
- **SQLite Vault & Queryable App-Store:** Schema-freier Collection-Store (`app_records`) mit Suche, Filtern, Sortierung, Pagination, Patch-Updates, Bulk-Import sowie JSON-/CSV-Export.
- **Auth, RBAC & API-Tokens:** Session-Auth, OpenID Connect (OIDC/PKCE), Rollenmodell (`admin`/`editor`), Scoped Bearer API-Tokens (`api_tokens`), CSRF-Schutz & Rate-Limiter.
- **Eingebaute Infrastruktur:** TTL-Cache (`app_cache`), Background-Job-Queue (`app_jobs`), Feature-Flags & App-Settings, Server-Sent Events (SSE), AES-256-GCM Verschlüsselung, HMAC-Signatur, cURL HTTP-Client, signierte Webhooks (Senden & Verifizieren), Audit-Log & Health-Check.
- **Upload- & Medien-Pipeline:** Sichere Datei-Uploads mit MIME-Prüfung und automatischer WebP-Konvertierung & Skalierung.
- **Zero-Dependency Frontend SDK (`engine.js`):** Reaktiver State-Store, Hash-SPA-Router, Pub/Sub Event-Bus, SSE-Client, Hotkeys sowie UI-Komponenten (Command Palette `Cmd+K`, Drag-&-Drop Dropzone, Toast, Modal, Confirm, sortierbare Tabelle & Formular-Builder).
- **WARP CLI (`tools/warp.php`):** App-Generator (`new-app`), Cron-Runner (`jobs:run`), Cache-Wartung, Feature-Flags & Health-Check direkt im Terminal.

---

## Projektstruktur

```text
├── ENGINE.md              # Vollständige API- & Funktionsreferenz
├── LICENSE                # Gamet Studios WARP Engine Source-Available License
├── api/
│   ├── app.php            # Einheitlicher Engine- & App-API-Router
│   ├── oidc.php           # OpenID Connect (SSO) Endpunkt
│   └── submit.php         # Formular-Endpunkt
├── includes/
│   ├── engine.php         # WARP Engine Core-Kernel
│   ├── db.php             # SQLite Vault & Schema-Initialisierung
│   ├── auth.php           # Session-Auth & CSRF
│   ├── oidc.php           # OIDC/PKCE-Implementierung
│   └── ...                # Config, Discord, Integrität & Telemetrie
├── assets/
│   ├── css/               # Reset, Design-Tokens, Base & Utilities
│   └── js/core/engine.js  # Frontend App-Framework SDK (gsEngine / warp)
├── apps/
│   └── example-notes/     # Starter-Beispiel-App (UI + eigenes API-Modul)
├── storage/               # Geschütztes Verzeichnis für die SQLite-Datenbank
└── tools/
    ├── router.php         # Lokaler Dev-Router (inkl. Sicherheitsregeln)
    └── warp.php           # WARP CLI (App-Generator, Jobs, Flags, Health)
```

---

## Schnellstart (Lokal)

```bash
php -S localhost:8080 tools/router.php
```

Danach im Browser die Beispiel-App öffnen:  
**`http://localhost:8080/apps/example-notes/`**

### Eigene App anlegen

```bash
php tools/warp.php new-app mein-projekt "Mein Projekt"
php tools/warp.php health
```

1. Erstellt automatisch `apps/mein-projekt/` mit `manifest.json`, `index.html` und `api.php`.
2. Backend-Aktionen stehen automatisch unter `/api/app.php?app=<mein-slug>&action=...` bereit.
3. Im Frontend einfach das SDK importieren:

```js
import { warp } from '/assets/js/core/engine.js';
// (vollständig kompatibler Alias für `gsEngine`)

const info = await warp.auth();
const res = await warp.store.query('mein-ns', { page: 1, perPage: 20 });
```

---

## Dokumentation & Lizenz

- **Technische Referenz:** Siehe [`ENGINE.md`](ENGINE.md) für alle Backend-Funktionen, API-Endpunkte und Frontend-SDK-Methoden.
- **Lizenz:** Source-Available — siehe [`LICENSE`](LICENSE).  
  Eigene Apps unter `apps/` dürfen frei entwickelt werden; Änderungen am Engine-Kern sowie kommerzielle Weiterverbreitung bedürfen der Zustimmung von Gamet Studios.

Kontakt: `contact@gametstudios.media`
