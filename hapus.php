<?php
include 'koneksi.php';

if (! isset($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = $_GET['id'];

$stmt = $conn->prepare(
    "DELETE FROM mahasiswa WHERE id=?"
);

if (! $stmt) {
    die("Prepare gagal : " . $conn->error);
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    header("Location: index.php");
    exit;
} else {

    echo "Gagal hapus data!";
}
