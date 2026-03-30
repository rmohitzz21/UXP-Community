<?php
/**
 * Database Configuration
 * UX Pacific Community
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'uxcommunity');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO Database Connection
 * @return PDO
 */
function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    
    return $pdo;
}

/**
 * Sanitize output for HTML display
 * @param string $str
 * @return string
 */
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Upload image with validation
 * @param array $file $_FILES array element
 * @param string $uploadDir Directory to upload to
 * @return array ['success' => bool, 'filename' => string|null, 'error' => string|null]
 */
function uploadImage($file, $uploadDir = 'uploads/ideas/') {
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => true, 'filename' => null, 'error' => null]; // No file uploaded, that's OK
    }
    
    // Validate upload error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'filename' => null, 'error' => 'File upload failed.'];
    }
    
    // Validate file size (max 5MB)
    $maxSize = 5 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'filename' => null, 'error' => 'File size must be less than 5MB.'];
    }
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'filename' => null, 'error' => 'Only JPG, PNG, GIF, and WebP images are allowed.'];
    }
    
    // Create upload directory if it doesn't exist
    $fullUploadDir = __DIR__ . '/../' . $uploadDir;
    if (!is_dir($fullUploadDir)) {
        mkdir($fullUploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('idea_', true) . '.' . strtolower($extension);
    $destination = $fullUploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $uploadDir . $filename, 'error' => null];
    }
    
    return ['success' => false, 'filename' => null, 'error' => 'Failed to save uploaded file.'];
}

/**
 * Delete uploaded file
 * @param string $filepath Path relative to project root
 * @return bool
 */
function deleteUploadedFile($filepath) {
    if (empty($filepath)) return true;
    
    $fullPath = __DIR__ . '/../' . $filepath;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return true;
}
