<?php
require_once __DIR__ . '/../includes/db.php';

$message = '';
$messageType = '';

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM event_registrations WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $message = 'Registration deleted successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        error_log("Delete registration error: " . $e->getMessage());
        $message = 'Failed to delete registration.';
        $messageType = 'danger';
    }
}

// Get filters
$eventFilter = $_GET['event'] ?? '';
$search = $_GET['search'] ?? '';

// Fetch all events for filter dropdown
$events = [];
try {
    $pdo = getDB();
    $events = $pdo->query("SELECT id, title FROM events ORDER BY event_date DESC")->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch events error: " . $e->getMessage());
}

// Fetch registrations with filters
$registrations = [];
try {
    $pdo = getDB();
    $sql = "SELECT r.*, e.title as event_title, e.event_date, e.event_type 
            FROM event_registrations r 
            JOIN events e ON r.event_id = e.id 
            WHERE 1=1";
    $params = [];
    
    if (!empty($eventFilter)) {
        $sql .= " AND r.event_id = :event_id";
        $params[':event_id'] = (int)$eventFilter;
    }
    
    if (!empty($search)) {
        $sql .= " AND (r.name LIKE :search OR r.email LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }
    
    $sql .= " ORDER BY r.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $registrations = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch registrations error: " . $e->getMessage());
}

// Get stats
$stats = ['total' => 0, 'today' => 0, 'this_week' => 0];
try {
    $pdo = getDB();
    $stats['total'] = $pdo->query("SELECT COUNT(*) FROM event_registrations")->fetchColumn();
    $stats['today'] = $pdo->query("SELECT COUNT(*) FROM event_registrations WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    $stats['this_week'] = $pdo->query("SELECT COUNT(*) FROM event_registrations WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetchColumn();
} catch (PDOException $e) {
    // Silently fail
}

// Get initials from name
function getInitials($name) {
    $words = explode(' ', trim($name));
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
    <title>Event Registrations | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="../img/faviconUXP444@4x-789.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin-style.css" />
    <style>
        .table-card {
            background: #1a1a2e;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .table { color: #fff; margin-bottom: 0; }
        .table thead th {
            background: rgba(255,255,255,0.05);
            color: #a0a0b8;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 14px 12px;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .table tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: #e0e0e0;
            background: #1a1a2e;
        }
        .table tbody tr:hover td { background: rgba(123,97,255,0.1); }
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #7b61ff, #9d4edd);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            color: #fff;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .event-badge {
            background: rgba(123,97,255,0.2);
            color: #7b61ff;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .action-btns .btn {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
        }
        .filter-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-form .form-select, .filter-form .form-control {
            background: #0f0f23;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            min-width: 180px;
        }
        .filter-form .form-select:focus, .filter-form .form-control:focus {
            background: #0f0f23;
            border-color: #7b61ff;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(123,97,255,0.25);
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="../img/LOGO.png" alt="UX Pacific" width="150" />
                <button class="sidebar-close d-lg-none" id="closeSidebar"><i class="bi bi-x-lg"></i></button>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item"><a href="index.php" class="nav-link"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a href="contacts.php" class="nav-link"><i class="bi bi-envelope-fill"></i><span>Contact Messages</span></a></li>
                    <li class="nav-item"><a href="members.php" class="nav-link"><i class="bi bi-people-fill"></i><span>Members</span></a></li>
                    <li class="nav-item"><a href="team.php" class="nav-link"><i class="bi bi-person-badge-fill"></i><span>Team</span></a></li>
                    <li class="nav-item"><a href="ideas.php" class="nav-link"><i class="bi bi-lightbulb-fill"></i><span>Ideas</span></a></li>
                    <li class="nav-item"><a href="events.php" class="nav-link"><i class="bi bi-calendar-event-fill"></i><span>Events</span></a></li>
                    <li class="nav-item"><a href="registrations.php" class="nav-link active"><i class="bi bi-person-check-fill"></i><span>Registrations</span></a></li>
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
                <form class="topbar-search" method="GET">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo h($search); ?>" />
                    <?php if ($eventFilter): ?>
                    <input type="hidden" name="event" value="<?php echo h($eventFilter); ?>" />
                    <?php endif; ?>
                </form>
                <div class="topbar-actions">
                    <button class="topbar-btn" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        <?php if ($stats['today'] > 0): ?>
                        <span class="badge"><?php echo $stats['today']; ?></span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown">
                        <button class="admin-profile dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                <div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1><i class="bi bi-person-check-fill me-2"></i>Event Registrations</h1>
                        <p class="text-muted">Manage all event registrations</p>
                    </div>
                    <form class="filter-form" method="GET">
                        <select class="form-select form-select-sm" name="event" onchange="this.form.submit()">
                            <option value="">All Events</option>
                            <?php foreach ($events as $evt): ?>
                            <option value="<?php echo $evt['id']; ?>" <?php echo $eventFilter == $evt['id'] ? 'selected' : ''; ?>>
                                <?php echo h($evt['title']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($search): ?>
                        <input type="hidden" name="search" value="<?php echo h($search); ?>" />
                        <?php endif; ?>
                    </form>
                </div>

                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Stats -->
                <div class="stats-grid mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary"><i class="bi bi-person-check-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['total']; ?></h3><p>Total Registrations</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-success"><i class="bi bi-calendar-check"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['today']; ?></h3><p>Today</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-info"><i class="bi bi-graph-up"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['this_week']; ?></h3><p>This Week</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-warning"><i class="bi bi-calendar-event"></i></div>
                        <div class="stat-info"><h3><?php echo count($events); ?></h3><p>Total Events</p></div>
                    </div>
                </div>

                <!-- Registrations Table -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Attendee</th>
                                    <th>Email</th>
                                    <th>Event</th>
                                    <th>Event Date</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($registrations)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox display-4 d-block mb-3 text-muted"></i>
                                        <p class="text-muted">No registrations found.</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($registrations as $reg): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar"><?php echo getInitials($reg['name']); ?></div>
                                            <div>
                                                <strong class="text-white"><?php echo h($reg['name']); ?></strong>
                                                <?php if ($reg['company']): ?>
                                                <div class="text-muted small"><?php echo h($reg['company']); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo h($reg['email']); ?>" style="color: #7b61ff; text-decoration: none;">
                                            <?php echo h($reg['email']); ?>
                                        </a>
                                        <?php if ($reg['phone']): ?>
                                        <div class="text-muted small"><?php echo h($reg['phone']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-white"><?php echo h($reg['event_title']); ?></span>
                                        <div class="mt-1">
                                            <span class="event-badge"><?php echo h($reg['event_type']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($reg['event_date'])); ?></td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($reg['created_at'])); ?></td>
                                    <td class="action-btns">
                                        <?php
                                        $mailSubject = "Re: Your Registration for " . $reg['event_title'];
                                        $mailBody = "Hello " . $reg['name'] . ",\n\nThank you for registering for \"" . $reg['event_title'] . "\".\n\n---\n\nBest regards,\nUX Pacific Team\nHello@uxpacific.com";
                                        $mailtoLink = "mailto:" . $reg['email'] . "?subject=" . rawurlencode($mailSubject) . "&body=" . rawurlencode($mailBody);
                                        ?>
                                        <a href="<?php echo $mailtoLink; ?>" class="btn btn-sm btn-outline-primary" title="Send Email">
                                            <i class="bi bi-envelope"></i>
                                        </a>
                                        <a href="?delete=<?php echo $reg['id']; ?><?php echo $eventFilter ? '&event=' . $eventFilter : ''; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this registration?');" 
                                           title="Delete">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        if (openBtn) openBtn.addEventListener('click', () => sidebar.classList.add('active'));
        if (closeBtn) closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));
    </script>
</body>
</html>
