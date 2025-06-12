<?php
// Mengimpor semua fungsi yang dibutuhkan (tambah, hapus, toggle, edit)
include 'functions.php';

// 🔁 Menangani aksi "edit" (GET method) dengan mengambil indeks tugas yang sedang diedit
$editIndex = isset($_GET['edit']) ? (int)$_GET['edit'] : -1;

// 🧠 Proses logika saat ada form yang dikirim (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ✅ Menambahkan tugas baru
    if (isset($_POST['tambah'])) {
        tambahTugas($_POST['judul']);
    }

    // 🗑️ Menghapus tugas berdasarkan index
    elseif (isset($_POST['hapus'])) {
        hapusTugas($_POST['hapus']);
    }

    // 🔁 Mengubah status tugas: dari 'belum' ↔ 'selesai'
    elseif (isset($_POST['toggle'])) {
        toggleStatus($_POST['toggle']);
    }

    // ✏️ Menyimpan hasil edit judul tugas
    elseif (isset($_POST['save'])) {
        editTugas($_POST['index'], $_POST['judul_baru']);
    }

    // 🔁 Redirect kembali ke index.php agar tidak terjadi resubmission saat reload
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ToDo List</title>
    <!-- 🎨 Link ke file CSS eksternal -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>📝 ToDoList</h1>
    <p>Gunakan centang untuk tandai selesai, atau hapus tugas.</p>

    <!-- 🧾 Form untuk menambahkan tugas baru -->
    <form method="post" class="tambah-form">
        <input type="text" name="judul" placeholder="Tulis tugas baru..." required>
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <!-- 📋 Tabel daftar tugas -->
    <table>
        <tr>
            <th>Status</th>
            <th>Judul Tugas</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>

        <!-- 🔁 Looping setiap item tugas dari session -->
        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
        <tr>
            <!-- ✅ Checkbox untuk toggle status -->
            <td>
                <form method="post">
                    <input type="hidden" name="toggle" value="<?= $index ?>">
                    <input type="checkbox" onchange="this.form.submit()" <?= $task['status'] === 'selesai' ? 'checked' : '' ?>>
                </form>
            </td>

            <!-- ✏️ Kolom judul tugas -->
            <td>
                <?php if ($editIndex === $index): ?>
                    <!-- 📝 Jika sedang dalam mode edit -->
                    <form method="post" style="display:flex; gap:5px;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="text" name="judul_baru" value="<?= htmlspecialchars($task['judul']) ?>" required>
                        <button type="submit" name="save">Save</button>
                    </form>
                <?php else: ?>
                    <!-- 📌 Tampilan normal jika tidak sedang edit -->
                    <span class="<?= $task['status'] === 'selesai' ? 'selesai' : '' ?>">
                        <?= htmlspecialchars($task['judul']) ?>
                    </span>
                <?php endif; ?>
            </td>

            <!-- 📌 Keterangan status tugas -->
            <td>
                <?= $task['status'] === 'selesai' ? '✅ <span class="green">Selesai</span>' : '⏳ <span class="gray">Belum selesai</span>' ?>
            </td>

            <!-- 🔘 Tombol Hapus dan Edit -->
            <td style="display: flex; gap: 5px;">
                <!-- 🗑️ Tombol hapus -->
                <form method="post">
                    <button type="submit" name="hapus" value="<?= $index ?>">Hapus</button>
                </form>

                <!-- ✏️ Tombol edit (ditampilkan jika tidak sedang edit baris ini) -->
                <?php if ($editIndex !== $index): ?>
                <form method="get">
                    <input type="hidden" name="edit" value="<?= $index ?>">
                    <button type="submit">Edit</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
