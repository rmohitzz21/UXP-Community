<?php
require_once __DIR__ . '/../includes/db.php';

$message = '';
$messageType = '';
$editEvent = null;
$dbError = false;

// Check database connection and tables exist
try {
    $pdo = getDB();
    // Check if events table exists
    $result = $pdo->query("SHOW TABLES LIKE 'events'");
    if ($result->rowCount() === 0) {
        $message = 'Database tables not found. Please run the setup.sql file in phpMyAdmin first.';
        $messageType = 'warning';
        $dbError = true;
    }
} catch (PDOException $e) {
    $message = 'Database connection failed. Please check your database configuration.';
    $messageType = 'danger';
    $dbError = true;
}

// Handle Create Event
if (!$dbError && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_type = $_POST['event_type'] ?? 'Workshop';
        $event_date = $_POST['event_date'] ?? '';
        $event_time = $_POST['event_time'] ?? '';
        $location = trim($_POST['location'] ?? '');
        $max_registrations = (int)($_POST['max_registrations'] ?? 100);
        $status = $_POST['status'] ?? 'active';
        
        $errors = [];
        if (empty($title)) $errors[] = 'Title is required.';
        if (empty($event_date)) $errors[] = 'Date is required.';
        if (empty($event_time)) $errors[] = 'Time is required.';
        
        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadImage($_FILES['image'], 'uploads/events/');
            if (!$uploadResult['success']) {
                $errors[] = $uploadResult['error'];
            } else {
                $imagePath = $uploadResult['filename'];
            }
        }
        
        if (empty($errors)) {
            try {
                $pdo = getDB();
                $stmt = $pdo->prepare("
                    INSERT INTO events (title, description, event_type, event_date, event_time, location, max_registrations, image, status)
                    VALUES (:title, :description, :event_type, :event_date, :event_time, :location, :max_registrations, :image, :status)
                ");
                $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':event_type' => $event_type,
                    ':event_date' => $event_date,
                    ':event_time' => $event_time,
                    ':location' => $location,
                    ':max_registrations' => $max_registrations,
                    ':image' => $imagePath,
                    ':status' => $status
                ]);
                $message = 'Event created successfully!';
                $messageType = 'success';
            } catch (PDOException $e) {
                error_log("Create event error: " . $e->getMessage());
                $message = 'Failed to create event: ' . $e->getMessage();
                $messageType = 'danger';
            }
        } else {
            $message = implode('<br>', $errors);
            $messageType = 'danger';
        }
    }
    
    // Handle Update Event
    if ($_POST['action'] === 'update' && isset($_POST['event_id'])) {
        $id = (int)$_POST['event_id'];
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_type = $_POST['event_type'] ?? 'Workshop';
        $event_date = $_POST['event_date'] ?? '';
        $event_time = $_POST['event_time'] ?? '';
        $location = trim($_POST['location'] ?? '');
        $max_registrations = (int)($_POST['max_registrations'] ?? 100);
        $status = $_POST['status'] ?? 'active';
        
        $errors = [];
        if (empty($title)) $errors[] = 'Title is required.';
        if (empty($event_date)) $errors[] = 'Date is required.';
        if (empty($event_time)) $errors[] = 'Time is required.';
        
        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadImage($_FILES['image'], 'uploads/events/');
            if (!$uploadResult['success']) {
                $errors[] = $uploadResult['error'];
            } else {
                $imagePath = $uploadResult['filename'];
            }
        }
        
        if (empty($errors)) {
            try {
                $pdo = getDB();
                
                $sql = "UPDATE events SET title = :title, description = :description, event_type = :event_type, 
                        event_date = :event_date, event_time = :event_time, location = :location, 
                        max_registrations = :max_registrations, status = :status";
                $params = [
                    ':title' => $title,
                    ':description' => $description,
                    ':event_type' => $event_type,
                    ':event_date' => $event_date,
                    ':event_time' => $event_time,
                    ':location' => $location,
                    ':max_registrations' => $max_registrations,
                    ':status' => $status,
                    ':id' => $id
                ];
                
                if ($imagePath) {
                    $sql .= ", image = :image";
                    $params[':image'] = $imagePath;
                }
                
                $sql .= " WHERE id = :id";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                
                $message = 'Event updated successfully!';
                $messageType = 'success';
            } catch (PDOException $e) {
                error_log("Update event error: " . $e->getMessage());
                $message = 'Failed to update event.';
                $messageType = 'danger';
            }
        } else {
            $message = implode('<br>', $errors);
            $messageType = 'danger';
        }
    }
}

// Handle Delete
if (!$dbError && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $pdo = getDB();
        
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image FROM events WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $event = $stmt->fetch();
        
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        if ($event && $event['image']) {
            deleteUploadedFile($event['image']);
        }
        
        $message = 'Event deleted successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        error_log("Delete event error: " . $e->getMessage());
        $message = 'Failed to delete event.';
        $messageType = 'danger';
    }
}

// Handle Edit (load event data)
if (!$dbError && isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $editEvent = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Fetch event error: " . $e->getMessage());
    }
}

// Fetch events with filters
$events = [];
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';

if (!$dbError) {
    try {
        $pdo = getDB();
        $sql = "SELECT e.*, 
                (SELECT COUNT(*) FROM event_registrations WHERE event_id = e.id) as registration_count 
                FROM events e WHERE 1=1";
        $params = [];
        
        if ($filter === 'upcoming') {
            $sql .= " AND e.event_date >= CURDATE() AND e.status = 'active'";
        } elseif ($filter === 'past') {
            $sql .= " AND (e.event_date < CURDATE() OR e.status = 'completed')";
        } elseif ($filter === 'draft') {
            $sql .= " AND e.status = 'draft'";
        }
        
        if (!empty($search)) {
            $sql .= " AND e.title LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY e.event_date DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $events = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Fetch events error: " . $e->getMessage());
    }
}

// Get counts for stats
$stats = ['total' => 0, 'upcoming' => 0, 'completed' => 0, 'registrations' => 0];
if (!$dbError) {
    try {
        $pdo = getDB();
        $stats['total'] = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
        $stats['upcoming'] = $pdo->query("SELECT COUNT(*) FROM events WHERE event_date >= CURDATE() AND status = 'active'")->fetchColumn();
        $stats['completed'] = $pdo->query("SELECT COUNT(*) FROM events WHERE event_date < CURDATE() OR status = 'completed'")->fetchColumn();
        $stats['registrations'] = $pdo->query("SELECT COUNT(*) FROM event_registrations")->fetchColumn();
    } catch (PDOException $e) {
        // Silently fail
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Events | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="../img/LOGO.png" />
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
        .event-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .no-image {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-active { background: rgba(34,197,94,0.2); color: #22c55e; }
        .status-draft { background: rgba(245,158,11,0.2); color: #f59e0b; }
        .status-completed { background: rgba(59,130,246,0.2); color: #3b82f6; }
        .status-cancelled { background: rgba(239,68,68,0.2); color: #ef4444; }
        .filter-tabs .nav-link {
            color: rgba(255,255,255,0.7);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
        }
        .filter-tabs .nav-link.active {
            background: rgba(123,97,255,0.2);
            color: #7b61ff;
        }
        .action-btns .btn {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
        }
        .modal-content {
            background: #1a1a2e;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .modal-header { border-bottom: 1px solid rgba(255,255,255,0.1); }
        .modal-footer { border-top: 1px solid rgba(255,255,255,0.1); }
        .form-control, .form-select {
            background: #0f0f23;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        .form-control:focus, .form-select:focus {
            background: #0f0f23;
            border-color: #7b61ff;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(123,97,255,0.25);
        }
        .form-label { color: #a0a0b8; }
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
                    <li class="nav-item"><a href="events.php" class="nav-link active"><i class="bi bi-calendar-event-fill"></i><span>Events</span></a></li>
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
                <form class="topbar-search" method="GET">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Search events..." value="<?php echo h($search); ?>" />
                    <input type="hidden" name="filter" value="<?php echo h($filter); ?>" />
                </form>
                <div class="topbar-actions">
                    <button class="topbar-btn" aria-label="Notifications">
                        <i class="bi bi-bell"></i>
                        <?php if ($stats['upcoming'] > 0): ?>
                        <span class="badge"><?php echo $stats['upcoming']; ?></span>
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
                <div class="content-header d-flex justify-content-between align-items-center">
                    <div>
                        <h1><i class="bi bi-calendar-event-fill me-2"></i>Events</h1>
                        <p class="text-muted">Manage community events and workshops</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal" onclick="resetForm()" <?php echo $dbError ? 'disabled' : ''; ?>>
                        <i class="bi bi-calendar-plus me-2"></i>Create Event
                    </button>
                </div>

                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <?php if ($dbError): ?>
                    <br><br>
                    <strong>How to fix:</strong><br>
                    1. Open phpMyAdmin (http://localhost/phpmyadmin)<br>
                    2. Copy the SQL from <code>includes/setup.sql</code><br>
                    3. Paste and run it in phpMyAdmin SQL tab<br>
                    4. Refresh this page
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Stats -->
                <div class="stats-grid mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary"><i class="bi bi-calendar-event-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['total']; ?></h3><p>Total Events</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-success"><i class="bi bi-calendar-check-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['upcoming']; ?></h3><p>Upcoming</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-info"><i class="bi bi-check-circle-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['completed']; ?></h3><p>Completed</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-warning"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['registrations']; ?></h3><p>Registrations</p></div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <ul class="nav filter-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter === 'all' ? 'active' : ''; ?>" href="?filter=all">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter === 'upcoming' ? 'active' : ''; ?>" href="?filter=upcoming">Upcoming</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter === 'past' ? 'active' : ''; ?>" href="?filter=past">Past</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter === 'draft' ? 'active' : ''; ?>" href="?filter=draft">Drafts</a>
                    </li>
                </ul>

                <!-- Events Table -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table" id="eventsTable">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Event</th>
                                    <th>Date & Time</th>
                                    <th>Location</th>
                                    <th>Registrations</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($events)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="bi bi-calendar-x display-4 d-block mb-3 text-muted"></i>
                                        <p class="text-muted">No events found.</p>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($events as $event): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($event['image'])): ?>
                                        <img src="../<?php echo h($event['image']); ?>" alt="" class="event-thumb">
                                        <?php else: ?>
                                        <div class="no-image"><i class="bi bi-calendar-event" style="color: #666;"></i></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong class="text-white"><?php echo h($event['title']); ?></strong>
                                        <div class="text-muted small"><?php echo h($event['event_type']); ?></div>
                                    </td>
                                    <td>
                                        <div><?php echo date('M d, Y', strtotime($event['event_date'])); ?></div>
                                        <div class="text-muted small"><?php echo date('h:i A', strtotime($event['event_time'])); ?></div>
                                    </td>
                                    <td><?php echo h($event['location'] ?: 'TBD'); ?></td>
                                    <td>
                                        <span class="text-white"><?php echo $event['registration_count']; ?></span>
                                        <span class="text-muted">/ <?php echo $event['max_registrations']; ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $event['status']; ?>">
                                            <?php echo ucfirst($event['status']); ?>
                                        </span>
                                    </td>
                                    <td class="action-btns">
                                        <a href="?edit=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="registrations.php?event=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-info" title="View Registrations">
                                            <i class="bi bi-people"></i>
                                        </a>
                                        <a href="?delete=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this event? All registrations will also be deleted.');" title="Delete">
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

    <!-- Event Modal (Create/Edit) -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data" id="eventForm">
                    <input type="hidden" name="action" id="formAction" value="create">
                    <input type="hidden" name="event_id" id="eventId" value="">
                    
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="modalTitle">Create New Event</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Event Title *</label>
                                <input type="text" class="form-control" name="title" id="eventTitle" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Event Type</label>
                                <select class="form-select" name="event_type" id="eventType">
                                    <option value="Workshop">Workshop</option>
                                    <option value="Masterclass">Masterclass</option>
                                    <option value="Meetup">Meetup</option>
                                    <option value="Webinar">Webinar</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control" name="event_date" id="eventDate" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Time *</label>
                                <input type="time" class="form-control" name="event_time" id="eventTime" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Registrations</label>
                                <input type="number" class="form-control" name="max_registrations" id="maxReg" value="100" min="1">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" id="eventLocation" placeholder="Virtual / Physical address">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="eventStatus">
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="eventDesc" rows="4"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Event Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Create Event</button>
                    </div>
                </form>
            </div>
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

        function resetForm() {
            document.getElementById('formAction').value = 'create';
            document.getElementById('eventId').value = '';
            document.getElementById('modalTitle').textContent = 'Create New Event';
            document.getElementById('submitBtn').textContent = 'Create Event';
            document.getElementById('eventForm').reset();
        }

        // Auto-open edit modal if edit event loaded
        <?php if ($editEvent): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('formAction').value = 'update';
            document.getElementById('eventId').value = '<?php echo $editEvent['id']; ?>';
            document.getElementById('modalTitle').textContent = 'Edit Event';
            document.getElementById('submitBtn').textContent = 'Update Event';
            document.getElementById('eventTitle').value = '<?php echo addslashes($editEvent['title']); ?>';
            document.getElementById('eventType').value = '<?php echo $editEvent['event_type']; ?>';
            document.getElementById('eventDate').value = '<?php echo $editEvent['event_date']; ?>';
            document.getElementById('eventTime').value = '<?php echo $editEvent['event_time']; ?>';
            document.getElementById('eventLocation').value = '<?php echo addslashes($editEvent['location'] ?? ''); ?>';
            document.getElementById('maxReg').value = '<?php echo $editEvent['max_registrations']; ?>';
            document.getElementById('eventStatus').value = '<?php echo $editEvent['status']; ?>';
            document.getElementById('eventDesc').value = `<?php echo addslashes($editEvent['description'] ?? ''); ?>`;
            
            var modal = new bootstrap.Modal(document.getElementById('eventModal'));
            modal.show();
        });
        <?php endif; ?>
    </script>
</body>
</html>
