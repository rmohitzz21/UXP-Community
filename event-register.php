<?php
require_once __DIR__ . '/includes/db.php';

$message = '';
$messageType = '';
$event = null;

// Get event ID from URL
$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch event details
if ($event_id > 0) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("
            SELECT e.*, 
                   (SELECT COUNT(*) FROM event_registrations WHERE event_id = e.id) as registration_count
            FROM events e 
            WHERE e.id = :id AND e.status = 'active'
        ");
        $stmt->execute([':id' => $event_id]);
        $event = $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Fetch event error: " . $e->getMessage());
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $event) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $expectations = trim($_POST['expectations'] ?? '');
    
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    
    // Check if already registered
    if (empty($errors)) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT id FROM event_registrations WHERE event_id = :event_id AND email = :email");
            $stmt->execute([':event_id' => $event_id, ':email' => $email]);
            if ($stmt->fetch()) {
                $errors[] = 'You are already registered for this event with this email address.';
            }
        } catch (PDOException $e) {
            error_log("Check registration error: " . $e->getMessage());
        }
    }
    
    // Check if event is full
    if (empty($errors) && $event['registration_count'] >= $event['max_registrations']) {
        $errors[] = 'Sorry, this event is fully booked.';
    }
    
    if (empty($errors)) {
        try {
            $pdo = getDB();
            $stmt = $pdo->prepare("
                INSERT INTO event_registrations (event_id, name, email, phone, company, expectations)
                VALUES (:event_id, :name, :email, :phone, :company, :expectations)
            ");
            $stmt->execute([
                ':event_id' => $event_id,
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':company' => $company,
                ':expectations' => $expectations
            ]);
            
            // PRG pattern - redirect with success message
            header("Location: event-register.php?id=$event_id&success=1");
            exit;
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $message = 'Registration failed. Please try again.';
            $messageType = 'danger';
        }
    } else {
        $message = implode('<br>', $errors);
        $messageType = 'danger';
    }
}

// Check for success redirect
if (isset($_GET['success']) && $_GET['success'] == 1 && $event) {
    $message = 'Registration successful! You will receive a confirmation email shortly.';
    $messageType = 'success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event ? h($event['title']) . ' - ' : ''; ?>Event Registration | UX Pacific</title>
    <link rel="icon" type="image/png" href="img/LOGO.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        .registration-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .event-header {
            background: #1a1a2e;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .event-badge {
            background: rgba(123, 97, 255, 0.2);
            color: #7b61ff;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 16px;
        }
        .event-title {
            font-family: 'Gabarito', sans-serif;
            font-size: 2rem;
            color: #fff;
            margin-bottom: 20px;
        }
        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            color: #a0a0b8;
        }
        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .event-meta-item i {
            color: #7b61ff;
        }
        .registration-form {
            background: #1a1a2e;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .form-label {
            color: #a0a0b8;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-control {
            background: #0f0f23;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            padding: 12px 16px;
            border-radius: 10px;
        }
        .form-control:focus {
            background: #0f0f23;
            border-color: #7b61ff;
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(123,97,255,0.25);
        }
        .form-control::placeholder {
            color: #6b6b80;
        }
        .btn-register {
            background: linear-gradient(135deg, #7b61ff, #9d4edd);
            border: none;
            padding: 14px 32px;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            color: #fff;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #6b51ef, #8d3ecd);
            color: #fff;
        }
        .back-link {
            color: #a0a0b8;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 30px;
        }
        .back-link:hover {
            color: #7b61ff;
        }
        .spots-badge {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .spots-badge.limited {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
        .spots-badge.full {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        .not-found {
            text-align: center;
            padding: 80px 20px;
        }
        .not-found i {
            font-size: 4rem;
            color: #6b6b80;
            margin-bottom: 20px;
        }
        .not-found h2 {
            color: #fff;
            margin-bottom: 16px;
        }
        .not-found p {
            color: #a0a0b8;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <a href="index.php#event-section" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Events
        </a>

        <?php if (!$event): ?>
        <div class="registration-form not-found">
            <i class="bi bi-calendar-x"></i>
            <h2>Event Not Found</h2>
            <p>The event you're looking for doesn't exist or is no longer available.</p>
            <a href="index.php#event-section" class="btn btn-register mt-4" style="max-width: 200px; margin: 20px auto;">
                View All Events
            </a>
        </div>
        <?php else: ?>

        <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="event-header">
            <span class="event-badge"><?php echo h($event['event_type']); ?></span>
            <h1 class="event-title"><?php echo h($event['title']); ?></h1>
            
            <div class="event-meta">
                <div class="event-meta-item">
                    <i class="bi bi-calendar3"></i>
                    <span><?php echo date('F d, Y', strtotime($event['event_date'])); ?></span>
                </div>
                <div class="event-meta-item">
                    <i class="bi bi-clock"></i>
                    <span><?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
                </div>
                <div class="event-meta-item">
                    <i class="bi bi-geo-alt"></i>
                    <span><?php echo h($event['location'] ?: 'TBD'); ?></span>
                </div>
            </div>

            <?php if ($event['description']): ?>
            <p style="color: #a0a0b8; margin-top: 20px; line-height: 1.7;">
                <?php echo nl2br(h($event['description'])); ?>
            </p>
            <?php endif; ?>

            <?php 
            $spotsLeft = $event['max_registrations'] - $event['registration_count'];
            $percentFull = ($event['registration_count'] / $event['max_registrations']) * 100;
            ?>
            <div style="margin-top: 20px;">
                <?php if ($spotsLeft <= 0): ?>
                <span class="spots-badge full">
                    <i class="bi bi-x-circle"></i> Event is fully booked
                </span>
                <?php elseif ($percentFull >= 80): ?>
                <span class="spots-badge limited">
                    <i class="bi bi-exclamation-circle"></i> Only <?php echo $spotsLeft; ?> spots left!
                </span>
                <?php else: ?>
                <span class="spots-badge">
                    <i class="bi bi-check-circle"></i> <?php echo $spotsLeft; ?> spots available
                </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($spotsLeft > 0 && (!isset($_GET['success']) || $_GET['success'] != 1)): ?>
        <div class="registration-form">
            <h2 style="color: #fff; font-family: 'Gabarito', sans-serif; margin-bottom: 30px;">
                <i class="bi bi-person-plus me-2" style="color: #7b61ff;"></i>Register for This Event
            </h2>
            
            <form method="POST" id="registrationForm" class="js-ajax-form" action="includes/form-handler.php">
                <input type="hidden" name="form_type" value="event_registration">
                <input type="text" name="website" value="" style="display:none" tabindex="-1" autocomplete="off">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter your full name"
                               value="<?php echo isset($_POST['name']) ? h($_POST['name']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address *</label>
                        <input type="email" class="form-control" name="email" required placeholder="Enter your email"
                               value="<?php echo isset($_POST['email']) ? h($_POST['email']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" name="phone" placeholder="Enter your phone number"
                               value="<?php echo isset($_POST['phone']) ? h($_POST['phone']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company/Organization</label>
                        <input type="text" class="form-control" name="company" placeholder="Where do you work?"
                               value="<?php echo isset($_POST['company']) ? h($_POST['company']) : ''; ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">What do you hope to learn from this event?</label>
                        <textarea class="form-control" name="expectations" rows="3" 
                                  placeholder="Tell us what you're hoping to gain from this event..."><?php echo isset($_POST['expectations']) ? h($_POST['expectations']) : ''; ?></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-register">
                            <i class="bi bi-check-circle me-2"></i>Complete Registration
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="registration-form" style="text-align: center;">
            <div style="width: 80px; height: 80px; background: rgba(34,197,94,0.2); border-radius: 50%; margin: 0 auto 24px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-check-lg" style="font-size: 2.5rem; color: #22c55e;"></i>
            </div>
            <h2 style="color: #fff; font-family: 'Gabarito', sans-serif; margin-bottom: 16px;">
                You're Registered!
            </h2>
            <p style="color: #a0a0b8; margin-bottom: 30px;">
                Thank you for registering for <strong style="color: #fff;"><?php echo h($event['title']); ?></strong>.<br>
                We'll send you a confirmation email with all the event details.
            </p>
            <a href="index.php#event-section" class="btn btn-register" style="max-width: 250px; margin: 0 auto;">
                <i class="bi bi-arrow-left me-2"></i>Back to Events
            </a>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./assets/js/form.js"></script>
</body>
</html>
