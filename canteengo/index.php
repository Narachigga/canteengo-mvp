<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CanteenGO - ITPLN</title>
    <!-- Import font Poppins dari Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { 
            background: linear-gradient(135deg, #f6fbf7 0%, #edf5ee 100%); 
            color: #333; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        /* Ornamen Cahaya Background (Biar gak polos) */
        .bg-shape1 { position: absolute; top: -100px; left: -100px; width: 450px; height: 450px; background: rgba(46, 125, 50, 0.08); border-radius: 50%; filter: blur(60px); z-index: -1; }
        .bg-shape2 { position: absolute; bottom: -150px; right: -100px; width: 550px; height: 550px; background: rgba(245, 124, 0, 0.08); border-radius: 50%; filter: blur(80px); z-index: -1; }

        /* Navigasi */
        nav { padding: 25px 50px; display: flex; justify-content: center; align-items: center; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03); border-bottom: 1px solid rgba(255,255,255,0.5); }
        .logo { font-size: 30px; font-weight: 800; color: #2e7d32; letter-spacing: -0.5px; }
        .logo span { color: #f57c00; }

        /* Kontainer Utama Bergaya Glassmorphism */
        .hero { max-width: 850px; margin: 80px auto; padding: 60px 40px; background: rgba(255, 255, 255, 0.6); border: 1px solid rgba(255, 255, 255, 0.9); backdrop-filter: blur(15px); border-radius: 36px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); text-align: center; }
        
        .badge { display: inline-block; padding: 8px 18px; background: #e8f5e9; color: #2e7d32; border-radius: 30px; font-size: 14px; font-weight: 600; margin-bottom: 25px; border: 1px solid #c8e6c9; }
        
        .hero h1 { font-size: 46px; margin-bottom: 20px; line-height: 1.25; font-weight: 800; background: linear-gradient(90deg, #1b5e20, #4caf50); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 18px; color: #666; margin-bottom: 45px; line-height: 1.7; font-weight: 400; padding: 0 20px; }
        
        /* Tombol Modern */
        .btn-container { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }
        .btn { padding: 18px 35px; border-radius: 50px; font-weight: 600; text-decoration: none; color: white; font-size: 16px; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); display: flex; align-items: center; justify-content: center; gap: 12px; border: none; }
        
        .btn-mhs { background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%); box-shadow: 0 10px 25px rgba(46, 125, 50, 0.3); }
        .btn-pdg { background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); box-shadow: 0 10px 25px rgba(245, 124, 0, 0.3); }
        
        .btn:hover { transform: translateY(-4px); }
        .btn-mhs:hover { box-shadow: 0 15px 30px rgba(46, 125, 50, 0.45); }
        .btn-pdg:hover { box-shadow: 0 15px 30px rgba(245, 124, 0, 0.45); }
    </style>
</head>
<body>
    <!-- Ornamen Background -->
    <div class="bg-shape1"></div>
    <div class="bg-shape2"></div>

    <nav>
        <div class="logo">Canteen<span>GO</span></div>
    </nav>
    
    <section class="hero">
        <div class="badge">✨ Prototipe Sistem ITPLN</div>
        <h1>Pesan dari Kelas,<br>Sampe Kantin Tinggal Lahap. 🚀</h1>
        <p>Ekosistem pemesanan makanan otomatis untuk memangkas waktu antre mahasiswa. Silakan uji coba simulasi riil kelompok SAMTECH dengan membuka dua layar di bawah ini.</p>
        
        <div class="btn-container">
            <a href="mahasiswa.php" target="_blank" class="btn btn-mhs">
                <span style="font-size: 22px;">📱</span> Buka Aplikasi Mahasiswa
            </a>
            <a href="pedagang.php" target="_blank" class="btn btn-pdg">
                <span style="font-size: 22px;">👨‍🍳</span> Monitor Dapur Kantin
            </a>
        </div>
    </section>
</body>
</html>
