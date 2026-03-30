<?php
require_once __DIR__ . '/includes/db.php';

$message = '';
$messageType = '';
$ideas = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Name is required (min 2 characters).';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    
    if (empty($title) || strlen($title) < 5) {
        $errors[] = 'Title is required (min 5 characters).';
    }
    
    if (empty($description) || strlen($description) < 20) {
        $errors[] = 'Description is required (min 20 characters).';
    }
    
    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = uploadImage($_FILES['image'], 'uploads/ideas/');
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
                INSERT INTO ideas (name, email, title, description, image) 
                VALUES (:name, :email, :title, :description, :image)
            ");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':title' => $title,
                ':description' => $description,
                ':image' => $imagePath
            ]);
            
            $message = 'Your idea has been submitted successfully! We\'ll review it soon.';
            $messageType = 'success';
            
            // Clear form
            $_POST = [];
        } catch (PDOException $e) {
            error_log("Idea submission error: " . $e->getMessage());
            $message = 'An error occurred. Please try again later.';
            $messageType = 'danger';
            
            // Delete uploaded image if DB insert failed
            if ($imagePath) {
                deleteUploadedFile($imagePath);
            }
        }
    } else {
        $message = implode('<br>', $errors);
        $messageType = 'danger';
    }
}

// Fetch approved ideas for display
try {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT id, name, title, description, image, created_at 
        FROM ideas 
        WHERE status = 'approved' 
        ORDER BY created_at DESC 
        LIMIT 20
    ");
    $ideas = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Fetch ideas error: " . $e->getMessage());
    $ideas = [];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Share Your Idea - UX Pacific</title>

        <!-- Bootstrap 5 CSS -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />

        <!-- Google Font -->
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap"
            rel="stylesheet"
        />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="./css/style.css" />
        <link rel="stylesheet" href="./css/join-style.css" />
        <style>
                /* Specific overrides for Idea Page */
                .idea-section {
                        padding: 140px 0 80px;
                        min-height: auto;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                }
                
                .idea-card {
                        background: linear-gradient(180deg, rgba(23, 23, 23, 0.4), rgba(10, 10, 20, 0.6));
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        border-radius: 32px;
                        padding: 36px 40px;
                        max-width: 800px;
                        margin: 0 auto;
                        backdrop-filter: blur(16px);
                        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5);
                        transition: opacity 0.3s ease;
                }

                .idea-form .contact-field input,
                .idea-form .contact-field textarea {
                          width: 100%;
                          background: linear-gradient(#121212, #121212) padding-box, linear-gradient(to right, #ffffff 0%, #2e2e3e 100%) border-box;
                          border: 2px solid transparent;
                          border-radius: 6px;
                          color: #eee;
                          padding: 16px 20px;
                          font-size: 1.1rem;
                          outline: none;
                          margin-bottom: 16px;
                }
                
                .idea-form .contact-field textarea {
                        min-height: 115px;
                        resize: vertical;
                }

                .idea-form .contact-field input:focus,
                .idea-form .contact-field textarea:focus {
                          border-color: #7b61ff;
                }

                .idea-form .contact-row {
                        display: flex;
                        gap: 20px;
                        margin-bottom: 0;
                }
                
                .idea-form .contact-row .contact-field {
                        flex: 1;
                }

                @media(max-width: 768px) {
                        .idea-form .contact-row {
                                flex-direction: column;
                                gap: 0;
                        }
                }

                /* Ideas Display Grid */
                .ideas-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                        gap: 24px;
                        margin-top: 40px;
                }
                
                .idea-item {
                        background: linear-gradient(180deg, rgba(30, 30, 40, 0.6), rgba(15, 15, 25, 0.8));
                        border: 1px solid rgba(255, 255, 255, 0.08);
                        border-radius: 20px;
                        padding: 24px;
                        transition: transform 0.3s ease, box-shadow 0.3s ease;
                }
                
                .idea-item:hover {
                        transform: translateY(-4px);
                        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
                }
                
                .idea-item-image {
                        width: 100%;
                        height: 180px;
                        object-fit: cover;
                        border-radius: 12px;
                        margin-bottom: 16px;
                }
                
                .idea-item h4 {
                        color: #fff;
                        font-size: 1.25rem;
                        margin-bottom: 8px;
                }
                
                .idea-item p {
                        color: rgba(255, 255, 255, 0.7);
                        font-size: 0.95rem;
                        line-height: 1.6;
                }
                
                .idea-meta {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-top: 16px;
                        padding-top: 16px;
                        border-top: 1px solid rgba(255, 255, 255, 0.1);
                }
                
                .idea-author {
                        color: #7b61ff;
                        font-weight: 500;
                }
                
                .idea-date {
                        color: rgba(255, 255, 255, 0.5);
                        font-size: 0.85rem;
                }

                /* File Input Styling */
                .file-input-wrapper {
                        position: relative;
                        overflow: hidden;
                }
                
                .file-input-wrapper input[type=file] {
                        position: absolute;
                        left: 0;
                        top: 0;
                        opacity: 0;
                        cursor: pointer;
                        width: 100%;
                        height: 100%;
                }
                
                .file-input-label {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 16px 20px;
                        background: linear-gradient(#121212, #121212) padding-box, linear-gradient(to right, #ffffff 0%, #2e2e3e 100%) border-box;
                        border: 2px dashed rgba(255, 255, 255, 0.2);
                        border-radius: 6px;
                        color: rgba(255, 255, 255, 0.6);
                        cursor: pointer;
                        transition: border-color 0.3s;
                }
                
                .file-input-label:hover {
                        border-color: #7b61ff;
                }

                /* Success Animation Styles */
                .success-checkmark-anim {
                        width: 80px;
                        height: 80px;
                        border-radius: 50%;
                        background: rgba(74, 222, 128, 0.15);
                        color: #4ade80;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto;
                        position: relative;
                }
                
                .success-checkmark-anim::after {
                        content: "";
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        border: 1px solid #4ade80;
                        animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
                }
                
                .success-checkmark-anim i {
                        display: inline-block;
                        opacity: 0;
                        transform: scale(0);
                }

                /* Class that triggers animation */
                .animate-trigger .success-checkmark-anim i {
                        animation: pop-in 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) 0.1s forwards;
                }

                @keyframes pulse-ring {
                        0% { transform: scale(0.9); opacity: 0.8; }
                        100% { transform: scale(1.5); opacity: 0; }
                }

                @keyframes pop-in {
                        0% { transform: scale(0); opacity: 0; }
                        100% { transform: scale(1); opacity: 1; }
                }
                
                /* Alert styling */
                .alert-custom {
                        border-radius: 12px;
                        padding: 16px 20px;
                        margin-bottom: 20px;
                }
        </style>
    </head>

    <body class="main">
        <!-- NAVBAR -->
          <nav class="navbar navbar-expand-lg fixed-top mt-3">
            <div class="container">
                <div class="navbar-glass w-100 d-flex align-items-center flex-wrap">
                    <a class="navbar-brand text-white" href="index.php">
                        <img src="img/ux-community.png" alt="UX Pacific Logo" class="navbar-logo" />
                    </a>

                    <button
                        class="navbar-toggler text-white ms-auto"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navMenu"
                    >
                        ☰
                    </button>

                    <div
                        class="collapse navbar-collapse justify-content-center"
                        id="navMenu"
                    >
                        <ul class="navbar-nav gap-2">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#about-section">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php#resources-section">Resources</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="index.php#event-section">Event</a></li>
                            <!-- Mobile Contact Button -->
                            <li class="nav-item d-lg-none mt-3">
                                <a href="contact.php" class="btn btn-primary-gradient w-100">Contact Us</a>
                            </li>
                        </ul>
                    </div>

                    <div class="d-none d-lg-block">
                        <a href="contact.php" class="btn btn-primary-gradient">Contact Us</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="main">
            <section class="idea-section">
                <div class="container text-center">
                   
                    <h1 class="join-main-title">Share Your  <span>Ideas.</span></h1>
                    <p class="join-main-subtitle">
                        Got an idea, a concept, or an event in mind?<br>
                        If it aligns with the community, we'd love to build it together.
                    </p>

                    <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-custom" role="alert">
                        <?php echo $message; ?>
                    </div>
                    <?php endif; ?>

                    <!-- FORM CONTAINER -->
                    <div class="idea-card" id="idea-card-container">
                        <form class="idea-form" method="POST" enctype="multipart/form-data">
                            <div class="contact-row">
                                <div class="contact-field">
                                    <input type="text" name="name" placeholder="Your Name" 
                                           value="<?php echo h($_POST['name'] ?? ''); ?>" required>
                                </div>
                                <div class="contact-field">
                                    <input type="email" name="email" placeholder="Your Email" 
                                           value="<?php echo h($_POST['email'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="contact-field mt-3">
                                <input type="text" name="title" placeholder="Idea Title" 
                                       value="<?php echo h($_POST['title'] ?? ''); ?>" required>
                            </div>

                            <div class="contact-field mt-3">
                                <textarea name="description" rows="5" placeholder="Describe your idea in detail..." required><?php echo h($_POST['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="contact-field mt-3">
                                <div class="file-input-wrapper">
                                    <label class="file-input-label">
                                        <i class="bi bi-cloud-upload"></i>
                                        <span id="file-name">Upload an image (optional, max 5MB)</span>
                                        <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" onchange="updateFileName(this)">
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-gradient mt-4 px-5 py-3 rounded-pill fw-bold">
                                Submit My Idea
                            </button>
                        </form>
                    </div>

                    <!-- EXTRA INFO -->
                    <div class="mt-5 text-white-50">
                        <p>Preferred email for detailed proposals pitch us on <a href="mailto:hello@uxpacific.com" class="text-primary text-decoration-none">hello@uxpacific.com</a></p>
                    </div>
                </div>
            </section>

            <!-- IDEAS DISPLAY SECTION -->
            <?php if (!empty($ideas)): ?>
            <section class="py-5">
                <div class="container">
                    <h2 class="text-white text-center mb-2">Community Ideas</h2>
                    <p class="text-white-50 text-center mb-4">Check out what others are thinking</p>
                    
                    <div class="ideas-grid">
                        <?php foreach ($ideas as $idea): ?>
                        <div class="idea-item">
                            <?php if ($idea['image']): ?>
                            <img src="<?php echo h($idea['image']); ?>" alt="<?php echo h($idea['title']); ?>" class="idea-item-image">
                            <?php endif; ?>
                            <h4><?php echo h($idea['title']); ?></h4>
                            <p><?php echo nl2br(h(substr($idea['description'], 0, 200))); ?><?php echo strlen($idea['description']) > 200 ? '...' : ''; ?></p>
                            <div class="idea-meta">
                                <span class="idea-author"><i class="bi bi-person-fill me-1"></i><?php echo h($idea['name']); ?></span>
                                <span class="idea-date"><?php echo date('M d, Y', strtotime($idea['created_at'])); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
        </main>

        <!-- FOOTER -->
        <footer class="site-footer">
                <div class="footer-main">
                    <div class="footer-top">
                        <div class="footer-brand">
                            <img src="img/ux-community.png" alt="UX Pacific" />
                            <p>
                                Join our community of designers and developers creating the
                                future of digital experiences.
                            </p>
                            <div class="footer-socials">
                                <a href="https://dribbble.com/social-ux-pacific" target="_blank" rel="noopener">
                                    <img src="img/bl.png" alt="Dribbble" />
                                </a>
                                <a href="https://www.instagram.com/official_uxpacific/" target="_blank" rel="noopener">
                                    <img src="img/i.png" alt="Instagram" />
                                </a>
                                <a href="https://www.linkedin.com/company/uxpacific/" target="_blank" rel="noopener">
                                    <img src="img/in1.png" alt="LinkedIn" />
                                </a>
                                <a href="https://in.pinterest.com/uxpacific/" target="_blank" rel="noopener">
                                    <img src="img/p.png" alt="Pinterest" />
                                </a>
                                <a href="https://www.behance.net/ux_pacific" target="_blank" rel="noopener">
                                    <img src="img/be.png" alt="Behance" />
                                </a>
                            </div>
                        </div>

                        <div class="footer-contact">
                            <p>+91 9274061063&nbsp;&nbsp;&nbsp;&nbsp;|</p>
                            <p>hello@uxpacific.com&nbsp;&nbsp;&nbsp;&nbsp;|</p>
                            <p>UX Pacific, Ahmedabad.</p>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>© 2026 UX Pacific Community. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#">Privacy Policy</a>
                        <span>•</span>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function updateFileName(input) {
                const label = document.getElementById('file-name');
                if (input.files && input.files[0]) {
                    label.textContent = input.files[0].name;
                } else {
                    label.textContent = 'Upload an image (optional, max 5MB)';
                }
            }
        </script>
    </body>
</html>
