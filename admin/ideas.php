<?php
require_once __DIR__ . '/../includes/db.php';

$message = '';
$messageType = '';

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        $pdo = getDB();
        
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image FROM ideas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $idea = $stmt->fetch();
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM ideas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        // Delete image file if exists
        if ($idea && $idea['image']) {
            deleteUploadedFile($idea['image']);
        }
        
        $message = 'Idea deleted successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        error_log("Delete idea error: " . $e->getMessage());
        $message = 'Failed to delete idea.';
        $messageType = 'danger';
    }
}

// Fetch all ideas
$ideas = [];

try {
    $pdo = getDB();
    $sql = "SELECT * FROM ideas ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $ideas = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch ideas error: " . $e->getMessage());
}

// Count total ideas
$totalIdeas = count($ideas);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Manage Ideas | UX Pacific Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../img/faviconUXP444@4x-789.png" />

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
    <style>
      /* Ideas Page Custom Styles */
      .table-card {
        background: #1a1a2e;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.05);
      }
      
      .table {
        color: #fff;
        margin-bottom: 0;
        table-layout: fixed;
      }
      
      .table thead th {
        background: rgba(255, 255, 255, 0.05);
        color: #a0a0b8;
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 14px 12px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: middle;
      }
      
      /* Column widths */
      .table th:nth-child(1),
      .table td:nth-child(1) { width: 90px; }  /* Image */
      .table th:nth-child(2),
      .table td:nth-child(2) { width: 180px; } /* Title */
      .table th:nth-child(3),
      .table td:nth-child(3) { width: 180px; } /* Submitted By */
      .table th:nth-child(4),
      .table td:nth-child(4) { width: auto; }  /* Description */
      .table th:nth-child(5),
      .table td:nth-child(5) { width: 100px; } /* Date */
      .table th:nth-child(6),
      .table td:nth-child(6) { width: 160px; } /* Actions */
      
      .table tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: #e0e0e0;
        background: #1a1a2e;
        height: 90px;
      }
      
      .table tbody tr:hover td {
        background: rgba(123, 97, 255, 0.1);
      }
      
      .idea-image-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        display: block;
      }
      
      .idea-title {
        font-weight: 600;
        color: #fff;
        font-size: 0.95rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      
      .idea-author {
        display: flex;
        flex-direction: column;
        gap: 2px;
      }
      
      .idea-author-name {
        font-weight: 500;
        color: #fff;
        font-size: 0.9rem;
      }
      
      .idea-author-email {
        color: #7b61ff;
        font-size: 0.8rem;
        word-break: break-all;
      }
      
      .idea-desc {
        color: #a0a0b8;
        font-size: 0.85rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      
      .idea-date {
        color: #888;
        font-size: 0.85rem;
        white-space: nowrap;
      }
      
      .action-btns {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
      }
      
      .action-btns .btn {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 6px;
        font-weight: 500;
        white-space: nowrap;
      }
      
      .action-btns .btn-outline-primary {
        border-color: #7b61ff;
        color: #7b61ff;
      }
      
      .action-btns .btn-outline-primary:hover {
        background: #7b61ff;
        color: #fff;
      }
      
      .action-btns .btn-outline-danger:hover {
        background: #dc3545;
        color: #fff;
      }
      
      /* Stats Card Styling */
      .stat-card {
        background: #1a1a2e;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        border: 1px solid rgba(255, 255, 255, 0.05);
      }
      
      .stat-card .stat-info h3 {
        color: #fff;
      }
      
      .stat-card .stat-info p {
        color: #a0a0b8;
      }
      
      /* Empty State */
      .empty-state {
        padding: 60px 20px;
        text-align: center;
      }
      
      .empty-state i {
        font-size: 4rem;
        color: #6b6b80;
        margin-bottom: 16px;
      }
      
      .empty-state p {
        color: #a0a0b8;
        font-size: 1.1rem;
      }
      
      /* No image placeholder */
      .no-image {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .no-image i {
        font-size: 1.2rem;
        color: #666;
      }
    </style>
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
            <li class="nav-item">
              <a href="index.php" class="nav-link">
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
            <!-- <li class="nav-item">
              <a href="members.php" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span>Members</span>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="team.php" class="nav-link">
                <i class="bi bi-person-badge-fill"></i>
                <span>Team</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="ideas.php" class="nav-link active">
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
            <input type="text" placeholder="Search ideas..." id="searchInput" />
          </div>

          <div class="topbar-actions">
            <button class="topbar-btn" aria-label="Notifications">
              <i class="bi bi-bell"></i>
              <?php if ($totalIdeas > 0): ?>
              <span class="badge"><?php echo $totalIdeas; ?></span>
              <?php endif; ?>
            </button>

            <div class="dropdown">
              <button class="admin-profile dropdown-toggle" type="button" data-bs-toggle="dropdown">
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

        <!-- IDEAS CONTENT -->
        <main class="admin-content">
          <div class="content-header">
            <h1><i class="bi bi-lightbulb-fill me-2"></i>Manage Ideas</h1>
            <p class="text-muted">Review and manage community idea submissions.</p>
          </div>

          <?php if ($message): ?>
          <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo h($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php endif; ?>

          <!-- STATS CARDS -->
          <div class="stats-grid mb-4">
            <div class="stat-card">
              <div class="stat-icon bg-primary">
                <i class="bi bi-lightbulb-fill"></i>
              </div>
              <div class="stat-info">
                <h3><?php echo $totalIdeas; ?></h3>
                <p>Total Ideas</p>
              </div>
            </div>
          </div>


          <!-- IDEAS TABLE -->
          <div class="table-card">
            <div class="table-responsive">
              <table class="table table-hover" id="ideasTable">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Submitted By</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($ideas)): ?>
                  <tr>
                    <td colspan="6">
                      <div class="empty-state">
                        <i class="bi bi-lightbulb"></i>
                        <p>No ideas submitted yet.</p>
                      </div>
                    </td>
                  </tr>
                  <?php else: ?>
                  <?php foreach ($ideas as $idea): 
                    // Build mailto link with pre-filled subject and body
                    $emailSubject = rawurlencode("Re: Your Idea - " . $idea['title']);
                    $emailBody = rawurlencode("Hi " . $idea['name'] . ",\n\nThank you for submitting your idea: \"" . $idea['title'] . "\"\n\nWe wanted to reach out regarding your submission.\n\n---\nYour original idea:\n" . $idea['description'] . "\n---\n\nBest regards,\nUX Pacific Team\nHello@uxpacific.com");
                    $mailtoLink = "mailto:" . h($idea['email']) . "?subject=" . $emailSubject . "&body=" . $emailBody;
                  ?>
                  <tr>
                    <td>
                      <?php if (!empty($idea['image'])): ?>
                      <img src="../<?php echo h($idea['image']); ?>" alt="" class="idea-image-thumb">
                      <?php else: ?>
                      <div class="no-image">
                        <i class="bi bi-image"></i>
                      </div>
                      <?php endif; ?>
                    </td>
                    <td><span class="idea-title"><?php echo h($idea['title']); ?></span></td>
                    <td>
                      <div class="idea-author">
                        <span class="idea-author-name"><?php echo h($idea['name']); ?></span>
                        <span class="idea-author-email"><?php echo h($idea['email']); ?></span>
                      </div>
                    </td>
                    <td><div class="idea-desc"><?php echo h($idea['description']); ?></div></td>
                    <td><span class="idea-date"><?php echo date('M d, Y', strtotime($idea['created_at'])); ?></span></td>
                    <td>
                      <div class="action-btns">
                        <a href="<?php echo $mailtoLink; ?>" 
                           class="btn btn-sm btn-outline-primary"
                           title="Reply via Email"
                           target="_blank"
                           rel="noopener">
                          <i class="bi bi-envelope-fill"></i> Reply
                        </a>
                        <a href="?delete=<?php echo $idea['id']; ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Are you sure you want to delete this idea?');">
                          <i class="bi bi-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Sidebar toggle
      const sidebar = document.getElementById('sidebar');
      const openBtn = document.getElementById('openSidebar');
      const closeBtn = document.getElementById('closeSidebar');

      if (openBtn) openBtn.addEventListener('click', () => sidebar.classList.add('active'));
      if (closeBtn) closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));

      // Search filter
      const searchInput = document.getElementById('searchInput');
      const table = document.getElementById('ideasTable');
      
      if (searchInput && table) {
        searchInput.addEventListener('input', function() {
          const filter = this.value.toLowerCase();
          const rows = table.querySelectorAll('tbody tr');
          
          rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
          });
        });
      }
    </script>
  </body>
</html>
