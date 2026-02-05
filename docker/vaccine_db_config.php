<?php
return [
    'host' => getenv('DB_HOST') ?: 'db',
    'dbname' => getenv('DB_NAME') ?: 'vaccine_db',
    'user' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: 'root',
    'charset' => 'utf8mb4'
];
