# Phase 4: Polish i preparació VPS - Research

**Researched:** 2026-03-06
**Domain:** Flask UX polish, security hardening, gunicorn/nginx production deployment
**Confidence:** HIGH

## Summary

Phase 4 finalizes the InDret article corrector web app for production deployment. The app is already functionally complete (phases 1-3 done): Flask handles upload, calls the corrector, renders an HTML report, and offers file downloads. Phase 4 adds the missing production polish: a loading spinner, informative error messages, a favicon, VPS deployment documentation (gunicorn + nginx), and a security review of MIME validation, path traversal prevention, and file size limits.

The main technical challenge is the **spinner/loading state**: Flask uses traditional form POST (full-page redirect pattern). The corrector takes several seconds. A pure-JS approach — intercepting the submit event, showing a spinner overlay, then letting the form POST proceed normally — is the right fit. This requires zero npm/build tooling and is consistent with the project's NF1 constraint (no JS frameworks). HTMX would also work but adds an external dependency unnecessarily.

The gunicorn/nginx stack is mature and well-documented. The critical insight for this app is the **timeout setting**: the corrector can take 10-30 seconds per document. The default gunicorn timeout (30 seconds) is borderline; setting it to 120 seconds is safe. MIME validation with `python-magic` requires a C system library (`libmagic`), which creates a VPS dependency; `puremagic` (pure Python, no C deps) is a simpler alternative for the DOCX byte-check use case.

**Primary recommendation:** Use pure JS for the spinner (no external deps), `puremagic` for MIME validation (no C library required), gunicorn with `timeout = 120`, and nginx as reverse proxy with a standard Unix socket configuration.

---

## Standard Stack

### Core (already in use)
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| Flask | >=3.0 | Web framework | Already used, phases 1-3 complete |
| Werkzeug | >=3.0 | `secure_filename`, request size limit | Already used |
| gunicorn | 23.x | Production WSGI server | Standard Python production server |

### New for Phase 4
| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| puremagic | 1.28+ | Pure-Python MIME type detection from file bytes | VPS deploy without libmagic C dependency |
| gunicorn | 23.x | Production WSGI server (already noted above) | Replaces `python app.py` in production |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| puremagic | python-magic | python-magic needs `libmagic` C library on VPS (`apt install libmagic1`). More accurate but adds system dep. Use if VPS is controlled Debian/Ubuntu. |
| puremagic | filetype | filetype is focused on images/video, limited Office doc support |
| pure JS spinner | htmx | htmx is elegant but adds a CDN dependency; overkill for a single form |
| gunicorn sync worker | gunicorn gevent | gevent adds complexity; sync workers are fine for this low-concurrency use case |

**Installation (new deps):**
```bash
pip install puremagic gunicorn
```

Update `web/requirements.txt`:
```
Flask>=3.0
Werkzeug>=3.0
puremagic>=1.28
gunicorn>=23.0
```

---

## Architecture Patterns

### Current Project Structure
```
web/
├── app.py                   # Flask app (complete)
├── corrector.py             # InDretCorrector (complete)
├── requirements.txt         # Add puremagic + gunicorn
├── gunicorn.conf.py         # NEW: production config
├── README.md                # Update with deployment instructions
├── templates/
│   ├── index.html           # Add spinner JS + ARIA
│   └── resultat.html        # (complete, no changes needed)
├── static/
│   ├── style.css            # Add spinner CSS
│   └── favicon.ico          # NEW: favicon
├── resources/
│   └── plantilla.docx       # (existing)
└── uploads/                 # Temporary files (gitignored)
```

### Pattern 1: Pure JS Submit Spinner (No Build Tooling)

**What:** Intercept the form submit event, show a full-overlay or button-state spinner, then let the browser submit normally (no fetch/AJAX — the corrector produces a file download, which requires a real POST response, not JSON).

**When to use:** Traditional form POST where the response is a redirect (not JSON). Cannot use fetch() here because the corrector flow ends with a redirect to `/resultat/<sid>`.

**Why not AJAX/fetch:** The form POST triggers a redirect chain (`/corregir` → redirect → `/resultat/<sid>`). If we used fetch(), we'd need to handle the redirect manually and then navigate. The native form POST handles this transparently.

**Example (inline `<script>` at bottom of index.html):**
```javascript
// Source: MDN Web Docs - HTMLFormElement: submit event
document.querySelector('form').addEventListener('submit', function(e) {
  const btn = this.querySelector('button[type="submit"]');
  const overlay = document.getElementById('loading-overlay');
  btn.disabled = true;
  btn.textContent = 'Processant...';
  if (overlay) overlay.hidden = false;
  // Do NOT call e.preventDefault() — let the form POST proceed
});
```

**CSS spinner (add to style.css):**
```css
/* Loading overlay */
#loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(255, 255, 255, 0.85);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  z-index: 100;
  font-size: 1rem;
  color: #1a3a5c;
  font-weight: 600;
}

#loading-overlay[hidden] { display: none; }

.spinner {
  width: 2.5rem;
  height: 2.5rem;
  border: 3px solid #e0e0e0;
  border-top-color: #1a3a5c;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}
```

**HTML to add to index.html (outside the container div, before `</body>`):**
```html
<div id="loading-overlay" hidden aria-live="polite" role="status">
  <div class="spinner" aria-hidden="true"></div>
  <p>Processant el document, espereu...</p>
</div>
```

### Pattern 2: gunicorn.conf.py for Production

**What:** A Python config file that gunicorn reads by default from the working directory.

**Critical setting:** `timeout = 120` — the corrector can take 10-30 seconds; the default 30s timeout would kill long documents. Set to 120s to be safe.

**File: `web/gunicorn.conf.py`**
```python
# Source: https://gunicorn.org/reference/settings/
import multiprocessing

# Workers: 2 is sufficient for editorial team (low concurrency)
# Formula: (CPU cores * 2) + 1, capped at 4 for VPS with limited RAM
workers = 2

# Bind to Unix socket (nginx will proxy to this)
bind = "unix:/tmp/indret-corrector.sock"

# Timeout: corrector can take 10-30s, set to 120s to be safe
timeout = 120
graceful_timeout = 30
keepalive = 2

# Logging to stdout/stderr (systemd/journald picks up)
accesslog = "-"
errorlog = "-"

# Memory leak prevention (restart workers after N requests)
max_requests = 500
max_requests_jitter = 50

# Security: don't expose internal headers
forwarded_allow_ips = "127.0.0.1"
```

### Pattern 3: nginx Reverse Proxy Configuration

**What:** nginx sits in front of gunicorn, handles SSL termination (if applicable), static files, and proxies `/` to the Unix socket.

**File: `/etc/nginx/sites-available/indret-corrector`**
```nginx
server {
    listen 80;
    server_name _;  # Or: corrector.indret.com

    # Max upload size (matches Flask's 20MB limit)
    client_max_body_size 21M;

    # Proxy to gunicorn Unix socket
    location / {
        include proxy_params;
        proxy_pass http://unix:/tmp/indret-corrector.sock;
        proxy_read_timeout 130s;  # Slightly above gunicorn timeout
        proxy_connect_timeout 10s;
    }
}
```

Enable with:
```bash
sudo ln -s /etc/nginx/sites-available/indret-corrector /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

### Pattern 4: systemd Service for Gunicorn

**What:** Keeps gunicorn running and restarts it on crash.

**File: `/etc/systemd/system/indret-corrector.service`**
```ini
[Unit]
Description=InDret Corrector d'articles — Gunicorn
After=network.target

[Service]
User=www-data
Group=www-data
WorkingDirectory=/path/to/web
Environment="PATH=/path/to/venv/bin"
ExecStart=/path/to/venv/bin/gunicorn -c gunicorn.conf.py app:app
Restart=on-failure
RestartSec=5

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl enable indret-corrector
sudo systemctl start indret-corrector
sudo systemctl status indret-corrector
```

### Pattern 5: MIME Validation with puremagic

**What:** Verify the uploaded file is actually a DOCX (not a disguised script) by reading its magic bytes, not just checking the filename extension.

**How DOCX magic bytes work:** DOCX files are ZIP archives. Their magic bytes are `PK\x03\x04` (hex `50 4B 03 04`). puremagic reads these and returns a MIME type.

**Add to `app.py` after saving the file:**
```python
# Source: https://pypi.org/project/puremagic/
import puremagic

ALLOWED_MIME = {
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/zip',  # Some systems report docx as zip; acceptable
}

# After fitxer.save(input_path):
try:
    mime = puremagic.magic_file(str(input_path))[0].mime_type
except (IndexError, puremagic.MagicException):
    mime = ''

if mime not in ALLOWED_MIME:
    input_path.unlink(missing_ok=True)
    flash('El fitxer no és un document .docx vàlid.')
    return redirect(url_for('index'))
```

**Note:** DOCX files are ZIP containers. puremagic may return `application/zip` for some valid DOCX files because the ZIP magic bytes come first. Accepting both `application/zip` AND the full DOCX MIME type is the correct approach. You can add a secondary check: open the zip and verify `word/document.xml` exists inside.

### Anti-Patterns to Avoid

- **Checking MIME from `request.files.mimetype`:** The browser-reported MIME type can be spoofed. Always validate on the server after saving, from the actual file bytes.
- **Using `e.preventDefault()` before form POST:** If you call `e.preventDefault()` in the submit handler, the form never submits. The spinner approach must let the form submit proceed.
- **gunicorn default timeout (30s) with a slow corrector:** The corrector can take 20+ seconds on large documents. Default 30s will kill workers mid-processing, producing a 502.
- **Binding gunicorn to 0.0.0.0:8000 without nginx:** Exposes gunicorn directly; nginx should be the public-facing server.
- **Running gunicorn as root:** Creates security risk if the app has any vulnerability.

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| File MIME detection | Byte-inspection logic | puremagic | Magic number databases are maintained by the library; hand-rolled byte checks miss edge cases |
| Spinner animation | Custom CSS animation from scratch | CSS `@keyframes` + inline `<script>` (already in style.css) | Just add `.spinner` class — the project's style.css already has the infrastructure |
| Process management | Shell scripts to restart gunicorn | systemd service | systemd handles crashes, boot startup, and log aggregation natively |
| WSGI serving | Custom HTTP server | gunicorn | Security, worker management, signals are complex to get right |

**Key insight:** For this project's scale (internal editorial tool, low concurrency), simple solutions are correct solutions. No need for Redis queues, Celery, or async workers.

---

## Common Pitfalls

### Pitfall 1: Spinner Stays on Screen After Browser Back Button

**What goes wrong:** User submits form, spinner appears, navigates back with browser back button. The spinner HTML is now `hidden=false` in the cached page — spinner shows immediately, form is non-functional.
**Why it happens:** Browser page cache (bfcache) restores the DOM state including JS-modified elements.
**How to avoid:** Reset spinner state on `pageshow` event (fires on bfcache restore):
```javascript
window.addEventListener('pageshow', function(e) {
  if (e.persisted) {
    document.getElementById('loading-overlay').hidden = true;
    document.querySelector('button[type="submit"]').disabled = false;
  }
});
```
**Warning signs:** QA testers who use the back button will hit this.

### Pitfall 2: gunicorn Worker Killed Mid-Request (502 to User)

**What goes wrong:** User uploads a large/complex document. gunicorn worker is killed after 30 seconds (default timeout). nginx returns 502 Bad Gateway.
**Why it happens:** gunicorn's default `timeout = 30` kills workers that don't respond within 30 seconds. spaCy NER + python-docx processing on complex articles can exceed this.
**How to avoid:** Set `timeout = 120` in `gunicorn.conf.py`. Also set `proxy_read_timeout 130s` in nginx (must be slightly higher than gunicorn timeout).
**Warning signs:** 502 errors on larger documents but not smaller ones.

### Pitfall 3: nginx `client_max_body_size` Rejects Files Before Flask Can

**What goes wrong:** User uploads a 15MB file. nginx rejects it with 413 (Request Entity Too Large) before Flask even sees it. Flask's `MAX_CONTENT_LENGTH` and the `RequestEntityTooLarge` handler never fire.
**Why it happens:** nginx has its own upload size limit, default 1MB, independent of Flask's setting.
**How to avoid:** Set `client_max_body_size 21M;` in the nginx server block (slightly above Flask's 20MB limit).
**Warning signs:** 413 errors from nginx, not Flask.

### Pitfall 4: puremagic Returns `application/zip` for Valid DOCX

**What goes wrong:** MIME validation rejects valid DOCX files because puremagic returns `application/zip` (DOCX is a ZIP format).
**Why it happens:** puremagic reads the ZIP magic bytes `PK\x03\x04` and may not drill into the ZIP structure to identify it as DOCX.
**How to avoid:** Accept both `application/vnd.openxmlformats-officedocument.wordprocessingml.document` and `application/zip` in `ALLOWED_MIME`. Optionally add a secondary check: open as ZIP and verify `word/document.xml` exists.
**Warning signs:** Editors report valid DOCX files being rejected.

### Pitfall 5: Favicon 404 Floods Logs

**What goes wrong:** Browser requests `/favicon.ico` on every page load. Without a favicon, this generates a 404 on every request, cluttering logs.
**How to avoid:** Add `web/static/favicon.ico`. In Flask, serve it explicitly or place it in `static/` and add a `<link rel="icon">` tag to the HTML templates. The simplest approach: add to `<head>`:
```html
<link rel="icon" href="{{ url_for('static', filename='favicon.ico') }}" type="image/x-icon">
```

### Pitfall 6: Path Traversal via Filename (Already Mitigated)

**Status:** Already handled in the existing code — `secure_filename()` is called and a UUID prefix is used (`{sid}_input_{nom_segur}`). The uploads directory is also not web-accessible. **No action needed for path traversal — verify this is in place during security review.**

---

## Code Examples

Verified patterns from official sources:

### Favicon in Flask Template
```html
<!-- Source: Flask docs - Static Files -->
<link rel="icon" href="{{ url_for('static', filename='favicon.ico') }}" type="image/x-icon">
```
Place in `<head>` of both `index.html` and `resultat.html`.

### Flask MAX_CONTENT_LENGTH (already in app.py — verify)
```python
# Source: Flask docs - MAX_CONTENT_LENGTH
app.config['MAX_CONTENT_LENGTH'] = 20 * 1024 * 1024  # 20MB
# Already set in app.py — confirm during security review
```

### puremagic DOCX Validation
```python
# Source: https://pypi.org/project/puremagic/
import puremagic

ALLOWED_MIME = {
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/zip',  # DOCX is a ZIP container; some systems report this
}

def validar_mime(path: Path) -> bool:
    """Returns True if file appears to be a DOCX based on magic bytes."""
    try:
        matches = puremagic.magic_file(str(path))
        if not matches:
            return False
        return matches[0].mime_type in ALLOWED_MIME
    except puremagic.MagicException:
        return False
```

### gunicorn.conf.py (canonical for this project)
```python
# Source: https://gunicorn.org/reference/settings/
import multiprocessing

workers = 2               # Adequate for low-concurrency editorial use
bind = "unix:/tmp/indret-corrector.sock"
timeout = 120             # Corrector can take 10-30s; default 30 would kill it
graceful_timeout = 30
keepalive = 2
accesslog = "-"
errorlog = "-"
max_requests = 500
max_requests_jitter = 50
forwarded_allow_ips = "127.0.0.1"
```

### JavaScript: Reset spinner on bfcache restore
```javascript
// Source: MDN Web Docs - pageshow event
window.addEventListener('pageshow', function(e) {
  if (e.persisted) {
    document.getElementById('loading-overlay').hidden = true;
    const btn = document.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = false;
      btn.textContent = 'Corregir article';
    }
  }
});
```

---

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| `gunicorn --workers 2 app:app` on CLI | `gunicorn.conf.py` config file | Always available, now standard | Reproducible, version-controllable configuration |
| `python-magic` (C library dep) | `puremagic` (pure Python) | puremagic mature since 2015 | No system library needed on VPS |
| Full-page polling for progress | Spinner overlay + native form POST | N/A for this project's scale | Simple and robust for non-concurrent use |

**Deprecated/outdated:**
- `flask.helpers.send_from_directory`: Prefer `flask.send_file` (already in use in app.py).
- gunicorn `config` parameter pointing to a module string requires `python:` prefix as of version 19.4 — use file path or the default `gunicorn.conf.py` discovery instead.

---

## Open Questions

1. **Does the VPS have a system package manager available?**
   - What we know: The project targets a generic VPS (undetermined OS).
   - What's unclear: Whether `apt install libmagic1` is feasible. If yes, `python-magic` gives more accurate MIME detection. If no, `puremagic` (pure Python) is the safe choice.
   - Recommendation: Default to `puremagic` in the plan; note in README that `python-magic` is an alternative if libmagic is available.

2. **Will the VPS serve over HTTPS?**
   - What we know: Out of scope per REQUIREMENTS.md ("Out of Scope: autenticació i control d'accés"). No SSL mentioned.
   - What's unclear: Whether nginx should be configured for SSL termination or HTTP-only.
   - Recommendation: Plan for HTTP-only nginx config. Add a note in README about Let's Encrypt/Certbot for future SSL if needed.

3. **What is the target VPS username and path?**
   - What we know: Unknown — the README will use placeholder paths.
   - Recommendation: Use `/opt/indret-corrector/web/` as the canonical example path in the systemd service and nginx config. Editors can adapt.

4. **Favicon design**
   - What we know: The project should have a favicon with title "InDret — Corrector d'articles" (already in index.html `<title>`).
   - What's unclear: Whether to generate a minimal ICO file (a simple blue square matching `#1a3a5c`) or use an existing InDret logo.
   - Recommendation: Generate a minimal 16x16 favicon.ico as a plain `#1a3a5c` blue square — no external tools needed, can be done in Python with Pillow or as a static asset committed directly.

---

## Sources

### Primary (HIGH confidence)
- [https://gunicorn.org/reference/settings/](https://gunicorn.org/reference/settings/) — timeout, workers, bind, config file format
- [https://flask.palletsprojects.com/en/stable/deploying/gunicorn/](https://flask.palletsprojects.com/en/stable/deploying/gunicorn/) — Flask-specific gunicorn documentation
- [https://pypi.org/project/puremagic/](https://pypi.org/project/puremagic/) — puremagic pure-Python MIME detection
- MDN Web Docs — `pageshow` event, form submit event

### Secondary (MEDIUM confidence)
- [https://betterstack.com/community/guides/scaling-python/gunicorn-explained/](https://betterstack.com/community/guides/scaling-python/gunicorn-explained/) — worker count recommendations, config structure
- [https://www.hackerone.com/blog/secure-file-uploads-flask-filtering-and-validation-techniques](https://www.hackerone.com/blog/secure-file-uploads-flask-filtering-and-validation-techniques) — multi-layer security for Flask file uploads
- [https://www.digitalocean.com/community/tutorials/how-to-serve-flask-applications-with-gunicorn-and-nginx-on-ubuntu-22-04](https://www.digitalocean.com/community/tutorials/how-to-serve-flask-applications-with-gunicorn-and-nginx-on-ubuntu-22-04) — nginx + gunicorn + systemd setup

### Tertiary (LOW confidence)
- Various WebSearch results on spinner patterns — verified against MDN patterns

---

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH — gunicorn and puremagic are well-documented, mature libraries with official PyPI pages verified
- Architecture (spinner): HIGH — pure JS form submit pattern is standard, bfcache pitfall is documented in MDN
- Architecture (gunicorn/nginx): HIGH — DigitalOcean tutorial and official gunicorn docs cross-referenced
- Security (MIME): MEDIUM — puremagic returning `application/zip` for DOCX is a known behavior; recommendation to accept both MIME types is based on DOCX format specification (ZIP container)
- Pitfalls: HIGH — gunicorn timeout and nginx `client_max_body_size` are well-documented production gotchas

**Research date:** 2026-03-06
**Valid until:** 2026-06-06 (stable libraries, 90-day validity)
