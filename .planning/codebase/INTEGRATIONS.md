# External Integrations

**Analysis Date:** 2026-03-05

## APIs & External Services

**None active.** The corrector is a fully offline, local CLI tool. No network calls are made during execution.

**Considered / Planned (not implemented):**
- Gemini API (Google) — noted in `articles_correc/indret-corrector.md` as a possible future optional layer for semantic tasks (surname detection, bibliography classification). The user holds a Gemini API key. No SDK is currently installed or imported.
- Claude API (Anthropic) — mentioned in `articles_correc/plan.md` as an option; rejected in favour of offline approach.

## Data Storage

**Databases:**
- None. The corrector reads and writes `.docx` files on the local filesystem only.

**File Storage:**
- Local filesystem only
- Input: any `.docx` file supplied as CLI argument
- Output: `<basename>_corregit.docx` and `<basename>_informe.md` written to the same directory as the input file

**Caching:**
- None. Each run is stateless and processes one document from scratch.

## Authentication & Identity

**Auth Provider:**
- None. No authentication required. The tool is a local CLI utility.

## Monitoring & Observability

**Error Tracking:**
- None. Errors surface via Python exceptions to the terminal.

**Logs:**
- Inline console output via `print()` showing phase progress (e.g., `[1/5] Text cleanup`, `[2/5] Styles`)
- A Markdown report (`<basename>_informe.md`) is generated per run documenting applied changes and warnings; this is the primary audit trail.

## CI/CD & Deployment

**Hosting:**
- Current: local macOS machine (developer/editor workstation)
- Planned: web server integration at `indret.com/formulario/` (not yet implemented)

**CI Pipeline:**
- None. No automated tests, no CI configuration.

## Environment Configuration

**Required env vars:**
- None. All runtime configuration is passed as CLI flags.

**Secrets location:**
- None required for current operation.

## Webhooks & Callbacks

**Incoming:**
- None currently. Future plan: trigger corrector from the article submission form at `indret.com/formulario/`.

**Outgoing:**
- None.

## WordPress Theme (context only)

The `articles_correc/` Python project lives inside a WordPress theme directory (`wp-content/themes/indret/`). The WordPress theme itself is a separate system:
- Theme serves the InDret journal website at `indret.com`
- Theme uses PHP + MySQL (standard WordPress stack)
- The Python corrector has no integration with WordPress — it operates entirely independently on the filesystem

---

*Integration audit: 2026-03-05*
