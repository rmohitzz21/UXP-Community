<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

$message = '';
$messageType = '';

// Check for flash message from redirect
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $messageType = $_SESSION['flash_type'] ?? 'info';
    unset($_SESSION['flash_message'], $_SESSION['flash_type']);
}

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $_SESSION['flash_message'] = 'Message deleted successfully.';
        $_SESSION['flash_type'] = 'success';
        header('Location: contacts.php');
        exit;
    } catch (PDOException $e) {
        error_log("Delete contact error: " . $e->getMessage());
        $message = 'Failed to delete message.';
        $messageType = 'danger';
    }
}

// Handle reply action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_action'])) {
    $contactId = (int)($_POST['contact_id'] ?? 0);
    $replyMessage = trim($_POST['reply_message'] ?? '');
    $recipientEmail = trim($_POST['recipient_email'] ?? '');
    $recipientName = trim($_POST['recipient_name'] ?? '');
    
    if ($contactId > 0 && !empty($replyMessage) && !empty($recipientEmail)) {
        // Send email
        $to = $recipientEmail;
        $subject = "Reply from UX Pacific Community";
        $headers = "From: hello@uxpacific.com\r\n";
        $headers .= "Reply-To: hello@uxpacific.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $emailBody = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <h2 style='color: #6147bd;'>Hello $recipientName,</h2>
            <p>Thank you for reaching out to UX Pacific Community!</p>
            <div style='background: #f5f5f5; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                " . nl2br(htmlspecialchars($replyMessage)) . "
            </div>
            <p>Best regards,<br><strong>UX Pacific Team</strong></p>
            <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
            <p style='font-size: 12px; color: #888;'>
                UX Pacific Community | hello@uxpacific.com | +91 92740-61063
            </p>
        </body>
        </html>
        ";
        
        if (mail($to, $subject, $emailBody, $headers)) {
            // Update status to replied
            try {
                $pdo = getDB();
                $stmt = $pdo->prepare("UPDATE contacts SET status = 'replied' WHERE id = :id");
                $stmt->execute([':id' => $contactId]);
                
                $_SESSION['flash_message'] = 'Reply sent successfully to ' . h($recipientEmail);
                $_SESSION['flash_type'] = 'success';
            } catch (PDOException $e) {
                error_log("Update contact status error: " . $e->getMessage());
            }
        } else {
            $_SESSION['flash_message'] = 'Failed to send email. Please check your mail configuration.';
            $_SESSION['flash_type'] = 'danger';
        }
        header('Location: contacts.php');
        exit;
    } else {
        $message = 'Please fill in all required fields.';
        $messageType = 'danger';
    }
}

// Handle status update
if (isset($_GET['status']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];
    
    if (in_array($status, ['new', 'pending', 'replied'])) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("UPDATE contacts SET status = :status WHERE id = :id");
            $stmt->execute([':status' => $status, ':id' => $id]);
            
            $_SESSION['flash_message'] = 'Status updated successfully.';
            $_SESSION['flash_type'] = 'success';
            header('Location: contacts.php');
            exit;
        } catch (PDOException $e) {
            error_log("Update status error: " . $e->getMessage());
        }
    }
}

// Search and filter
$search = trim($_GET['search'] ?? '');
$statusFilter = $_GET['filter'] ?? 'all';

// Fetch all contacts
$contacts = [];
try {
    $pdo = getDB();
    
    // Create table if not exists
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contacts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            industry VARCHAR(50),
            message TEXT NOT NULL,
            status ENUM('new', 'pending', 'replied') DEFAULT 'new',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    $sql = "SELECT * FROM contacts WHERE 1=1";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " AND (name LIKE :search OR email LIKE :search2)";
        $params[':search'] = "%$search%";
        $params[':search2'] = "%$search%";
    }
    
    if ($statusFilter !== 'all' && in_array($statusFilter, ['new', 'pending', 'replied'])) {
        $sql .= " AND status = :status";
        $params[':status'] = $statusFilter;
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $contacts = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch contacts error: " . $e->getMessage());
}

$totalContacts = count($contacts);

// Count by status
$statusCounts = ['new' => 0, 'pending' => 0, 'replied' => 0];
try {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM contacts GROUP BY status");
    while ($row = $stmt->fetch()) {
        $statusCounts[$row['status']] = $row['count'];
    }
} catch (PDOException $e) {
    // Ignore
}

function getInitials($name) {
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= strtoupper($word[0]);
        }
        if (strlen($initials) >= 2) break;
    }
    return $initials ?: 'U';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Contact Messages | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="../img/faviconUXP444@4x-789.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin-style.css" />
    <style>
      .alert-custom {
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
      }
      .alert-success {
        background: rgba(74, 222, 128, 0.15);
        border: 1px solid rgba(74, 222, 128, 0.3);
        color: #4ade80;
      }
      .alert-danger {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
      }
      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
      }
      .stat-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
      }
      .stat-card h3 {
        font-size: 2rem;
        margin: 0;
        background: linear-gradient(135deg, #7b61ff, #00d4ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      .stat-card p {
        margin: 8px 0 0;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
      }
      .message-preview {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
      .badge-industry {
        background: rgba(123, 97, 255, 0.2);
        color: #a78bfa;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
      }
      .reply-modal .modal-content {
        background: #1a1a2e;
        border: 1px solid rgba(255, 255, 255, 0.1);
      }
      .reply-modal .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      }
      .reply-modal .modal-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
      }
      .reply-modal .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
      }
      .reply-modal .form-control:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: #7b61ff;
        box-shadow: none;
      }
      .view-modal .original-message {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
      }
      .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: rgba(255, 255, 255, 0.5);
      }
      .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
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
            <li class="nav-item"><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a href="contacts.php" class="nav-link active"><i class="bi bi-envelope-fill"></i><span>Contact Messages</span></a></li>
            <!-- <li class="nav-item"><a href="members.php" class="nav-link"><i class="bi bi-people-fill"></i><span>Members</span></a></li> -->
                        <li class="nav-item"><a href="team.php" class="nav-link"><i class="bi bi-calendar-event-fill"></i><span>Team</span></a></li> 
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
          <form class="topbar-search" method="GET" action="contacts.php">
            <i class="bi bi-search"></i>
            <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo h($search); ?>" aria-label="Search messages" />
            <?php if ($statusFilter !== 'all'): ?>
            <input type="hidden" name="filter" value="<?php echo h($statusFilter); ?>">
            <?php endif; ?>
          </form>
          <div class="topbar-actions">
            <button class="topbar-btn" aria-label="Notifications"><i class="bi bi-bell"></i><span class="badge"><?php echo $statusCounts['new']; ?></span></button>
            <div class="dropdown">
              <button class="admin-profile dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="profile-avatar"><i class="bi bi-person-fill"></i></div>
                <span class="profile-name"><?php echo h($_SESSION['admin_username'] ?? 'Admin'); ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                <li><hr class="dropdown-divider" /></li>
                <li><a class="dropdown-item text-danger" href="?logout=1">Logout</a></li>
              </ul>
            </div>
          </div>
        </header>

        <main class="admin-content">
          <?php if ($message): ?>
          <div class="alert alert-<?php echo $messageType; ?> alert-custom alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php endif; ?>

          <div class="content-header d-flex justify-content-between align-items-center">
            <div>
              <h1>Contact Messages</h1>
              <p class="text-muted">Manage all contact form submissions</p>
            </div>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" id="statusFilter" style="width: 150px; background: rgba(255,255,255,0.05); color: #fff; border-color: rgba(255,255,255,0.1);">
                <option value="all" <?php echo $statusFilter === 'all' ? 'selected' : ''; ?> style="background: #1a1a2e; color: #fff;">All Status</option>
                <option value="new" <?php echo $statusFilter === 'new' ? 'selected' : ''; ?> style="background: #1a1a2e; color: #fff;">New (<?php echo $statusCounts['new']; ?>)</option>
                <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?> style="background: #1a1a2e; color: #fff;">Pending (<?php echo $statusCounts['pending']; ?>)</option>
                <option value="replied" <?php echo $statusFilter === 'replied' ? 'selected' : ''; ?> style="background: #1a1a2e; color: #fff;">Replied (<?php echo $statusCounts['replied']; ?>)</option>
              </select>
            </div>
          </div>

          <!-- Stats -->
          <div class="stats-grid">
            <div class="stat-card">
              <h3><?php echo $totalContacts; ?></h3>
              <p>Total Messages</p>
            </div>
            <div class="stat-card">
              <h3><?php echo $statusCounts['new']; ?></h3>
              <p>New Messages</p>
            </div>
            <div class="stat-card">
              <h3><?php echo $statusCounts['pending']; ?></h3>
              <p>Pending</p>
            </div>
            <div class="stat-card">
              <h3><?php echo $statusCounts['replied']; ?></h3>
              <p>Replied</p>
            </div>
          </div>

          <div class="admin-card">
            <div class="card-body">
              <?php if (empty($contacts)): ?>
              <div class="empty-state">
                <i class="bi bi-envelope-open"></i>
                <h4>No messages found</h4>
                <p><?php echo !empty($search) ? 'Try a different search term.' : 'Contact messages will appear here.'; ?></p>
              </div>
              <?php else: ?>
              <div class="table-responsive">
                <table class="admin-table">
                  <thead>
                    <tr>
                      <th style="width: 40px;"><input type="checkbox" class="form-check-input" id="selectAll" /></th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Industry</th>
                      <th>Message</th>
                      <th>Date</th>
                      <!-- <th>Status</th> -->
                      <th style="width: 120px;">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                      <td><input type="checkbox" class="form-check-input row-checkbox" value="<?php echo $contact['id']; ?>" /></td>
                      <td>
                        <div class="user-info">
                          <div class="user-avatar"><?php echo getInitials($contact['name']); ?></div>
                          <span><?php echo h($contact['name']); ?></span>
                        </div>
                      </td>
                      <td><?php echo h($contact['email']); ?></td>
                      <td><?php echo h($contact['phone'] ?: '-'); ?></td>
                      <td>
                        <?php if ($contact['industry']): ?>
                        <span class="badge-industry"><?php echo h($contact['industry']); ?></span>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                      </td>
                      <td><span class="message-preview" title="<?php echo h($contact['message']); ?>"><?php echo h(substr($contact['message'], 0, 50)); ?><?php echo strlen($contact['message']) > 50 ? '...' : ''; ?></span></td>
                      <td><?php echo date('M d, Y', strtotime($contact['created_at'])); ?></td>
                     
                      <td>
                        <button class="btn-action" title="View" data-bs-toggle="modal" data-bs-target="#viewModal" 
                          data-id="<?php echo $contact['id']; ?>"
                          data-name="<?php echo h($contact['name']); ?>"
                          data-email="<?php echo h($contact['email']); ?>"
                          data-phone="<?php echo h($contact['phone']); ?>"
                          data-industry="<?php echo h($contact['industry']); ?>"
                          data-message="<?php echo h($contact['message']); ?>"
                          data-date="<?php echo date('F d, Y \a\t h:i A', strtotime($contact['created_at'])); ?>"
                          >
                          <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn-action" title="Reply" data-bs-toggle="modal" data-bs-target="#replyModal"
                          data-id="<?php echo $contact['id']; ?>"
                          data-name="<?php echo h($contact['name']); ?>"
                          data-email="<?php echo h($contact['email']); ?>"
                          data-message="<?php echo h($contact['message']); ?>">
                          <i class="bi bi-reply"></i>
                        </button>
                        <a href="contacts.php?delete=<?php echo $contact['id']; ?>" class="btn-action" title="Delete" 
                          onclick="return confirm('Are you sure you want to delete this message?');">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade view-modal" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: #1a1a2e; border: 1px solid rgba(255,255,255,0.1);">
          <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="modal-title text-white" id="viewModalLabel">Message Details</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-white">
            <div class="d-flex align-items-center mb-3">
              <div class="user-avatar me-3" id="viewInitials" style="width: 50px; height: 50px; font-size: 1.2rem;"></div>
              <div>
                <h5 class="mb-0" id="viewName"></h5>
                <small class="text-muted" id="viewEmail"></small>
              </div>
              <span class="status-badge ms-auto" id="viewStatus"></span>
            </div>
            <div class="row mb-2">
              <div class="col-6">
                <small class="text-muted">Phone</small>
                <p id="viewPhone" class="mb-0"></p>
              </div>
              <div class="col-6">
                <small class="text-muted">Industry</small>
                <p id="viewIndustry" class="mb-0"></p>
              </div>
            </div>
            <small class="text-muted">Received</small>
            <p id="viewDate" class="mb-0"></p>
            <div class="original-message">
              <small class="text-muted">Message</small>
              <p id="viewMessage" class="mb-0 mt-2" style="white-space: pre-wrap;"></p>
            </div>
          </div>
          <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.1);">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="viewReplyBtn">
              <i class="bi bi-reply me-1"></i> Reply
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Reply Modal -->
    <div class="modal fade reply-modal" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="replyModalLabel">Reply to <span id="replyToName"></span></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="contacts.php">
            <div class="modal-body">
              <input type="hidden" name="reply_action" value="1">
              <input type="hidden" name="contact_id" id="replyContactId">
              <input type="hidden" name="recipient_email" id="replyEmail">
              <input type="hidden" name="recipient_name" id="replyName">
              
              <div class="mb-3">
                <label class="form-label text-white">Sending to:</label>
                <p class="text-muted mb-0" id="replyToEmail"></p>
              </div>
              
              <div class="mb-3">
                <label class="form-label text-white">Original Message:</label>
                <div class="original-message" style="background: rgba(255,255,255,0.05); padding: 12px; border-radius: 8px; max-height: 100px; overflow-y: auto;">
                  <small id="originalMessage" class="text-muted"></small>
                </div>
              </div>
              
              <div class="mb-3">
                <label for="replyMessage" class="form-label text-white">Your Reply:</label>
                <textarea class="form-control" id="replyMessage" name="reply_message" rows="5" placeholder="Type your reply here..." required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-1"></i> Send Reply
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
    <script>
      // Status filter
      document.getElementById('statusFilter').addEventListener('change', function() {
        const filter = this.value;
        const search = '<?php echo addslashes($search); ?>';
        let url = 'contacts.php';
        const params = [];
        
        if (filter !== 'all') params.push('filter=' + filter);
        if (search) params.push('search=' + encodeURIComponent(search));
        
        if (params.length > 0) url += '?' + params.join('&');
        window.location.href = url;
      });

      // View Modal
      const viewModal = document.getElementById('viewModal');
      viewModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        
        const name = button.getAttribute('data-name');
        const initials = name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
        
        document.getElementById('viewInitials').textContent = initials;
        document.getElementById('viewName').textContent = name;
        document.getElementById('viewEmail').textContent = button.getAttribute('data-email');
        document.getElementById('viewPhone').textContent = button.getAttribute('data-phone') || '-';
        document.getElementById('viewIndustry').textContent = button.getAttribute('data-industry') || '-';
        document.getElementById('viewMessage').textContent = button.getAttribute('data-message');
        document.getElementById('viewDate').textContent = button.getAttribute('data-date');
        
        const status = button.getAttribute('data-status');
        const statusBadge = document.getElementById('viewStatus');
        statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        statusBadge.className = 'status-badge ' + status;
        
        // Set up reply button
        document.getElementById('viewReplyBtn').onclick = function() {
          const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
          document.getElementById('replyContactId').value = button.getAttribute('data-id');
          document.getElementById('replyEmail').value = button.getAttribute('data-email');
          document.getElementById('replyName').value = name;
          document.getElementById('replyToName').textContent = name;
          document.getElementById('replyToEmail').textContent = button.getAttribute('data-email');
          document.getElementById('originalMessage').textContent = button.getAttribute('data-message');
          
          bootstrap.Modal.getInstance(viewModal).hide();
          replyModal.show();
        };
      });

      // Reply Modal
      const replyModal = document.getElementById('replyModal');
      replyModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button) return;
        
        document.getElementById('replyContactId').value = button.getAttribute('data-id');
        document.getElementById('replyEmail').value = button.getAttribute('data-email');
        document.getElementById('replyName').value = button.getAttribute('data-name');
        document.getElementById('replyToName').textContent = button.getAttribute('data-name');
        document.getElementById('replyToEmail').textContent = button.getAttribute('data-email');
        document.getElementById('originalMessage').textContent = button.getAttribute('data-message');
        document.getElementById('replyMessage').value = '';
      });

      // Select all checkbox
      document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
      });
    </script>
  </body>
</html>
