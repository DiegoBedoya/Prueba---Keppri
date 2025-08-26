<?php

// Database setup script
// This script will create the products table directly using PDO

try {
    // Connect to MySQL
    $host = '127.0.0.1';
    $db   = 'prueba_keppri';
    $user = 'root';
    $pass = '';
    $port = 3306;
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Try to create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    echo "Database created or already exists.\n";
    
    // Connect to the database
    $pdo->exec("USE `$db`");
    
    // Create the products table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS `products` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    echo "Products table created successfully!\n";
    
    // Add some sample products
    $stmt = $pdo->prepare("INSERT INTO products (name, price, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
    
    $sampleProducts = [
        ['Laptop', 999.99],
        ['Smartphone', 699.99],
        ['Headphones', 89.99],
        ['Mouse', 24.99],
    ];
    
    foreach ($sampleProducts as $product) {
        $stmt->execute([$product[0], $product[1]]);
    }
    
    echo "Sample products added successfully!\n";
    echo "Setup completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
