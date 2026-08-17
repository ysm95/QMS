# QMS Implementation Status

This Laravel prototype is aligned to the BRSD in `docs/QMS_ysaidea_BRSD_and_Architecture_Blueprint.md`.

Implemented in the current prototype:

- Laravel QMS application shell
- QMS dashboard route at `/dashboard`
- QMS prototype Blade UI using the reference screens
- QMS occurrence submission endpoint
- SQLite prototype database
- QMS occurrence, action, investigation, audit, risk, and document models
- Migrations and seed data
- Seeded demo users:
  - `admin@qms.test` / `password`
  - `yahya.alnaaimi@qms.test` / `Yahya@2026`
  - `mazin.alfarsi@qms.test` / `Mazin@2026`
  - `aisha.albalushi@qms.test` / `Dummy@2026`
  - `omar.alharthy@qms.test` / `Dummy@2026`
- Hostinger VPS deployment script template in `deploy/hostinger_publish_qms.sh`

Important limitation:

This is a first Laravel prototype, not the full enterprise QMS described in the BRSD. Authentication scaffolding, advanced authorization, workflow designer, form designer, AI governance, Microsoft Entra integration, and production hardening are still roadmap items.
