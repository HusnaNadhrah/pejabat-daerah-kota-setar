<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM booking_requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 2px solid #333;
        }
        .booking-details {
            margin-bottom: 30px;
        }
        .detail-row {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .label {
            font-weight: bold;
            min-width: 200px;
            display: inline-block;
        }
        .section-title {
            font-weight: bold;
            font-size: 1.1em;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #666;
        }
        .checkbox-group {
            margin-left: 200px;
        }
        .checkbox-label {
            margin-right: 20px;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }
        .status-pending {
            background-color: #ffd700;
            color: black;
        }
        .status-approved {
            background-color: #28a745;
        }
        .status-rejected {
            background-color: #dc3545;
        }
        @media print {
            body {
                padding: 0;
            }
            button {
                display: none;
            }
            .header {
                border-bottom: 2px solid #000;
            }
            .detail-row {
                border-bottom: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>BUTIRAN TEMPAHAN</h2>
        <h3>PEJABAT DAERAH</h3>
    </div>

    <div class="booking-details">
        <!-- Basic Details -->
        <div class="section-title">Maklumat Mesyuarat</div>
        <div class="detail-row">
            <span class="label">Tajuk Mesyuarat:</span>
            <span><?php echo htmlspecialchars($booking['tajuk_mesyuarat']); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Pengerusi:</span>
            <span><?php echo htmlspecialchars($booking['pengerusi']); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Tarikh:</span>
            <span><?php echo date('d/m/Y', strtotime($booking['tarikh'])); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Masa Mula:</span>
            <span><?php echo date('h:i A', strtotime($booking['masa_mula'])); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Masa Akhir:</span>
            <span><?php echo date('h:i A', strtotime($booking['masa_akhir'])); ?></span>
        </div>

        <!-- Location Details -->
        <div class="section-title">Tempat Mesyuarat</div>
        <div class="detail-row">
            <span class="label">Bilik:</span>
            <div class="checkbox-group">
                <span class="checkbox-label">
                    <?php echo $booking['bilik_gerakan'] ? '☑' : '☐'; ?> Bilik Gerakan
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['bilik_pegawai'] ? '☑' : '☐'; ?> Bilik Pegawai
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['bilik_pkob'] ? '☑' : '☐'; ?> Bilik PKOB
                </span>
            </div>
        </div>
        <div class="detail-row">
            <span class="label">Bahagian:</span>
            <div class="checkbox-group">
                <span class="checkbox-label">
                    <?php echo $booking['pentadbiran'] ? '☑' : '☐'; ?> Pentadbiran
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['pembangunan'] ? '☑' : '☐'; ?> Pembangunan
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['agensi_luar'] ? '☑' : '☐'; ?> Agensi Luar
                </span>
            </div>
        </div>

        <!-- ICT Requirements -->
        <div class="section-title">Keperluan ICT</div>
        <div class="detail-row">
            <span class="label">Peralatan:</span>
            <div class="checkbox-group">
                <span class="checkbox-label">
                    <?php echo $booking['perakam_suara'] ? '☑' : '☐'; ?> Perakam Suara
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['webcam'] ? '☑' : '☐'; ?> Webcam
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['laptop'] ? '☑' : '☐'; ?> Laptop
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['projector'] ? '☑' : '☐'; ?> Projector
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['pointer'] ? '☑' : '☐'; ?> Pointer
                </span>
            </div>
        </div>

        <!-- Attendance Details -->
        <div class="section-title">Maklumat Kehadiran</div>
        <div class="detail-row">
            <span class="label">Jumlah Ahli:</span>
            <span><?php echo htmlspecialchars($booking['jumlah_ahli']); ?></span>
        </div>

        <!-- Food Details -->
        <div class="section-title">Maklumat Makanan</div>
        <div class="detail-row">
            <span class="label">Jadual Makan:</span>
            <div class="checkbox-group">
                <span class="checkbox-label">
                    <?php echo $booking['makan_pagi'] ? '☑' : '☐'; ?> Pagi
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['makan_tengahari'] ? '☑' : '☐'; ?> Tengahari
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['makan_petang'] ? '☑' : '☐'; ?> Petang
                </span>
            </div>
        </div>
        <div class="detail-row">
            <span class="label">Bilik Makan:</span>
            <div class="checkbox-group">
                <span class="checkbox-label">
                    <?php echo $booking['bilik_makan'] ? '☑' : '☐'; ?> Bilik Makan
                </span>
                <span class="checkbox-label">
                    <?php echo $booking['bilik_makan_vip'] ? '☑' : '☐'; ?> Bilik Makan VIP
                </span>
            </div>
        </div>
        <div class="detail-row">
            <span class="label">Menu:</span>
            <span><?php echo htmlspecialchars($booking['menu']); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Kaedah Hidangan:</span>
            <span><?php echo htmlspecialchars($booking['kaedah_hidangan']); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Bilangan:</span>
            <span><?php echo htmlspecialchars($booking['bilangan']); ?></span>
        </div>

        <!-- Applicant Details -->
        <div class="section-title">Maklumat Pemohon</div>
        <div class="detail-row">
            <span class="label">Nama Pemohon:</span>
            <span><?php echo htmlspecialchars($booking['nama_pemohon']); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Jabatan/Unit:</span>
            <span><?php echo htmlspecialchars($booking['jabatan_unit']); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">No. Telefon:</span>
            <span><?php echo htmlspecialchars($booking['no_telefon']); ?></span>
        </div>
        <div class="detail-row">
            <span class="label">Tarikh Mohon:</span>
            <span><?php echo date('d/m/Y', strtotime($booking['tarikh_submit'])); ?></span>
        </div>

        <!-- Status -->
        <div class="section-title">Status Tempahan</div>
        <div class="detail-row">
            <span class="label">Status:</span>
            <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                <?php echo htmlspecialchars($booking['status']); ?>
            </span>
        </div>

        <!-- Additional Documents -->
        <?php if(!empty($booking['surat_jemputan']) || !empty($booking['senarai_jemputan'])): ?>
        <div class="section-title">Dokumen Tambahan</div>
        <?php if(!empty($booking['surat_jemputan'])): ?>
        <div class="detail-row">
            <span class="label">Surat Jemputan:</span>
            <span><?php echo htmlspecialchars($booking['surat_jemputan']); ?></span>
        </div>
        <?php endif; ?>
        <?php if(!empty($booking['senarai_jemputan'])): ?>
        <div class="detail-row">
            <span class="label">Senarai Jemputan:</span>
            <span><?php echo htmlspecialchars($booking['senarai_jemputan']); ?></span>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>