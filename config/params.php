<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    'smsPilot' => [
        'enabled' => (bool) (int) ($_ENV['SMSPILOT_ENABLED'] ?? getenv('SMSPILOT_ENABLED') ?: 0),
        'apiKey' => $_ENV['SMSPILOT_API_KEY'] ?? getenv('SMSPILOT_API_KEY') ?: '',
        'from' => $_ENV['SMSPILOT_FROM'] ?? getenv('SMSPILOT_FROM') ?: 'INFORM',
    ],
];
