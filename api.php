<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/* ==========================
   KONFIGURASI DATABASE
========================== */

//$host = 'localhost';
//$user = 'root';
//$pass = 'rootpass';
//$db   = 'hybrid_db';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

/* ==========================
   TEST CONNECTION
========================== */
if ($method === 'GET' && $action === 'test') {
    $version = $pdo->query("SELECT VERSION() as version")->fetch();
    echo json_encode([
        'success' => true,
        'message' => 'Terhubung ke MySQL ' . $version['version']
    ]);
    exit;
}

/* ==========================
   GET DATA
========================== */
if ($method === 'GET' && $action === 'get') {

    $store = $_GET['store'] ?? '';
    $table = $store === 'users' ? 'users' : 'posts';

    $stmt = $pdo->query("SELECT * FROM $table ORDER BY created_at DESC");
    $rows = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data' => $rows
    ]);
    exit;
}

/* ==========================
   POST (ADD / UPDATE / DELETE)
========================== */
if ($method === 'POST') {

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit;
    }

    $action = $input['action'] ?? '';
    $store  = $input['store'] ?? '';
    $data   = $input['data'] ?? [];

    try {

        /* ======================
           USERS
        ====================== */
        if ($store === 'users') {

            if ($action === 'add' || $action === 'update') {

                $createdAt = date('Y-m-d H:i:s', strtotime($data['createdAt']));
                $updatedAt = date('Y-m-d H:i:s', strtotime($data['updatedAt'] ?? $data['createdAt']));

                $sql = "INSERT INTO users
                        (id, username, email, name, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE
                        username = VALUES(username),
                        email = VALUES(email),
                        name = VALUES(name),
                        updated_at = VALUES(updated_at)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['id'],
                    $data['username'],
                    $data['email'],
                    $data['name'],
                    $createdAt,
                    $updatedAt
                ]);

                echo json_encode(['success' => true]);
                exit;
            }

            if ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$data['id']]);

                echo json_encode(['success' => true]);
                exit;
            }
        }

        /* ======================
           POSTS
        ====================== */
        if ($store === 'posts') {

            if ($action === 'add' || $action === 'update') {

                $createdAt = date('Y-m-d H:i:s', strtotime($data['createdAt']));
                $updatedAt = date('Y-m-d H:i:s', strtotime($data['updatedAt'] ?? $data['createdAt']));

                $sql = "INSERT INTO posts
                        (id, user_id, title, content, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE
                        user_id = VALUES(user_id),
                        title = VALUES(title),
                        content = VALUES(content),
                        updated_at = VALUES(updated_at)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $data['id'],
                    $data['userId'],
                    $data['title'],
                    $data['content'],
                    $createdAt,
                    $updatedAt
                ]);

                echo json_encode(['success' => true]);
                exit;
            }

            if ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
                $stmt->execute([$data['id']]);

                echo json_encode(['success' => true]);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);