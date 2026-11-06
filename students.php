<?php require 'auth.php'; 

// ADD STUDENT (May Auto-Generate na ng Student Number)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    // Kunin ang pinakamalaking student_id para malaman kung pang-ilan na
    $result = $conn->query("SELECT MAX(student_id) as max_id FROM students");
    $row = $result->fetch_assoc();
    $next_id = $row['max_id'] ? $row['max_id'] + 1 : 1;
    
    // Gumawa ng format na "2024-001"
    $student_number = date('Y') . '-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare("INSERT INTO students (student_number, first_name, last_name, section) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $student_number, $_POST['first_name'], $_POST['last_name'], $_POST['section']);
    $stmt->execute();
}

// DELETE STUDENT
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM attendance WHERE student_id = $id");
    $conn->query("DELETE FROM students WHERE student_id = $id");
}

// SEARCH
$search = $_GET['search'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students | SAS Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg-dark: #0f172a; --sidebar-bg: #1e293b; --accent: #6366f1; --text-main: #f8fafc; --text-muted: #94a3b8; --card-bg: #1e293b; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--bg-dark); color: var(--text-main); overflow-x: hidden; }
        
        /* Sidebar */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: var(--sidebar-bg); padding-top: 20px; border-right: 1px solid #334155; z-index: 1000;}
        .sidebar h4 { text-align: center; color: white; font-weight: 700; margin-bottom: 30px; letter-spacing: 1px; font-size: 22px; display: flex; justify-content: center; align-items: center; gap: 10px;}
        .sidebar a { padding: 15px 25px; text-decoration: none; font-size: 15px; color: var(--text-muted); display: block; transition: 0.3s; border-left: 3px solid transparent; font-weight: 500;}
        .sidebar a:hover, .sidebar a.active { color: white; background: rgba(99, 102, 241, 0.1); border-left: 3px solid var(--accent); }
        .sidebar a i { margin-right: 15px; width: 20px; text-align: center; font-size: 18px;}
        .user-profile { text-align: center; padding: 0 20px 20px; border-bottom: 1px solid #334155; margin-bottom: 20px;}
        .user-profile i { font-size: 40px; color: var(--accent); margin-bottom: 10px;}
        
        /* Main Content */
        .main-content { margin-left: 260px; padding: 40px; }
        .page-title { font-weight: 700; font-size: 32px; margin-bottom: 30px; color: white; }
        
        /* Cards & Forms */
        .glass-card { background: var(--card-bg); border-radius: 16px; padding: 25px; border: 1px solid #334155; margin-bottom: 30px;}
        .form-control { background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 8px;}
        .form-control:focus { background: #0f172a; color: white; border-color: var(--accent); box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);}
        .form-control::placeholder { color: #64748b; }
        
        /* Table */
        .table { color: var(--text-main); margin-bottom: 0;}
        .table thead th { background: #0f172a; color: var(--text-muted); border-bottom: 2px solid #334155; font-weight: 600; padding: 15px;}
        .table tbody td { border-bottom: 1px solid #334155; padding: 15px; vertical-align: middle;}
        .table tbody tr:hover { background: rgba(255,255,255,0.02); }
        
        /* Buttons */
        .btn-primary { background: var(--accent); border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; color: white;}
        .btn-primary:hover { background: #4f46e5; }
        .btn-danger { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: none; }
        .btn-danger:hover { background: #ef4444; color: white; }
    </style>
</head>
<body>
    <div class="sidebar">
        <!-- Inayos natin yung Logo Title -->
        <h4><i class="fa-solid fa-layer-group" style="color: #3b82f6;"></i> SAS Premium</h4>
        
        <div class="user-profile">
            <i class="fa-solid fa-circle-user"></i>
            <h6 class="text-white mb-0"><?= htmlspecialchars($_SESSION['full_name']) ?></h6>
        </div>
        
        <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="students.php" class="active"><i class="fa-solid fa-user-graduate"></i> Manage Students</a>
        <a href="classes.php"><i class="fa-solid fa-book-open"></i> Manage Classes</a>
        <a href="attendance.php"><i class="fa-solid fa-clipboard-user"></i> Record Attendance</a>
        <a href="reports.php"><i class="fa-solid fa-file-lines"></i> Reports</a>
        <div style="position: absolute; bottom: 20px; width: 100%;">
            <a href="logout.php" style="color: #f87171;"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2 class="page-title">Manage Students</h2>
        
        <div class="glass-card">
            <h5 class="mb-4 text-white">Add New Student <span class="text-muted" style="font-size: 14px; font-weight: normal;">(Auto-generated ang Student No.)</span></h5>
            <form method="POST" class="row g-3">
                <div class="col-md-4"><input name="first_name" class="form-control" placeholder="First Name" required></div>
                <div class="col-md-4"><input name="last_name" class="form-control" placeholder="Last Name" required></div>
                <div class="col-md-3"><input name="section" class="form-control" placeholder="Section"></div>
                <div class="col-md-1"><button name="add" class="btn btn-primary w-100 h-100" title="Add Student"><i class="fa-solid fa-plus"></i></button></div>
            </form>
        </div>

        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 text-white">Student Directory</h5>
                <form method="GET" class="d-flex" style="width: 300px;">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary"><i class="fa-solid fa-search"></i></button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr><th>Student No.</th><th>Name</th><th>Section</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        if($search != ''){
                            $stmt = $conn->prepare("SELECT * FROM students WHERE first_name LIKE ? OR last_name LIKE ? OR section LIKE ? OR student_number LIKE ? ORDER BY last_name");
                            $like = "%$search%";
                            $stmt->bind_param("ssss", $like, $like, $like, $like);
                            $stmt->execute();
                            $rows = $stmt->get_result();
                        } else {
                            $rows = $conn->query("SELECT * FROM students ORDER BY last_name");
                        }

                        if($rows->num_rows > 0) {
                            while ($s = $rows->fetch_assoc()) {
                                echo "<tr>
                                  <td><span class='badge' style='background:#334155; font-size:14px; padding:8px 12px;'>{$s['student_number']}</span></td>
                                  <td class='fw-bold'>{$s['first_name']} {$s['last_name']}</td>
                                  <td>{$s['section']}</td>
                                  <td><a href='?delete={$s['student_id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Delete student?')\"><i class='fa-solid fa-trash'></i></a></td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted py-4'>No students found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>