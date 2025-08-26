<?php

// Simple development server script
// This script will start a PHP built-in web server for our application

// First, check if there's an APP_KEY in the .env file
$envFile = __DIR__ . '/.env';
$envContent = file_get_contents($envFile);

if (strpos($envContent, 'APP_KEY=') !== false && strpos($envContent, 'APP_KEY=base64:') === false) {
    // Generate a random key
    $key = 'base64:' . base64_encode(random_bytes(32));
    $envContent = preg_replace('/APP_KEY=/', 'APP_KEY=' . $key, $envContent);
    file_put_contents($envFile, $envContent);
    echo "Generated APP_KEY successfully.\n";
}

// Check if database exists and setup if needed
echo "Running database setup script...\n";
include_once __DIR__ . '/setup.php';

// Start the built-in PHP web server
$host = '127.0.0.1';
$port = '8000';
$rootDir = __DIR__ . '/public';

echo "Starting development server at http://$host:$port\n";
echo "Press Ctrl+C to stop the server\n";

// Using PHP's built-in web server
$command = sprintf(
    'cd "%s" && %s -S %s:%s "%s/server.php"',
    __DIR__,
    PHP_BINARY,
    $host,
    $port,
    $rootDir
);

system($command);
