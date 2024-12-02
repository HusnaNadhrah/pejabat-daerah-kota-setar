<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize variables to store error messages
$error_message = '';
$success_message = '';

try {
    // Database connection using PDO
    $host = 'localhost';
    $dbname = 'reservation';
    $username = '';
    $password = 'root';
    
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Debug: Print POST data
        // echo "<pre>POST Data: "; print_r($_POST); echo "</pre>";

        // Validate required fields
        $required_fields = [
            'tajuk_mesyuarat',
            'pengerusi',
            'tarikh',
            'masa_mula',
            'masa_akhir',
            'masa',
            'jumlah_ahli',
            'menu',
            'hidangan',
            'bilangan',
            'nama_pemohon',
            'jabatan_unit',
            'no_telefon',
            'tarikh_submit'
        ];

        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $missing_fields[] = $field;
            }
        }

        if (!empty($missing_fields)) {
            throw new Exception("Missing required fields: " . implode(", ", $missing_fields));
        }

        // Prepare SQL statement
        $sql = "INSERT INTO booking_requests (
            tajuk_mesyuarat, 
            pengerusi, 
            tarikh, 
            masa_mula, 
            masa_akhir,
            bilik_gerakan,
            bilik_pegawai,
            bilik_pkob,
            pentadbiran,
            pembangunan,
            agensi_luar,
            masa,
            perakam_suara,
            webcam,
            laptop,
            projector,
            pointer,
            jumlah_ahli,
            makan_pagi,
            makan_tengahari,
            makan_petang,
            bilik_makan,
            bilik_makan_vip,
            menu,
            kaedah_hidangan,
            bilangan,
            nama_pemohon,
            jabatan_unit,
            no_telefon,
            tarikh_submit,
            surat_jemputan,
            senarai_jemputan,
            status
        ) VALUES (
            :tajuk_mesyuarat, 
            :pengerusi, 
            :tarikh, 
            :masa_mula, 
            :masa_akhir,
            :bilik_gerakan,
            :bilik_pegawai,
            :bilik_pkob,
            :pentadbiran,
            :pembangunan,
            :agensi_luar,
            :masa,
            :perakam_suara,
            :webcam,
            :laptop,
            :projector,
            :pointer,
            :jumlah_ahli,
            :makan_pagi,
            :makan_tengahari,
            :makan_petang,
            :bilik_makan,
            :bilik_makan_vip,
            :menu,
            :kaedah_hidangan,
            :bilangan,
            :nama_pemohon,
            :jabatan_unit,
            :no_telefon,
            :tarikh_submit,
            :surat_jemputan,
            :senarai_jemputan,
            'PENDING'
        )";

        $stmt = $pdo->prepare($sql);

        // Create parameters array
        $params = [
            'tajuk_mesyuarat' => $_POST['tajuk_mesyuarat'],
            'pengerusi' => $_POST['pengerusi'],
            'tarikh' => $_POST['tarikh'],
            'masa_mula' => $_POST['masa_mula'],
            'masa_akhir' => $_POST['masa_akhir'],
            'bilik_gerakan' => isset($_POST['bilik_gerakan']) ? 1 : 0,
            'bilik_pegawai' => isset($_POST['bilik_pegawai']) ? 1 : 0,
            'bilik_pkob' => isset($_POST['bilik_PKOB']) ? 1 : 0,
            'pentadbiran' => isset($_POST['Pentadbiran']) ? 1 : 0,
            'pembangunan' => isset($_POST['Pembangunan']) ? 1 : 0,
            'agensi_luar' => isset($_POST['Agensi Luar']) ? 1 : 0,
            'masa' => $_POST['masa'],
            'perakam_suara' => isset($_POST['perakam_suara']) ? 1 : 0,
            'webcam' => isset($_POST['webcam']) ? 1 : 0,
            'laptop' => isset($_POST['laptop']) ? 1 : 0,
            'projector' => isset($_POST['projector']) ? 1 : 0,
            'pointer' => isset($_POST['pointer']) ? 1 : 0,
            'jumlah_ahli' => $_POST['jumlah_ahli'],
            'makan_pagi' => isset($_POST['pagi']) ? 1 : 0,
            'makan_tengahari' => isset($_POST['tengahari']) ? 1 : 0,
            'makan_petang' => isset($_POST['petang']) ? 1 : 0,
            'bilik_makan' => isset($_POST['bilik_makan']) ? 1 : 0,
            'bilik_makan_vip' => isset($_POST['bilik_makan_vip']) ? 1 : 0,
            'menu' => $_POST['menu'],
            'kaedah_hidangan' => $_POST['hidangan'],
            'bilangan' => $_POST['bilangan'],
            'nama_pemohon' => $_POST['nama_pemohon'],
            'jabatan_unit' => $_POST['jabatan_unit'],
            'no_telefon' => $_POST['no_telefon'],
            'tarikh_submit' => $_POST['tarikh_submit'],
            'surat_jemputan' => $_POST['surat_jemputan'] ?? '',
            'senarai_jemputan' => $_POST['senarai_jemputan'] ?? ''
        ];

        // Debug: Print parameters
        // echo "<pre>Parameters: "; print_r($params); echo "</pre>";

        if ($stmt->execute($params)) {
            $success_message = "Permohonan berjaya dihantar!";
            echo "<script>
                alert('$success_message');
                window.location.href = 'dashboard.php';
            </script>";
        } else {
            throw new Exception("Failed to execute statement");
        }
    }
} catch (PDOException $e) {
    $error_message = "Database Error: " . $e->getMessage();
    error_log($error_message);
    echo "<script>alert('$error_message');</script>";
} catch (Exception $e) {
    $error_message = "Error: " . $e->getMessage();
    error_log($error_message);
    echo "<script>alert('$error_message');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Permohonan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom:9 px;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="text"], 
        input[type="date"], 
        input[type="time"], 
        input[type="number"], 
        select {
            width: 95%;
            padding: 5px;
            margin: 5px 0;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="checkbox"], 
        input[type="radio"] {
            margin-right: 5px;
        }

        .section-title {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 3px;
            text-align: center;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 9px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            display: block;
            width: 100%;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            margin: 10px 0;
        }
        .success-message {
            color: green;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
    <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>    
    <h1>BORANG PERMOHONAN (MESYUARAT/ PERBINCANGAN/ BENGKEL/ SEMINAR/ TAKLIMAT)</h1>
        <h1>DAN PERMOHONAN PESANAN MAKANAN</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <table>
            <tr>
                <td>Tajuk Mesyuarat:</td>
                <td colspan="3"><input type="text" id="tajuk_mesyuarat" name="tajuk_mesyuarat" required></td>
            </tr>
            <tr>
                <td>Pengerusi:</td>
                <td colspan="3"><input type="text" id="pengerusi" name="pengerusi" required></td>
            </tr>
            <tr>
                <td>Tarikh/Hari:</td>
                <td><input type="date" id="tarikh" name="tarikh" required></td>
                <td>Masa Mula:</td>
                <td><input type="time" id="masa_mula" name="masa_mula" required></td>
            </tr>
            <tr>
                <td>Masa Akhir:</td>
                <td colspan="3"><input type="time" id="masa_akhir" name="masa_akhir" required></td>
            </tr>
            <tr>
                <td>Tempat Mesyuarat:</td>
                <td><input type="checkbox" id="bilik_gerakan" name="bilik_gerakan"> Bilik Gerakan</td>
                <td><input type="checkbox" id="bilik_pegawai" name="bilik_pegawai"> Bilik Pegawai Daerah</td>
                <td><input type="checkbox" id="bilik PKOB" name="bilik_PKOB"> Bilik PKOB</td>
                <td></td>
            </tr>
            <tr>
                <td>Tempat Mesyuarat:</td>
                <td><input type="checkbox" id="Pentadbiran" name="Pentadbiran"> Pentadbiran</td>
                <td><input type="checkbox" id="Pembangunan" name="Pembangunan"> Pembangunan</td>
                <td><input type="checkbox" id="Agensi Luar" name="Agensi Luar"> Agensi Luar</td>
                <td></td>
            </tr>
            <tr>
                <td>Masa:</td>
                <td colspan="3"><input type="time" id="masa" name="masa" required></td>
            </tr>
            <tr>
                <td>Keperluan ICT:</td>
                <td><input type="checkbox" id="perakam_suara" name="perakam_suara"> Perakam Suara</td>
                <td><input type="checkbox" id="webcam" name="webcam"> Webcam</td>
                <td><input type="checkbox" id="laptop" name="laptop"> Laptop</td>
            </tr>
            <tr>
                <td></td>
                <td><input type="checkbox" id="projector" name="projector"> Projector</td>
                <td><input type="checkbox" id="pointer" name="pointer"> Pointer</td>
                <td></td>
            </tr>
            <tr>
                <td>Jumlah Ahli:</td>
                <td colspan="3"><input type="text" id="jumlah_ahli" name="jumlah_ahli" required></td>
            </tr>
            
            <tr>
                <td>Jadual Makan:</td>
                <td><input type="checkbox" id="pagi" name="pagi"> Pagi</td>
                <td><input type="checkbox" id="tengahari" name="tengahari"> Tengahari</td>
                <td><input type="checkbox" id="petang" name="petang"> Petang</td>
            </tr>
            <tr>
                <td>Bilik Makan:</td>
                <td colspan="3">
                    <input type="checkbox" id="bilik_makan" name="bilik_makan"> Bilik Makan 
                    <input type="checkbox" id="bilik_makan_vip" name="bilik_makan_vip"> Bilik Makan VIP
                </td>
            </tr>
            <tr>
                <td>Menu:</td>
                <td colspan="3"><input type="text" id="menu" name="menu" required></td>
            </tr>
            <tr>
                <td>Kaedah Hidangan:</td>
                <td><input type="radio" id="vip" name="hidangan" value="VIP"> VIP</td>
                <td><input type="radio" id="bufet" name="hidangan" value="BUFET"> BUFET</td>
                <td><input type="radio" id="food_container" name="hidangan" value="FOOD CONTAINER"> FOOD CONTAINER</td>
            </tr>
            <tr>
                <td>Bilangan:</td>
                <td colspan="3"><input type="number" id="bilangan" name="bilangan" required></td>
            </tr>
            <tr>
                <td>Nama Pemohon:</td>
                <td colspan="3"><input type="text" id="nama_pemohon" name="nama_pemohon" required></td>
            </tr>
            <tr>
                <td>Jabatan/Unit:</td>
                <td colspan="3"><input type="text" id="jabatan_unit" name="jabatan_unit" required></td>
            </tr>
            <tr>
                <td>No. Telefon:</td>
                <td colspan="3"><input type="number" id="no_telefon" name="no_telefon" required></td>
            </tr>
            <tr>
                <td>Tarikh:</td>
                <td colspan="3"><input type="date" id="tarikh_submit" name="tarikh_submit" required></td>
            </tr>
            <tr>
                <td>Surat Jemputan:</td>
                <td colspan="3"><input type="text" id="surat_jemputan" name="surat_jemputan"></td>
            </tr>
            <tr>
                <td>Senarai Jemputan:</td>
                <td colspan="3"><input type="text" id="senarai_jemputan" name="senarai_jemputan"></td>
            </tr>
        </table>
        <button type="submit" class="submit-btn">Hantar</button>
        </form>
    </div>
</body>
</html>


