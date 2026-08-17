<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QMS - Quality Management System Prototype</title>
  <link rel="stylesheet" href="{{ asset('qms-assets/style.css') }}" />
</head>
<body>
  @if (session("status"))<div class="server-flash">{{ session("status") }}</div>@endif
  <div class="app-shell">
    <aside class="sidebar" aria-label="Main navigation">
      <div class="brand">
        <div class="brand-mark">Q</div>
        <div>
          <strong>QMS</strong>
          <span>ysaidea.com</span>
        </div>
      </div>

      <nav class="nav-list">
        <button class="nav-item active" data-view="dashboard">Dashboard</button>
        <button class="nav-item" data-view="report">Submit Report</button>
        <button class="nav-item" data-view="records">Records</button>
        <button class="nav-item" data-view="investigation">Investigation</button>
        <button class="nav-item" data-view="actions">CAPA / Actions</button>
        <button class="nav-item" data-view="audit">Audit</button>
        <button class="nav-item" data-view="risk">Risk</button>
        <button class="nav-item" data-view="documents">Documents</button>
        <button class="nav-item" data-view="bi">BI</button>
        <button class="nav-item" data-view="admin">Admin Center</button>
      </nav>

      <div class="sidebar-footer">
        <span>Prototype build</span>
        <strong>Phase 1</strong>
      </div>
    </aside>

    <main class="main">
      <header class="topbar">
        <div class="search">
          <span>Search</span>
          <input id="globalSearch" type="search" placeholder="Reports, actions, audits, risks..." />
        </div>
        <div class="topbar-actions">
          <button class="icon-button" id="rtlToggle" title="Toggle Arabic/RTL preview">AR</button>
          <button class="icon-button" title="Notifications">7</button>
          <button class="primary-button" data-view-shortcut="report">Submit</button>
        </div>
      </header>

      <section id="dashboard" class="view active-view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Enterprise Quality, Safety, HSE and Risk</p>
            <h1>Command dashboard</h1>
          </div>
          <button class="primary-button" data-view-shortcut="report">New occurrence</button>
        </div>

        <div class="metric-grid">
          <article class="metric">
            <span>Open occurrences</span>
            <strong>42</strong>
            <small>8 high priority</small>
          </article>
          <article class="metric">
            <span>Overdue CAPA</span>
            <strong>11</strong>
            <small>3 awaiting verification</small>
          </article>
          <article class="metric">
            <span>Risk exposure</span>
            <strong>Medium</strong>
            <small>2 controls due review</small>
          </article>
          <article class="metric">
            <span>Audit readiness</span>
            <strong>86%</strong>
            <small>ISO / SMS evidence mapped</small>
          </article>
        </div>

        <div class="content-grid">
          <article class="panel wide">
            <div class="panel-header">
              <h2>Live workflow board</h2>
              <span class="status-pill">Updated now</span>
            </div>
            <div class="kanban">
              <div class="lane">
                <h3>Submitted</h3>
                <div class="work-card">Ground safety observation<span>QMS-2026-00435</span></div>
                <div class="work-card">Quality NCR from audit<span>NCR-2026-00018</span></div>
              </div>
              <div class="lane">
                <h3>Screening</h3>
                <div class="work-card high">Unsafe condition near scaffolding<span>HSE-2026-00092</span></div>
              </div>
              <div class="lane">
                <h3>Investigation</h3>
                <div class="work-card">Repeated FOD finding<span>SMS-2026-00121</span></div>
              </div>
              <div class="lane">
                <h3>CAPA</h3>
                <div class="work-card overdue">Revise barricade control<span>CAPA-2026-00077</span></div>
              </div>
            </div>
          </article>

          <article class="panel">
            <div class="panel-header">
              <h2>Critical actions</h2>
            </div>
            <ul class="signal-list">
              <li><strong>3</strong><span>CAPA awaiting verification</span></li>
              <li><strong>5</strong><span>Reports unassigned over 24h</span></li>
              <li><strong>2</strong><span>High risks pending acceptance</span></li>
            </ul>
          </article>

          <article class="panel">
            <div class="panel-header">
              <h2>Reference screens</h2>
            </div>
            <div class="reference-strip">
              <img src="qms-assets/references/IMG_3381.png" alt="Reference workflow form screen" />
              <img src="qms-assets/references/IMG_3386.png" alt="Reference record review screen" />
              <img src="qms-assets/references/IMG_3387.png" alt="Reference detail form screen" />
            </div>
          </article>
        </div>
      </section>

      <section id="report" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Fast reporting</p>
            <h1>Submit occurrence</h1>
          </div>
          <button class="secondary-button" id="saveDraft">Save draft</button>
        </div>

        <div class="workflow-ribbon" aria-label="Report workflow">
          <span class="done">Draft</span>
          <span class="active">Submitted</span>
          <span>HSE Review</span>
          <span>Investigation</span>
          <span>CAPA</span>
          <span>Closed</span>
        </div>

        <div class="form-layout">
          <form class="panel form-panel" id="reportForm" method="POST" action="{{ route('qms.occurrences.store') }}" data-server="true">
            @csrf
            <h2>Occurrence details</h2>
            <div class="form-grid">
              <label>Observation type
                <select id="observationType" name="type">
                  <option>Unsafe condition</option>
                  <option>Flight safety</option>
                  <option>Ground safety</option>
                  <option>Quality nonconformance</option>
                  <option>Environmental</option>
                </select>
              </label>
              <label>Location
                <input type="text" name="location" value="OQB Locations" />
              </label>
              <label>Exact location
                <input type="text" name="exact_location" placeholder="Example: CAE 135, Equipment area" />
              </label>
              <label>Reported by
                <input type="text" name="reported_by" value="Mazin Al Farsi" />
              </label>
              <label>Date and time
                <input type="datetime-local" />
              </label>
              <label>Confidential report
                <select name="confidential">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </label>
            </div>

            <div id="flightFields" class="conditional-box">
              <h3>Flight safety details</h3>
              <div class="form-grid">
                <label>Flight number<input type="text" placeholder="WY123" /></label>
                <label>Aircraft registration<input type="text" placeholder="A4O-..." /></label>
                <label>Sector<input type="text" placeholder="MCT-SLL" /></label>
                <label>Departure<input type="text" placeholder="MCT" /></label>
                <label>Destination<input type="text" placeholder="SLL" /></label>
              </div>
            </div>

            <label>Description
              <textarea rows="5" name="description" placeholder="Describe what happened, who was involved, immediate controls, and any evidence."></textarea>
            </label>

            <fieldset class="checkbox-grid">
              <legend>Unsafe condition tags</legend>
              <label><input type="checkbox" /> Congested restricted area</label>
              <label><input type="checkbox" /> Walking surfaces</label>
              <label><input type="checkbox" /> Visibility</label>
              <label><input type="checkbox" /> Slippery surface</label>
              <label><input type="checkbox" /> PPE</label>
              <label><input type="checkbox" /> Barricade</label>
              <label><input type="checkbox" /> Housekeeping</label>
              <label><input type="checkbox" /> Equipment / tools</label>
            </fieldset>

            <div class="button-row">
              <button type="submit" class="primary-button">Submit report</button>
              <button type="button" class="secondary-button">Attach evidence</button>
            </div>
          </form>

          <aside class="panel helper-panel">
            <h2>AI quality check</h2>
            <p>Prototype assistant checks whether the report has enough detail before screening.</p>
            <ul class="check-list">
              <li>Clear location</li>
              <li>Event date and time</li>
              <li>Immediate action</li>
              <li>Evidence attached</li>
            </ul>
          </aside>
        </div>
      </section>

      <section id="records" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Record workspace</p>
            <h1>Unsafe condition near scaffolding</h1>
          </div>
          <span class="status-pill warning">HSE Review</span>
        </div>

        <div class="record-layout">
          <article class="panel wide">
            <div class="record-head">
              <div>
                <h2>QMS-2026-00435</h2>
                <p>Reported by contractor, location: Training Engineering LLC, CAE 135 equipment area.</p>
              </div>
              <strong class="risk-badge">Risk: High</strong>
            </div>
            <div class="workflow-ribbon compact">
              <span class="done">Draft</span>
              <span class="done">Submitted</span>
              <span class="active">HSE Review</span>
              <span>Investigation</span>
              <span>CAPA</span>
              <span>Closed</span>
            </div>
            <div class="detail-grid">
              <div><span>Observation type</span><strong>Unsafe condition</strong></div>
              <div><span>Person responsible</span><strong>HSE Reviewer</strong></div>
              <div><span>Due date</span><strong>2026-08-20</strong></div>
              <div><span>Department</span><strong>Engineering</strong></div>
            </div>
            <h3>Observation description</h3>
            <p>A rusted pipe was observed and there was no signage displayed in an area where scaffolding erection was in progress. Immediate action requested: advise crew to display signage and barricade the area.</p>
          </article>

          <aside class="panel">
            <h2>Next actions</h2>
            <button class="primary-button full">Accept for investigation</button>
            <button class="secondary-button full">Return for more info</button>
            <button class="secondary-button full">Reject report</button>
            <ul class="timeline">
              <li><strong>Submitted</strong><span>Today 08:30</span></li>
              <li><strong>Auto-classified</strong><span>Unsafe condition</span></li>
              <li><strong>Assigned</strong><span>HSE Review team</span></li>
            </ul>
          </aside>
        </div>
      </section>

      <section id="investigation" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Investigation management</p>
            <h1>Investigation workspace</h1>
          </div>
          <button class="primary-button">Generate report</button>
        </div>
        <div class="content-grid">
          <article class="panel wide">
            <h2>Analysis board</h2>
            <div class="analysis-grid">
              <div><strong>5 Whys</strong><span>Why was signage missing?</span></div>
              <div><strong>Fishbone</strong><span>People, Process, Equipment, Environment</span></div>
              <div><strong>Bow-tie</strong><span>Threats, barriers, consequences</span></div>
              <div><strong>SHELL</strong><span>Software, Hardware, Environment, Liveware</span></div>
            </div>
          </article>
          <article class="panel">
            <h2>Investigation team</h2>
            <ul class="person-list">
              <li><strong>Lead</strong><span>HSE Reviewer</span></li>
              <li><strong>Member</strong><span>Engineering Manager</span></li>
              <li><strong>Reviewer</strong><span>Safety Admin</span></li>
            </ul>
          </article>
          <article class="panel">
            <h2>Findings</h2>
            <p>Preliminary finding: barricade and signage control was not verified before work started.</p>
          </article>
        </div>
      </section>

      <section id="actions" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Centralized improvement</p>
            <h1>CAPA and actions</h1>
          </div>
          <button class="primary-button">Create action</button>
        </div>
        <div class="table-panel">
          <table>
            <thead>
              <tr><th>ID</th><th>Action</th><th>Owner</th><th>Due</th><th>Status</th></tr>
            </thead>
            <tbody>
              <tr><td>CAPA-077</td><td>Revise barricade control checklist</td><td>Engineering</td><td>2026-08-22</td><td><span class="status-pill warning">Overdue risk</span></td></tr>
              <tr><td>ACT-118</td><td>Brief contractors on signage requirements</td><td>HSE</td><td>2026-08-19</td><td><span class="status-pill">In progress</span></td></tr>
              <tr><td>ACT-119</td><td>Verify controlled area inspection</td><td>Safety</td><td>2026-08-25</td><td><span class="status-pill success">Open</span></td></tr>
            </tbody>
          </table>
        </div>
      </section>

      <section id="audit" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Audit assurance</p>
            <h1>Audit programme</h1>
          </div>
          <button class="primary-button">Plan audit</button>
        </div>
        <div class="content-grid">
          <article class="panel">
            <h2>August internal audit</h2>
            <p>ISO 9001 and SMS evidence review for Engineering and Ground Operations.</p>
          </article>
          <article class="panel">
            <h2>Checklist designer</h2>
            <p>Sections, clauses, weighted questions, automatic findings, evidence and attachments.</p>
          </article>
          <article class="panel">
            <h2>Open findings</h2>
            <strong class="big-number">6</strong>
          </article>
        </div>
      </section>

      <section id="risk" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Risk-based thinking</p>
            <h1>Risk register</h1>
          </div>
          <button class="primary-button">Assess risk</button>
        </div>
        <div class="risk-matrix panel">
          <h2>5 x 5 risk matrix preview</h2>
          <div class="matrix" aria-label="Risk matrix">
            <span class="low">1</span><span class="low">2</span><span class="med">3</span><span class="med">4</span><span class="high">5</span>
            <span class="low">2</span><span class="med">4</span><span class="med">6</span><span class="high">8</span><span class="high">10</span>
            <span class="med">3</span><span class="med">6</span><span class="high">9</span><span class="high">12</span><span class="critical">15</span>
            <span class="med">4</span><span class="high">8</span><span class="high">12</span><span class="critical">16</span><span class="critical">20</span>
            <span class="high">5</span><span class="high">10</span><span class="critical">15</span><span class="critical">20</span><span class="critical">25</span>
          </div>
        </div>
      </section>

      <section id="documents" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Documented information</p>
            <h1>Controlled documents</h1>
          </div>
          <button class="primary-button">Upload revision</button>
        </div>
        <div class="table-panel">
          <table>
            <thead>
              <tr><th>Document</th><th>Version</th><th>Owner</th><th>Review date</th><th>Status</th></tr>
            </thead>
            <tbody>
              <tr><td>Safety Reporting Procedure</td><td>v3.2</td><td>Safety</td><td>2026-11-01</td><td>Published</td></tr>
              <tr><td>Contractor HSE Manual</td><td>v2.0</td><td>HSE</td><td>2026-10-15</td><td>Review</td></tr>
              <tr><td>Internal Audit Procedure</td><td>v1.8</td><td>Quality</td><td>2027-01-10</td><td>Published</td></tr>
            </tbody>
          </table>
        </div>
      </section>

      <section id="bi" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Business intelligence</p>
            <h1>Executive BI</h1>
          </div>
          <button class="primary-button">Ask AI</button>
        </div>
        <div class="content-grid">
          <article class="panel wide">
            <h2>Occurrence trend</h2>
            <div class="bar-chart">
              <span style="height:38%"></span>
              <span style="height:58%"></span>
              <span style="height:45%"></span>
              <span style="height:80%"></span>
              <span style="height:62%"></span>
              <span style="height:70%"></span>
            </div>
          </article>
          <article class="panel">
            <h2>Natural-language BI</h2>
            <p>Example: Which departments have the highest overdue actions this month?</p>
          </article>
        </div>
      </section>

      <section id="admin" class="view">
        <div class="page-title">
          <div>
            <p class="eyebrow">Configuration</p>
            <h1>Administration control center</h1>
          </div>
          <button class="primary-button">Publish changes</button>
        </div>
        <div class="admin-grid">
          <button>Branding</button>
          <button>Organizations</button>
          <button>Users</button>
          <button>Roles</button>
          <button>Permissions</button>
          <button>Forms</button>
          <button>Workflows</button>
          <button>Notifications</button>
          <button>Risk matrices</button>
          <button>Reports</button>
          <button>AI providers</button>
          <button>Integrations</button>
        </div>
      </section>
    </main>
  </div>

  <div class="toast" id="toast" role="status" aria-live="polite"></div>
  <script src="{{ asset('qms-assets/app.js') }}"></script>
</body>
</html>
