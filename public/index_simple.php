<?php
// Simple direct PHP implementation for our Product CRUD app

// Database connection from environment variables
$host = getenv('DB_HOST') ?: '127.0.0.1';
$db   = getenv('DB_DATABASE') ?: 'prueba_keppri';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: 3306;
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Request handling
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// API Routes
if (strpos($request_uri, '/api/products') === 0) {
    header('Content-Type: application/json');
    
    // Extract ID from URL if present
    $id = null;
    if (preg_match('/\/api\/products\/(\d+)/', $request_uri, $matches)) {
        $id = $matches[1];
    }
    
    // Handle different HTTP methods
    switch ($method) {
        case 'GET':
            if ($id) {
                // Get a specific product
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                
                if ($product) {
                    echo json_encode($product);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Product not found']);
                }
            } else {
                // Get all products
                $stmt = $pdo->query("SELECT * FROM products");
                $products = $stmt->fetchAll();
                echo json_encode($products);
            }
            break;
            
        case 'POST':
            // Create a new product
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['name']) || !isset($data['price'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Name and price are required']);
                exit;
            }
            
            $stmt = $pdo->prepare("INSERT INTO products (name, price, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
            $stmt->execute([$data['name'], $data['price']]);
            
            $id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            http_response_code(201);
            echo json_encode($product);
            break;
            
        case 'PUT':
            // Update a product
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Product ID is required']);
                exit;
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['name']) || !isset($data['price'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Name and price are required']);
                exit;
            }
            
            $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$data['name'], $data['price'], $id]);
            
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            echo json_encode($product);
            break;
            
        case 'DELETE':
            // Delete a product
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Product ID is required']);
                exit;
            }
            
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            
            http_response_code(204); // No content
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
    exit;
}

// Web Routes
if ($request_uri === '/' || $request_uri === '/products') {
    // Serve products page
    include 'products_page.php';
    exit;
}

// 404 Not Found
http_response_code(404);
echo '404 - Not Found';
