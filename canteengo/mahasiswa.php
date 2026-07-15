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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CanteenGO - App</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #eef2f3; display: flex; flex-direction: column; align-items: center; padding: 40px 20px; margin: 0; min-height: 100vh; }
        
        /* Frame Mobile */
        .phone-frame { width: 100%; max-width: 400px; height: 750px; background: #fafcfa; border: 14px solid #1a1a1a; border-radius: 40px; padding: 25px; box-shadow: 0 25px 50px rgba(0,0,0,0.15); position: relative; overflow-y: auto; overflow-x: hidden; }
        .phone-frame::-webkit-scrollbar { width: 6px; }
        .phone-frame::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
        
        .app-header { text-align: center; font-size: 26px; font-weight: 800; color: #2e7d32; margin-bottom: 25px; letter-spacing: -0.5px; }
        
        /* Desain Card Menu */
        .menu-card { background: white; border: 2px solid #eee; border-radius: 20px; padding: 20px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: 0.3s; cursor: pointer; }
        .menu-title { font-size: 17px; font-weight: 700; color: #2d3748; margin-bottom: 5px; }
        .menu-price { color: #f57c00; font-weight: 700; font-size: 15px; }
        
        /* Area Opsi Modifikasi Makanan */
        .options-container { display: none; margin-top: 15px; padding-top: 15px; border-top: 1px dashed #cbd5e0; animation: fadeIn 0.3s ease; }
        .options-container.show { display: block; }
        .mod-group { margin-bottom: 12px; text-align: left; }
        .mod-label { font-size: 12px; color: #718096; font-weight: 700; display: block; margin-bottom: 5px; }
        .mod-select { width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #e2e8f0; font-family: 'Poppins'; font-size: 13px; color: #2d3748; outline: none; }
        .mod-group label { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #4a5568; margin-top: 8px; cursor: pointer; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }

        .btn-order { width: 100%; padding: 18px; background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white; border: none; border-radius: 16px; font-size: 16px; font-weight: 700; cursor: pointer; box-shadow: 0 8px 20px rgba(46,125,50,0.3); transition: 0.3s; margin-top: 10px; margin-bottom: 20px; }
        .btn-order:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(46,125,50,0.4); }
        .btn-order:disabled { background: #a0aec0; cursor: not-allowed; box-shadow: none; transform: none; }
        
        /* Modal QRIS */
        .qris-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.95); backdrop-filter: blur(5px); display: none; flex-direction: column; justify-content: center; align-items: center; z-index: 10; text-align: center; padding: 20px; border-radius: 25px; }
        .spinner { width: 50px; height: 50px; border: 5px solid #e2e8f0; border-top: 5px solid #f57c00; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        
        /* Status Tracker UI */
        .tracker-box { margin-top: 20px; background: white; padding: 30px 20px; border-radius: 24px; border: 1px solid #e0e0e0; text-align: center; box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
        .steps { display: flex; justify-content: space-between; margin-top: 30px; position: relative; }
        .step { font-size: 13px; color: #a0aec0; font-weight: 700; width: 33%; display: flex; flex-direction: column; gap: 10px; align-items: center; z-index: 1; background: white; padding: 0 5px; }
        .step.active { color: #2e7d32; transform: scale(1.1); }
        .step.active-cooking { color: #f57c00; transform: scale(1.1); }
        .steps::before { content: ''; position: absolute; top: 15px; left: 15%; width: 70%; height: 2px; background: #edf2f7; z-index: 0; }
        .icon { font-size: 28px; }
        
        /* Rating Stars */
        .star { cursor: pointer; font-size: 35px; filter: grayscale(100%); transition: 0.2s; }
        .star:hover { transform: scale(1.1); }
    </style>
</head>
<body>
    <div class="phone-frame">
        <div class="app-header">Canteen<span>GO</span></div>
        
        <?php if (empty($antrean_aktif)): ?>
            <!-- HALAMAN PEMESANAN (MENU) -->
            <form id="form-pesanan" method="POST">
                <div id="qris-modal" class="qris-overlay">
                    <div class="spinner"></div>
                    <div style="font-size: 18px; font-weight: 700; color: #2d3748;">Memproses QRIS...</div>
                    <div style="font-size: 13px; color: #718096; margin-top: 8px;">Mohon tunggu sebentar.</div>
                </div>

                <input type="hidden" name="menu" id="input_menu_final">
                <input type="hidden" name="pesan" value="1">

                <!-- MENU 1: Ayam Geprek -->
                <div class="menu-card" id="card_ayam">
                    <label style="display:flex; justify-content:space-between; width:100%; cursor:pointer;">
                        <div>
                            <div class="menu-title">🍗 Ayam Geprek</div>
                            <div class="menu-price">Rp 15.000</div>
                        </div>
                        <input type="radio" name="menu_base" value="Ayam Geprek" data-id="ayam" style="transform: scale(1.5); accent-color: #2e7d32;" onchange="activateCard('ayam')">
                    </label>
                    <div id="options_ayam" class="options-container">
                        <div class="mod-group">
                            <span class="mod-label">Level Pedas (Wajib)</span>
                            <select class="mod-select">
                                <option value="Lv Normal">Normal</option>
                                <option value="Lv Pedas">Pedas</option>
                                <option value="Lv Ekstra">Ekstra Pedas</option>
                            </select>
                        </div>
                        <div class="mod-group">
                            <span class="mod-label">Tambahan (Opsional)</span>
                            <label><input type="checkbox" value="Nasi" data-harga="4000" onchange="hitungTotal()"> Nasi Putih (+Rp 4.000)</label>
                            <label><input type="checkbox" value="Es Teh" data-harga="3000" onchange="hitungTotal()"> Es Teh Manis (+Rp 3.000)</label>
                        </div>
                    </div>
                </div>

                <!-- MENU 2: Mie Goreng -->
                <div class="menu-card" id="card_mie">
                    <label style="display:flex; justify-content:space-between; width:100%; cursor:pointer;">
                        <div>
                            <div class="menu-title">🍜 Mie Goreng Spesial</div>
                            <div class="menu-price">Rp 12.000</div>
                        </div>
                        <input type="radio" name="menu_base" value="Mie Goreng" data-id="mie" style="transform: scale(1.5); accent-color: #2e7d32;" onchange="activateCard('mie')">
                    </label>
                    <div id="options_mie" class="options-container">
                        <div class="mod-group">
                            <span class="mod-label">Pilihan Telur</span>
                            <select class="mod-select">
                                <option value="Dadar">Telur Dadar</option>
                                <option value="Ceplok">Telur Ceplok</option>
                            </select>
                        </div>
                        <div class="mod-group">
                            <span class="mod-label">Ekstra Topping</span>
                            <label><input type="checkbox" value="Sosis" data-harga="3000" onchange="hitungTotal()"> Sosis Sapi (+Rp 3.000)</label>
                        </div>
                    </div>
                </div>
                
                <!-- MENU 3: Kopi Susu -->
                <div class="menu-card" id="card_kopi">
                    <label style="display:flex; justify-content:space-between; width:100%; cursor:pointer;">
                        <div>
                            <div class="menu-title">☕ Kopi Susu Aren</div>
                            <div class="menu-price">Rp 10.000</div>
                        </div>
                        <input type="radio" name="menu_base" value="Kopi Aren" data-id="kopi" style="transform: scale(1.5); accent-color: #2e7d32;" onchange="activateCard('kopi')">
                    </label>
                    <div id="options_kopi" class="options-container">
                        <div class="mod-group">
                            <span class="mod-label">Tingkat Manis</span>
                            <select class="mod-select">
                                <option value="Normal Sugar">Normal Sugar</option>
                                <option value="Less Sugar">Less Sugar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="prosesQRIS()" class="btn-order" id="btn-pay" disabled>Pilih Menu Dulu</button>
            </form>

            <script>
                const basePrices = { 'ayam': 15000, 'mie': 12000, 'kopi': 10000 };

                function activateCard(id) {
                    document.querySelectorAll('.options-container').forEach(el => el.classList.remove('show'));
                    document.querySelectorAll('.menu-card').forEach(el => el.style.borderColor = '#eee');

                    document.getElementById('options_' + id).classList.add('show');
                    document.getElementById('card_' + id).style.borderColor = '#4caf50';
                    
                    document.getElementById('btn-pay').disabled = false;
                    hitungTotal();
                }

                function hitungTotal() {
                    let selected = document.querySelector('input[name="menu_base"]:checked');
                    if(!selected) return;

                    let menuId = selected.dataset.id;
                    let total = basePrices[menuId];

                    let optionsDiv = document.getElementById('options_' + menuId);
                    if(optionsDiv) {
                        let checks = optionsDiv.querySelectorAll('input[type="checkbox"]:checked');
                        checks.forEach(c => { total += parseInt(c.dataset.harga); });
                    }

                    let rupiah = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(total);
                    document.getElementById('btn-pay').innerText = "Bayar " + rupiah;
                }

                function prosesQRIS() {
                    let selected = document.querySelector('input[name="menu_base"]:checked');
                    let menuName = selected.value;
                    let menuId = selected.dataset.id;
                    let finalMenu = menuName;

                    let optionsDiv = document.getElementById('options_' + menuId);
                    if(optionsDiv) {
                        let mods = [];
                        let selects = optionsDiv.querySelectorAll('select');
                        selects.forEach(s => mods.push(s.value));

                        let checks = optionsDiv.querySelectorAll('input[type="checkbox"]:checked');
                        checks.forEach(c => mods.push("+" + c.value));

                        if(mods.length > 0) {
                            finalMenu += " (" + mods.join(", ") + ")";
                        }
                    }

                    finalMenu = finalMenu.substring(0, 50);
                    document.getElementById('input_menu_final').value = finalMenu;

                    document.getElementById('qris-modal').style.display = 'flex';
                    setTimeout(function() {
                        document.getElementById('form-pesanan').submit();
                    }, 2500);
                }
            </script>

        <?php else: 
            // HALAMAN TRACKER STATUS & RATING
            $q = mysqli_query($conn, "SELECT status_pesanan FROM antrean_pesanan WHERE nomor_antrean = '$antrean_aktif' ORDER BY id DESC LIMIT 1");
            $data = mysqli_fetch_assoc($q);
            $status = $data ? $data['status_pesanan'] : 'Waiting';
        ?>
            <div class="tracker-box">
                <div style="font-size: 13px; color:#a0aec0; font-weight: 700; letter-spacing: 1px;">KODE PENGAMBILAN</div>
                <div style="color:#1a202c; font-size: 46px; font-weight: 800; margin: 10px 0; letter-spacing: 2px;"><?php echo htmlspecialchars($antrean_aktif); ?></div>
                <div class="steps">
                    <div class="step <?php echo ($status=='Waiting'||$status=='Cooking'||$status=='Ready')?'active':''; ?>"><span class="icon">📥</span> Diterima</div>
                    <div class="step <?php echo ($status=='Cooking'||$status=='Ready')?'active-cooking':''; ?>"><span class="icon">🍳</span> Dimasak</div>
                    <div class="step <?php echo ($status=='Ready')?'active':''; ?>"><span class="icon">✅</span> Siap Ambil</div>
                </div>
                
                <?php if($status=='Ready'): ?>
                    <!-- Form Rating -->
                    <div id="rating-area" style="margin-top:35px; background:#fafcfa; padding:20px; border-radius:20px; border:1px solid #e0e0e0; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                        <p style="font-weight:700; color:#2d3748; margin-bottom:15px;">Berikan rating CanteenGO!</p>
                        <div class="rating" id="star-container" style="display: flex; flex-direction: row-reverse; justify-content: center; gap: 5px;">
                            <span class="star" onclick="setRating(5)">⭐</span>
                            <span class="star" onclick="setRating(4)">⭐</span>
                            <span class="star" onclick="setRating(3)">⭐</span>
                            <span class="star" onclick="setRating(2)">⭐</span>
                            <span class="star" onclick="setRating(1)">⭐</span>
                        </div>
                        <button id="btn-submit-rating" onclick="submitRating()" style="margin-top:20px; background:#f57c00; color:white; border:none; padding:12px 25px; border-radius:20px; font-weight:bold; cursor:pointer; display:none; width:100%; box-shadow: 0 4px 15px rgba(245,124,0,0.3);">Kirim Rating</button>
                    </div>

                    <!-- Ucapan Terima Kasih -->
                    <div id="thank-you-area" style="display:none; margin-top:35px; padding:25px 20px; background:#e8f5e9; border-radius:20px; border:1px solid #c8e6c9;">
                        <p style="font-size:45px; margin:0;">🎉</p>
                        <p style="font-weight:800; color:#2e7d32; margin-top:10px; font-size:16px;">Terima kasih atas feedbacknya!</p>
                        <p style="font-size:13px; color:#4caf50;">Rating kamu membantu kami berkembang.</p>
                        <a href="mahasiswa.php" style="display:inline-block; margin-top:15px; color:white; background:#2e7d32; padding: 10px 20px; border-radius: 20px; text-decoration:none; font-weight:bold;">Pesan Lagi</a>
                    </div>

                    <script>
                        function setRating(val) {
                            let stars = document.querySelectorAll('.star');
                            stars.forEach(el => el.style.filter = "grayscale(100%)");
                            stars.forEach((star, index) => {
                                let starValue = 5 - index; 
                                if(starValue <= val) {
                                    star.style.filter = "grayscale(0%)"; 
                                }
                            });
                            document.getElementById('btn-submit-rating').style.display = 'block';
                        }
                        
                        function submitRating() {
                            document.getElementById('rating-area').style.display = 'none';
                            document.getElementById('thank-you-area').style.display = 'block';
                        }
                    </script>
                <?php else: ?>
                    <div style="font-size:12px; color:#a0aec0; margin-top:35px; background: #f7fafc; padding: 10px; border-radius: 8px;">*Refresh halaman ini untuk update status dari dapur.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
