<?php 
include 'koneksi.php'; 
$antrean_aktif = isset($_GET['antrean']) ? $_GET['antrean'] : '';

if (isset($_POST['pesan'])) {
    $menu = $_POST['menu'];
    $kode = "#" . rand(100, 999); 
    mysqli_query($conn, "INSERT INTO antrean_pesanan (nomor_antrean, nama_menu, status_pesanan) VALUES ('$kode', '$menu', 'Waiting')");
    header("Location: mahasiswa.php?antrean=" . urlencode($kode));
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CanteenGO - App</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #eef2f3; display: flex; flex-direction: column; align-items: center; padding: 40px 20px; margin: 0; }
        .phone-frame { width: 380px; min-height: 650px; background: #fafcfa; border: 14px solid #1a1a1a; border-radius: 40px; padding: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); position: relative; }
        .app-header { text-align: center; font-size: 24px; font-weight: 800; color: #2e7d32; margin-bottom: 25px; }
        .menu-card { background: white; border: 1px solid #eee; border-radius: 16px; padding: 20px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.2s; cursor: pointer; }
        .menu-card:hover { border-color: #2e7d32; }
        .menu-title { font-size: 16px; font-weight: bold; color: #333; margin-bottom: 5px; }
        .menu-price { color: #f57c00; font-weight: 600; font-size: 15px; }
        .btn-order { width: 100%; padding: 16px; background: #2e7d32; color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 15px rgba(46,125,50,0.3); transition: 0.2s; margin-top: 15px; }
        .btn-order:hover { background: #1b5e20; transform: translateY(-2px); }
        
        .tracker-box { margin-top: 30px; background: white; padding: 25px 20px; border-radius: 20px; border: 1px solid #e0e0e0; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .steps { display: flex; justify-content: space-between; margin-top: 25px; position: relative; }
        .step { font-size: 12px; color: #a0aec0; font-weight: 700; width: 33%; display: flex; flex-direction: column; gap: 8px; align-items: center; }
        .step.active { color: #2e7d32; transform: scale(1.1); }
        .step.active-cooking { color: #f57c00; transform: scale(1.1); }
        .icon { font-size: 24px; }
    </style>
</head>
<body>
    <div class="phone-frame">
        <div class="app-header">CanteenGO</div>
        
        <?php if (empty($antrean_aktif)): ?>
            <form method="POST">
                <label class="menu-card">
                    <div>
                        <div class="menu-title">🍗 Ayam Geprek Level 5</div>
                        <div class="menu-price">Rp 15.000</div>
                    </div>
                    <input type="radio" name="menu" value="Ayam Geprek Level 5" checked style="transform: scale(1.5); accent-color: #2e7d32;">
                </label>
                <label class="menu-card">
                    <div>
                        <div class="menu-title">🍜 Mie Goreng Spesial</div>
                        <div class="menu-price">Rp 12.000</div>
                    </div>
                    <input type="radio" name="menu" value="Mie Goreng Spesial" style="transform: scale(1.5); accent-color: #2e7d32;">
                </label>
                <button type="submit" name="pesan" class="btn-order">Bayar & Pesan (QRIS)</button>
            </form>
        <?php else: 
            $q = mysqli_query($conn, "SELECT status_pesanan FROM antrean_pesanan WHERE nomor_antrean = '$antrean_aktif' ORDER BY id DESC LIMIT 1");
            $data = mysqli_fetch_assoc($q);
            $status = $data ? $data['status_pesanan'] : 'Waiting';
        ?>
            <div class="tracker-box">
                <div style="font-size: 14px; color:#718096; font-weight: 600;">KODE PENGAMBILAN</div>
                <div style="color:#2e7d32; font-size: 42px; font-weight: 900; margin: 10px 0; letter-spacing: 2px;"><?php echo htmlspecialchars($antrean_aktif); ?></div>
                
                <div class="steps">
                    <div class="step <?php echo ($status=='Waiting'||$status=='Cooking'||$status=='Ready')?'active':''; ?>">
                        <span class="icon">📥</span> Diterima
                    </div>
                    <div class="step <?php echo ($status=='Cooking'||$status=='Ready')?'active-cooking':''; ?>">
                        <span class="icon">🍳</span> Dimasak
                    </div>
                    <div class="step <?php echo ($status=='Ready')?'active':''; ?>">
                        <span class="icon">✅</span> Siap Ambil
                    </div>
                </div>
                
                <?php if($status=='Ready'): ?>
                    <a href="mahasiswa.php" style="display:inline-block; margin-top:30px; text-decoration:none; color:white; background:#2e7d32; padding: 10px 20px; border-radius:20px; font-weight:bold;">Selesai - Pesan Lagi?</a>
                <?php else: ?>
                    <div style="font-size:12px; color:#a0aec0; margin-top:30px;">*Refresh halaman untuk melihat status terbaru.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
