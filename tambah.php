<?php
include 'koneksi.php';

$error   = "";
$success = "";

$nama    = "";
$nim     = "";
$jurusan = "";

if (isset($_POST['submit'])) {

    $nama    = trim($_POST['nama']);
    $nim     = trim($_POST['nim']);
    $jurusan = trim($_POST['jurusan']);

    $nama    = htmlspecialchars($nama);
    $nim     = htmlspecialchars($nim);
    $jurusan = htmlspecialchars($jurusan);

    if (empty($nama) || empty($nim) || empty($jurusan)) {

        $error = "Semua field wajib diisi!";
    } elseif (strlen($nama) < 3) {

        $error = "Nama minimal 3 karakter!";
    } elseif (! is_numeric($nim)) {

        $error = "NIM harus berupa angka!";
    } elseif (strlen($nim) < 5) {

        $error = "NIM minimal 5 digit!";
    } elseif (strlen($jurusan) < 3) {

        $error = "Jurusan minimal 3 karakter!";
    } else {

        $cek = $conn->prepare(
            "SELECT id FROM mahasiswa WHERE nim=?"
        );

        if (! $cek) {
            die("Prepare gagal : " . $conn->error);
        }

        $cek->bind_param("s", $nim);

        $cek->execute();

        $result = $cek->get_result();

        if ($result->num_rows > 0) {

            $error = "NIM sudah terdaftar!";
        } else {

            $stmt = $conn->prepare(
                "INSERT INTO mahasiswa(nama, nim, jurusan)
                VALUES (?, ?, ?)"
            );

            if (! $stmt) {
                die("Prepare gagal : " . $conn->error);
            }

            $stmt->bind_param(
                "sss",
                $nama,
                $nim,
                $jurusan
            );

            if ($stmt->execute()) {

                $success = "Data berhasil ditambahkan!";

                $nama    = "";
                $nim     = "";
                $jurusan = "";
            } else {

                $error = "Gagal tambah data!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Tambah Data</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h2 class="form-title">Tambah Data Mahasiswa</h2>

        <?php if ($error != "") { ?>
            <div class="error">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <?php if ($success != "") { ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php } ?>

        <form method="POST">

            <input
                type="text"
                name="nama"
                placeholder="Masukkan Nama"
                value="<?php echo $nama; ?>">

            <input
                type="text"
                name="nim"
                placeholder="Masukkan NIM"
                value="<?php echo $nim; ?>">

            <select name="jurusan">

                <option value="">
                    -- Pilih Jurusan --
                </option>

                <option value="Teknik Informatika" <?= $jurusan == 'Teknik Informatika' ? 'selected' : ''; ?>>
                    Teknik Informatika
                </option>

                <option value="Sistem Informasi" <?= $jurusan == 'Sistem Informasi' ? 'selected' : ''; ?>>
                    Sistem Informasi
                </option>

                <option value="Manajemen Informatika" <?= $jurusan == 'Manajemen Informatika' ? 'selected' : ''; ?>>
                    Manajemen Informatika
                </option>

                <option value="Teknik Komputer" <?= $jurusan == 'Teknik Komputer' ? 'selected' : ''; ?>>
                    Teknik Komputer
                </option>

            </select>

            <button type="submit" name="submit">
                Simpan
            </button>

            <a href="index.php" class="btn kembali">
                Kembali
            </a>

        </form>

    </div>

</body>

</html>