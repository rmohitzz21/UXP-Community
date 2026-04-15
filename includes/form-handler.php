<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');

require_once __DIR__ . '/mailer.php';
$config = require __DIR__ . '/config.php';

function jsonResponse(bool $success, string $message, int $status = 200, array $extra = []): void
{
    http_response_code($status);
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message,
    ], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

function getClientIp(): string
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', (string)$_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function sanitizeString(?string $value, int $maxLength): string
{
    $value = trim((string)$value);
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value) ?? '';
    if (mb_strlen($value) > $maxLength) {
        $value = mb_substr($value, 0, $maxLength);
    }
    return $value;
}

function prettyFormType(string $formType): string
{
    $map = [
        'contact' => 'Contact Form',
        'join_community' => 'Join Community',
        'event_registration' => 'Event Registration',
        'share_your_idea' => 'Share Your Idea',
    ];
    return $map[$formType] ?? ucwords(str_replace('_', ' ', $formType));
}

function buildAdminHtml(string $appName, string $prettyType, string $ip, array $data): string
{
    $rows = '';
    foreach ($data as $k => $v) {
        $label = ucwords(str_replace('_', ' ', $k));
        $value = nl2br(htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'));
        $rows .= "<tr><td style=\"padding:10px 12px;border:1px solid #ececf3;font-weight:600;color:#37374a;\">{$label}</td><td style=\"padding:10px 12px;border:1px solid #ececf3;color:#111;\">{$value}</td></tr>";
    }

    return "<!doctype html><html><body style=\"margin:0;background:#f7f8fc;font-family:Segoe UI,Arial,sans-serif;\">
    <table role=\"presentation\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"padding:24px;\">
      <tr><td align=\"center\">
        <table role=\"presentation\" width=\"680\" cellspacing=\"0\" cellpadding=\"0\" style=\"max-width:680px;background:#fff;border:1px solid #ececf3;border-radius:12px;overflow:hidden;\">
          <tr><td style=\"background:linear-gradient(135deg,#5a3fff,#7a67ff);padding:18px 24px;color:#fff;\"><h2 style=\"margin:0;font-size:20px;\">{$appName}</h2><p style=\"margin:6px 0 0;font-size:13px;opacity:.9;\">New {$prettyType} submission</p></td></tr>
          <tr><td style=\"padding:18px 24px;\">
            <p style=\"margin:0 0 12px;color:#4a4a5f;\">A new form submission has been received.</p>
            <p style=\"margin:0 0 16px;color:#6d6d82;font-size:13px;\">Source IP: <strong>" . htmlspecialchars($ip, ENT_QUOTES, 'UTF-8') . "</strong></p>
            <table role=\"presentation\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"border-collapse:collapse;background:#fff;\">{$rows}</table>
          </td></tr>
        </table>
      </td></tr>
    </table></body></html>";
}

function buildUserHtml(string $userName, string $prettyType): string
{
    $safeName = htmlspecialchars($userName, ENT_QUOTES, 'UTF-8');
    $safeType = htmlspecialchars($prettyType, ENT_QUOTES, 'UTF-8');
    return "<!doctype html><html><body style=\"margin:0;background:#f5f7ff;font-family:Segoe UI,Arial,sans-serif;\">
    <table role=\"presentation\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"padding:24px;\">
      <tr><td align=\"center\">
        <table role=\"presentation\" width=\"680\" cellspacing=\"0\" cellpadding=\"0\" style=\"max-width:680px;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e7e9f3;\">
          <tr>
            <td style=\"background:linear-gradient(135deg,#1a1a2e,#6147bd);padding:26px 28px;color:#fff;\">
              <h1 style=\"margin:0;font-size:24px;line-height:1.2;\">UX Pacific Community</h1>
              <p style=\"margin:8px 0 0;font-size:14px;opacity:.9;\">Thanks for reaching out. We received your submission.</p>
            </td>
          </tr>
          <tr><td style=\"padding:28px;\">
            <p style=\"margin:0 0 12px;color:#232336;font-size:16px;\">Hi {$safeName},</p>
            <p style=\"margin:0 0 14px;color:#4e4e62;line-height:1.65;\">Thank you for submitting the <strong>{$safeType}</strong>. Our team has received your details and will contact you soon.</p>
            <div style=\"background:#f3f4ff;border:1px solid #e3e6ff;border-radius:10px;padding:14px 16px;color:#444a6a;\">
              <p style=\"margin:0 0 6px;font-weight:600;\">What happens next?</p>
              <p style=\"margin:0;line-height:1.6;\">We review your submission and respond with the next steps within 24 hours.</p>
            </div>
            <p style=\"margin:18px 0 0;color:#4e4e62;line-height:1.65;\">Best regards,<br><strong>UX Pacific Team</strong></p>
          </td></tr>
        </table>
      </td></tr>
    </table></body></html>";
}

function logSubmission(string $formType, string $status, string $message, string $ip): void
{
    $logsDir = __DIR__ . '/../logs';
    if (!is_dir($logsDir)) {
        mkdir($logsDir, 0755, true);
    }

    $line = json_encode([
        'timestamp' => date('c'),
        'form_type' => $formType,
        'ip' => $ip,
        'status' => $status,
        'message' => $message,
    ], JSON_UNESCAPED_UNICODE);

    file_put_contents($logsDir . '/form-submissions.log', $line . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function checkRateLimit(string $ip, int $minSeconds): bool
{
    $logsDir = __DIR__ . '/../logs';
    if (!is_dir($logsDir)) {
        mkdir($logsDir, 0755, true);
    }
    $key = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $ip) ?: 'unknown';
    $file = $logsDir . '/rate-' . $key . '.txt';
    $now = time();
    $last = is_file($file) ? (int)file_get_contents($file) : 0;

    if ($last > 0 && ($now - $last) < $minSeconds) {
        return false;
    }
    file_put_contents($file, (string)$now, LOCK_EX);
    return true;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    jsonResponse(false, 'Invalid request method.', 405);
}

$ip = getClientIp();
$maxLen = (int)$config['security']['max_field_length'];
$honeypotField = (string)$config['security']['honeypot_field'];
$minInterval = (int)$config['security']['min_seconds_between_submissions'];

if (!empty($_POST[$honeypotField])) {
    logSubmission('unknown', 'blocked', 'Honeypot triggered', $ip);
    jsonResponse(false, 'Spam detected.', 400);
}

if (!checkRateLimit($ip, $minInterval)) {
    logSubmission('unknown', 'blocked', 'Rate limit triggered', $ip);
    jsonResponse(false, 'Please wait a few seconds before submitting again.', 429);
}

$rawType = sanitizeString($_POST['form_type'] ?? '', 64);
$normalizedType = preg_replace('/\s+/', '_', strtolower(str_replace('-', '_', $rawType))) ?? '';

if ($normalizedType === 'share_your_idea' || $normalizedType === 'share_youridea') {
    $formType = 'share_your_idea';
} else {
    $formType = $normalizedType;
}

$data = [];
$errors = [];

switch ($formType) {
    case 'contact':
        $data['name'] = sanitizeString($_POST['name'] ?? '', 120);
        $data['email'] = sanitizeString($_POST['email'] ?? '', 180);
        $data['phone'] = sanitizeString($_POST['phone'] ?? '', 40);
        $data['industry'] = sanitizeString($_POST['industry'] ?? '', 80);
        $data['message'] = sanitizeString($_POST['message'] ?? '', $maxLen);

        if ($data['name'] === '') $errors[] = 'Name is required.';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if ($data['message'] === '') $errors[] = 'Message is required.';
        break;

    case 'join_community':
        $data['email'] = sanitizeString($_POST['email'] ?? '', 180);
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        break;

    case 'event_registration':
        $data['name'] = sanitizeString($_POST['name'] ?? '', 120);
        $data['email'] = sanitizeString($_POST['email'] ?? '', 180);
        $data['phone'] = sanitizeString($_POST['phone'] ?? '', 40);
        $data['company'] = sanitizeString($_POST['company'] ?? '', 180);
        $data['message'] = sanitizeString($_POST['message'] ?? ($_POST['expectations'] ?? ''), $maxLen);

        if ($data['name'] === '') $errors[] = 'Name is required.';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        break;

    case 'share_your_idea':
        $data['name'] = sanitizeString($_POST['name'] ?? '', 120);
        $data['email'] = sanitizeString($_POST['email'] ?? '', 180);
        $data['idea'] = sanitizeString($_POST['idea'] ?? ($_POST['title'] ?? ''), 200);
        $data['details'] = sanitizeString($_POST['details'] ?? ($_POST['description'] ?? ''), $maxLen);

        if ($data['name'] === '') $errors[] = 'Name is required.';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if ($data['idea'] === '') $errors[] = 'Idea is required.';
        if ($data['details'] === '') $errors[] = 'Details are required.';
        break;

    default:
        logSubmission($formType ?: 'unknown', 'error', 'Invalid form type', $ip);
        jsonResponse(false, 'Invalid form type.', 400);
}

if ($errors) {
    $errorText = implode(' ', $errors);
    logSubmission($formType, 'validation_error', $errorText, $ip);
    jsonResponse(false, $errorText, 422, ['errors' => $errors]);
}

$prettyType = prettyFormType($formType);
$adminSubject = $prettyType . ' | New Submission';
$adminBodyText = "New submission received\n\nForm: {$formType}\nIP: {$ip}\n\n";
foreach ($data as $k => $v) {
    $adminBodyText .= ucfirst($k) . ": {$v}\n";
}
$adminBodyHtml = buildAdminHtml((string)$config['app_name'], $prettyType, $ip, $data);

$adminResult = sendMail(
    $config,
    $config['admin_email'],
    'Admin',
    $adminSubject,
    $adminBodyHtml,
    $adminBodyText
);

$userEmail = $data['email'] ?? '';
$userName = $data['name'] ?? 'there';
$autoSubject = 'Submission received - UX Pacific Community';
$autoText = "Hi {$userName},\n\nThank you for submitting the {$prettyType}. Our team has received your details and will contact you soon.\n\nBest regards,\nUX Pacific Team";
$autoHtml = buildUserHtml($userName, $prettyType);

$autoResult = ['success' => false, 'error' => 'No recipient'];
if ($userEmail !== '' && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    $autoResult = sendMail($config, $userEmail, $userName, $autoSubject, $autoHtml, $autoText);
}

// Submission is accepted if at least one outbound mail succeeds.
if (!$adminResult['success'] && !$autoResult['success']) {
    logSubmission(
        $formType,
        'error',
        'Both mails failed. Admin: ' . ($adminResult['error'] ?? 'n/a') . ' | Auto: ' . ($autoResult['error'] ?? 'n/a'),
        $ip
    );
    jsonResponse(false, 'Submission saved, but email delivery failed. Please try again later.', 500);
}

$deliveryMessage = 'Admin=' . ($adminResult['success'] ? 'sent' : 'failed') . ', Auto=' . ($autoResult['success'] ? 'sent' : 'failed');
logSubmission($formType, 'success', $deliveryMessage, $ip);
jsonResponse(true, 'Submitted successfully.');
