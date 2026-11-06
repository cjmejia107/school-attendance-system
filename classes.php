<?php require 'auth.php'; 

// ADD CLASS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $stmt = $conn->prepare("INSERT INTO classes (class_name, schedule) VALUES (?,?)");
    $stmt->bind_param("ss", $_POST['class_name'], $_POST['schedule']);
    $stmt->execute();
}

// DELETE CLASS
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Burahin muna ang attendance records ng class na ito para walang error
    $conn->query("DELETE FROM attendance WHERE class_id = $id");
    // Tapos burahin ang class
    $conn->query("DELETE FROM classes WHERE class_id = $id");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Classes | SAS Premium</title>
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
        .form-control:focus { background: #0f172a; color: white; border-color: var(--accent); box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);}
        .form-control::placeholder { color: #64748b; }
        
        .table { color: var(--text-main); margin-bottom: 0;}
        .table thead th { background: #0f172a; color: var(--text-muted); border-bottom: 2px solid #334155; font-weight: 600; padding: 15px;}
        .table tbody td { border-bottom: 1px solid #334155; padding: 15px; vertical-align: middle; background-color: transparent !important; color: white;}
        .table tbody tr:hover td { background-color: rgba(255,255,255,0.02) !important; }
        
        .btn-primary { background: var(--accent); border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; color: white;}
        .btn-primary:hover { background: #4f46e5; }
        .btn-danger { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: none; padding: 8px 12px; border-radius: 6px;}
        .btn-danger:hover { background: #ef4444; color: white; }
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
        <a href="classes.php" class="active"><i class="fa-solid fa-book-open"></i> Manage Classes</a>
        <a href="attendance.php"><i class="fa-solid fa-clipboard-user"></i> Record Attendance</a>
        <a href="reports.php"><i class="fa-solid fa-file-lines"></i> Reports</a>
        <div style="position: absolute; bottom: 20px; width: 100%;">
            <a href="logout.php" style="color: #f87171;"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2 class="page-title">Manage Classes</h2>
        
        <div class="glass-card">
            <h5 class="mb-4 text-white">Add New Class</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-5"><input name="class_name" class="form-control" placeholder="Class Name (e.g. CC104)" required></div>
                <div class="col-md-5"><input name="schedule" class="form-control" placeholder="Schedule (e.g. MWF 10:00 AM)"></div>
                <div class="col-md-2"><button name="add" class="btn btn-primary w-100">Add Class</button></div>
            </form>
        </div>

        <div class="glass-card">
            <h5 class="mb-4 text-white">Class List</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Class Name</th><th>Schedule</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $rows = $conn->query("SELECT * FROM classes");
                        if($rows->num_rows > 0){
                            while ($c = $rows->fetch_assoc()) {
                                echo "<tr>
                                  <td class='fw-bold text-primary'>{$c['class_name']}</td>
                                  <td>{$c['schedule']}</td>
                                  <td><a href='?delete={$c['class_id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete this class and its attendance records?')\"><i class='fa-solid fa-trash'></i></a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center text-muted py-4'>No classes found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>