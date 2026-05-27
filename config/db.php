<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'mysql:host=%s;port=%s;dbname=%s',
        $_ENV['DB_HOST'] ?? 'mysql',
        $_ENV['DB_PORT'] ?? '3306',
        $_ENV['DB_NAME'] ?? 'yii2'
    ),
    'username' => $_ENV['DB_USER'] ?? 'yii2',
    'password' => $_ENV['DB_PASSWORD'] ?? 'yii2',
    'charset' => 'utf8mb4',
];
