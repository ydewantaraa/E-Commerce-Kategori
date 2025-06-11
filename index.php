<?php
include '../koneksi.php'; // File koneksi ke database

// Ambil kategori dari URL (jika ada)
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Query kategori unik manual (karena kita tahu kategorinya tetap)
$kategori_list = ['Kaos', 'Sepatu', 'Jam'];

// Ambil produk berdasarkan filter
if ($filter_kategori && in_array($filter_kategori, $kategori_list)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE kategori = ?");
    $stmt->bind_param("s", $filter_kategori);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Produk Kami</h1>

    <!-- Filter Kategori -->
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori_list as $kategori): ?>
                        <option value="<?= $kategori ?>" <?= ($kategori === $filter_kategori) ? 'selected' : '' ?>>
                            <?= $kategori ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <!-- Daftar Produk -->
    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="images/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama']) ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row['nama']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['deskripsi']) ?></p>
                            <p class="card-text"><strong>Rp <?= number_format($row['harga'], 0, ',', '.') ?></strong></p>
                            <p class="text-muted"><small>Kategori: <?= htmlspecialchars($row['kategori']) ?></small></p>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="btn btn-primary w-100">Beli Sekarang</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    Produk tidak ditemukan untuk kategori ini.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
