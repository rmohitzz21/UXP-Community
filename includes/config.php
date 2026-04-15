<?php
declare(strict_types=1);

/**
 * Central email + form configuration.
 * Keep sensitive credentials in environment variables in production.
 */
return [
    'app_name' => 'UX Pacific Community',
    'admin_email' => getenv('UXP_ADMIN_EMAIL') ?: 'hello@uxpacific.com',
    'from_email' => getenv('UXP_FROM_EMAIL') ?: 'support@uxpacific.com',
    'from_name' => 'UX Pacific Support',

    'smtp' => [
        'host' => getenv('UXP_SMTP_HOST') ?: 'mail.uxpacific.com',
        'port' => (int)(getenv('UXP_SMTP_PORT') ?: 465),
        'encryption' => getenv('UXP_SMTP_ENCRYPTION') ?: 'ssl',
        'username' => getenv('UXP_SMTP_USERNAME') ?: 'support@uxpacific.com',
        'password' => getenv('UXP_SMTP_PASSWORD') ?: 'UXPacific#2025',
        'timeout' => 10,
    ],

    'security' => [
        'max_field_length' => 2000,
        'min_seconds_between_submissions' => 8,
        'honeypot_field' => 'website',
    ],
];
