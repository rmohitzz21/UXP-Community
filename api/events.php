<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Example mock data from a database model
$events = [
    [
        'id' => 1,
        'title' => 'Design Systems Summit 2026',
        'type' => 'Upcoming Event',
        'description' => 'A 24-hour creative marathon where designers team up to build, prototype, and share ideas using Figma.',
        'date' => 'Nov 15, 2026',
        'time' => '10:00 AM PST',
        'location' => 'Virtual',
        'attendees' => '500+ attendees',
        'image' => 'assets/img/et.png'
    ],
    [
        'id' => 2,
        'title' => 'Web3 Design Patterns',
        'type' => 'Past Event',
        'description' => 'Dive into real design projects, UX challenges, and research stories crafted by our community.',
        'date' => 'Nov 15, 2024',
        'time' => '10:00 AM PST',
        'location' => 'Virtual',
        'attendees' => '800+ attended',
        'image' => 'assets/img/et.png'
    ]
];

echo json_encode(['status' => 'success', 'data' => $events]);
exit;
