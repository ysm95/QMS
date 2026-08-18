# QMS.ysaidea.com Enterprise BRSD and Architecture Specification v2.0

Date: 2026-08-18  
Target URL: https://qms.ysaidea.com  
Platform: Laravel modular monolith, enterprise-ready architecture  
Confidentiality note: user-provided screenshots and sample files are confidential reference material only. They must not be embedded, copied, published, committed, or exposed in the application.

## 1. Executive Summary

QMS.ysaidea.com will replace the existing Miniworld application with a modern integrated Quality, Safety, HSE, Risk, Audit, Compliance, Investigation, CAPA, Document Control, Workflow, Reporting, Analytics, and AI-enabled platform for aviation and enterprise governance.

The platform will be a modular Laravel product. Core engines such as forms, workflow, permissions, notifications, numbering, audit trail, attachments, reporting, analytics, AI gateway, record graph, and configuration governance will be reused across business modules. This avoids duplicated logic and allows modules to be licensed, enabled, disabled, or expanded independently.

The product goal is not to copy Ideagen, Intelex, ETQ, Centrik, Cority, Enablon, MasterControl, SafetyCulture, Sphera, or similar systems. The goal is to adopt the strongest enterprise QMS/SMS/EHSQ patterns and improve them with a cleaner task-focused UX, better aviation fit, stronger configuration governance, permission-safe AI, lower normal-page load, and modern Laravel maintainability.

## 2. Business Background

Current enterprise QMS/SMS tools are often powerful but difficult to use. Common pain points include dense administration screens, fragmented modules, weak task visibility, overloaded reporting forms, slow workflows, inconsistent notification logic, limited offline reporting, and poor traceability between requirements, manuals, audits, findings, risk controls, actions, training, suppliers, and management review decisions.

The new platform should support aviation safety and quality environments where occurrence reporting, Just Culture, confidential reporting, risk assessment, CAPA, audit evidence, regulatory traceability, controlled documents, competency, and management oversight must work as one system.

## 3. Problem Statement

The organization needs a modern replacement for Miniworld that can:

- reduce reporting friction for frontline users;
- improve action accountability and closure quality;
- provide configurable forms, workflows, notifications, reports, numbering and permissions;
- support aviation SMS, QMS, HSE, audit, compliance and risk operations;
- provide traceability across records and evidence;
- support secure AI assistance without exposing restricted data;
- operate with production-grade auditability, security, backups and deployment controls.

## 4. Project Vision

Create a configurable enterprise platform where authorized administrators can define:

Form -> Access -> Review -> Acceptance -> Investigation -> Recommendations -> Actions -> Acceptance -> Monitoring -> Escalation -> Verification -> Effectiveness Review -> Closure

while normal users receive a simple, fast, mobile-friendly experience focused on completing their task.

## 5. Project Objectives

| ID | Objective |
|---|---|
| OBJ-001 | Replace Miniworld safely with QMS.ysaidea.com using backup and rollback controls. |
| OBJ-002 | Provide a modern modular QMS/SMS/HSE/Risk/Audit/Compliance platform. |
| OBJ-003 | Enable no-code/low-code administration for forms, workflows, reports, notifications and dashboards. |
| OBJ-004 | Implement granular RBAC plus contextual access scopes. |
| OBJ-005 | Provide task-centric UX: My Work, dashboards, timelines, record workspaces and quick reporting. |
| OBJ-006 | Maintain immutable audit trail and historical version integrity. |
| OBJ-007 | Make AI optional, governed, permission-aware, cited and human-approved. |
| OBJ-008 | Prepare for Arabic/English, Microsoft Entra ID, API integrations and mobile/offline operation. |

## 6. Business Scope

In scope:

- Core platform engines.
- QMS/SMS/HSE occurrence and incident management.
- NCR, CAPA, investigation, recommendations, actions.
- Risk, audits, checklists, compliance obligations, documents.
- Training, competency, qualifications and currency.
- Supplier qualification, SCAR and scorecards.
- Management review, governance meetings and MOC.
- BI, dashboards, reports, exports and global search.
- Notification center and automation.
- AI gateway with governance.
- Module licensing and system administration.
- Secure deployment replacing Miniworld.

Out of scope for first production release:

- Autonomous AI approval, closure, disciplinary decisions, or risk acceptance.
- Copying proprietary competitor UI or content.
- Destructive Miniworld replacement before verified backup and rollback.
- Unsupported external regulatory-content scraping.

## 7. Stakeholders and Personas

| Persona | Primary Needs |
|---|---|
| Frontline Reporter | Submit quick report, attach evidence, see permitted feedback. |
| Action User | Accept actions, upload evidence, request extension, close assigned tasks. |
| Investigator | Manage evidence, interviews, findings, causal analysis, recommendations. |
| Key User | Monitor scoped incidents/actions, review, recommend, assign, escalate. |
| Department Manager | See department risks/actions, approve/accept, manage overdue work. |
| Auditor | Plan audits, execute checklists, create findings/actions, report closure. |
| Document Owner | Draft/revise/approve/distribute controlled documents. |
| Training Owner | Manage courses, requirements, expiry, competency gaps. |
| Supplier/Contractor | Access restricted assigned audits, NCRs, SCAR and evidence upload. |
| Executive | See safety/quality/risk trends, serious issues, SPIs/KPIs and assurance status. |
| Super Administrator | Configure platform, security, licensing, builders, workflows and integrations. |

## 8. Core Platform Engines

| Engine | Purpose |
|---|---|
| Identity and Authorization Engine | Users, roles, groups, contextual scopes, field masking. |
| Licensing and Feature Flag Engine | Module licensing, expiry, user/storage/AI limits. |
| Form/Schema Engine | Dynamic forms, versions, components, conditions, access rules. |
| Workflow/State Engine | Workflow versions, tasks, SLA, approvals, branching, escalation. |
| Rule Engine | Safe visual conditions for workflows and notifications. |
| Numbering Engine | Configurable IDs by module/org/year/department. |
| Action/Task Engine | Unified assignment, acceptance, due dates, evidence and closure. |
| Approval/Signature Engine | Controlled approvals and e-signatures. |
| Notification Engine | Templates, rules, recipient resolution, channels, deduplication, delivery logs. |
| Attachment/File Engine | Secure files, evidence metadata, scanning readiness, retention. |
| Audit-Trail Engine | Immutable old/new values, reasons, exports and sensitive access. |
| Reporting/Template Engine | PDF/Word/Excel/CSV templates, queued exports. |
| Analytics Metric Engine | KPI/SPI catalog, formulas, dashboards and drill-down security. |
| Search Engine | Permission-filtered global search and saved views. |
| Compliance Obligation Engine | Requirement-to-control-to-evidence traceability. |
| Configuration Governance Engine | Draft/publish/rollback/import/export/environment promotion. |
| Record Graph Engine | Cross-module relationships and permission-safe graph view. |
| AI Gateway | Approved provider abstraction, masking, citations, human review gates. |
| Integration/Event Engine | Outbox, idempotency, retries, replay, dead-letter monitoring. |

## 9. Core Business Modules

| Category | Modules |
|---|---|
| Core business modules | Occurrences, incidents, NCR, CAPA/actions, investigations, recommendations, risk, audits, documents, compliance obligations, notifications, reports, dashboards. |
| Optional licensed modules | HSE advanced modules, supplier SCAR/scorecards, equipment/calibration, MOC, training/competency, management meetings, offline mobile, AI, BI advanced analytics. |
| Aviation-specific extensions | Flight safety, ground safety, aircraft registration, route/sector, stations, mandatory/voluntary/confidential reporting, Just Culture, SPI/SPT, IOSA/ICAO-aligned traceability. |
| HSE extensions | Permit to Work, JSA/JHA, LOTO, contractor safety, PPE, chemical/SDS, occupational exposure, environmental events, emergency drills. |
| Future integrations | Microsoft Entra ID, HR, ERP, flight systems, airport systems, data warehouse, Power BI, Teams, SMS, WhatsApp Business, regulatory-content provider. |

## 10. Competitive Benchmark Feature Matrix

| Capability | Benchmark Pattern | Decision for QMS.ysaidea.com |
|---|---|---|
| Configurable app/process designer | ETQ and Intelex emphasize no-code app/configuration builders. | Adopt with Laravel-owned schema/versioning and approved visual builder adapters. |
| Mobile/offline field capture | ETQ and Cority promote mobile/offline capture. | Improve with offline-first sync audit, conflict handling and permission revalidation. |
| Unified EHSQ platform | Intelex, Cority and Enablon emphasize unified platform breadth. | Adopt as modular monolith, avoid fragmented modules. |
| Advanced analytics/AI | ETQ, Cority and Enablon emphasize AI-assisted analytics. | Improve with controlled paid AI, source citations, permission-filtered retrieval and human approval. |
| Regulatory-content gateways | Intelex and Enablon include regulatory intelligence concepts. | Adapt for aviation; integrate licensed sources only with licensing compliance. |
| CAPA/root-cause/effectiveness | ETQ/MasterControl/Intelex emphasize closed-loop CAPA. | Adopt and strengthen with action acceptance, SMART checks and recurrence monitoring. |
| Document control/training linkage | MasterControl/ETQ pattern. | Adopt, but translate from life-science validation to aviation QMS/SMS needs. |
| Audit/inspection mobile execution | Cority/Intelex pattern. | Adopt for audit checklist designer and offline evidence capture. |
| Supplier quality/SCAR | ETQ/Intelex/MasterControl pattern. | Adopt as optional licensed module. |
| Complex life-science validation package | MasterControl/ETQ life-science-heavy control. | Optional; reject as default unless regulated customer requires it. |
| Dense legacy admin tree UI | Q-Pulse-style administration pattern. | Improve: preserve capability, replace with searchable modern control center. |
| Traditional large forms | Common in older systems. | Improve with quick/full report modes and progressive disclosure. |
| AI without governance | Industry risk highlighted by Cority research on shadow AI. | Reject; AI must be governed, logged and permission-aware. |

Benchmark sources used include ETQ/Octave, Intelex, Cority, Enablon, MasterControl and public feature references listed at the end of this document.

## 11. Requirement Matrix

| ID | Module | Requirement | Reason | Priority | Dependencies | Acceptance Criteria |
|---|---|---|---|---|---|---|
| BR-001 | Platform | Replace Miniworld with QMS.ysaidea.com using verified backup and rollback. | Protect business continuity. | Critical | Deployment plan | Backups verified before switch; rollback tested. |
| FR-001 | Forms | Admin can build dynamic form templates with components, validation, conditions and permissions. | No-code reporting configuration. | Critical | Form engine | Published form version renders and submits correctly. |
| FR-002 | Forms | Published form versions are immutable for historical submissions. | Compliance traceability. | Critical | Version model | Editing a published form creates a new draft version. |
| FR-003 | Workflow | Admin can configure workflow stages, branching, SLA, assignments and escalation. | Flexible process governance. | Critical | Workflow engine | Record follows assigned workflow version. |
| FR-004 | Notifications | Email design, notification template and notification rule are separate concepts. | Maintainability and governance. | Critical | Notification engine | Admin can create all three independently. |
| FR-005 | Permissions | System supports RBAC plus contextual scopes. | Least privilege. | Critical | Authorization engine | User access differs by own/assigned/department/all scope. |
| FR-006 | Occurrences | Users can submit mandatory, voluntary, confidential and anonymous reports. | Reporting culture. | Critical | Forms, permissions | Confidential reporter identity is masked by default. |
| FR-007 | CAPA | Actions require assignment, acceptance, progress, evidence, verification and effectiveness review where configured. | Accountability. | Critical | Action engine | Status changes record timestamps and audit log. |
| FR-008 | Investigation | Investigations include team, evidence, findings, root cause, recommendations and action linkage. | Defensible investigation. | High | Record graph | Recommendation links to source incident and actions. |
| FR-009 | Risk | Admin can configure risk matrices, ratings, thresholds and acceptance authority. | Avoid hard-coded risk model. | High | Risk engine | Multiple matrices can coexist by module/org. |
| FR-010 | Audit | Audits support programs, plans, checklists, findings, evidence and CAPA. | Assurance coverage. | High | Audit engine | Finding can create action and link evidence. |
| FR-011 | Compliance | Obligations trace to internal controls, documents, evidence, audits, risks and actions. | Regulatory readiness. | High | Compliance engine | Requirement traceability chain is visible. |
| FR-012 | Documents | Document control supports revisions, approval, distribution and read-understood tracking. | Controlled information. | High | Workflow, signatures | Approved revision cannot be silently changed. |
| FR-013 | Training | Training requirements link to roles, documents, changes, expiry and competency. | Operational readiness. | High | Training engine | Dashboard shows due/overdue qualifications. |
| FR-014 | Suppliers | Supplier lifecycle supports qualification, risk tier, certificates, audits, SCAR and scorecards. | Supplier assurance. | Medium | Supplier module | Supplier can see only assigned external scope. |
| FR-015 | MOC | Changes include impact assessment, approval, implementation, training/document gates and PIR. | Change risk control. | High | Workflow, risk, docs | Change cannot close until required gates complete. |
| FR-016 | BI | Dashboards are role/scoped and support drill-down subject to record permissions. | Trusted analytics. | High | Analytics, permissions | Drill-down never leaks restricted records. |
| FR-017 | Search | Global search returns only permitted records. | Findability with security. | High | Search, policies | Unauthorized records absent from results. |
| FR-018 | API | Provide `/api/v1` architecture with auth scopes, idempotency and audit logging. | Integration readiness. | Medium | Sanctum/API | API docs and rate limits exist. |
| SEC-001 | Security | Enforce least privilege, CSRF/XSS/SQL injection protections and secure password storage. | Baseline security. | Critical | Laravel security | Security tests cover core workflows. |
| SEC-002 | Security | Sensitive fields support field-level masking and enhanced access logging. | Confidentiality. | Critical | Authorization | Confidential reporter identity restricted. |
| SEC-003 | Security | Sensitive approvals support re-authentication/e-signature where configured. | Decision integrity. | High | Signature engine | Signature includes signer, meaning, time, record version. |
| AI-001 | AI | AI is disabled until approved provider, data controls and governance are configured. | Avoid unsafe AI. | Critical | AI gateway | AI request blocked without approved provider. |
| AI-002 | AI | AI retrieval is permission-filtered before context is provided. | Prevent leakage. | Critical | Search, policies | AI cannot cite unauthorized records. |
| UX-001 | UX | Frontline reports support quick/full modes, mobile layout and progressive disclosure. | Reduce reporting friction. | Critical | Form renderer | A report can be submitted quickly on mobile. |
| UX-002 | UX | My Work consolidates assigned reviews/actions/approvals/investigations/training. | Task efficiency. | High | Task engine | User can see all assigned work in one place. |
| NFR-001 | Performance | Normal pages avoid heavy builder JS; builders lazy-load only in admin design screens. | Low server/client load. | Critical | Build strategy | Dashboard bundle excludes visual builder libraries. |
| NFR-002 | Performance | Use pagination, indexes, caching and queued exports. | Scalability. | High | Database/queue | Large export runs in background. |
| INT-001 | Integrations | Microsoft Entra ID integration supports SSO, sync, group mapping and fallback local auth. | Enterprise identity. | High | Identity engine | Users can log in via Entra when enabled. |
| DEP-001 | Deployment | Use release-directory/blue-green style deployment if supported. | Safe replacement. | Critical | VPS setup | Domain switch happens after health checks. |

## 12. Role and Permission Matrix

| Module | Reporter | Action User | Key User | Investigator | Auditor | Manager | Executive | Admin | Super Admin |
|---|---|---|---|---|---|---|---|---|---|
| Occurrences | Create, view own | View assigned | View scoped, review, recommend | View assigned | View audit scope | View department, approve | View dashboards | Configure module | All |
| Actions | View own source | Accept, update assigned | Assign, monitor scoped | Create linked | Create from findings | Verify/approve scoped | View summaries | Configure action rules | All |
| Investigations | View permitted feedback | Contribute evidence if assigned | Monitor scoped | Lead/team work | Audit evidence | Approve scoped | Summary | Configure workflows | All |
| Audits | Respond if auditee | Complete assigned action | Monitor scoped | View linked | Plan/execute | Approve response | Summary | Configure checklist | All |
| Documents | Read assigned | Acknowledge | Monitor scoped | Link evidence | Audit docs | Approve scoped | Summary | Configure document control | All |
| Risk | Report hazard | Own controls | Review scoped risks | Link findings | Audit controls | Accept per authority | Heatmap | Configure matrices | All |
| Reports/Exports | Own permitted | Assigned permitted | Scoped export | Scoped export | Audit export | Department export | Executive export | Configure reports | All |
| Administration | None | None | Limited scoped settings | None | None | Department user view | None | Manage assigned areas | Global configure |

Detailed permissions use names such as:

- `occurrences.view.own`
- `occurrences.view.assigned`
- `occurrences.view.department`
- `occurrences.view.all`
- `occurrences.create`
- `occurrences.review`
- `occurrences.accept`
- `occurrences.assign`
- `occurrences.investigate`
- `recommendations.create`
- `recommendations.accept`
- `actions.accept`
- `actions.submit_evidence`
- `actions.verify`
- `actions.close`
- `reports.export`
- `forms.publish`
- `workflows.publish`
- `notifications.manage`
- `admin.security.manage`

Access scopes:

- OWN
- ASSIGNED
- DEPARTMENT
- BUSINESS_UNIT
- LOCATION
- ORGANIZATION
- SPECIFIC_GROUP
- ALL

## 13. Workflow Architecture

Workflow definitions are versioned. A record starts a workflow instance referencing a specific workflow version. In-flight records remain on their version unless an authorized migration is executed and audited.

Workflow engine entities:

- workflow_definitions
- workflow_versions
- workflow_steps
- workflow_transitions
- workflow_instances
- workflow_tasks
- workflow_sla_rules
- workflow_delegations
- workflow_reassignment_history
- workflow_failures

Capabilities:

- sequential approvals;
- parallel approvals;
- configurable quorum;
- return/reject/reopen;
- SLA timers;
- business calendars;
- pause/resume;
- delegation and substitutes;
- separation-of-duties;
- idempotent automated steps;
- retry/failure handling;
- visual timeline per record.

## 14. Database ERD and Schema Proposal

Core schema groups:

```text
organizations
departments
business_units
locations
stations
users
roles
permissions
permission_groups
permission_templates
access_scopes
key_user_assignments

licensed_modules
licenses
feature_flags

forms
form_versions
form_sections
form_fields
form_conditions
form_access_rules
form_workflow_links
form_notification_links
form_submissions
form_submission_values

numbering_rules
numbering_sequences

workflow_definitions
workflow_versions
workflow_steps
workflow_transitions
workflow_instances
workflow_tasks
workflow_sla_rules

occurrences
incident_assignments
incident_classifications
incident_recommendations
findings
root_causes

actions
action_assignments
action_extensions
action_evidence
action_verifications
action_dependencies

investigations
investigation_members
investigation_evidence
interviews
investigation_decisions

risks
risk_assessments
risk_matrices
hazards
barriers
barrier_assurance_records

audits
audit_programs
audit_plans
audit_checklists
audit_findings
evidence_requests

non_conformances
quality_events

documents
document_revisions
document_distribution
document_acknowledgements
document_change_impacts

compliance_obligations
compliance_requirements
compliance_mappings
regulatory_changes

change_requests
change_impacts

training_courses
training_requirements
competencies
qualifications
user_qualifications
training_assignments

equipment
equipment_types
calibrations
maintenance_records

suppliers
supplier_qualifications
supplier_scorecards
supplier_scars

management_reviews
meetings
meeting_decisions

email_designs
email_design_versions
notification_templates
notification_template_versions
notification_rules
notification_groups
notification_deliveries

attachments
comments
mentions
approvals
electronic_signatures
record_links
tags
saved_views
dashboard_definitions
metric_definitions

audit_logs
sensitive_access_logs
exports
retention_rules
legal_holds

ai_providers
ai_prompt_versions
ai_interactions
ai_feedback

api_clients
integration_events
outbox_events
webhook_subscriptions
sync_batches
sync_conflicts
configuration_packages
configuration_versions
```

Design rules:

- Use foreign keys and composite indexes on module, status, owner, due date, workflow state and scope fields.
- Use soft deletes only for operational convenience; critical records use cancellation/supersession/archive.
- Store immutable snapshots for published form, workflow, report, email and notification versions.
- Store typed searchable submission values for reporting instead of only JSON.
- Add `organization_id` where practical for future multi-organization readiness.

## 15. Laravel Application Architecture

Initial architecture: modular monolith.

Recommended layers:

- HTTP controllers: thin request/response only.
- Form Requests: validation.
- Services/Actions: business operations.
- Policies/Gates: authorization.
- Domain Events: cross-module triggers.
- Listeners/Jobs: notifications, exports, AI jobs, sync and integration side effects.
- Models: Eloquent persistence and relationships.
- View Components/Blade/Livewire: UI.
- DTOs where they clarify complex form/workflow/rule payloads.
- Repositories only where real storage abstraction is needed.

Recommended packages, subject to install-time compatibility check:

- Laravel 13 first-party auth, queues, notifications, mail, policies.
- Spatie Laravel Permission for roles/permissions with teams/wildcard support.
- Livewire 4 for interactive admin builders and matrix screens.
- Filament 5 selectively for admin CRUD, not as the entire user experience.
- SurveyJS Creator for visual Form Designer if license approved.
- Unlayer enterprise/on-prem for visual Email Designer if security/licensing approved.
- Maizzle/MJML for portable controlled email rendering fallback.
- Laravel Horizon with Redis in production.
- Laravel Reverb for realtime notifications when needed.
- Laravel Sanctum for `/api/v1` and future mobile/API integrations.

## 16. UI/UX Design System

Principles:

- Task-first, not database-first.
- Simple for regular users, powerful for administrators.
- Avoid dense legacy admin clutter.
- Preserve data-heavy efficiency with modern tables.
- Mobile and tablet first for reporting, inspections, action completion and evidence capture.
- Progressive disclosure.
- Clear status, risk, workflow and due-date signals.
- No unnecessary builder JS on normal pages.

Components:

- buttons, icon buttons;
- inputs, dropdowns, multi-selects, searchable pickers;
- modals, drawers, side panels;
- cards only for repeated items and record panels;
- dense data grids with filters and saved views;
- status/risk badges;
- workflow ribbons/timelines;
- relationship panels;
- notification center;
- dashboard widgets;
- empty/loading/error states;
- bilingual/RTL-ready layout tokens.

## 17. Navigation Structure

Primary navigation:

- Dashboard
- My Work
- Reporting
- Occurrences / Incidents
- Actions / CAPA
- Investigations
- NCR / Quality Events
- Risk
- Audits
- Documents
- Compliance
- Training / Competency
- Management of Change
- Suppliers / SCAR
- Equipment / Calibration
- Management Review
- Analytics
- Search
- Notifications
- AI Assistant
- Administration

Administration navigation:

- System settings and branding
- Users, roles, groups, scopes, key users
- Module licensing
- Form Designer
- Workflow Designer
- Notification Designer
- Email Designer
- Report Designer
- Dashboard Designer
- Numbering Designer
- Risk Matrix Designer
- Reference data
- Configuration packages
- Audit/security logs
- Queue/job monitor
- Integration monitor

## 18. Dashboard Architecture

Dashboards are permission-scoped and role-personalized:

- User dashboard: my reports, actions, notifications, due dates.
- Manager dashboard: department incidents, open/overdue actions, approvals, trends.
- Key User dashboard: scoped incidents, recommendations, overdue actions, verification queue.
- Administrator dashboard: users, licenses, queues, failed jobs, storage, system health.
- Executive dashboard: safety performance, quality performance, high risks, serious incidents, audit performance, NCR trends, KPI/SPI.

Dashboards use a metric catalog:

- metric code;
- owner;
- formula;
- version;
- data source;
- permission scope;
- thresholds;
- drill-down route;
- data quality notes.

## 19. AI Architecture

AI is optional and disabled unless configured.

AI requirements:

- provider abstraction;
- model/version logging;
- prompt/template versioning;
- permission-filtered retrieval before AI context is built;
- masking/redaction policy;
- citations/evidence references;
- confidence/uncertainty indicator where practical;
- human approval gates;
- audit logs;
- per-module enable/disable;
- cost/token budgets;
- safe fallback when unavailable;
- no autonomous closure/approval/risk acceptance/disciplinary decisions.

AI use cases:

- improve occurrence narrative;
- suggest categories and missing information;
- duplicate/similar incident detection;
- investigation question suggestions;
- 5 Whys/Fishbone/SHELL assistance;
- draft recommendations;
- audit checklist suggestions;
- CAPA wording quality checks;
- analytics question answering.

## 20. Licensing Architecture

Licensed modules:

- QMS Core
- SMS
- HSE
- Risk
- Audit
- NCR
- CAPA
- Investigation
- Documents
- Analytics
- AI
- Emergency Response
- Supplier Management
- Training
- MOC
- Equipment/Calibration

License data:

- module;
- status;
- expiry;
- user limit;
- storage limit;
- AI usage limit;
- grace period;
- enforcement policy.

Expired modules hide or disable new work according to policy but must not delete existing data.

## 21. Microsoft AD / Entra ID Integration

Architecture:

- optional SSO via Microsoft Entra ID;
- group-to-role mapping;
- group-to-permission-template mapping;
- department/business unit sync where reliable;
- automatic provisioning/deactivation;
- fallback local auth for break-glass/admin where approved;
- synchronization audit;
- retry and dead-letter sync failures;
- manual override with audit.

Use Laravel Socialite/Microsoft provider or SAML/OIDC package after compatibility review. Use service-account governance and never store unnecessary Microsoft secrets in code.

## 22. Security Architecture

Controls:

- RBAC plus contextual access scopes;
- Laravel policies for records;
- field-level masking;
- sensitive access logs;
- CSRF/XSS/SQL injection protection;
- password hashing;
- MFA-ready;
- session controls;
- login throttling;
- IP/security logs;
- encrypted secure file storage;
- signed download URLs;
- permission-based exports;
- segregation of duties;
- break-glass access with reason;
- re-authentication for sensitive signatures;
- device/session inventory;
- secret rotation readiness;
- retention/legal holds;
- tamper-evident audit strategy for critical records.

## 23. Reporting and Export Architecture

Report Designer is separate from Form Designer.

Report templates support:

- PDF, Word, Excel, printable, CSV;
- logo/header/footer/page numbering;
- dynamic values;
- tables/images/signatures;
- classification labels;
- QR codes;
- revision information;
- confidentiality markings.

Large exports run as queued jobs and create export records with:

- requester;
- scope;
- filters;
- format;
- status;
- file reference;
- audit reason for sensitive exports.

## 24. Regulatory Obligation and Traceability Architecture

Traceability chain:

Regulatory Requirement -> Internal Control -> Manual/Procedure -> Responsible Owner -> Evidence -> Audit/Assurance Activity -> Finding/Gap -> Action/Change -> Closure

Regulatory-change impact workflow:

1. Requirement change logged.
2. System identifies linked manuals, procedures, forms, workflows, training, controls and owners.
3. Impact-review tasks generated.
4. Decisions recorded.
5. Change requests/actions launched.
6. Implementation tracked.
7. Complete traceability preserved.

## 25. Management of Change Architecture

MOC records include:

- change request;
- reason/category/scope;
- owner/sponsor;
- temporary/permanent;
- affected departments/locations/systems/assets;
- safety/quality/HSE/security/compliance/human factors/training/document impacts;
- risk assessment;
- implementation/communication/training/document gates;
- approvals;
- go-live decision;
- post-implementation/effectiveness review;
- rollback/contingency plan.

MOC can originate from audits, incidents, regulatory changes, CAPA, risk assessments, supplier changes, technology changes or management decisions.

## 26. Training / Competency / Qualification Architecture

Support:

- course catalog;
- role curricula;
- competency requirements;
- initial/recurrent training;
- qualifications/currency;
- grace periods;
- prerequisites;
- assessments;
- evidence/certificates;
- exemptions with approval;
- reminders/escalations;
- competency gaps.

Document revisions and MOC can trigger training requirements.

## 27. Equipment / Asset / Calibration Architecture

Optional module:

- asset register;
- type/category/serial/location/custodian;
- criticality and safety-critical flag;
- serviceability status;
- inspection/calibration/maintenance requirements;
- certificates and due dates;
- defects/restrictions/out-of-service;
- return to service;
- linked incidents/risks/barriers/audits/actions.

System-controlled workflows should prevent use of expired/unserviceable assets where practical.

## 28. Supplier Qualification / SCAR / Scorecard Architecture

Supplier lifecycle:

- onboarding;
- qualification;
- approval status;
- risk tier;
- certificates and expiry alerts;
- supplier audit history;
- supplier NCR;
- SCAR/CAPA;
- scorecards;
- SLA/service quality indicators;
- repeat issue detection;
- suspension/disqualification/requalification;
- external evidence portal.

Suppliers access only assigned external scope.

## 29. Electronic Signature Architecture

Signature requirements are configurable by workflow state and module.

Capture:

- signer;
- date/time;
- meaning of signature;
- record/version;
- authentication context;
- reason/comment;
- IP/session metadata.

A signature must remain bound to the exact record version signed.

## 30. Offline Mobile and Synchronization Architecture

Initial offline candidates:

- quick occurrence reporting;
- inspections;
- audit checklists;
- evidence/photo capture;
- action updates;
- equipment inspection;
- read-only controlled documents.

Sync architecture:

- encrypted local cache where practical;
- sync queue;
- attachment resumability;
- server-authoritative permission recheck;
- version validation;
- conflict detection/resolution;
- retry and partial failure handling;
- sync audit log;
- safe cleanup of local data.

Offline mode is never a permission bypass.

## 31. Risk Barrier / Bow-Tie Assurance Architecture

Support:

- hazards;
- threats;
- consequences;
- preventive barriers;
- recovery/mitigative barriers;
- barrier owners;
- performance standards;
- effectiveness status;
- impaired/degraded/failed state;
- critical control verification;
- assurance tests;
- Bow-Tie linkage;
- risk aggregation and trend.

## 32. Unified My Work Task Architecture

My Work consolidates:

- assigned actions;
- reviews;
- approvals;
- investigations;
- audit tasks;
- evidence requests;
- document reviews;
- training;
- risk reviews;
- change tasks;
- supplier responses;
- compliance reviews.

Use a generic work-item/task projection for queueing and dashboarding, while preserving explicit domain records for business logic. Avoid putting all domain logic into one polymorphic table.

## 33. Configuration Versioning and Environment Promotion

Configuration package types:

- forms;
- workflows;
- rules;
- notifications;
- dashboards;
- numbering;
- risk matrices;
- reference data;
- roles/permission templates;
- report templates.

Lifecycle:

Draft -> Validate -> Preview Impact -> Approve -> Publish -> Export Package -> Promote to UAT/Production -> Monitor -> Rollback if needed

Configuration must be reproducible, not only manually clicked in production.

## 34. Data Retention and Legal Hold

Each record type has:

- retention schedule;
- owner;
- confidentiality class;
- archive date;
- destruction eligibility;
- legal/regulatory hold;
- disposal approval;
- disposal evidence;
- export/archive format.

Critical safety, quality, compliance and audit records should not be destructively deleted.

## 35. Integration Event Reliability Architecture

Use an outbox pattern for critical integrations.

Events include:

- correlation ID;
- idempotency key;
- source module;
- payload hash;
- delivery attempts;
- last error;
- dead-letter status;
- replay controls;
- audit trail.

Examples:

- Entra user sync;
- HR synchronization;
- notification delivery;
- document-to-training trigger;
- regulatory-change trigger;
- data warehouse feed;
- webhook delivery;
- AI jobs.

## 36. Cross-Module Relationship / Record Graph Architecture

Relationships:

- Occurrence -> Hazard -> Risk Assessment -> Investigation -> Root Cause -> Recommendation -> Action -> Document Change -> Training -> Audit Verification.
- Regulatory Change -> Compliance Requirement -> MOC -> Procedure Revision -> Training -> Audit Evidence.

Record graph must be permission-aware. Search and AI must never expose unauthorized records through graph expansion.

## 37. Deployment and Miniworld Replacement Plan

Safe deployment steps:

1. Identify existing Miniworld path, database, storage and environment file.
2. Back up current application files.
3. Back up current database.
4. Back up uploaded files/storage.
5. Validate backups can be restored.
6. Deploy QMS to separate release directory.
7. Configure `.env` with production database, mail, queue, cache and app URL.
8. Run Composer install.
9. Run npm install/build.
10. Run migrations.
11. Link storage.
12. Configure file permissions.
13. Configure queue worker and scheduler.
14. Configure web server for qms.ysaidea.com.
15. Verify SSL.
16. Run health checks.
17. Smoke-test login, dashboard, forms, actions, notifications and public portal.
18. Switch current symlink/domain routing.
19. Monitor logs/jobs/errors.
20. Roll back to previous release if health checks fail.

Never overwrite Miniworld before verified backup and rollback.

## 38. Testing Strategy

Test categories:

- unit tests;
- feature tests;
- auth tests;
- permission/scope tests;
- form version tests;
- workflow tests;
- notification/rule tests;
- action acceptance tests;
- recommendation tests;
- audit trail tests;
- export tests;
- AI governance tests;
- API tests;
- deployment smoke tests.

Critical paths must have automated tests before production use.

## 39. Implementation Roadmap

Phase 1: Foundation  
Authentication, organization model, users, roles, permission templates, scopes, settings, branding, module licensing.

Phase 2: Platform Engines  
Form Designer, Workflow Designer, Notification Template/Rule Designer, Email Designer, Report Designer, Numbering Designer, Attachment Engine, Audit Trail.

Phase 3: Operational Modules  
Occurrences, Incidents, NCR, CAPA, Actions, Recommendations, Investigations, Risk.

Phase 4: Assurance Modules  
Audits, checklist designer, documents, compliance obligations, management reviews.

Phase 5: Extended Enterprise  
Training/competency, MOC, suppliers/SCAR, equipment/calibration, HSE extensions.

Phase 6: Intelligence  
Dashboards, KPI/SPI catalog, analytics, AI assistant, similar-event detection, record graph.

Phase 7: Integration  
Microsoft Entra ID, API v1, webhooks, HR/ERP/data warehouse integrations, Teams/email production channels.

Phase 8: Production Hardening  
Backups, monitoring, Horizon/Redis, scheduler, load testing, security review, UAT, Miniworld cutover.

## 40. Risks and Mitigations

| Risk | Mitigation |
|---|---|
| Scope too large for one release | Modular roadmap and shared engines. |
| Permission leakage | Policies, scopes, tests, masked fields, sensitive access logs. |
| Historical records altered by config changes | Immutable published versions and snapshots. |
| Heavy builder slows normal users | Lazy-load admin builders only. |
| Notification spam | Deduplication, throttling, digests, preferences, mandatory controls. |
| AI exposes restricted data | Permission-filtered retrieval before prompt construction. |
| Unsafe Miniworld cutover | Backups, release directories, health checks, rollback. |
| Vendor lock-in for builders | Store portable Laravel-owned schemas and adapter payloads. |
| Poor adoption | Task-focused UX and quick report modes. |

## 41. Assumptions

- Initial production deployment is single organization but must be multi-organization ready.
- Domain `qms.ysaidea.com` is already configured or will be pointed to VPS.
- Hostinger VPS supports required PHP extensions, Composer, Node, database, queue worker and scheduler.
- External AI, Microsoft, SMS, WhatsApp and regulatory-content providers require later approval and credentials.
- Uploaded screenshots remain confidential.

## 42. Dependencies

- Approved production database credentials.
- Mail transport credentials.
- Queue/scheduler setup.
- Storage/backup strategy.
- Licensing decisions for visual form/email builders.
- Microsoft Entra app registration for SSO/sync.
- Security review before operational use.
- User acceptance testing with representative roles.

## 43. Definition of Done

A feature is done only when:

- implemented behind correct permission checks;
- validated server-side;
- audited where important;
- covered by tests for critical behavior;
- documented in BRSD/architecture or module docs;
- performant with pagination/indexes/queues where needed;
- responsive and accessible enough for target users;
- does not expose confidential records or screenshots;
- deployable through migrations/seeders without manual database edits.

## 44. Production Acceptance Criteria

- Miniworld backup and rollback verified.
- Production `.env` configured securely.
- Migrations run successfully.
- Seeded system defaults present.
- Login works for approved users.
- Public report portal works.
- Dashboard loads under acceptable response time.
- Occurrence workflow, action acceptance and recommendation creation work.
- Notification templates/rules exist and are auditable.
- Exports are permission-controlled.
- Logs and queue/scheduler are monitored.
- No confidential screenshots or sample files are deployed.

## 45. Source Links

- ETQ Reliance platform: https://www.etq.com/platform/
- Octave Reliance overview: https://www.octave.com/products/asset-performance-management/reliance
- Intelex applications: https://www.intelex.com/products/applications/
- Intelex platform: https://www.intelex.com/ehs-platform/
- Cority audit/inspection capabilities: https://go.cority.com/corityone/audit-inspections/
- Cority platform: https://www.cority.com/corityone/
- Enablon platform: https://www.wolterskluwer.com/en/solutions/enablon
- MasterControl integrated QMS reference: https://www.mastercontrol.com/events/virtual/benefits-of-integrated-qms-platform-ondemand/
- Laravel release notes: https://laravel.com/docs/13.x/releases
- Laravel queues: https://laravel.com/docs/13.x/queues
- Laravel notifications: https://laravel.com/docs/13.x/notifications
- Laravel Horizon: https://laravel.com/docs/13.x/horizon
- Laravel Reverb: https://laravel.com/docs/13.x/reverb
- Laravel Sanctum: https://laravel.com/docs/13.x/sanctum
- Spatie Laravel Permission teams: https://spatie.be/docs/laravel-permission/v8/basic-usage/teams-permissions
- SurveyJS Creator: https://surveyjs.io/survey-creator/documentation/end-user-guide/skip-logic-in-forms
- Form.io logic: https://help.form.io/userguide/form-building/logic-and-conditions
- Unlayer pricing: https://unlayer.com/pricing
- GrapesJS docs: https://grapesjs.com/docs/
- MJML: https://github.com/mjmlio/mjml
- Maizzle: https://maizzle.com/docs/introduction
