<?php require 'auth.php';

$date = $_GET['date'] ?? date('Y-m-d');

// EXPORT TO CSV LOGIC
if (isset($_GET['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Attendance_Report_' . $date . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Student Name', 'Class', 'Status')); // Header row

    $stmt = $conn->prepare("SELECT s.first_name, s.last_name, c.class_name, a.status FROM attendance a JOIN students s ON s.student_id = a.student_id JOIN classes c ON c.class_id = a.class_id WHERE a.date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $rows = $stmt->get_result();
    
    while ($row = $rows->fetch_assoc()) {
        fputcsv($output, array($row['first_name'] . ' ' . $row['last_name'], $row['class_name'], $row['status']));
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports | SAS Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg-dark: #0f172a; --sidebar-bg: #1e293b; --accent: #6366f1; --text-main: #f8fafc; --text-muted: #94a3b8; --card-bg: #1e293b; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--bg-dark); color: var(--text-main); overflow-x: hidden; }
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: var(--sidebar-bg); padding-top: 20px; border-right: 1px solid #334155; z-index: 1000;}
        .sidebar h4 { text-align: center; color: white; font-weight: 700; margin-bottom: 30px; letter-spacing: 1px; font-size: 22px;}
        .sidebar a { padding: 15px 25px; text-decoration: none; font-size: 15px; color: var(--text-muted); display: block; transition: 0.3s; border-left: 3px solid transparent; font-weight: 500;}
        .sidebar a:hover, .sidebar a.active { color: white; background: rgba(99, 102, 241, 0.1); border-left: 3px solid var(--accent); }
        .sidebar a i { margin-right: 15px; width: 20px; text-align: center; font-size: 18px;}
        .user-profile { text-align: center; padding: 0 20px 20px; border-bottom: 1px solid #334155; margin-bottom: 20px;}
        .user-profile i { font-size: 40px; color: var(--accent); margin-bottom: 10px;}
        .main-content { margin-left: 260px; padding: 40px; }
        .page-title { font-weight: 700; font-size: 32px; margin-bottom: 30px; color: white; }
        .glass-card { background: var(--card-bg); border-radius: 16px; padding: 25px; border: 1px solid #334155; margin-bottom: 30px;}
        .form-control { background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 8px;}
        .form-control:focus { background: #0f172a; color: white; border-color: var(--accent); box-shadow: none;}
        .table { color: var(--text-main); margin-bottom: 0;}
        .table thead th { background: #0f172a; color: var(--text-muted); border-bottom: 2px solid #334155; font-weight: 600; padding: 15px;}
        .table tbody td { border-bottom: 1px solid #334155; padding: 15px; vertical-align: middle; background-color: transparent !important; color: white;}
        .table tbody tr:hover td { background-color: rgba(255,255,255,0.03) !important; }
        .badge-present { background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid #22c55e; }
        .badge-absent { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; }
        .badge-late { background: rgba(234, 179, 8, 0.2); color: #eab308; border: 1px solid #eab308; }
        .badge-excused { background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: 1px solid #3b82f6; }
        .btn-primary { background: var(--accent); border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600;}
        .btn-success { background: #22c55e; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600;}
        .btn-warning { background: #f59e0b; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; color: white;}
        
        @media print {
            body { background: white !important; color: black !important; }
            .sidebar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            .glass-card { border: none !important; box-shadow: none !important; padding: 0 !important; background: transparent !important; margin-bottom: 0 !important;}
            .table { color: black !important; }
            .table thead th { background: #f8fafc !important; color: black !important; border-bottom: 2px solid #000 !important; }
            .table tbody td { color: black !important; border-bottom: 1px solid #ddd !important; background: white !important;}
            .badge-present, .badge-absent, .badge-late, .badge-excused { border: 1px solid #333 !important; color: black !important; background: transparent !important;}
        }
    </style>
</head>
<body>
    <div class="sidebar no-print">
        <h4><i class="fa-solid fa-layer-group text-primary"></i> SAS Premium</h4>
        <div class="user-profile">
            <i class="fa-solid fa-circle-user"></i>
            <h6 class="text-white mb-0"><?= htmlspecialchars($_SESSION['full_name']) ?></h6>
        </div>
        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="students.php"><i class="fa-solid fa-user-graduate"></i> Manage Students</a>
        <a href="classes.php"><i class="fa-solid fa-book-open"></i> Manage Classes</a>
        <a href="attendance.php"><i class="fa-solid fa-clipboard-user"></i> Record Attendance</a>
        <a href="reports.php" class="active"><i class="fa-solid fa-file-lines"></i> Reports</a>
        <div style="position: absolute; bottom: 20px; width: 100%;">
            <a href="logout.php" style="color: #f87171;"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2 class="page-title no-print">Attendance Reports</h2>
        
        <div class="glass-card no-print">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-auto"><label class="text-muted fw-bold">Select Date:</label></div>
                <div class="col-auto"><input type="date" name="date" class="form-control" value="<?= $date ?>"></div>
                <div class="col-auto"><button class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button></div>
                
                <div class="col-auto ms-auto d-flex gap-2">
                    <!-- EXPORT EXCEL BUTTON -->
                    <a href="?date=<?= $date ?>&export=1" class="btn btn-warning"><i class="fa-solid fa-file-excel"></i> Export to Excel</a>
                    <!-- PRINT BUTTON -->
                    <button type="button" onclick="window.print()" class="btn btn-success"><i class="fa-solid fa-print"></i> Print / Save PDF</button>
                </div>
            </form>
        </div>

        <div class="glass-card">
            <div class="d-none d-print-block text-center mb-4">
                <h2 style="font-weight:800; color: black; font-family: Arial, sans-serif;">School Attendance Report</h2>
                <p style="color: black; font-family: Arial, sans-serif;">Date: <?= date('F d, Y', strtotime($date)) ?></p>
                <hr style="border: 1px solid black;">
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Student Name</th><th>Class</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $conn->prepare("SELECT s.first_name, s.last_name, c.class_name, a.status FROM attendance a JOIN students s ON s.student_id = a.student_id JOIN classes c ON c.class_id = a.class_id WHERE a.date = ?");
                        $stmt->bind_param("s", $date);
                        $stmt->execute();
                        $rows = $stmt->get_result();
                        
                        if($rows->num_rows > 0){
                            while ($r = $rows->fetch_assoc()) {
                                $badge = strtolower($r['status']);
                                echo "<tr>
                                  <td class='fw-bold'>{$r['first_name']} {$r['last_name']}</td>
                                  <td>{$r['class_name']}</td>
                                  <td><span class='badge badge-$badge px-3 py-2'>{$r['status']}</span></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center text-muted py-4'>No records found for this date.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>