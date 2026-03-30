<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Members | Admin - UX Pacific</title>
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
          <img src="../img/LOGO.png" alt="UX Pacific" class="sidebar-logo" />
          <span class="sidebar-brand">UX Pacific</span>
          <button class="sidebar-close d-lg-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
        </div>
        <nav class="sidebar-nav">
          <ul class="nav-list">
            <li class="nav-item"><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a href="contacts.php" class="nav-link"><i class="bi bi-envelope-fill"></i><span>Contact Messages</span></a></li>
            <li class="nav-item"><a href="members.php" class="nav-link active"><i class="bi bi-people-fill"></i><span>Members</span></a></li>
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
            <input type="text" placeholder="Search members..." aria-label="Search members" />
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
              <h1>Members</h1>
              <p class="text-muted">Manage community members</p>
            </div>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" style="width: 150px; background: rgba(255,255,255,0.05); color: #fff; border-color: rgba(255,255,255,0.1);">
                <option value="all" style="background: #1a1a2e; color: #fff;">All Members</option>
                <option value="active" style="background: #1a1a2e; color: #fff;">Active</option>
                <option value="inactive" style="background: #1a1a2e; color: #fff;">Inactive</option>
              </select>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                <i class="bi bi-person-plus me-2"></i>Add Member
              </button>
            </div>
          </div>

          <!-- Members Stats -->
          <div class="stats-grid mb-4">
            <div class="stat-card">
              <div class="stat-icon bg-primary"><i class="bi bi-people-fill"></i></div>
              <div class="stat-info"><h3>1,248</h3><p>Total Members</p></div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-success"><i class="bi bi-person-check-fill"></i></div>
              <div class="stat-info"><h3>1,180</h3><p>Active Members</p></div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-warning"><i class="bi bi-person-plus-fill"></i></div>
              <div class="stat-info"><h3>68</h3><p>New This Month</p></div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-info"><i class="bi bi-star-fill"></i></div>
              <div class="stat-info"><h3>45</h3><p>Premium Members</p></div>
            </div>
          </div>

          <div class="admin-card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th><input type="checkbox" class="form-check-input" /></th>
                      <th>Member</th>
                      <th>Email</th>
                      <th>Joined</th>
                      <th>Role</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">VR</div><span>Vikram Rao</span></div></td>
                      <td>vikram@example.com</td>
                      <td>Mar 27, 2026</td>
                      <td><span class="badge-industry">Designer</span></td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">SP</div><span>Sneha Patel</span></div></td>
                      <td>sneha@design.in</td>
                      <td>Mar 26, 2026</td>
                      <td><span class="badge-industry">Developer</span></td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">AJ</div><span>Amit Joshi</span></div></td>
                      <td>amit@tech.io</td>
                      <td>Mar 25, 2026</td>
                      <td><span class="badge-industry">Designer</span></td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">KD</div><span>Kavya Desai</span></div></td>
                      <td>kavya@startup.co</td>
                      <td>Mar 24, 2026</td>
                      <td><span class="badge-industry">Researcher</span></td>
                      <td><span class="status-badge inactive">Inactive</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Edit"><i class="bi bi-pencil"></i></button>
                        <button class="btn-action" title="Delete"><i class="bi bi-trash"></i></button>
                      </td>
                    </tr>
                    <tr>
                      <td><input type="checkbox" class="form-check-input" /></td>
                      <td><div class="user-info"><div class="user-avatar">RB</div><span>Ravi Bansal</span></div></td>
                      <td>ravi@agency.com</td>
                      <td>Mar 23, 2026</td>
                      <td><span class="badge-industry">Designer</span></td>
                      <td><span class="status-badge active">Active</span></td>
                      <td>
                        <button class="btn-action" title="View"><i class="bi bi-eye"></i></button>
                        <button class="btn-action" title="Edit"><i class="bi bi-pencil"></i></button>
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

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--admin-sidebar); border: 1px solid var(--admin-border);">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="addMemberModalLabel">Add New Member</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form>
              <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" placeholder="Enter full name" />
              </div>
              <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" placeholder="Enter email address" />
              </div>
              <div class="mb-3">
                <label class="form-label">Role</label>
                <select class="form-select">
                  <option value="" style="background: #1a1a2e; color: #6b6b80;">Select role</option>
                  <option value="designer" style="background: #1a1a2e; color: #fff;">Designer</option>
                  <option value="developer" style="background: #1a1a2e; color: #fff;">Developer</option>
                  <option value="researcher" style="background: #1a1a2e; color: #fff;">Researcher</option>
                  <option value="manager" style="background: #1a1a2e; color: #fff;">Manager</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select">
                  <option value="active" style="background: #1a1a2e; color: #fff;">Active</option>
                  <option value="inactive" style="background: #1a1a2e; color: #fff;">Inactive</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Add Member</button>
          </div>
        </div>
      </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
  </body>
</html>
