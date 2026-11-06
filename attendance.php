<?php require 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $class_id = $_POST['class_id'];
    if(isset($_POST['status'])){
        foreach ($_POST['status'] as $student_id => $status) {
            $stmt = $conn->prepare("INSERT INTO attendance (student_id, class_id, date, status) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE status = VALUES(status)");
            $stmt->bind_param("iiss", $student_id, $class_id, $date, $status);
            $stmt->execute();
        }
        $success = "Attendance saved successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance | SAS Premium</title>
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
        .form-control, .form-select { background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 8px;}
        .form-control:focus, .form-select:focus { background: #0f172a; color: white; border-color: var(--accent); box-shadow: none;}
        
        .table { color: var(--text-main); margin-bottom: 0;}
        .table thead th { background: #0f172a; color: var(--text-muted); border-bottom: 2px solid #334155; font-weight: 600; padding: 15px;}
        .table tbody td { border-bottom: 1px solid #334155; padding: 15px; vertical-align: middle;}
        
        .btn-primary { background: var(--accent); border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600;}
        .btn-primary:hover { background: #4f46e5; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4><i class="fa-solid fa-layer-group text-primary"></i> SAS Premium</h4>
        <div class="user-profile">
            <i class="fa-solid fa-circle-user"></i>
            <h6 class="text-white mb-0"><?= htmlspecialchars($_SESSION['full_name']) ?></h6>
        </div>
        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="students.php"><i class="fa-solid fa-user-graduate"></i> Manage Students</a>
        <a href="classes.php"><i class="fa-solid fa-book-open"></i> Manage Classes</a>
        <a href="attendance.php" class="active"><i class="fa-solid fa-clipboard-user"></i> Record Attendance</a>
        <a href="reports.php"><i class="fa-solid fa-file-lines"></i> Reports</a>
        <div style="position: absolute; bottom: 20px; width: 100%;">
            <a href="logout.php" style="color: #f87171;"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2 class="page-title">Record Attendance</h2>

        <?php if (!empty($success)) echo "<div class='alert alert-success' style='background:rgba(34,197,94,0.2); color:#22c55e; border:none;'><i class='fa-solid fa-check-circle'></i> $success</div>"; ?>

        <div class="glass-card">
            <form method="POST">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="text-muted mb-2 fw-bold">Select Class</label>
                        <select name="class_id" class="form-select" required>
                            <?php
                            $classes = $conn->query("SELECT * FROM classes");
                            if($classes->num_rows > 0) {
                                while ($c = $classes->fetch_assoc())
                                    echo "<option value='{$c['class_id']}'>{$c['class_name']} ({$c['schedule']})</option>";
                            } else {
                                echo "<option value=''>No classes available</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted mb-2 fw-bold">Select Date</label>
                        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead>
                            <tr><th>Student Name</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $students = $conn->query("SELECT * FROM students ORDER BY last_name");
                            if($students->num_rows > 0){
                                while ($s = $students->fetch_assoc()) {
                                    echo "<tr>
                                      <td class='fw-bold'>{$s['first_name']} {$s['last_name']}</td>
                                      <td>
                                        <select name='status[{$s['student_id']}]' class='form-select w-50'>
                                          <option value='Present'>Present</option>
                                          <option value='Absent'>Absent</option>
                                          <option value='Late'>Late</option>
                                          <option value='Excused'>Excused</option>
                                        </select>
                                      </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2' class='text-center text-muted'>No students found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3"><i class="fa-solid fa-cloud-arrow-up me-2"></i> Save Attendance Record</button>
            </form>
        </div>
    </div>
</body>
</html>