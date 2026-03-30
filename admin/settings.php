<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Settings | Admin - UX Pacific</title>
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
            <li class="nav-item"><a href="contacts.php" class="nav-link"><i class="bi bi-envelope-fill"></i><span>Contact Messages</span></a></li>
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
            <li class="nav-item"><a href="settings.php" class="nav-link active"><i class="bi bi-gear-fill"></i><span>Settings</span></a></li>
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
            <input type="text" placeholder="Search settings..." aria-label="Search settings" />
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
          <div class="content-header">
            <h1>Settings</h1>
            <p class="text-muted">Manage system settings and preferences</p>
          </div>

          <div class="admin-card">
            <div class="card-header">
              <h2>General Settings</h2>
            </div>
            <div class="card-body">
              <form>
                <div class="mb-3">
                  <label class="form-label">Site Title</label>
                  <input type="text" class="form-control" value="UX Pacific Community" />
                </div>
                <div class="mb-3">
                  <label class="form-label">Site Description</label>
                  <textarea class="form-control" rows="3">A vibrant community for UX professionals in the Pacific region</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </form>
            </div>
          </div>

          <div class="admin-card mt-4">
            <div class="card-header">
              <h2>Email Settings</h2>
            </div>
            <div class="card-body">
              <form>
                <div class="mb-3">
                  <label class="form-label">From Email</label>
                  <input type="email" class="form-control" value="admin@uxpacific.com" />
                </div>
                <div class="mb-3">
                  <label class="form-label">SMTP Server</label>
                  <input type="text" class="form-control" placeholder="smtp.example.com" />
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </form>
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
