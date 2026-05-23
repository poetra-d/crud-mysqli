<?php
    include 'koneksi.php';

    $data = $conn->query("SELECT * FROM mahasiswa ORDER BY id DESC");

    if (! $data) {
    die("Query gagal : " . $conn->error);
    }
?>

<!DOCTYPE html>
<html>
<head>

    <title>CRUD Mahasiswa</title>

    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container">

    <h2>Data Mahasiswa</h2>

    <a href="tambah.php" class="btn tambah">
        Tambah Data
    </a>

    <table>

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>

        <?php
            $no = 1;

            while ($row = $data->fetch_assoc()) {
            ?>

        <tr>

            <td><?php echo $no++; ?></td>

            <td><?php echo $row['nama']; ?></td>

            <td><?php echo $row['nim']; ?></td>

            <td><?php echo $row['jurusan']; ?></td>

            <td>

                <a
                    href="edit.php?id=<?php echo $row['id']; ?>"
                    class="btn edit"
                >
                    Edit
                </a>

                <a
                    href="hapus.php?id=<?php echo $row['id']; ?>"
                    class="btn hapus"
                    onclick="return confirm('Yakin hapus data?')"
                >
                    Hapus
                </a>

            </td>

        </tr>

        <?php }?>

    </table>

</div>

</body>
</html>