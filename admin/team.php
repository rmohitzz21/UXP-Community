<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

$message = '';
$messageType = '';
$dbError = false;

// Check database and table
try {
    $pdo = getDB();
    $stmt = $pdo->query("SHOW TABLES LIKE 'team_members'");
    if ($stmt->rowCount() === 0) {
        $dbError = true;
        $message = 'Team members table not found. Please run the SQL from includes/setup.sql in phpMyAdmin.';
        $messageType = 'warning';
    }
} catch (PDOException $e) {
    $dbError = true;
    $message = 'Database connection failed. Check includes/db.php configuration.';
    $messageType = 'danger';
}

// Handle create team member
if (!$dbError && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $behance_url = trim($_POST['behance_url'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    
    if (empty($name) || empty($role)) {
        $message = 'Name and role are required.';
        $messageType = 'danger';
    } else {
        $imagePath = null;
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/team/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['image']['tmp_name']);
            
            if (!in_array($mimeType, $allowedTypes)) {
                $message = 'Invalid image type. Only JPG, PNG, WebP, and GIF are allowed.';
                $messageType = 'danger';
            } elseif ($_FILES['image']['size'] > $maxSize) {
                $message = 'Image too large. Maximum size is 5MB.';
                $messageType = 'danger';
            } else {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = 'team_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/team/' . $filename;
                }
            }
        }
        
        if (empty($message)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO team_members (name, role, bio, image, linkedin_url, behance_url, display_order) 
                    VALUES (:name, :role, :bio, :image, :linkedin_url, :behance_url, :display_order)
                ");
                $stmt->execute([
                    ':name' => $name,
                    ':role' => $role,
                    ':bio' => $bio,
                    ':image' => $imagePath,
                    ':linkedin_url' => $linkedin_url,
                    ':behance_url' => $behance_url,
                    ':display_order' => $display_order
                ]);
                
                $message = 'Team member added successfully!';
                $messageType = 'success';
            } catch (PDOException $e) {
                error_log("Create team member error: " . $e->getMessage());
                $message = 'Failed to add team member.';
                $messageType = 'danger';
            }
        }
    }
}

// Handle update team member
if (!$dbError && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $behance_url = trim($_POST['behance_url'] ?? '');
    $display_order = (int)($_POST['display_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($id <= 0 || empty($name) || empty($role)) {
        $message = 'Invalid data. Name and role are required.';
        $messageType = 'danger';
    } else {
        try {
            // Get current image
            $stmt = $pdo->prepare("SELECT image FROM team_members WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $current = $stmt->fetch();
            $imagePath = $current['image'] ?? null;
            
            // Handle new image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/team/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                $maxSize = 5 * 1024 * 1024;
                
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['image']['tmp_name']);
                
                if (in_array($mimeType, $allowedTypes) && $_FILES['image']['size'] <= $maxSize) {
                    // Delete old image
                    if ($imagePath && file_exists(__DIR__ . '/../' . $imagePath)) {
                        unlink(__DIR__ . '/../' . $imagePath);
                    }
                    
                    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $filename = 'team_' . uniqid() . '.' . $ext;
                    $targetPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $imagePath = 'uploads/team/' . $filename;
                    }
                }
            }
            
            $stmt = $pdo->prepare("
                UPDATE team_members 
                SET name = :name, role = :role, bio = :bio, image = :image, 
                    linkedin_url = :linkedin_url, behance_url = :behance_url, 
                    display_order = :display_order, is_active = :is_active
                WHERE id = :id
            ");
            $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':role' => $role,
                ':bio' => $bio,
                ':image' => $imagePath,
                ':linkedin_url' => $linkedin_url,
                ':behance_url' => $behance_url,
                ':display_order' => $display_order,
                ':is_active' => $is_active
            ]);
            
            $message = 'Team member updated successfully!';
            $messageType = 'success';
        } catch (PDOException $e) {
            error_log("Update team member error: " . $e->getMessage());
            $message = 'Failed to update team member.';
            $messageType = 'danger';
        }
    }
}

// Handle delete
if (!$dbError && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    try {
        // Get image path
        $stmt = $pdo->prepare("SELECT image FROM team_members WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $member = $stmt->fetch();
        
        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM team_members WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        // Delete image
        if ($member && $member['image'] && file_exists(__DIR__ . '/../' . $member['image'])) {
            unlink(__DIR__ . '/../' . $member['image']);
        }
        
        $message = 'Team member deleted successfully.';
        $messageType = 'success';
    } catch (PDOException $e) {
        error_log("Delete team member error: " . $e->getMessage());
        $message = 'Failed to delete team member.';
        $messageType = 'danger';
    }
}

// Fetch all team members
$teamMembers = [];
$stats = ['total' => 0, 'active' => 0];

if (!$dbError) {
    try {
        $stmt = $pdo->query("SELECT * FROM team_members ORDER BY display_order ASC, created_at DESC");
        $teamMembers = $stmt->fetchAll();
        
        $stats['total'] = count($teamMembers);
        $stats['active'] = count(array_filter($teamMembers, fn($m) => $m['is_active']));
    } catch (PDOException $e) {
        error_log("Fetch team members error: " . $e->getMessage());
    }
}

// Get member for editing
$editMember = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    foreach ($teamMembers as $m) {
        if ($m['id'] == $_GET['edit']) {
            $editMember = $m;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Team Members | Admin - UX Pacific</title>
    <link rel="icon" type="image/png" href="../img/faviconUXP444@4x-789.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin-style.css" />
    <style>
        .team-card {
            background: var(--admin-card);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 16px;
            border: 1px solid var(--admin-border);
            transition: all 0.3s ease;
        }
        .team-card:hover {
            border-color: var(--admin-primary);
            transform: translateY(-2px);
        }
        .team-card-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--admin-bg);
        }
        .team-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .team-card-info {
            flex: 1;
        }
        .team-card-info h5 {
            margin: 0 0 4px;
            color: #fff;
            font-weight: 600;
        }
        .team-card-info .role {
            color: var(--admin-primary);
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .team-card-info .bio {
            color: #a0a0b8;
            font-size: 0.85rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }
        .team-card-actions {
            display: flex;
            gap: 8px;
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
        .order-badge {
            background: rgba(123, 97, 255, 0.2);
            color: var(--admin-primary);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .social-links {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }
        .social-links a {
            color: #a0a0b8;
            font-size: 1.1rem;
            transition: color 0.2s;
        }
        .social-links a:hover {
            color: var(--admin-primary);
        }
        .preview-img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: 10px;
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
                    <li class="nav-item"><a href="team.php" class="nav-link active"><i class="bi bi-person-badge-fill"></i><span>Team</span></a></li>
                    <li class="nav-item"><a href="ideas.php" class="nav-link"><i class="bi bi-lightbulb-fill"></i><span>Ideas</span></a></li>
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
                    <input type="text" placeholder="Search team..." id="searchInput" />
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
                        <h1>Team Members</h1>
                        <p class="text-muted">Manage your website team section</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="bi bi-person-plus me-2"></i>Add Team Member
                    </button>
                </div>

                <!-- Stats -->
                <div class="stats-grid mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['total']; ?></h3><p>Total Members</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-success"><i class="bi bi-person-check-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['active']; ?></h3><p>Active</p></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-warning"><i class="bi bi-person-x-fill"></i></div>
                        <div class="stat-info"><h3><?php echo $stats['total'] - $stats['active']; ?></h3><p>Inactive</p></div>
                    </div>
                </div>

                <!-- Team Members List -->
                <div class="admin-card">
                    <div class="card-body">
                        <?php if (empty($teamMembers)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-people" style="font-size: 4rem; color: #4a4a6a;"></i>
                            <h4 class="mt-3" style="color: #a0a0b8;">No team members yet</h4>
                            <p style="color: #6b6b80;">Add your first team member to display on the website.</p>
                        </div>
                        <?php else: ?>
                        <div id="teamList">
                            <?php foreach ($teamMembers as $member): ?>
                            <div class="team-card" data-name="<?php echo strtolower(h($member['name'])); ?>">
                                <div class="team-card-img">
                                    <?php if ($member['image']): ?>
                                    <img src="../<?php echo h($member['image']); ?>" alt="<?php echo h($member['name']); ?>" />
                                    <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center h-100" style="background: var(--admin-primary); color: #fff; font-size: 1.5rem; font-weight: 600;">
                                        <?php echo strtoupper(substr($member['name'], 0, 2)); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="team-card-info">
                                    <h5>
                                        <span class="status-dot <?php echo $member['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                        <?php echo h($member['name']); ?>
                                        <span class="order-badge ms-2">#<?php echo $member['display_order']; ?></span>
                                    </h5>
                                    <div class="role"><?php echo h($member['role']); ?></div>
                                    <?php if ($member['bio']): ?>
                                    <div class="bio"><?php echo h($member['bio']); ?></div>
                                    <?php endif; ?>
                                    <div class="social-links">
                                        <?php if ($member['linkedin_url']): ?>
                                        <a href="<?php echo h($member['linkedin_url']); ?>" target="_blank" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                                        <?php endif; ?>
                                        <?php if ($member['behance_url']): ?>
                                        <a href="<?php echo h($member['behance_url']); ?>" target="_blank" title="Behance"><i class="bi bi-behance"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="team-card-actions">
                                    <a href="?edit=<?php echo $member['id']; ?>" class="btn-action" title="Edit" data-bs-toggle="modal" data-bs-target="#editMemberModal" 
                                       data-id="<?php echo $member['id']; ?>"
                                       data-name="<?php echo h($member['name']); ?>"
                                       data-role="<?php echo h($member['role']); ?>"
                                       data-bio="<?php echo h($member['bio'] ?? ''); ?>"
                                       data-linkedin="<?php echo h($member['linkedin_url'] ?? ''); ?>"
                                       data-behance="<?php echo h($member['behance_url'] ?? ''); ?>"
                                       data-order="<?php echo $member['display_order']; ?>"
                                       data-active="<?php echo $member['is_active']; ?>"
                                       data-image="<?php echo h($member['image'] ?? ''); ?>">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?delete=<?php echo $member['id']; ?>" class="btn-action text-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this team member?');">
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

    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: var(--admin-sidebar); border: 1px solid var(--admin-border);">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Add Team Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create" />
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter full name" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <input type="text" name="role" class="form-control" required placeholder="e.g., Community Head, Designer" />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea name="bio" class="form-control" rows="3" placeholder="Short description about this team member..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="linkedin_url" class="form-control" placeholder="https://linkedin.com/in/..." />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Behance URL</label>
                                <input type="url" name="behance_url" class="form-control" placeholder="https://behance.net/..." />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control" value="0" min="0" />
                                <small class="text-muted">Lower numbers appear first</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewImage(this, 'addPreview')" />
                                <small class="text-muted">JPG, PNG, WebP or GIF (max 5MB)</small>
                                <img id="addPreview" class="preview-img d-none" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: var(--admin-sidebar); border: 1px solid var(--admin-border);">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Edit Team Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="id" id="editId" />
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="editName" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <input type="text" name="role" id="editRole" class="form-control" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea name="bio" id="editBio" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="linkedin_url" id="editLinkedin" class="form-control" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Behance URL</label>
                                <input type="url" name="behance_url" id="editBehance" class="form-control" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" id="editOrder" class="form-control" min="0" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="editActive" />
                                    <label class="form-check-label" for="editActive">Active</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewImage(this, 'editPreview')" />
                                <img id="editPreview" class="preview-img mt-2" />
                            </div>
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
        // Image preview
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Populate edit modal
        document.getElementById('editMemberModal').addEventListener('show.bs.modal', function(event) {
            const btn = event.relatedTarget;
            if (!btn) return;
            
            document.getElementById('editId').value = btn.dataset.id || '';
            document.getElementById('editName').value = btn.dataset.name || '';
            document.getElementById('editRole').value = btn.dataset.role || '';
            document.getElementById('editBio').value = btn.dataset.bio || '';
            document.getElementById('editLinkedin').value = btn.dataset.linkedin || '';
            document.getElementById('editBehance').value = btn.dataset.behance || '';
            document.getElementById('editOrder').value = btn.dataset.order || 0;
            document.getElementById('editActive').checked = btn.dataset.active === '1';
            
            const preview = document.getElementById('editPreview');
            if (btn.dataset.image) {
                preview.src = '../' + btn.dataset.image;
                preview.classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
            }
        });

        // Search
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.team-card').forEach(card => {
                const name = card.dataset.name;
                card.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });
    </script>
</body>
</html>
