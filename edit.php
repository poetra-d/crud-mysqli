<?php
include 'koneksi.php';

if (! isset($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = $_GET['id'];

$query = $conn->prepare(
    "SELECT * FROM mahasiswa WHERE id=?"
);

if (! $query) {
    die("Prepare gagal : " . $conn->error);
}

$query->bind_param("i", $id);

$query->execute();

$result = $query->get_result();

$data = $result->fetch_assoc();

if (! $data) {
    die("Data tidak ditemukan!");
}

$error = "";

if (isset($_POST['submit'])) {

    $nama    = trim($_POST['nama']);
    $nim     = trim($_POST['nim']);
    $jurusan = trim($_POST['jurusan']);

    $nama    = htmlspecialchars($nama);
    $nim     = htmlspecialchars($nim);
    $jurusan = htmlspecialchars($jurusan);

    if (
        empty($nama) ||
        empty($nim) ||
        empty($jurusan)
    ) {

        $error = "Semua field wajib diisi!";
    } elseif (! is_numeric($nim)) {

        $error = "NIM harus angka!";
    } else {

        $cek = $conn->prepare(
            "SELECT id FROM mahasiswa
            WHERE nim=? AND id != ?"
        );

        $cek->bind_param("si", $nim, $id);

        $cek->execute();

        $cekResult = $cek->get_result();

        if ($cekResult->num_rows > 0) {

            $error = "NIM sudah digunakan!";
        } else {

            $stmt = $conn->prepare(
                "UPDATE mahasiswa
                SET nama=?, nim=?, jurusan=?
                WHERE id=?"
            );

            if (! $stmt) {
                die("Prepare gagal : " . $conn->error);
            }

            $stmt->bind_param(
                "sssi",
                $nama,
                $nim,
                $jurusan,
                $id
            );

            if ($stmt->execute()) {

                header("Location: index.php");
                exit;
            } else {

                $error = "Gagal update data!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Data</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h2 class="form-title">Edit Data Mahasiswa</h2>

        <?php if ($error != "") { ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <input
                type="text"
                name="nama"
                value="<?php echo $data['nama']; ?>">

            <input
                type="text"
                name="nim"
                value="<?php echo $data['nim']; ?>">

            <select name="jurusan">

                <option value="">
                    -- Pilih Jurusan --
                </option>

                <option value="Teknik Informatika" <?= $data['jurusan'] == 'Teknik Informatika' ? 'selected' : ''; ?>>
                    Teknik Informatika
                </option>

                <option value="Sistem Informasi" <?= $data['jurusan'] == 'Sistem Informasi' ? 'selected' : ''; ?>>
                    Sistem Informasi
                </option>

                <option value="Manajemen Informatika" <?= $data['jurusan'] == 'Manajemen Informatika' ? 'selected' : ''; ?>>
                    Manajemen Informatika
                </option>

                <option value="Teknik Komputer" <?= $data['jurusan'] == 'Teknik Komputer' ? 'selected' : ''; ?>>
                    Teknik Komputer
                </option>

            </select>


            <button type="submit" name="submit">
                Update
            </button>

            <a href="index.php" class="btn kembali">
                Kembali
            </a>

        </form>

    </div>

</body>

</html>