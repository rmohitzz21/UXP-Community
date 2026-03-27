<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Event Registrations | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="img/LOGO.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/admin-style.css" />
  </head>
  <body>
    <div class="admin-wrapper">
      <!-- SIDEBAR -->
      <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
          <img src="img/LOGO.png" alt="UX Pacific" class="sidebar-logo" />
          <span class="sidebar-brand">UX Pacific</span>
          <button class="sidebar-close d-lg-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
        </div>
        <nav class="sidebar-nav">
          <ul class="nav-list">
            <li class="nav-item"><a href="admin.html" class="nav-link"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a href="admin-contacts.html" class="nav-link"><i class="bi bi-envelope-fill"></i><span>Contact Messages</span></a></li>
            <li class="nav-item"><a href="admin-members.html" class="nav-link"><i class="bi bi-people-fill"></i><span>Members</span></a></li>
            <li class="nav-item"><a href="admin-events.html" class="nav-link"><i class="bi bi-calendar-event-fill"></i><span>Events</span></a></li>
            <li class="nav-item"><a href="admin-registrations.html" class="nav-link active"><i class="bi bi-person-check-fill"></i><span>Registrations</span></a></li>
            <li class="nav-item"><a href="admin-resources.html" class="nav-link"><i class="bi bi-folder-fill"></i><span>Resources</span></a></li>
            <li class="nav-item"><a href="admin-settings.html" class="nav-link"><i class="bi bi-gear-fill"></i><span>Settings</span></a></li>
          </ul>
        </nav>
        <div class="sidebar-footer">
          <a href="index.html" class="nav-link"><i class="bi bi-box-arrow-left"></i><span>View Website</span></a>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <div class="admin-main">
        <header class="admin-topbar">
          <button class="sidebar-toggle d-lg-none" id="openSidebar"><i class="bi bi-list"></i></button>
          <div class="topbar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search registrations..." aria-label="Search registrations" id="searchInput" />
          </div>
          <div class="topbar-actions">
            <button class="topbar-btn" aria-label="Notifications"><i class="bi bi-bell"></i><span class="badge">3</span></button>
            <div class="dropdown">
              <button class="admin-profile dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="profile-avatar"><i class="bi bi-person-fill"></i></div>
                <span class="profile-name">Admin</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="admin-settings.html">Settings</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
              </ul>
            </div>
          </div>
        </header>

        <main class="admin-content">
          <div class="content-header d-flex justify-content-between align-items-center">
            <div>
              <h1>Event Registrations</h1>
              <p class="text-muted">View and manage all event registrations from the website</p>
            </div>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" style="width: 180px; background: rgba(255,255,255,0.05); color: #fff; border-color: rgba(255,255,255,0.1);" id="eventFilter">
                <option value="all" style="background: #1a1a2e; color: #fff;">All Events</option>
                <option value="Design Systems Summit 2026" style="background: #1a1a2e; color: #fff;">Design Systems Summit 2026</option>
              </select>
              <button class="btn btn-primary" onclick="exportRegistrations()">
                <i class="bi bi-download me-2"></i>Export CSV
              </button>
            </div>
          </div>

          <!-- Registration Stats -->
          <div class="stats-grid mb-4">
            <div class="stat-card">
              <div class="stat-icon bg-primary"><i class="bi bi-person-check-fill"></i></div>
              <div class="stat-info"><h3 id="totalRegistrations">0</h3><p>Total Registrations</p></div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-success"><i class="bi bi-calendar-check"></i></div>
              <div class="stat-info"><h3 id="todayRegistrations">0</h3><p>Today</p></div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-warning"><i class="bi bi-briefcase"></i></div>
              <div class="stat-info"><h3 id="professionalCount">0</h3><p>Professionals</p></div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-info"><i class="bi bi-mortarboard"></i></div>
              <div class="stat-info"><h3 id="studentCount">0</h3><p>Students</p></div>
            </div>
          </div>

          <!-- Registrations Table -->
          <div class="admin-card">
            <div class="card-header">
              <h2>All Registrations</h2>
              <button class="btn btn-sm btn-outline-danger" onclick="clearAllRegistrations()">
                <i class="bi bi-trash me-1"></i>Clear All
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="admin-table" id="registrationsTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Company</th>
                      <th>Role</th>
                      <th>Experience</th>
                      <th>Event</th>
                      <th>Registered</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="registrationsBody">
                    <!-- Dynamic content will be loaded here -->
                  </tbody>
                </table>
              </div>
              
              <div id="noRegistrations" class="text-center py-5" style="display: none;">
                <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-muted);"></i>
                <h4 class="mt-3" style="color: var(--text-secondary);">No Registrations Yet</h4>
                <p style="color: var(--text-muted);">Event registrations from the website will appear here.</p>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- View Registration Modal -->
    <div class="modal fade" id="viewRegistrationModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: var(--admin-sidebar); border: 1px solid var(--admin-border);">
          <div class="modal-header border-0">
            <h5 class="modal-title">Registration Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="registrationDetails">
            <!-- Dynamic content -->
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="sendConfirmationEmail()">
              <i class="bi bi-envelope me-2"></i>Send Confirmation
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/admin.js"></script>
    <script>
      // Load and display registrations
      function loadRegistrations() {
        const registrations = JSON.parse(localStorage.getItem('eventRegistrations') || '[]');
        const tbody = document.getElementById('registrationsBody');
        const noRegistrations = document.getElementById('noRegistrations');
        const eventFilter = document.getElementById('eventFilter').value;
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        
        // Filter registrations
        let filtered = registrations;
        if (eventFilter !== 'all') {
          filtered = filtered.filter(r => r.eventName === eventFilter);
        }
        if (searchQuery) {
          filtered = filtered.filter(r => 
            r.firstName.toLowerCase().includes(searchQuery) ||
            r.lastName.toLowerCase().includes(searchQuery) ||
            r.email.toLowerCase().includes(searchQuery) ||
            r.phone.includes(searchQuery)
          );
        }

        // Sort by most recent first
        filtered.sort((a, b) => new Date(b.registeredAt) - new Date(a.registeredAt));

        if (filtered.length === 0) {
          tbody.innerHTML = '';
          noRegistrations.style.display = 'block';
        } else {
          noRegistrations.style.display = 'none';
          tbody.innerHTML = filtered.map(reg => `
            <tr>
              <td><span style="font-family: monospace; font-size: 0.75rem; color: var(--text-muted);">${reg.id}</span></td>
              <td>
                <div class="user-info">
                  <div class="user-avatar">${reg.firstName.charAt(0)}${reg.lastName.charAt(0)}</div>
                  <span>${reg.firstName} ${reg.lastName}</span>
                </div>
              </td>
              <td>${reg.email}</td>
              <td>${reg.phone}</td>
              <td>${reg.company || '-'}</td>
              <td><span class="badge-industry">${formatRole(reg.role)}</span></td>
              <td>${formatExperience(reg.experience)}</td>
              <td><span style="font-size: 0.8125rem;">${reg.eventName}</span></td>
              <td>${formatDate(reg.registeredAt)}</td>
              <td>
                <button class="btn-action" title="View" onclick="viewRegistration('${reg.id}')">
                  <i class="bi bi-eye"></i>
                </button>
                <button class="btn-action" title="Delete" onclick="deleteRegistration('${reg.id}')">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          `).join('');
        }

        // Update stats
        updateStats(registrations);
      }

      function updateStats(registrations) {
        document.getElementById('totalRegistrations').textContent = registrations.length;
        
        // Today's registrations
        const today = new Date().toDateString();
        const todayCount = registrations.filter(r => new Date(r.registeredAt).toDateString() === today).length;
        document.getElementById('todayRegistrations').textContent = todayCount;

        // Role counts
        const professionals = registrations.filter(r => ['designer', 'developer', 'product-manager', 'researcher', 'freelancer'].includes(r.role)).length;
        const students = registrations.filter(r => r.role === 'student').length;
        document.getElementById('professionalCount').textContent = professionals;
        document.getElementById('studentCount').textContent = students;
      }

      function formatRole(role) {
        const roles = {
          'designer': 'UX/UI Designer',
          'developer': 'Developer',
          'product-manager': 'Product Manager',
          'researcher': 'UX Researcher',
          'student': 'Student',
          'freelancer': 'Freelancer',
          'other': 'Other'
        };
        return roles[role] || role;
      }

      function formatExperience(exp) {
        const levels = {
          'beginner': 'Beginner',
          'intermediate': 'Intermediate',
          'advanced': 'Advanced',
          'expert': 'Expert'
        };
        return levels[exp] || '-';
      }

      function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
      }

      function viewRegistration(id) {
        const registrations = JSON.parse(localStorage.getItem('eventRegistrations') || '[]');
        const reg = registrations.find(r => r.id === id);
        
        if (reg) {
          const details = document.getElementById('registrationDetails');
          details.innerHTML = `
            <div class="row">
              <div class="col-md-6">
                <div class="mb-4">
                  <h6 style="color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Personal Information</h6>
                  <div class="d-flex align-items-center gap-3 mt-3">
                    <div class="user-avatar" style="width: 60px; height: 60px; font-size: 1.25rem;">${reg.firstName.charAt(0)}${reg.lastName.charAt(0)}</div>
                    <div>
                      <h5 style="margin: 0; color: var(--text-primary);">${reg.firstName} ${reg.lastName}</h5>
                      <p style="margin: 0; color: var(--text-muted); font-size: 0.875rem;">${formatRole(reg.role)}</p>
                    </div>
                  </div>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Email</label>
                  <p style="color: var(--text-primary); margin: 4px 0 0;">${reg.email}</p>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Phone</label>
                  <p style="color: var(--text-primary); margin: 4px 0 0;">${reg.phone}</p>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Company</label>
                  <p style="color: var(--text-primary); margin: 4px 0 0;">${reg.company || 'Not provided'}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <h6 style="color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Registration Details</h6>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Event</label>
                  <p style="color: var(--primary); margin: 4px 0 0; font-weight: 500;">${reg.eventName}</p>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Experience Level</label>
                  <p style="color: var(--text-primary); margin: 4px 0 0;">${formatExperience(reg.experience) || 'Not specified'}</p>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Registration ID</label>
                  <p style="color: var(--text-primary); margin: 4px 0 0; font-family: monospace;">${reg.id}</p>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Registered At</label>
                  <p style="color: var(--text-primary); margin: 4px 0 0;">${new Date(reg.registeredAt).toLocaleString()}</p>
                </div>
                <div class="mb-3">
                  <label style="color: var(--text-muted); font-size: 0.75rem;">Newsletter</label>
                  <p style="margin: 4px 0 0;">
                    ${reg.updates ? '<span style="color: var(--success);">✓ Subscribed</span>' : '<span style="color: var(--text-muted);">Not subscribed</span>'}
                  </p>
                </div>
              </div>
              ${reg.expectations ? `
              <div class="col-12 mt-3">
                <label style="color: var(--text-muted); font-size: 0.75rem;">Expectations / Goals</label>
                <p style="color: var(--text-secondary); margin: 8px 0 0; background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px;">${reg.expectations}</p>
              </div>
              ` : ''}
            </div>
          `;
          
          const modal = new bootstrap.Modal(document.getElementById('viewRegistrationModal'));
          modal.show();
        }
      }

      function deleteRegistration(id) {
        if (confirm('Are you sure you want to delete this registration?')) {
          let registrations = JSON.parse(localStorage.getItem('eventRegistrations') || '[]');
          registrations = registrations.filter(r => r.id !== id);
          localStorage.setItem('eventRegistrations', JSON.stringify(registrations));
          loadRegistrations();
        }
      }

      function clearAllRegistrations() {
        if (confirm('Are you sure you want to delete ALL registrations? This cannot be undone.')) {
          localStorage.setItem('eventRegistrations', '[]');
          loadRegistrations();
        }
      }

      function exportRegistrations() {
        const registrations = JSON.parse(localStorage.getItem('eventRegistrations') || '[]');
        if (registrations.length === 0) {
          alert('No registrations to export.');
          return;
        }

        const headers = ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Company', 'Role', 'Experience', 'Event', 'Expectations', 'Newsletter', 'Registered At'];
        const rows = registrations.map(r => [
          r.id,
          r.firstName,
          r.lastName,
          r.email,
          r.phone,
          r.company || '',
          formatRole(r.role),
          formatExperience(r.experience),
          r.eventName,
          r.expectations || '',
          r.updates ? 'Yes' : 'No',
          r.registeredAt
        ]);

        const csvContent = [headers, ...rows].map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `event-registrations-${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        URL.revokeObjectURL(url);
      }

      function sendConfirmationEmail() {
        alert('Confirmation email feature would be integrated with your email service (SendGrid, Mailchimp, etc.)');
      }

      // Event listeners
      document.getElementById('eventFilter').addEventListener('change', loadRegistrations);
      document.getElementById('searchInput').addEventListener('input', loadRegistrations);

      // Initial load
      document.addEventListener('DOMContentLoaded', loadRegistrations);
    </script>
  </body>
</html>
