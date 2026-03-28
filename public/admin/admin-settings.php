<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Settings | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="img/LOGO.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/admin-style.css" />
    <style>
      .settings-section {
        margin-bottom: 24px;
      }
      .settings-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--admin-border);
      }
      .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid var(--admin-border);
      }
      .setting-item:last-child {
        border-bottom: none;
      }
      .setting-info h4 {
        font-size: 0.9375rem;
        font-weight: 500;
        margin-bottom: 4px;
      }
      .setting-info p {
        font-size: 0.8125rem;
        color: var(--text-muted);
        margin: 0;
      }
      .form-switch .form-check-input {
        width: 48px;
        height: 24px;
        cursor: pointer;
      }
      .form-switch .form-check-input:checked {
        background-color: var(--primary);
        border-color: var(--primary);
      }
      .danger-zone {
        border: 1px solid var(--danger);
        border-radius: 12px;
        padding: 20px;
        background: rgba(239, 68, 68, 0.05);
      }
      .danger-zone h4 {
        color: var(--danger);
        font-size: 1rem;
        margin-bottom: 8px;
      }
      .nav-tabs-custom {
        border-bottom: 1px solid var(--admin-border);
        gap: 4px;
      }
      .nav-tabs-custom .nav-link {
        background: none;
        border: none;
        color: var(--text-muted);
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        font-weight: 500;
      }
      .nav-tabs-custom .nav-link:hover {
        color: var(--text-primary);
      }
      .nav-tabs-custom .nav-link.active {
        background: var(--admin-card);
        color: var(--primary);
        border: 1px solid var(--admin-border);
        border-bottom: none;
      }
    </style>
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
            <li class="nav-item"><a href="admin-registrations.html" class="nav-link"><i class="bi bi-person-check-fill"></i><span>Registrations</span></a></li>
            <li class="nav-item"><a href="admin-resources.html" class="nav-link"><i class="bi bi-folder-fill"></i><span>Resources</span></a></li>
            <li class="nav-item"><a href="admin-settings.html" class="nav-link active"><i class="bi bi-gear-fill"></i><span>Settings</span></a></li>
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
                <li><a class="dropdown-item" href="admin-settings.html">Settings</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
              </ul>
            </div>
          </div>
        </header>

        <main class="admin-content">
          <div class="content-header">
            <h1>Settings</h1>
            <p class="text-muted">Manage your account and website settings</p>
          </div>

          <!-- Settings Tabs -->
          <ul class="nav nav-tabs-custom mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">General</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profile</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">Notifications</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">Security</button>
            </li>
          </ul>

          <div class="tab-content">
            <!-- General Settings -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
              <div class="row">
                <div class="col-lg-8">
                  <div class="admin-card">
                    <div class="card-body">
                      <div class="settings-section">
                        <h3>Website Information</h3>
                        <div class="mb-3">
                          <label class="form-label">Site Name</label>
                          <input type="text" class="form-control" value="UX Pacific Community" />
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Site Description</label>
                          <textarea class="form-control" rows="3">Join our community of designers and developers creating the future of digital experiences.</textarea>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" value="hello@uxpacific.com" />
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" class="form-control" value="+91 92740-61063" />
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Address</label>
                          <input type="text" class="form-control" value="512, D&C Majestic, Near Law Garden, Ahmedabad" />
                        </div>
                      </div>

                      <div class="settings-section">
                        <h3>Social Media Links</h3>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-instagram me-2"></i>Instagram</label>
                            <input type="url" class="form-control" value="https://www.instagram.com/official_uxpacific/" />
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-linkedin me-2"></i>LinkedIn</label>
                            <input type="url" class="form-control" value="https://www.linkedin.com/company/uxpacific/" />
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-dribbble me-2"></i>Dribbble</label>
                            <input type="url" class="form-control" value="https://dribbble.com/social-ux-pacific" />
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="bi bi-behance me-2"></i>Behance</label>
                            <input type="url" class="form-control" value="https://www.behance.net/ux_pacific" />
                          </div>
                        </div>
                      </div>

                      <button class="btn btn-primary">Save Changes</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Profile Settings -->
            <div class="tab-pane fade" id="profile" role="tabpanel">
              <div class="row">
                <div class="col-lg-8">
                  <div class="admin-card">
                    <div class="card-body">
                      <div class="settings-section">
                        <h3>Admin Profile</h3>
                        <div class="d-flex align-items-center gap-4 mb-4">
                          <div class="profile-avatar" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="bi bi-person-fill"></i>
                          </div>
                          <div>
                            <button class="btn btn-outline-primary btn-sm mb-2">Upload Photo</button>
                            <p class="text-muted mb-0" style="font-size: 0.8125rem;">JPG, PNG or GIF. Max size 2MB.</p>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" value="Admin" />
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" value="User" />
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Email Address</label>
                          <input type="email" class="form-control" value="admin@uxpacific.com" />
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Role</label>
                          <input type="text" class="form-control" value="Super Admin" disabled />
                        </div>
                      </div>
                      <button class="btn btn-primary">Update Profile</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Notification Settings -->
            <div class="tab-pane fade" id="notifications" role="tabpanel">
              <div class="row">
                <div class="col-lg-8">
                  <div class="admin-card">
                    <div class="card-body">
                      <div class="settings-section">
                        <h3>Email Notifications</h3>
                        <div class="setting-item">
                          <div class="setting-info">
                            <h4>New Contact Messages</h4>
                            <p>Receive email when someone submits the contact form</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked />
                          </div>
                        </div>
                        <div class="setting-item">
                          <div class="setting-info">
                            <h4>New Member Registrations</h4>
                            <p>Get notified when a new member joins the community</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked />
                          </div>
                        </div>
                        <div class="setting-item">
                          <div class="setting-info">
                            <h4>Event Registrations</h4>
                            <p>Receive updates when someone registers for an event</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" />
                          </div>
                        </div>
                        <div class="setting-item">
                          <div class="setting-info">
                            <h4>Weekly Reports</h4>
                            <p>Get a weekly summary of community activity</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked />
                          </div>
                        </div>
                      </div>
                      <button class="btn btn-primary">Save Preferences</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Security Settings -->
            <div class="tab-pane fade" id="security" role="tabpanel">
              <div class="row">
                <div class="col-lg-8">
                  <div class="admin-card mb-4">
                    <div class="card-body">
                      <div class="settings-section">
                        <h3>Change Password</h3>
                        <div class="mb-3">
                          <label class="form-label">Current Password</label>
                          <input type="password" class="form-control" placeholder="Enter current password" />
                        </div>
                        <div class="mb-3">
                          <label class="form-label">New Password</label>
                          <input type="password" class="form-control" placeholder="Enter new password" />
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Confirm New Password</label>
                          <input type="password" class="form-control" placeholder="Confirm new password" />
                        </div>
                      </div>
                      <button class="btn btn-primary">Update Password</button>
                    </div>
                  </div>

                  <div class="admin-card mb-4">
                    <div class="card-body">
                      <div class="settings-section mb-0">
                        <h3>Two-Factor Authentication</h3>
                        <div class="setting-item border-0 pt-0">
                          <div class="setting-info">
                            <h4>Enable 2FA</h4>
                            <p>Add an extra layer of security to your account</p>
                          </div>
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="danger-zone">
                    <h4><i class="bi bi-exclamation-triangle me-2"></i>Danger Zone</h4>
                    <p class="text-muted mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                    <button class="btn btn-danger">Delete Account</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/admin.js"></script>
  </body>
</html>
