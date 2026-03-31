<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid resource ID']);
    exit;
}

try {
    $pdo = getDB();
    
    // Get resource
    $stmt = $pdo->prepare("SELECT * FROM resources WHERE id = :id AND is_active = 1");
    $stmt->execute([':id' => $id]);
    $resource = $stmt->fetch();
    
    if (!$resource) {
        echo json_encode(['success' => false, 'error' => 'Resource not found']);
        exit;
    }
    
    // Increment download count
    $stmt = $pdo->prepare("UPDATE resources SET downloads = downloads + 1 WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    // If file exists, redirect to download
    if ($resource['file_path'] && file_exists(__DIR__ . '/' . $resource['file_path'])) {
        header('Location: ' . $resource['file_path']);
        exit;
    }
    
    // If external link, redirect
    if ($resource['external_link']) {
        header('Location: ' . $resource['external_link']);
        exit;
    }
    
    echo json_encode(['success' => true, 'downloads' => $resource['downloads'] + 1]);
    
} catch (PDOException $e) {
    error_log("Download tracking error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
