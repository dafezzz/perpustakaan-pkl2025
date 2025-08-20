<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Persetujuan Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333333;
        }

        p {
            color: #555555;
            line-height: 1.6;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3f51b5;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

    </style>
</head>
<body>
    <div class="email-container">
        <h2>Halo, {{ $peminjaman->nama_lengkap }}</h2>

        <p>
            Kami informasikan bahwa <strong>pengajuan peminjaman buku</strong> dengan judul:
        </p>

        <p style="font-size: 18px; font-weight: bold; color: #3f51b5;">
            {{ $peminjaman->book->judul }}
        </p>

        <p>
            Telah <strong>disetujui</strong>. Silakan datang ke perpustakaan untuk mengambil buku sesuai dengan jadwal yang telah ditentukan.
        </p>

        <p>
            Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi petugas perpustakaan.
        </p>

        <p class="footer">
            Terima kasih,<br>
            <strong>Staff Perpustakaan</strong>
        </p>
    </div>
</body>
</html>
