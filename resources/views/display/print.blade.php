<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Nomor Antrian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Opsional: Ikon cetak -->
    <script src="https://kit.fontawesome.com/your-font-awesome-key.js" crossorigin="anonymous"></script>
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            text-align: center;
            background: #f0f0f0;
            padding: 30px 15px;
            color: #000; /* Hitam pekat */
        }

        .ticket {
            max-width: 320px;
            margin: 0 auto;
            padding: 15px 10px;
            background: #fff;
            border: 1px solid #000; /* Garis tepi hitam tebal */
            border-radius: 8px;
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sekolah {
            font-size: 10px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
            
        }

        .nomor {
            font-size: 56px;
            font-weight: 900; /* Bold maksimal */
            color: #000;
            margin: 20px 0;
            line-height: 1.1;
        }

        .info {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
        }

        .waktu {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-bottom: 15px;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #000;
            font-weight: bold;
            line-height: 1.5;
        }

        .divider {
            margin: 14px auto;
            width: 90%;
            border-top: 3px solid #000; /* Garis tebal, hitam pekat */
            height: 1px;
        }

        /* Tombol hanya muncul di layar */
        .no-print {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-print {
            background-color: #000;
            color: white;
        }

        .btn-print:hover {
            background-color: #222;
        }

        .btn-back {
            background-color: #555;
            color: white;
        }

        .btn-back:hover {
            background-color: #333;
        }

        /* Aturan cetak: pastikan semuanya hitam dan tebal */
        @media print {
            body {
                background: white;
                padding: 0;
                color: #000;
            }

            .ticket {
                max-width: 58mm;
                padding: 10px 5px;
                margin: 0 auto;
                border-radius: 0;
                font-size: 12px;
            }

            .header {
                font-size: 12px;
                font-weight: bold;
            }

            .nomor {
                font-size: 40px;
                font-weight: 900;
                margin: 12px 0;
            }

            .info, .waktu {
                font-size: 9px;
                font-weight: bold;
            }

            .footer {
                font-size: 8px;
                font-weight: bold;
            }

            .suport{
                font-size: 8px;
                font-weight: bold;
            }

            .divider {
                border-top: 3px solid #000;
                margin: 12px auto;
            }

            .no-print {
                display: none !important;
            }

            /* Paksa cetak background (opsional, tergantung printer) */
            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header -->
        <div class="header">ANTRIAN PPDB</div>
        <div class="sekolah">SMK MUHAMMADIYAH 1 BANTUL</div>
        <div class="divider"></div>

        <!-- Nomor Antrian -->
        <div class="nomor">{{ $antrian->nomor_antrian }}</div>


        <!-- Loket -->
        <div class="info">Loket: {{ $antrian->unit->unit }}</div>

        <!-- Waktu -->
        <div class="waktu">Waktu: <br>{{ $antrian->created_at->format('d-m-Y H:i') }}</div>
       
        <!-- Footer -->
        <div class="footer">
            HARAP TUNGGU DIPANGGIL<br>
            TERIMA KASIH
        </div>
         <div class="divider"></div>
        <div class="suport">Aplikasi Ini Karya Siswa RPL <br>SMK Muhammadiyah 1 Bantul</div>
    </div>

    <!-- Tombol (Hanya di layar) -->
    <div class="no-print">
        <button onclick="cetakDanKembali()" class="btn btn-print">
            <i class="fas fa-print"></i> Cetak Lagi
        </button>
        <button onclick="window.location.href='{{ route('antrian.ambil') }}'" class="btn btn-back">
            Kembali ke Antrian
        </button>
    </div>

    <script>
        function cetakDanKembali() {
            window.print();
            // Redirect setelah print dialog ditutup
            setTimeout(function() {
                window.location.href = '{{ route('antrian.ambil') }}';
            }, 500); // Delay kecil untuk memastikan print selesai
        }

        // Otomatis cetak + redirect saat halaman pertama kali dimuat
        window.addEventListener('load', function() {
            window.print();
            setTimeout(function() {
                window.location.href = '{{ route('antrian.ambil') }}';
            }, 500);
        });
    </script>
</body>
</html>
