<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Contact Messages | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="../img/LOGO.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin-style.css" />
  </head>
  <body>
    <div class="admin-wrapper">
      <!-- SIDEBAR -->
      <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
          <img src="../img/LOGO.png" alt="UX Pacific" width="150" />
          <button class="sidebar-close d-lg-none" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
        <nav class="sidebar-nav">
          <ul class="nav-list">
            <li class="nav-item"><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a href="contacts.php" class="nav-link active"><i class="bi bi-envelope-fill"></i><span>Contact Messages</span></a></li>
            <li class="nav-item"><a href="members.php" class="nav-link"><i class="bi bi-people-fill"></i><span>Members</span></a></li>
             <li class="nav-item">
              <a href="ideas.php" class="nav-link">
                <i class="bi bi-lightbulb-fill"></i>
                <span>Ideas</span>
              </a>
            </li>
            <li class="nav-item"><a href="events.php" class="nav-link"><i class="bi bi-calendar-event-fill"></i><span>Events</span></a></li>
            <li class="nav-item"><a href="registrations.php" class="nav-link"><i class="bi bi-person-check-fill"></i><span>Registrations</span></a></li>
            <li class="nav-item"><a href="resources.php" class="nav-link"><i class="bi bi-folder-fill"></i><span>Resources</span></a></li>
            <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear-fill"></i><span>Settings</span></a></li>
          </ul>
        </nav>
        <div class="sidebar-footer">
          <a href="../index.php" class="nav-link"><i class="bi bi-box-arrow-left"></i><span>View Website</span></a>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <div class="admin-main">
        <header class="admin-topbar">
          <button class="sidebar-toggle d-lg-none" id="openSidebar"><i class="bi bi-list"></i></button>
          <div class="topbar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search messages..." aria-label="Search messages" />
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
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
              </ul>
            </div>
          </div>
        </header>

        <main class="admin-content">
          <div class="content-header d-flex justify-content-between align-items-center">
            <div>
              <h1>Contact Messages</h1>
              <p class="text-muted">Manage all contact form submissions</p>
            </div>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" style="width: 150px; background: rgba(255,255,255,0.05); color: #fff; border-color: rgba(255,255,255,0.1);">
                <option value="all" style="background: #1a1a2e; color: #fff;">All Status</option>
                <option value="new" style="background: #1a1a2e; color: #fff;">New</option>
                <option value="pending" style="background: #1a1a2e; color: #fff;">Pending</option>
                <option value="replied" style="background: #1a1a2e; color: #fff;">Replied</option>
              </select>
              <button class="btn btn-primary"><i class="bi bi-download me-2"></i>Export</button>
            </div>
          </div>

          <div class="admin-card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th><input type="checkbox" class="form-check-input" /></th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Industry</th>
                      <th>Message</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">RK</div><span>Rahul Kumar</span></div></td>
                      <td>rahul@example.com</td>
                      <td>+91 98765-43210</td>
                      <td><span class="badge-industry">Technology</span></td>
                      <td><span class="text-truncate d-inline-block" style="max-width: 150px;">Looking to collaborate on a UX project...</span></td>
                      <td>Mar 27, 2026</td>
                      <td><span class="status-badge new">New</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Reply"><i class="bi bi-reply"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">PS</div><span>Priya Sharma</span></div></td>
                      <td>priya@design.co</td>
                      <td>+91 87654-32109</td>
                      <td><span class="badge-industry">Design</span></td>
                      <td><span class="text-truncate d-inline-block" style="max-width: 150px;">Interested in joining the design team...</span></td>
                      <td>Mar 26, 2026</td>
                      <td><span class="status-badge pending">Pending</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Reply"><i class="bi bi-reply"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">AM</div><span>Arjun Mehta</span></div></td>
                      <td>arjun@startup.io</td>
                      <td>+91 76543-21098</td>
                      <td><span class="badge-industry">E-Commerce</span></td>
                      <td><span class="text-truncate d-inline-block" style="max-width: 150px;">Need help with our e-commerce UX...</span></td>
                      <td>Mar 25, 2026</td>
                      <td><span class="status-badge replied">Replied</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Reply"><i class="bi bi-reply"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">NS</div><span>Neha Singh</span></div></td>
                      <td>neha@health.org</td>
                      <td>+91 65432-10987</td>
                      <td><span class="badge-industry">Healthcare</span></td>
                      <td><span class="text-truncate d-inline-block" style="max-width: 150px;">Would love to discuss healthcare UX...</span></td>
                      <td>Mar 24, 2026</td>
                      <td><span class="status-badge replied">Replied</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Reply"><i class="bi bi-reply"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">VG</div><span>Vikram Gupta</span></div></td>
                      <td>vikram@fintech.com</td>
                      <td>+91 54321-09876</td>
                      <td><span class="badge-industry">Finance</span></td>
                      <td><span class="text-truncate d-inline-block" style="max-width: 150px;">Looking for design consultation...</span></td>
                      <td>Mar 23, 2026</td>
                      <td><span class="status-badge new">New</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Reply"><i class="bi bi-reply"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <nav class="pagination-wrapper">
                <ul class="pagination">
                  <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                  <li class="page-item active"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
              </nav>
            </div>
          </div>
        </main>
      </div>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
  </body>
</html>
