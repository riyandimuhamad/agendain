<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sertifikat</title>
    <style>
        /* CSS bisa ditambahkan di sini untuk desain */
        @page { margin: 0px; } /* Menghilangkan margin halaman default */
        body { font-family: 'Helvetica', sans-serif; text-align: center; margin: 0; padding: 0; }
        .container {
            /* Contoh border, ganti dengan background image jika perlu */
            border: 15px solid #4CAF50;
            width: 90%;
            height: 90%; /* Tinggi relatif terhadap halaman */
            margin: 5% auto; /* Tengah secara vertikal & horizontal */
            padding: 30px;
            box-sizing: border-box; /* Agar padding tidak menambah ukuran */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 { font-size: 45px; color: #388E3C; margin-bottom: 10px; }
        h2 { font-size: 28px; margin-top: 5px; margin-bottom: 15px; }
        p { font-size: 18px; margin-bottom: 10px; }
        .nama-peserta { font-size: 32px; font-weight: bold; color: #1B5E20; margin-top: 10px; margin-bottom: 20px;}
        .nama-event { font-size: 24px; font-weight: bold; margin-top: 10px; margin-bottom: 20px;}
    </style>
</head>
<body>
    <div class="container">
        <p style="font-size: 22px; font-weight: bold;">SERTIFIKAT PARTISIPASI</p>
        <h1>CERTIFICATE</h1>
        <p>Dengan bangga diberikan kepada:</p>
        <div class="nama-peserta">{{NAMA_PESERTA}}</div>
        <p>Atas partisipasinya sebagai peserta dalam kegiatan:</p>
        <div class="nama-event">{{NAMA_EVENT}}</div>
        <p>Diselenggarakan pada tanggal: {{TANGGAL_EVENT}}</p>

        </div>
</body>
</html>