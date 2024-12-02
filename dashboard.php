<?php 
session_start();
// In dashboard.php
if (!isset($_SESSION['user_id'])) {
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

// Get all bookings
$sql = "SELECT * FROM booking_requests ORDER BY tarikh DESC";
$result = $conn->query($sql);

$user_email = $_SESSION['email'] ?? 'Unknown User';
$user_fullname = $_SESSION['fullname'] ?? 'Unknown';
?>

<!DOCTYPE html>
<html>
<head>
    <title>DASHBOARD</title>
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
            justify-content: center;
        }

        .stat-card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            min-width: 150px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #0066cc;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .table-container {
            padding: 0 2rem 2rem 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 8px 12px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            min-width: 100px;
            font-size: 0.9rem;
        }

        .status-pending {
            background-color: #ffd700;
            color: #000;
        }

        .status-approved {
            background-color: #28a745;
            color: white;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
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

        /* Responsive design */
        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
                align-items: center;
            }

            .stat-card {
                width: 100%;
                max-width: 300px;
            }

            .table-container {
                overflow-x: auto;
            }

            .header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
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
            <span><?php echo htmlspecialchars($user_email); ?></span>
                <div class="user-icon"></div>
            </div>
            <a href="logout.php" class="logout-button" onclick="return confirm('Are you sure you want to logout?')">
            Logout
        </a>
        </header>

        <nav>
            <a href="dashboard.php" class="nav-link">DASHBOARD</a>
            <a href="reservation.php" class="nav-link">BORANG TEMPAHAN</a>
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
                <th>NAMA PEMOHON</th>
                <th>HARI</th>
                <th>TARIKH</th>
                <th>MASA MULA</th>
                <th>MASA AKHIR</th>
                <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
            $bil = 1;
            while($row = $result->fetch_assoc()): 
                $status_class = 'status-pending';
                if ($row['status'] == 'APPROVED') {
                    $status_class = 'status-approved';
                } elseif ($row['status'] == 'REJECTED') {
                    $status_class = 'status-rejected';
                }

                // Get day name in Malay
                $hari = date('l', strtotime($row['tarikh']));
                $hari_malay = [
                    'Sunday' => 'Ahad',
                    'Monday' => 'Isnin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Khamis',
                    'Friday' => 'Jumaat',
                    'Saturday' => 'Sabtu'
                ];
                $hari = $hari_malay[$hari];
            ?>
                     <tr>
                <td><?php echo $bil++; ?></td>
                <td><?php echo htmlspecialchars($row['tajuk_mesyuarat']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_pemohon']); ?></td>
                <td><?php echo $hari; ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['tarikh'])); ?></td>
                <td><?php echo date('h:i A', strtotime($row['masa_mula'])); ?></td>
                <td><?php echo date('h:i A', strtotime($row['masa_akhir'])); ?></td>
                <td>
                    <div class="status-badge <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </div>
                </td>
            </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    });
    </script>
</body>
</html>