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

// Handle status change
if (isset($_GET['status']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    $allowedStatuses = ['pending', 'approved', 'rejected'];
    
    if (in_array($status, $allowedStatuses)) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE ideas SET status = :status WHERE id = :id");
            $stmt->execute([':status' => $status, ':id' => $id]);
            
            $message = 'Idea status updated.';
            $messageType = 'success';
        } catch (PDOException $e) {
            error_log("Update status error: " . $e->getMessage());
            $message = 'Failed to update status.';
            $messageType = 'danger';
        }
    }
}

// Fetch all ideas
$ideas = [];
$filter = $_GET['filter'] ?? 'all';

try {
    $pdo = getDB();
    $sql = "SELECT * FROM ideas";
    if ($filter !== 'all' && in_array($filter, ['pending', 'approved', 'rejected'])) {
        $sql .= " WHERE status = :status";
    }
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    if ($filter !== 'all' && in_array($filter, ['pending', 'approved', 'rejected'])) {
        $stmt->execute([':status' => $filter]);
    } else {
        $stmt->execute();
    }
    $ideas = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch ideas error: " . $e->getMessage());
}

// Count by status
$counts = ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0];
try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM ideas GROUP BY status");
    while ($row = $stmt->fetch()) {
        $counts[$row['status']] = $row['count'];
        $counts['total'] += $row['count'];
    }
} catch (PDOException $e) {
    // Silently fail
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Manage Ideas | UX Pacific Admin</title>

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
    <style>
      .idea-image-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
      }
      .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
      }
      .status-pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
      .status-approved { background: rgba(40, 167, 69, 0.2); color: #28a745; }
      .status-rejected { background: rgba(220, 53, 69, 0.2); color: #dc3545; }
      .filter-tabs .nav-link {
        color: rgba(255,255,255,0.7);
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
      }
      .filter-tabs .nav-link.active {
        background: rgba(123, 97, 255, 0.2);
        color: #7b61ff;
      }
      .action-btns .btn {
        padding: 4px 10px;
        font-size: 0.85rem;
      }
      .idea-desc {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
            <li class="nav-item">
              <a href="members.php" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span>Members</span>
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
              <?php if ($counts['pending'] > 0): ?>
              <span class="badge"><?php echo $counts['pending']; ?></span>
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
                <h3><?php echo $counts['total']; ?></h3>
                <p>Total Ideas</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-warning">
                <i class="bi bi-clock-fill"></i>
              </div>
              <div class="stat-info">
                <h3><?php echo $counts['pending']; ?></h3>
                <p>Pending Review</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-success">
                <i class="bi bi-check-circle-fill"></i>
              </div>
              <div class="stat-info">
                <h3><?php echo $counts['approved']; ?></h3>
                <p>Approved</p>
              </div>
            </div>
            <div class="stat-card">
              <div class="stat-icon bg-danger">
                <i class="bi bi-x-circle-fill"></i>
              </div>
              <div class="stat-info">
                <h3><?php echo $counts['rejected']; ?></h3>
                <p>Rejected</p>
              </div>
            </div>
          </div>

          <!-- FILTER TABS -->
          <ul class="nav filter-tabs mb-4">
            <li class="nav-item">
              <a class="nav-link <?php echo $filter === 'all' ? 'active' : ''; ?>" href="?filter=all">All</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo $filter === 'pending' ? 'active' : ''; ?>" href="?filter=pending">Pending</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo $filter === 'approved' ? 'active' : ''; ?>" href="?filter=approved">Approved</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo $filter === 'rejected' ? 'active' : ''; ?>" href="?filter=rejected">Rejected</a>
            </li>
          </ul>

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
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($ideas)): ?>
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                      <i class="bi bi-inbox display-6 d-block mb-2"></i>
                      No ideas found.
                    </td>
                  </tr>
                  <?php else: ?>
                  <?php foreach ($ideas as $idea): ?>
                  <tr>
                    <td>
                      <?php if ($idea['image']): ?>
                      <img src="../<?php echo h($idea['image']); ?>" alt="" class="idea-image-thumb">
                      <?php else: ?>
                      <div class="idea-image-thumb bg-secondary d-flex align-items-center justify-content-center">
                        <i class="bi bi-image text-muted"></i>
                      </div>
                      <?php endif; ?>
                    </td>
                    <td><strong><?php echo h($idea['title']); ?></strong></td>
                    <td>
                      <div><?php echo h($idea['name']); ?></div>
                      <small class="text-muted"><?php echo h($idea['email']); ?></small>
                    </td>
                    <td class="idea-desc" title="<?php echo h($idea['description']); ?>">
                      <?php echo h($idea['description']); ?>
                    </td>
                    <td>
                      <span class="status-badge status-<?php echo $idea['status']; ?>">
                        <?php echo ucfirst($idea['status']); ?>
                      </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($idea['created_at'])); ?></td>
                    <td class="action-btns">
                      <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                          Status
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="?id=<?php echo $idea['id']; ?>&status=approved">
                            <i class="bi bi-check-circle text-success me-2"></i>Approve
                          </a></li>
                          <li><a class="dropdown-item" href="?id=<?php echo $idea['id']; ?>&status=pending">
                            <i class="bi bi-clock text-warning me-2"></i>Pending
                          </a></li>
                          <li><a class="dropdown-item" href="?id=<?php echo $idea['id']; ?>&status=rejected">
                            <i class="bi bi-x-circle text-danger me-2"></i>Reject
                          </a></li>
                        </ul>
                      </div>
                      <a href="?delete=<?php echo $idea['id']; ?>" 
                         class="btn btn-sm btn-outline-danger"
                         onclick="return confirm('Are you sure you want to delete this idea?');">
                        <i class="bi bi-trash"></i>
                      </a>
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
