<?php

if (!isset($_GET['id'])) {
    die("Art ImageId is required");
}
$artImageId = $_GET['id'];

$imageFolder = "../images";
$imagePath = "$imageFolder/$artImageId.jpeg";

// Mengecek apakah file gambar ada
if (file_exists($imagePath)) {
    // Mengambil isi file gambar
    $imageData = file_get_contents($imagePath);

    // Mengatur header untuk pengiriman gambar
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . strlen($imageData));

    // Mengirimkan gambar
    echo $imageData;
    exit;
} else {
    // Jika file gambar tidak ditemukan, mengirimkan respon 404
    header("HTTP/1.0 404 Not Found");
    echo json_encode(['status' => 'error', 'message' => 'Image not found']);
    exit;
}


// Menutup koneksi
$mysqli->close();