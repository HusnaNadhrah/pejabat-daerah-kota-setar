<?php 
session_start();
// In admin_dashboard.php
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit();
}

include('connection.php');

// Get counts for each category
$sql_pentadbiran = "SELECT COUNT(*) as count FROM booking_requests WHERE pentadbiran = 1";
$sql_pembangunan = "SELECT COUNT(*) as count FROM booking_requests WHERE pembangunan = 1";
$sql_agensi_luar = "SELECT COUNT(*) as count FROM booking_requests WHERE agensi_luar = 1";
$pentadbiran_count = $conn->query($sql_pentadbiran)->fetch_assoc()['count'];
$pembangunan_count = $conn->query($sql_pembangunan)->fetch_assoc()['count'];
$agensi_luar_count = $conn->query($sql_agensi_luar)->fetch_assoc()['count'];


if ($_POST) {
    if (isset($_POST['approve'])) {
        $id = $_POST['id'];
        $sql = "UPDATE booking_requests SET status = 'APPROVED' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM booking_requests WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Get recent bookings
$sql_recent = "SELECT * FROM booking_requests ORDER BY created_at DESC LIMIT 10";
$recent_bookings = $conn->query($sql_recent);

$sql = "SELECT * FROM booking_requests ORDER BY tarikh DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
    <title>DASHBOARD</title>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f0f0;
        }

        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #E6E6FA;
            min-height: 100vh;
        }

        .header {
            background-color: #E6E6FA;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 40px;
            height: 40px;
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .user-section {
        display: flex;
        align-items: center;
        gap: 1rem; /* Increased gap for better spacing */
    }

    .logout-button {
        padding: 0.5rem 1rem;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background-color 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .logout-button:hover {
        background-color: #c82333;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

        .user-icon {
            width: 30px;
            height: 30px;
            background-color: #ccc;
            border-radius: 50%;
        }

        .stats-container {
            display: flex;
            gap: 2rem;
            padding: 2rem;
        }

        .stat-card {
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            min-width: 150px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .table-container {
            padding: 0 2rem 2rem 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
        }

        .action-button {
            padding: 0.5rem 1rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button {
            padding: 0.5rem 1rem;
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        nav {
            padding: 0 2rem;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1rem;
        }
        .nav-link {
            padding: 0.8rem 1rem;
            color: #333;
            text-decoration: none;
            display: inline-block;
            position: relative;
            margin-right: 1rem;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #007bff;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .nav-link.active::after {
            transform: scaleX(1);
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <header class="header">
            <div class="logo-section">
                <img src="logo.png" alt="Logo Kedah" class="logo">
                <span class="title">Pejabat Daerah</span>
            </div>
            <div class="user-section">
                <span>admin</span>
                <div class="user-icon"></div>
            </div>
            <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?')">
            Logout
        </a>
        </header>

        <nav>
            <a href="dashboard.php" class="nav-link">DASHBOARD</a>
            <!-- <a href="reservation.php" class="nav-link">BORANG TEMPAHAN</a> -->
        </nav>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $pentadbiran_count; ?></div>
                <div class="stat-label">pentadbiran</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pembangunan_count; ?></div>
                <div class="stat-label">pembangunan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $agensi_luar_count; ?></div>
                <div class="stat-label">agensi luar</div>
            </div>
        </div>

        <div class="table-container">
        <table>
        <thead>
            <tr>
                <th>BIL</th>
                <th>TAJUK MESYUARAT</th>
                <th>PENGERUSI</th>
                <th>NAMA PEMOHON</th>
                <th>JABATAN</th>
                <th>TARIKH</th>
                <th>MASA MULA</th>
                <th>MASA AKHIR</th>
                <th>LOKASI</th>
                <th>KEPERLUAN ICT</th>
                <th>JUMLAH AHLI</th>
                <th>MAKANAN</th>
                <th>STATUS</th>
                <th class="no-print">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $bil = 1;
            while($row = $result->fetch_assoc()): 
                // Format location
                $lokasi = [];
                if($row['bilik_gerakan']) $lokasi[] = "Bilik Gerakan";
                if($row['bilik_pegawai']) $lokasi[] = "Bilik Pegawai";
                if($row['bilik_pkob']) $lokasi[] = "Bilik PKOB";
                
                // Format ICT requirements
                $ict = [];
                if($row['perakam_suara']) $ict[] = "Perakam Suara";
                if($row['webcam']) $ict[] = "Webcam";
                if($row['laptop']) $ict[] = "Laptop";
                if($row['projector']) $ict[] = "Projector";
                if($row['pointer']) $ict[] = "Pointer";

                // Format food requirements
                $makanan = [];
                if($row['makan_pagi']) $makanan[] = "Pagi";
                if($row['makan_tengahari']) $makanan[] = "Tengahari";
                if($row['makan_petang']) $makanan[] = "Petang";
                
                // Status class
                $status_class = 'status-pending';
                if ($row['status'] == 'APPROVED') {
                    $status_class = 'status-approved';
                } elseif ($row['status'] == 'REJECTED') {
                    $status_class = 'status-rejected';
                }
            ?>
            <tr>
                <td><?php echo $bil++; ?></td>
                <td><?php echo htmlspecialchars($row['tajuk_mesyuarat']); ?></td>
                <td><?php echo htmlspecialchars($row['pengerusi']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_pemohon']); ?></td>
                <td><?php echo htmlspecialchars($row['jabatan_unit']); ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['tarikh'])); ?></td>
                <td><?php echo date('h:i A', strtotime($row['masa_mula'])); ?></td>
                <td><?php echo date('h:i A', strtotime($row['masa_akhir'])); ?></td>
                <td><?php echo implode(", ", $lokasi); ?></td>
                <td><?php echo implode(", ", $ict); ?></td>
                <td><?php echo htmlspecialchars($row['jumlah_ahli']); ?></td>
                <td>
                    <?php if(!empty($makanan)): ?>
                        Waktu: <?php echo implode(", ", $makanan); ?><br>
                        Menu: <?php echo htmlspecialchars($row['menu']); ?><br>
                        Hidangan: <?php echo htmlspecialchars($row['kaedah_hidangan']); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="status-badge <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </div>
                </td>
                <td class="action-buttons">
                    <button onclick="printBooking(<?php echo $row['id']; ?>)" 
                            class="action-button print-button">
                        PRINT
                    </button>
                    
                    <?php if($row['status'] != 'APPROVED'): ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve" 
                                class="action-button approve-button">
                            APPROVE
                        </button>
                    </form>
                    <?php endif; ?>

                    <form method="POST" style="display: inline;"
                          onsubmit="return confirm('Are you sure you want to delete this booking?');">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete" 
                                class="delete-button">
                            DELETE
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
        </div>
    </div>

    <script>
 function printBooking(id) {
        // Create print window with booking details
        let printWindow = window.open('print_booking.php?id=' + id, '_blank');
        printWindow.onload = function() {
            printWindow.print();
        }
    }

document.addEventListener('DOMContentLoaded', function() {
            // Get current page URL
            const currentPage = window.location.pathname.split('/').pop();
            
            // Get all navigation links
            const navLinks = document.querySelectorAll('.nav-link');
            
            // Remove active class from all links first
            navLinks.forEach(link => {
                link.classList.remove('active');
                
                // Add active class if href matches current page
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
        // Add interactivity if needed
        document.querySelectorAll('.action-button').forEach(button => {
            button.addEventListener('click', function() {
                // Handle button click
                console.log('Action button clicked');
            });
        });

        // You can add more JavaScript functionality as needed
        // For example, to fetch and update the statistics or table data
    </script>
</body>
</html>