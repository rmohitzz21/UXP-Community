<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

$message = '';
$messageType = '';
$dbError = false;

// Check database and table
try {
    $pdo = getDB();
    $stmt = $pdo->query("SHOW TABLES LIKE 'resources'");
    if ($stmt->rowCount() === 0) {
        $dbError = true;
        $message = 'Resources table not found. Please run the SQL from includes/setup.sql in phpMyAdmin.';
        $messageType = 'warning';
    }
} catch (PDOException $e) {
    $dbError = true;
    $message = 'Database connection failed. Check includes/db.php configuration.';
    $messageType = 'danger';
}

// Handle create resource
if (!$dbError && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? 'link';
    $category = $_POST['category'] ?? 'design';
    $external_link = trim($_POST['external_link'] ?? '');
    
    if (empty($title)) {
        $message = 'Title is required.';
        $messageType = 'danger';
    } else {
        $filePath = null;
        $thumbnailPath = null;
        
        // Handle file upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/resources/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $allowedTypes = [
                'application/pdf',
                'image/jpeg', 'image/png', 'image/webp', 'image/gif',
                'video/mp4', 'video/webm',
                'application/zip', 'application/x-zip-compressed'
            ];
            $maxSize = 10 * 1024 * 1024; // 10MB
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['file']['tmp_name']);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $message = 'Invalid file type. Allowed: PDF, images, videos, ZIP.';
                $messageType = 'danger';
            } elseif ($_FILES['file']['size'] > $maxSize) {
                $message = 'File too large. Maximum size is 10MB.';
                $messageType = 'danger';
            } else {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $filename = 'resource_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                    $filePath = 'uploads/resources/' . $filename;
                }
            }
        }
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/resources/thumbnails/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $maxSize = 5 * 1024 * 1024;
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['thumbnail']['tmp_name']);
            
            if (in_array($mimeType, $allowedTypes) && $_FILES['thumbnail']['size'] <= $maxSize) {
                $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                $filename = 'thumb_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetPath)) {
                    $thumbnailPath = 'uploads/resources/thumbnails/' . $filename;
                }
            }
        }
        
        if (empty($message)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO resources (title, description, type, file_path, external_link, category, thumbnail) 
                    VALUES (:title, :description, :type, :file_path, :external_link, :category, :thumbnail)
                ");
                $stmt->execute([
                    ':title' => $title,
                    ':description' => $description,
                    ':type' => $type,
                    ':file_path' => $filePath,
                    ':external_link' => $external_link ?: null,
                    ':category' => $category,
                    ':thumbnail' => $thumbnailPath
                ]);
                
                $message = 'Resource added successfully!';
                $messageType = 'success';
            } catch (PDOException $e) {
                error_log("Create resource error: " . $e->getMessage());
                $message = 'Failed to add resource.';
                $messageType = 'danger';
            }
        }
    }
}

// Handle update resource
if (!$dbError && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? 'link';
    $category = $_POST['category'] ?? 'design';
    $external_link = trim($_POST['external_link'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($id <= 0 || empty($title)) {
        $message = 'Invalid data. Title is required.';
        $messageType = 'danger';
    } else {
        try {
            // Get current file paths
            $stmt = $pdo->prepare("SELECT file_path, thumbnail FROM resources WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $current = $stmt->fetch();
            $filePath = $current['file_path'] ?? null;
            $thumbnailPath = $current['thumbnail'] ?? null;
            
            // Handle new file upload
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/resources/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $allowedTypes = [
                    'application/pdf',
                    'image/jpeg', 'image/png', 'image/webp', 'image/gif',
                    'video/mp4', 'video/webm',
                    'application/zip', 'application/x-zip-compressed'
                ];
                $maxSize = 10 * 1024 * 1024;
                
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['file']['tmp_name']);
                
                if (in_array($mimeType, $allowedTypes) && $_FILES['file']['size'] <= $maxSize) {
                    // Delete old file
                    if ($filePath && file_exists(__DIR__ . '/../' . $filePath)) {
                        unlink(__DIR__ . '/../' . $filePath);
                    }
                    
                    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                    $filename = 'resource_' . uniqid() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                        $filePath = 'uploads/resources/' . $filename;
                    }
                }
            }
            
            // Handle new thumbnail upload
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/resources/thumbnails/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                $maxSize = 5 * 1024 * 1024;
                
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['thumbnail']['tmp_name']);
                
                if (in_array($mimeType, $allowedTypes) && $_FILES['thumbnail']['size'] <= $maxSize) {
                    // Delete old thumbnail
                    if ($thumbnailPath && file_exists(__DIR__ . '/../' . $thumbnailPath)) {
                        unlink(__DIR__ . '/../' . $thumbnailPath);
                    }
                    
                    $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                    $filename = 'thumb_' . uniqid() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetPath)) {
                        $thumbnailPath = 'uploads/resources/thumbnails/' . $filename;
                    }
                }
            }
            
            $stmt = $pdo->prepare("
                UPDATE resources 
                SET title = :title, description = :description, type = :type, 
                    file_path = :file_path, external_link = :external_link, 
                    category = :category, thumbnail = :thumbnail, is_active = :is_active
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':title' => $title,
                ':description' => $description,
                ':type' => $type,
                ':file_path' => $filePath,
                ':external_link' => $external_link ?: null,
                ':category' => $category,
                ':thumbnail' => $thumbnailPath,
                ':is_active' => $is_active
            ]);
            
            $message = 'Resource updated successfully!';
            $messageType = 'success';
        } catch (PDOException $e) {
            error_log("Update resource error: " . $e->getMessage());
            $message = 'Failed to update resource.';
            $messageType = 'danger';
        }
    }
}

// Handle delete
if (!$dbError && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        // Get file paths
        $stmt = $pdo->prepare("SELECT file_path, thumbnail FROM resources WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $resource = $stmt->fetch();
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM resources WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        // Delete files
        if ($resource) {
            if ($resource['file_path'] && file_exists(__DIR__ . '/../' . $resource['file_path'])) {
                unlink(__DIR__ . '/../' . $resource['file_path']);
            }
            if ($resource['thumbnail'] && file_exists(__DIR__ . '/../' . $resource['thumbnail'])) {
                unlink(__DIR__ . '/../' . $resource['thumbnail']);
            }
        }
        
        $message = 'Resource deleted successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        error_log("Delete resource error: " . $e->getMessage());
        $message = 'Failed to delete resource.';
        $messageType = 'danger';
    }
}

// Filters
$search = trim($_GET['search'] ?? '');
$filterCategory = $_GET['category'] ?? '';
$filterType = $_GET['type'] ?? '';

// Fetch resources
$resources = [];
$stats = ['total' => 0, 'active' => 0, 'downloads' => 0];

if (!$dbError) {
    try {
        $sql = "SELECT * FROM resources WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (title LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        if (!empty($filterCategory)) {
            $sql .= " AND category = :category";
            $params[':category'] = $filterCategory;
        }
        if (!empty($filterType)) {
            $sql .= " AND type = :type";
            $params[':type'] = $filterType;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $resources = $stmt->fetchAll();
        
        // Get stats
        $stats['total'] = $pdo->query("SELECT COUNT(*) FROM resources")->fetchColumn();
        $stats['active'] = $pdo->query("SELECT COUNT(*) FROM resources WHERE is_active = 1")->fetchColumn();
        $stats['downloads'] = $pdo->query("SELECT SUM(downloads) FROM resources")->fetchColumn() ?: 0;
    } catch (PDOException $e) {
        error_log("Fetch resources error: " . $e->getMessage());
    }
}

// Helper for type icons
function getTypeIcon($type) {
    $icons = [
        'pdf' => 'bi-file-pdf',
        'link' => 'bi-link-45deg',
        'article' => 'bi-file-text',
        'video' => 'bi-play-circle',
        'image' => 'bi-image',
        'other' => 'bi-file-earmark'
    ];
    return $icons[$type] ?? 'bi-file-earmark';
}

// Helper for category badges
function getCategoryClass($category) {
    $classes = [
        'design' => 'bg-primary',
        'development' => 'bg-success',
        'strategy' => 'bg-warning',
        'uiux' => 'bg-info',
        'tools' => 'bg-secondary',
        'templates' => 'bg-danger',
        'other' => 'bg-dark'
    ];
    return $classes[$category] ?? 'bg-dark';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Resources | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="../img/faviconUXP444@4x-789.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin-style.css" />
    <style>
        .resource-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: var(--admin-card);
            border-radius: 12px;
            margin-bottom: 12px;
            border: 1px solid var(--admin-border);
            transition: all 0.3s ease;
        }
        .resource-row:hover {
            border-color: var(--admin-primary);
            transform: translateY(-2px);
        }
        .resource-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .resource-icon.pdf { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
        .resource-icon.link { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .resource-icon.article { background: rgba(16, 185, 129, 0.2); color: #10b981; }
        .resource-icon.video { background: rgba(139, 92, 246, 0.2); color: #8b5cf6; }
        .resource-icon.image { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
        .resource-icon.other { background: rgba(107, 114, 128, 0.2); color: #6b7280; }
        .resource-info {
            flex: 1;
            min-width: 0;
        }
        .resource-info h5 {
            margin: 0;
            color: #fff;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .resource-info p {
            margin: 4px 0 0;
            color: #a0a0b8;
            font-size: 0.85rem;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .resource-meta {
            display: flex;
            gap: 16px;
            align-items: center;
            flex-shrink: 0;
        }
        .resource-meta .badge {
            font-size: 0.75rem;
            padding: 6px 12px;
        }
        .resource-meta .downloads {
            color: #a0a0b8;
            font-size: 0.85rem;
        }
        .resource-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        .status-dot.active { background: #22c55e; }
        .status-dot.inactive { background: #ef4444; }
        .filter-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .filter-bar select, .filter-bar input {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--admin-border);
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            min-width: 150px;
        }
        .filter-bar select option {
            background: var(--admin-card);
            color: #fff;
        }
        .preview-thumb {
            max-width: 80px;
            max-height: 60px;
            border-radius: 8px;
            object-fit: cover;
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
                    <li class="nav-item"><a href="contacts.php" class="nav-link"><i class="bi bi-envelope-fill"></i><span>Contact Messages</span></a></li>
                    <!-- <li class="nav-item"><a href="members.php" class="nav-link"><i class="bi bi-people-fill"></i><span>Members</span></a></li> -->
                    <li class="nav-item"><a href="team.php" class="nav-link"><i class="bi bi-person-badge-fill"></i><span>Team</span></a></li>
                    <li class="nav-item"><a href="ideas.php" class="nav-link"><i class="bi bi-lightbulb-fill"></i><span>Ideas</span></a></li>
                    <li class="nav-item"><a href="events.php" class="nav-link"><i class="bi bi-calendar-event-fill"></i><span>Events</span></a></li>
                    <li class="nav-item"><a href="registrations.php" class="nav-link"><i class="bi bi-person-check-fill"></i><span>Registrations</span></a></li>
                    <li class="nav-item"><a href="resources.php" class="nav-link active"><i class="bi bi-folder-fill"></i><span>Resources</span></a></li>
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
                    <form method="GET" style="display: flex; gap: 8px;">
                        <input type="text" name="search" placeholder="Search resources..." value="<?php echo h($search); ?>" />
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <div class="topbar-actions">
                    <div class="dropdown">
                        <button class="admin-profile dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <div class="profile-avatar"><i class="bi bi-person-fill"></i></div>
                            <span class="profile-name">Admin</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="admin-content">
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle' : ($messageType === 'warning' ? 'exclamation-triangle' : 'x-circle'); ?>-fill me-2"></i>
                    <?php echo h($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="content-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1>Resources</h1>
                        <p class="text-muted">Manage community resources and files</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Resource
                    </button>
                </div>

                <!-- Stats -->
                <div class="stats-grid mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary"><i class="bi bi-folder-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['total']; ?></h3><p>Total Resources</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-success"><i class="bi bi-check-circle-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['active']; ?></h3><p>Active</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-info"><i class="bi bi-download"></i></div>
                        <div class="stat-info"><h3><?php echo number_format($stats['downloads']); ?></h3><p>Total Downloads</p></div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-bar">
                    <form method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                        <select name="category" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="design" <?php echo $filterCategory === 'design' ? 'selected' : ''; ?>>Design</option>
                            <option value="development" <?php echo $filterCategory === 'development' ? 'selected' : ''; ?>>Development</option>
                            <option value="strategy" <?php echo $filterCategory === 'strategy' ? 'selected' : ''; ?>>Strategy</option>
                            <option value="uiux" <?php echo $filterCategory === 'uiux' ? 'selected' : ''; ?>>UI/UX</option>
                            <option value="tools" <?php echo $filterCategory === 'tools' ? 'selected' : ''; ?>>Tools</option>
                            <option value="templates" <?php echo $filterCategory === 'templates' ? 'selected' : ''; ?>>Templates</option>
                            <option value="other" <?php echo $filterCategory === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                        <select name="type" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="pdf" <?php echo $filterType === 'pdf' ? 'selected' : ''; ?>>PDF</option>
                            <option value="link" <?php echo $filterType === 'link' ? 'selected' : ''; ?>>Link</option>
                            <option value="article" <?php echo $filterType === 'article' ? 'selected' : ''; ?>>Article</option>
                            <option value="video" <?php echo $filterType === 'video' ? 'selected' : ''; ?>>Video</option>
                            <option value="image" <?php echo $filterType === 'image' ? 'selected' : ''; ?>>Image</option>
                        </select>
                        <?php if ($filterCategory || $filterType || $search): ?>
                        <a href="resources.php" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Resources List -->
                <div class="admin-card">
                    <div class="card-body">
                        <?php if (empty($resources)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-folder" style="font-size: 4rem; color: #4a4a6a;"></i>
                            <h4 class="mt-3" style="color: #a0a0b8;">No resources found</h4>
                            <p style="color: #6b6b80;">Add your first resource to get started.</p>
                        </div>
                        <?php else: ?>
                        <div id="resourcesList">
                            <?php foreach ($resources as $res): ?>
                            <div class="resource-row">
                                <div class="resource-icon <?php echo $res['type']; ?>">
                                    <i class="bi <?php echo getTypeIcon($res['type']); ?>"></i>
                                </div>
                                <div class="resource-info">
                                    <h5>
                                        <span class="status-dot <?php echo $res['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                        <?php echo h($res['title']); ?>
                                    </h5>
                                    <p><?php echo h($res['description'] ?? 'No description'); ?></p>
                                </div>
                                <div class="resource-meta">
                                    <?php if ($res['thumbnail']): ?>
                                    <img src="../<?php echo h($res['thumbnail']); ?>" alt="Thumbnail" class="preview-thumb" />
                                    <?php endif; ?>
                                    <span class="badge <?php echo getCategoryClass($res['category']); ?>"><?php echo ucfirst($res['category']); ?></span>
                                    <span class="downloads"><i class="bi bi-download me-1"></i><?php echo $res['downloads']; ?></span>
                                </div>
                                <div class="resource-actions">
                                    <?php if ($res['file_path']): ?>
                                    <a href="../<?php echo h($res['file_path']); ?>" class="btn-action" title="Download" download>
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($res['external_link']): ?>
                                    <a href="<?php echo h($res['external_link']); ?>" class="btn-action" title="Open Link" target="_blank">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <?php endif; ?>
                                    <button class="btn-action" title="Edit" data-bs-toggle="modal" data-bs-target="#editResourceModal"
                                        data-id="<?php echo $res['id']; ?>"
                                        data-title="<?php echo h($res['title']); ?>"
                                        data-description="<?php echo h($res['description'] ?? ''); ?>"
                                        data-type="<?php echo $res['type']; ?>"
                                        data-category="<?php echo $res['category']; ?>"
                                        data-link="<?php echo h($res['external_link'] ?? ''); ?>"
                                        data-active="<?php echo $res['is_active']; ?>"
                                        data-file="<?php echo h($res['file_path'] ?? ''); ?>"
                                        data-thumb="<?php echo h($res['thumbnail'] ?? ''); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <a href="?delete=<?php echo $res['id']; ?>" class="btn-action text-danger" title="Delete" onclick="return confirm('Delete this resource?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Resource Modal -->
    <div class="modal fade" id="addResourceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: var(--admin-sidebar); border: 1px solid var(--admin-border);">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Add Resource</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create" />
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" required placeholder="Resource title" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" id="addType">
                                    <option value="pdf">PDF</option>
                                    <option value="link" selected>Link</option>
                                    <option value="article">Article</option>
                                    <option value="video">Video</option>
                                    <option value="image">Image</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Brief description of this resource..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="design">Design</option>
                                    <option value="development">Development</option>
                                    <option value="strategy">Strategy</option>
                                    <option value="uiux">UI/UX</option>
                                    <option value="tools">Tools</option>
                                    <option value="templates">Templates</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">External Link</label>
                                <input type="url" name="external_link" class="form-control" placeholder="https://..." />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File Upload</label>
                                <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.zip" />
                                <small class="text-muted">PDF, images, video, ZIP (max 10MB)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*" />
                                <small class="text-muted">Preview image (max 5MB)</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Resource</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Resource Modal -->
    <div class="modal fade" id="editResourceModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: var(--admin-sidebar); border: 1px solid var(--admin-border);">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Edit Resource</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="id" id="editId" />
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="editTitle" class="form-control" required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" id="editType" class="form-select">
                                    <option value="pdf">PDF</option>
                                    <option value="link">Link</option>
                                    <option value="article">Article</option>
                                    <option value="video">Video</option>
                                    <option value="image">Image</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" id="editCategory" class="form-select">
                                    <option value="design">Design</option>
                                    <option value="development">Development</option>
                                    <option value="strategy">Strategy</option>
                                    <option value="uiux">UI/UX</option>
                                    <option value="tools">Tools</option>
                                    <option value="templates">Templates</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">External Link</label>
                                <input type="url" name="external_link" id="editLink" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Replace File</label>
                                <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp,.gif,.mp4,.webm,.zip" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Replace Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="editActive" />
                                    <label class="form-check-label" for="editActive">Active</label>
                                </div>
                            </div>
                            <div class="col-12" id="currentFileInfo"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/admin.js"></script>
    <script>
        // Populate edit modal
        document.getElementById('editResourceModal').addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            if (!btn) return;
            
            document.getElementById('editId').value = btn.dataset.id || '';
            document.getElementById('editTitle').value = btn.dataset.title || '';
            document.getElementById('editDescription').value = btn.dataset.description || '';
            document.getElementById('editType').value = btn.dataset.type || 'link';
            document.getElementById('editCategory').value = btn.dataset.category || 'design';
            document.getElementById('editLink').value = btn.dataset.link || '';
            document.getElementById('editActive').checked = btn.dataset.active === '1';
            
            // Show current file info
            const fileInfo = document.getElementById('currentFileInfo');
            let html = '';
            if (btn.dataset.file) {
                html += '<span class="badge bg-info me-2"><i class="bi bi-file-earmark me-1"></i>File attached</span>';
            }
            if (btn.dataset.thumb) {
                html += '<img src="../' + btn.dataset.thumb + '" class="preview-thumb" />';
            }
            fileInfo.innerHTML = html;
        });
    </script>
</body>
</html>
