<?php 
include 'koneksi.php'; 
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $st = mysqli_real_escape_string($conn, $_GET['status']);
    mysqli_query($conn, "UPDATE antrean_pesanan SET status_pesanan = '$st' WHERE id = $id");
    header("Location: pedagang.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CanteenGO - Dapur</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #edf2f7; padding: 40px 20px; display: flex; justify-content: center; margin: 0; }
        .tablet-frame { width: 100%; max-width: 850px; background: white; border-radius: 24px; padding: 35px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-top: 8px solid #2e7d32; }
        .header-dapur { margin-bottom: 30px; border-bottom: 2px solid #f0f4f8; padding-bottom: 15px; }
        .header-dapur h2 { color: #1a202c; font-size: 26px; margin-bottom: 5px; }
        .order-item { display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border: 1px solid #e2e8f0; border-radius: 16px; margin-bottom: 15px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .time-badge { padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 700; background: #e2e8f0; color: #4a5568; display: inline-block; margin-bottom: 8px; }
        .btn-action { padding: 14px 24px; border-radius: 12px; font-weight: 800; font-size: 15px; color: white; text-decoration: none; display: inline-block; transition: 0.2s; }
        .btn-wait { background-color: #dd6b20; box-shadow: 0 4px 10px rgba(221, 107, 32, 0.3); }
        .btn-wait:hover { background-color: #c05621; transform: translateY(-2px); }
        .btn-cook { background-color: #38a169; box-shadow: 0 4px 10px rgba(56, 161, 105, 0.3); }
        .btn-cook:hover { background-color: #2f855a; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="tablet-frame">
        <div class="header-dapur">
            <h2>🍳 Monitor Dapur Kantin (Antrean FIFO)</h2>
            <p style="color:#718096; font-size:15px;">Daftar pesanan dari mahasiswa, otomatis terurut dari yang memesan paling awal.</p>
        </div>

        <?php
        $sql = "SELECT * FROM antrean_pesanan WHERE status_pesanan != 'Ready' ORDER BY waktu_pesan ASC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='order-item'>";
                echo "<div>";
                echo "<div class='time-badge'>" . substr($row['waktu_pesan'], 11, 5) . " WIB</div>";
                echo "<h3 style='margin: 0 0 5px 0; font-size: 22px; color: #1a202c;'>Order " . $row['nomor_antrean'] . "</h3>";
                echo "<div style='color:#4a5568; font-size:16px; font-weight: 500;'>" . htmlspecialchars($row['nama_menu']) . "</div>";
                echo "</div>";
                
                echo "<div>";
                if ($row['status_pesanan'] == 'Waiting') {
                    echo "<a href='pedagang.php?id=" . $row['id'] . "&status=Cooking' class='btn-action btn-wait'>🔥 Mulai Masak</a>";
                } elseif ($row['status_pesanan'] == 'Cooking') {
                    echo "<a href='pedagang.php?id=" . $row['id'] . "&status=Ready' class='btn-action btn-cook'>✅ Siap Ambil</a>";
                }
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div style='text-align:center; padding: 60px 20px; background: #f7fafc; border-radius: 16px; border: 2px dashed #cbd5e0; color: #a0aec0; font-size: 16px; font-weight: 600;'>Dapur santai. Belum ada pesanan masuk dari mahasiswa.</div>";
        }
        ?>
    </div>
</body>
</html>
