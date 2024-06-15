<?php
include('../config/Database.php');
header("Content-Type: application/json; charset=UTF-8");
$headers = getallheaders();
$email = isset($headers['authorization']) ? $headers['authorization'] : '';
$method = $_SERVER['REQUEST_METHOD'];

if ($method != "POST") {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    exit;
}

try {
    if (empty($email)) {
        http_response_code(401);
        throw new Exception('Unauthorized');
    }

    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if (empty($id)) {
        http_response_code(400);
        throw new Exception('Invalid request: ID is required');
    }

    $email = $mysqli->real_escape_string($email);
    $artId = $mysqli->real_escape_string($id);

    $selectSql = "SELECT image_id FROM arts WHERE id = '$artId' AND email = '$email'";
    $artResult = $mysqli->query($selectSql);
    if ($artResult && $artResult->num_rows > 0) {
        $art = $artResult->fetch_assoc();
        $imageId = $art['image_id'];

        $fileName = $imageId . ".jpeg";
        $directory = "../images/";
        $targetFilePath = $directory . $fileName;

        if (file_exists($targetFilePath)) {
            unlink($targetFilePath);
        }

        $deleteSql = "DELETE FROM arts WHERE id = '$artId' AND email = '$email'";
        if ($mysqli->query($deleteSql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Art deleted successfully"]);
        } else {
            http_response_code(500);
            throw new Exception("Error: " . $deleteSql . "<br>" . $mysqli->error);
        }
    } else {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Forbidden: You do not have permission to delete this art data']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}

$mysqli->close();