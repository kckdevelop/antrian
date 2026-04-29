<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Nomor Antrian - SMK Musaba</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Google untuk tampilan modern -->
    <link href="https://googleapis.com" rel="stylesheet">
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cloudflare.com">
    
    <style>
        :root {
            --primary-color: #0056b3;
            --dark-color: #333;
            --bg-color: #e9ecef;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-color);
            padding: 40px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .ticket {
            width: 350px;
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
            border: 1px solid #ddd;
        }

        /* Aksen dekoratif potongan tiket */
        .ticket::before, .ticket::after {
            content: "";
            position: absolute;
            height: 20px;
            width: 20px;
            background: var(--bg-color);
            border-radius: 50%;
            top: 75%;
        }
        .ticket::before { left: -10px; }
        .ticket::after { right: -10px; }

        .header-title {
            font-weight: 900;
            font-size: 18px;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .sub-header {
            font-size: 12px;
            color: #777;
            margin-bottom: 15px;
        }

        .divider {
            border-top: 2px dashed #ccc;
            margin: 15px 0;
        }

        .nomor-label {
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }

        .nomor {
            font-family: 'Share Tech Mono', monospace;
            font-size: 72px;
            font-weight: bold;
            color: var(--dark-color);
            margin: 10px 0;
        }

        .unit-box {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 5px solid var(--primary-color);
        }

        .unit-name {
            font-weight: 700;
            font-size: 18px;
            color: var(--dark-color);
        }

        .waktu {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .footer-msg {
            font-size: 13px;
            font-weight: 600;
            margin: 20px 0 10px 0;
            line-height: 1.4;
        }

        /* Bagian Kredit Siswa */
        .credit {
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-style: italic;
        }

        .no-print {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-print { background: var(--primary-color); color: white; }
        .btn-back { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }

        @media print {
            body { background: white; padding: 0; }
            .ticket { 
                width: 100%; 
                box-shadow: none; 
                border: 1px solid #000;
                border-radius: 0;
            }
            .no-print { display: none !important; }
            .ticket::before, .ticket::after { display: none; }
        }
    </style>
</head>
<body>

    <div class="ticket" id="printableTicket">
        <div class="header-title">Antrian Sistem</div>
        <div class="sub-header">SMK Muhammadiyah 1 Bantul</div>
        
        <div class="divider"></div>
        
        <div class="nomor-label">NOMOR ANTRIAN</div>
        <div class="nomor">{{ $antrian->nomor_antrian }}</div>
        
        <div class="unit-box">
            <div class="nomor-label">LOKET / UNIT</div>
            <div class="unit-name">{{ $antrian->unit->unit }}</div>
        </div>

        <div class="waktu">
            <i class="far fa-calendar-alt"></i> {{ $antrian->created_at->format('d M Y') }} &nbsp;
            <i class="far fa-clock"></i> {{ $antrian->created_at->format('H:i') }}
        </div>

        <div class="footer-msg">
            Silahkan tunggu nomor Anda dipanggil.<br>Terima Kasih atas Kunjungannya.
        </div>

        <div class="credit">
            Developed by:<br>
            <strong>Siswa-Siswi RPL SMK Muhammadiyah 1 Bantul</strong>
        </div>
    </div>

    <div class="no-print">
        <button onclick="cetakLagi()" class="btn btn-print">
            <i class="fas fa-print"></i> Cetak Lagi
        </button>
        <button onclick="window.location.href='{{ route('antrian.ambil') }}'" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </button>
    </div>

    <script>
        function cetakLagi() {
            window.print();
        }

        // Otomatis cetak dan kembali saat halaman dimuat
        window.addEventListener('load', function() {
            window.print();
            setTimeout(function() {
                window.location.href = '{{ route('antrian.ambil') }}';
            }, 1000); 
        });
    </script>
</body>
</html>
