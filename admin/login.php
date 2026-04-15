<?php
require_once __DIR__ . '/../includes/session.php';
uxp_start_session();
require_once __DIR__ . '/../includes/db.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$message = '';
$messageType = '';

// Default admin credentials (change these in production!)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123'); // Change this!

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $message = 'Please enter both username and password.';
        $messageType = 'danger';
    } elseif ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        // Successful login
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_login_time'] = time();
        
        header('Location: index.php');
        exit;
    } else {
        $message = 'Invalid username or password.';
        $messageType = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login | UX Pacific Community</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../img/faviconUXP444@4x-789.png">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-primary: #030014;
            --bg-secondary: #0a0a1f;
            --card-bg: rgba(15, 15, 35, 0.7);
            --border-color: rgba(139, 92, 246, 0.15);
            --border-glow: rgba(139, 92, 246, 0.4);
            --primary: #8b5cf6;
            --primary-light: #a78bfa;
            --primary-dark: #7c3aed;
            --accent: #06b6d4;
            --text-primary: #ffffff;
            --text-secondary: #c4b5fd;
            --text-muted: #8b8b9e;
            --success: #10b981;
            --danger: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }
        
        .bg-gradient-1 {
            position: absolute;
            top: -30%;
            left: -20%;
            width: 70%;
            height: 70%;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            animation: float1 15s ease-in-out infinite;
        }
        
        .bg-gradient-2 {
            position: absolute;
            bottom: -30%;
            right: -20%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.12) 0%, transparent 70%);
            animation: float2 18s ease-in-out infinite;
        }
        
        .bg-gradient-3 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at center, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
        }
        
        /* Floating particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(139, 92, 246, 0.5);
            border-radius: 50%;
            animation: particleFloat 20s infinite;
        }
        
        .particle:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; animation-duration: 25s; }
        .particle:nth-child(2) { left: 20%; top: 80%; animation-delay: -5s; animation-duration: 20s; }
        .particle:nth-child(3) { left: 60%; top: 10%; animation-delay: -10s; animation-duration: 28s; }
        .particle:nth-child(4) { left: 80%; top: 50%; animation-delay: -15s; animation-duration: 22s; }
        .particle:nth-child(5) { left: 40%; top: 60%; animation-delay: -2s; animation-duration: 18s; }
        .particle:nth-child(6) { left: 90%; top: 30%; animation-delay: -7s; animation-duration: 24s; }
        .particle:nth-child(7) { left: 5%; top: 70%; animation-delay: -12s; animation-duration: 26s; }
        .particle:nth-child(8) { left: 70%; top: 90%; animation-delay: -3s; animation-duration: 21s; }
        
        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-40px, 20px) scale(1.05); }
            66% { transform: translate(30px, -30px) scale(0.95); }
        }
        
        @keyframes particleFloat {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-100vh) translateX(50px); opacity: 0; }
        }
        
        /* Grid pattern overlay */
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(139, 92, 246, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139, 92, 246, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }
        
        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        /* Main container */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Login card */
        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px 40px;
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            box-shadow: 
                0 0 0 1px rgba(139, 92, 246, 0.1),
                0 20px 50px -10px rgba(0, 0, 0, 0.5),
                0 0 100px -20px rgba(139, 92, 246, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        /* Card glow effect */
        .login-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, 
                transparent 30%, 
                rgba(139, 92, 246, 0.1) 50%, 
                transparent 70%);
            border-radius: 25px;
            z-index: -1;
            animation: borderGlow 3s ease-in-out infinite;
        }
        
        @keyframes borderGlow {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }
        
        .logo-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulseGlow 3s ease-in-out infinite;
        }
        
        @keyframes pulseGlow {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.5));
            animation: logoFloat 4s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        .login-header h1 {
            font-family: 'Gabarito', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .login-header h1 span {
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
            font-weight: 400;
        }
        
        /* Form styles */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-label i {
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            z-index: 2;
            transition: color 0.3s ease;
        }
        
        .form-control {
            width: 100%;
            padding: 16px 50px 16px 52px;
            background: rgba(139, 92, 246, 0.05);
            border: 2px solid transparent;
            border-radius: 14px;
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 400;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }
        
        .form-control:hover {
            background: rgba(139, 92, 246, 0.08);
            border-color: rgba(139, 92, 246, 0.2);
        }
        
        .form-control:focus {
            background: rgba(139, 92, 246, 0.1);
            border-color: var(--primary);
            box-shadow: 
                0 0 0 4px rgba(139, 92, 246, 0.15),
                0 0 30px -5px rgba(139, 92, 246, 0.3);
        }
        
        .form-control:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--primary);
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-light);
            background: rgba(139, 92, 246, 0.1);
        }
        
        /* Remember me */
        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }
        
        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
        }
        
        .custom-checkbox input {
            display: none;
        }
        
        .checkbox-box {
            width: 22px;
            height: 22px;
            border: 2px solid rgba(139, 92, 246, 0.3);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            background: rgba(139, 92, 246, 0.05);
        }
        
        .checkbox-box i {
            font-size: 0.8rem;
            color: white;
            opacity: 0;
            transform: scale(0);
            transition: all 0.2s ease;
        }
        
        .custom-checkbox input:checked + .checkbox-box {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-color: var(--primary);
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
        }
        
        .custom-checkbox input:checked + .checkbox-box i {
            opacity: 1;
            transform: scale(1);
        }
        
        .checkbox-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 400;
        }
        
        /* Login button */
        .btn-login {
            width: 100%;
            padding: 18px 24px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 15px 40px -10px rgba(139, 92, 246, 0.5),
                0 0 0 1px rgba(139, 92, 246, 0.3);
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .btn-login i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
        }
        
        .btn-login:hover i {
            transform: translateX(4px);
        }
        
        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        
        .alert-danger i {
            font-size: 1.2rem;
            color: var(--danger);
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 28px 0;
            gap: 16px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
        }
        
        .divider span {
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Back link */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 28px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: var(--primary-light);
            background: rgba(139, 92, 246, 0.1);
        }
        
        .back-link i {
            transition: transform 0.3s ease;
        }
        
        .back-link:hover i {
            transform: translateX(-4px);
        }
        
        /* Security badge */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(139, 92, 246, 0.1);
        }
        
        .security-badge i {
            color: var(--success);
            font-size: 0.9rem;
        }
        
        .security-badge span {
            color: var(--text-muted);
            font-size: 0.8rem;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 36px 28px;
                border-radius: 20px;
            }
            
            .login-header h1 {
                font-size: 1.75rem;
            }
            
            .form-control {
                padding: 14px 45px 14px 48px;
            }
            
            .btn-login {
                padding: 16px 20px;
            }
        }
        
        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        .btn-login.loading .btn-text {
            opacity: 0;
        }
        
        .btn-login .spinner {
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            opacity: 0;
        }
        
        .btn-login.loading .spinner {
            opacity: 1;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Animated background -->
    <div class="bg-animation">
        <div class="bg-gradient-1"></div>
        <div class="bg-gradient-2"></div>
        <div class="bg-gradient-3"></div>
        <div class="grid-overlay"></div>
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>
    
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-wrapper">
                    <div class="logo-glow"></div>
                    <img src="../img/faviconUXP444@4x-789.png" alt="UX Pacific" class="login-logo">
                </div>
                <h1>Welcome <span>Back</span></h1>
                <p>Sign in to access your admin dashboard</p>
            </div>
            
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?php echo h($message); ?></span>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php" id="loginForm">
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person-fill"></i>
                        Username
                    </label>
                    <div class="input-wrapper">
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" 
                               value="<?php echo h($_POST['username'] ?? ''); ?>" required autofocus>
                        <i class="bi bi-person input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-lock-fill"></i>
                        Password
                    </label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="form-control" 
                               placeholder="Enter your password" required>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="remember-row">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="remember">
                        <span class="checkbox-box">
                            <i class="bi bi-check"></i>
                        </span>
                        <span class="checkbox-label">Remember me</span>
                    </label>
                </div>
                
                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        Sign In
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </button>
            </form>
            
            <div class="divider">
                <span>or</span>
            </div>
            
            <a href="../index.php" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Back to Main Website
            </a>
            
            <div class="security-badge">
                <i class="bi bi-shield-check"></i>
                <span>Secured with SSL encryption</span>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
        
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const username = document.querySelector('input[name="username"]').value.trim();
            const password = document.querySelector('input[name="password"]').value;
            
            if (username && password) {
                btn.classList.add('loading');
            }
        });
        
        // Input focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>
</html>
