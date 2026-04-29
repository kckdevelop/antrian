<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Nomor Antrian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Roboto+Mono:wght@500&display=swap" rel="stylesheet">
    
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        /* Desain Tiket (Tampilan Layar) */
        .ticket {
            width: 320px;
            background: #fff;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            border: 1px solid #e0e0e0;
        }

        /* Efek Lubang Tiket di Sisi Kiri & Kanan (Opsional estetika) */
        .ticket::before, .ticket::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: #f4f6f9;
            border-radius: 50%;
            top: 80px;
            z-index: 1;
        }
        .ticket::before { left: -10px; }
        .ticket::after { right: -10px; }

        /* Header Tiket */
        .ticket-header {
            background: linear-gradient(135deg, #2c3e50, #4a6fa5);
            color: #fff;
            padding: 20px 15px;
            text-align: center;
        }
        
        .ticket-header h1 {
            font-size: 18px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .ticket-header .subtitle {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 4px;
        }

        /* Badan Tiket */
        .ticket-body {
            padding: 25px 20px;
            text-align: center;
            background: #fff;
            border-top: 2px dashed #e0e0e0;
            margin-top: -1px; /* Hack untuk menyembunyikan garis di belakang lubang */
        }

        .queue-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .queue-number {
            font-family: 'Roboto Mono', monospace;
            font-size: 64px;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 2px 2px 0px rgba(0,0,0,0.05);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            font-size: 13px;
            border-left: 4px solid #4a6fa5;
        }

        .info-row div {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #555;
        }

        .info-row i {
            color: #4a6fa5;
        }

        /* Footer Tiket */
        .ticket-footer {
            background-color: #fafafa;
            padding: 15px;
            text-align: center;
            border-top: 2px dashed #e0e0e0;
        }

        .instruction {
            font-size: 12px;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Kredit Pembuat (Info Baru) */
        .credit-box {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 9px;
            color: #95a5a6;
            line-height: 1.4;
        }
        
        .credit-box strong {
            display: block;
            margin-bottom: 2px;
            color: #7f8c8d;
        }

        /* Tombol Aksi */
        .actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-print {
            background-color: #3498db;
            color: white;
        }
        
        .btn-back {
            background-color: #fff;
            color: #333;
            border: 2px solid #ddd;
        }

        /* Media Print (Pengaturan Saat Cetak) */
        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
            }

            .actions {
                display: none !important;
            }

            .ticket {
                width: 80mm; /* Ukuran standar struk thermal */
                min-height: auto;
                border: none;
                border-radius: 0;
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            /* Hilangkan efek lubang saat cetak */
            .ticket::before, .ticket::after {
                display: none;
            }

            .ticket-header {
                background: #fff; /* Putih untuk hemat tinta */
                color: #000;
                border-bottom: 1px dashed #000;
                padding: 10px 0;
            }

            .ticket-body {
                padding: 15px 0;
                border: none;
            }

            .queue-number {
                color: #000;
            }

            .info-row {
                border: 1px solid #000;
                background: #fff;
                border-radius: 0;
                margin: 5px 10px;
                text-align: left;
                justify-content: flex-start;
                gap: 10px;
            }
            
            .info-row div {
                color: #000;
            }

            .ticket-footer {
                border-top: 1px dashed #000;
                background: #fff;
                padding: 10px 0;
            }

            .instruction {
                color: #000;
                font-weight: bold;
            }

            .credit-box {
                color: #000;
                border-top: 1px solid #ddd;
            }
            
            /* Pastikan warna hitam pekat */
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color: #000 !important; /* Force hitam putih untuk printer thermal */
            }
        }
    </style>
</head>
<body>

    <div class="ticket">
        <!-- Header -->
        <div class="ticket-header">
            <h1>Nomor Antrian</h1>
            <div class="subtitle">Sistem Antrian Musaba</div>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            <div class="queue-label">Nomor Anda</div>
            <div class="queue-number">{{ $antrian->nomor_antrian }}</div>

            <div class="info-row">
                <div>
                    <i class="fas fa-user-tie"></i>
                    <span>Loket:</span>
                </div>
                <div>{{ $antrian->unit->unit }}</div>
            </div>

            <div class="info-row">
                <div>
                    <i class="fas fa-calendar-alt"></i>
                    <span>Tanggal:</span>
                </div>
                <div>{{ $antrian->created_at->format('d-m-Y') }}</div>
            </div>

            <div class="info-row">
                <div>
                    <i class="fas fa-clock"></i>
                    <span>Waktu:</span>
                </div>
                <div>{{ $antrian->created_at->format('H:i') }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="ticket-footer">
            <div class="instruction">
                <i class="fas fa-exclamation-circle"></i> Harap Tunggu Dipanggil
            </div>
            
            <!-- Informasi Pembuat -->
            <div class="credit-box">
                <strong>Developed by:</strong>
                Siswa Siswi Jurusan RPL<br>
                SMK Muhammadiyah 1 Bantul
            </div>
        </div>
    </div>

    <!-- Tombol Aksi (Hanya tampil di layar) -->
    <div class="actions no-print">
        <button onclick="cetakDanKembali()" class="btn btn-print">
            <i class="fas fa-print"></i> Cetak Tiket
        </button>
        <button onclick="window.location.href='{{ route('antrian.ambil') }}'" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Ambil Antrian Baru
        </button>
    </div>

    <script>
        function cetakDanKembali() {
            window.print();
            setTimeout(function() {
                window.location.href = '{{ route('antrian.ambil') }}';
            }, 1000);
        }

        // Opsional: Auto print saat halaman dibuka (uncomment jika diperlukan)
        /*
        window.addEventListener('load', function() {
            window.print();
            setTimeout(function() {
                window.location.href = '{{ route('antrian.ambil') }}';
            }, 1000);
        });
        */
    </script>
</body>
</html>
