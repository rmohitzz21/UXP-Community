<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Admin Dashboard | UX Pacific Community</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../img/LOGO.png" />

    <!-- Bootstrap 5 CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />

    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../css/admin-style.css" />
  </head>

  <body>
    <div class="admin-wrapper">
      <!-- SIDEBAR -->
      <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
          <img src="../img/LOGO.png" alt="UX Pacific" class=""  width="150" />
          
          <button class="sidebar-close d-lg-none" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <nav class="sidebar-nav">
          <ul class="nav-list">
            <li class="nav-item">
              <a href="index.php" class="nav-link active">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="contacts.php" class="nav-link">
                <i class="bi bi-envelope-fill"></i>
                <span>Contact Messages</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="members.php" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span>Members</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="ideas.php" class="nav-link">
                <i class="bi bi-lightbulb-fill"></i>
                <span>Ideas</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="events.php" class="nav-link">
                <i class="bi bi-calendar-event-fill"></i>
                <span>Events</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="registrations.php" class="nav-link">
                <i class="bi bi-person-check-fill"></i>
                <span>Registrations</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="resources.php" class="nav-link">
                <i class="bi bi-folder-fill"></i>
                <span>Resources</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings.php" class="nav-link">
                <i class="bi bi-gear-fill"></i>
                <span>Settings</span>
              </a>
            </li>
          </ul>
        </nav>

        <div class="sidebar-footer">
          <a href="../index.php" class="nav-link">
            <i class="bi bi-box-arrow-left"></i>
            <span>View Website</span>
          </a>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <div class="admin-main">
        <!-- TOP BAR -->
        <header class="admin-topbar">
          <button class="sidebar-toggle d-lg-none" id="openSidebar">
            <i class="bi bi-list"></i>
          </button>

          <div class="topbar-search">
            <i class="bi bi-search"></i>
            <input
              type="text"
              placeholder="Search..."
              aria-label="Search dashboard"
            />
          </div>

          <div class="topbar-actions">
            <button class="topbar-btn" aria-label="Notifications">
              <i class="bi bi-bell"></i>
              <span class="badge">3</span>
            </button>

            <div class="dropdown">
              <button
                class="admin-profile dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <div class="profile-avatar">
                  <i class="bi bi-person-fill"></i>
                </div>
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

        <!-- DASHBOARD CONTENT -->
        <main class="admin-content">
          <div class="content-header">
            <h1>Dashboard</h1>
            <p class="text-muted">Welcome back! Here's your community overview.</p>
          </div>

          <!-- STATS CARDS -->
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon bg-primary">
                <i class="bi bi-people-fill"></i>
              </div>
              <div class="stat-info">
                <h3>1,248</h3>
                <p>Total Members</p>
              </div>
              <span class="stat-trend up">
                <i class="bi bi-arrow-up"></i> 12%
              </span>
            </div>

            <div class="stat-card">
              <div class="stat-icon bg-success">
                <i class="bi bi-envelope-fill"></i>
              </div>
              <div class="stat-info">
                <h3>56</h3>
                <p>New Messages</p>
              </div>
              <span class="stat-trend up">
                <i class="bi bi-arrow-up"></i> 8%
              </span>
            </div>

            <div class="stat-card">
              <div class="stat-icon bg-warning">
                <i class="bi bi-calendar-event-fill"></i>
              </div>
              <div class="stat-info">
                <h3>12</h3>
                <p>Upcoming Events</p>
              </div>
              <span class="stat-trend down">
                <i class="bi bi-arrow-down"></i> 3%
              </span>
            </div>

            <div class="stat-card">
              <div class="stat-icon bg-info">
                <i class="bi bi-eye-fill"></i>
              </div>
              <div class="stat-info">
                <h3>8.4K</h3>
                <p>Page Views</p>
              </div>
              <span class="stat-trend up">
                <i class="bi bi-arrow-up"></i> 24%
              </span>
            </div>
          </div>

          <!-- CHARTS & TABLES ROW -->
          <div class="row g-4 mt-2">
            <!-- RECENT CONTACTS -->
            <div class="col-lg-8">
              <div class="admin-card">
                <div class="card-header">
                  <h2>Recent Contact Messages</h2>
                  <a href="contacts.php" class="btn btn-sm btn-outline-primary">
                    View All
                  </a>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="admin-table">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Industry</th>
                          <th>Date</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <div class="user-info">
                              <div class="user-avatar">RK</div>
                              <span>Rahul Kumar</span>
                            </div>
                          </td>
                          <td>rahul@example.com</td>
                          <td><span class="badge-industry">Technology</span></td>
                          <td>Mar 27, 2026</td>
                          <td><span class="status-badge new">New</span></td>
                          <td>
                            <button class="btn-action" title="View">
                              <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn-action" title="Reply">
                              <i class="bi bi-reply"></i>
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="user-info">
                              <div class="user-avatar">PS</div>
                              <span>Priya Sharma</span>
                            </div>
                          </td>
                          <td>priya@design.co</td>
                          <td><span class="badge-industry">Design</span></td>
                          <td>Mar 26, 2026</td>
                          <td><span class="status-badge pending">Pending</span></td>
                          <td>
                            <button class="btn-action" title="View">
                              <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn-action" title="Reply">
                              <i class="bi bi-reply"></i>
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="user-info">
                              <div class="user-avatar">AM</div>
                              <span>Arjun Mehta</span>
                            </div>
                          </td>
                          <td>arjun@startup.io</td>
                          <td><span class="badge-industry">E-Commerce</span></td>
                          <td>Mar 25, 2026</td>
                          <td><span class="status-badge replied">Replied</span></td>
                          <td>
                            <button class="btn-action" title="View">
                              <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn-action" title="Reply">
                              <i class="bi bi-reply"></i>
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <div class="user-info">
                              <div class="user-avatar">NS</div>
                              <span>Neha Singh</span>
                            </div>
                          </td>
                          <td>neha@health.org</td>
                          <td><span class="badge-industry">Healthcare</span></td>
                          <td>Mar 24, 2026</td>
                          <td><span class="status-badge replied">Replied</span></td>
                          <td>
                            <button class="btn-action" title="View">
                              <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn-action" title="Reply">
                              <i class="bi bi-reply"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- QUICK ACTIONS & RECENT MEMBERS -->
            <div class="col-lg-4">
              <div class="admin-card mb-4">
                <div class="card-header">
                  <h2>Quick Actions</h2>
                </div>
                <div class="card-body">
                  <div class="quick-actions">
                    <a href="events.php" class="quick-action-btn">
                      <i class="bi bi-calendar-plus"></i>
                      <span>Add Event</span>
                    </a>
                    <a href="resources.php" class="quick-action-btn">
                      <i class="bi bi-upload"></i>
                      <span>Upload Resource</span>
                    </a>
                    <a href="members.php" class="quick-action-btn">
                      <i class="bi bi-person-plus"></i>
                      <span>Add Member</span>
                    </a>
                    <a href="#" class="quick-action-btn">
                      <i class="bi bi-megaphone"></i>
                      <span>Send Announcement</span>
                    </a>
                  </div>
                </div>
              </div>

              <div class="admin-card">
                <div class="card-header">
                  <h2>New Members</h2>
                  <a href="members.php" class="btn btn-sm btn-outline-primary">
                    View All
                  </a>
                </div>
                <div class="card-body">
                  <ul class="member-list">
                    <li class="member-item">
                      <div class="member-avatar">VR</div>
                      <div class="member-info">
                        <span class="member-name">Vikram Rao</span>
                        <span class="member-email">vikram@example.com</span>
                      </div>
                      <span class="member-date">Today</span>
                    </li>
                    <li class="member-item">
                      <div class="member-avatar">SP</div>
                      <div class="member-info">
                        <span class="member-name">Sneha Patel</span>
                        <span class="member-email">sneha@design.in</span>
                      </div>
                      <span class="member-date">Yesterday</span>
                    </li>
                    <li class="member-item">
                      <div class="member-avatar">AJ</div>
                      <div class="member-info">
                        <span class="member-name">Amit Joshi</span>
                        <span class="member-email">amit@tech.io</span>
                      </div>
                      <span class="member-date">2 days ago</span>
                    </li>
                    <li class="member-item">
                      <div class="member-avatar">KD</div>
                      <div class="member-info">
                        <span class="member-name">Kavya Desai</span>
                        <span class="member-email">kavya@startup.co</span>
                      </div>
                      <span class="member-date">3 days ago</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- UPCOMING EVENTS -->
          <div class="row g-4 mt-2">
            <div class="col-12">
              <div class="admin-card">
                <div class="card-header">
                  <h2>Upcoming Events</h2>
                  <a href="events.php" class="btn btn-sm btn-outline-primary">
                    Manage Events
                  </a>
                </div>
                <div class="card-body">
                  <div class="events-grid">
                    <div class="event-card">
                      <div class="event-date">
                        <span class="day">05</span>
                        <span class="month">APR</span>
                      </div>
                      <div class="event-info">
                        <h4>UX Design Workshop</h4>
                        <p><i class="bi bi-clock"></i> 10:00 AM - 2:00 PM</p>
                        <p><i class="bi bi-geo-alt"></i> Virtual Event</p>
                      </div>
                      <span class="event-attendees">
                        <i class="bi bi-people"></i> 48 registered
                      </span>
                    </div>

                    <div class="event-card">
                      <div class="event-date">
                        <span class="day">12</span>
                        <span class="month">APR</span>
                      </div>
                      <div class="event-info">
                        <h4>Design System Masterclass</h4>
                        <p><i class="bi bi-clock"></i> 3:00 PM - 5:00 PM</p>
                        <p><i class="bi bi-geo-alt"></i> Ahmedabad Office</p>
                      </div>
                      <span class="event-attendees">
                        <i class="bi bi-people"></i> 32 registered
                      </span>
                    </div>

                    <div class="event-card">
                      <div class="event-date">
                        <span class="day">20</span>
                        <span class="month">APR</span>
                      </div>
                      <div class="event-info">
                        <h4>Community Meetup</h4>
                        <p><i class="bi bi-clock"></i> 6:00 PM - 9:00 PM</p>
                        <p><i class="bi bi-geo-alt"></i> Law Garden, Ahmedabad</p>
                      </div>
                      <span class="event-attendees">
                        <i class="bi bi-people"></i> 85 registered
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
  </body>
</html>
