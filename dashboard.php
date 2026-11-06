<?php require 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | SAS Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --bg-dark: #0f172a; --sidebar-bg: #1e293b; --accent: #6366f1; --text-main: #f8fafc; --text-muted: #94a3b8; --card-bg: #1e293b; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--bg-dark); color: var(--text-main); overflow-x: hidden; }
        
        /* Sidebar */
        .sidebar { height: 100vh; width: 260px; position: fixed; top: 0; left: 0; background: var(--sidebar-bg); padding-top: 20px; border-right: 1px solid #334155; z-index: 1000;}
        .sidebar h4 { text-align: center; color: white; font-weight: 700; margin-bottom: 30px; letter-spacing: 1px; font-size: 22px;}
        .sidebar a { padding: 15px 25px; text-decoration: none; font-size: 15px; color: var(--text-muted); display: block; transition: 0.3s; border-left: 3px solid transparent; font-weight: 500;}
        .sidebar a:hover, .sidebar a.active { color: white; background: rgba(99, 102, 241, 0.1); border-left: 3px solid var(--accent); }
        .sidebar a i { margin-right: 15px; width: 20px; text-align: center; font-size: 18px;}
        .user-profile { text-align: center; padding: 0 20px 20px; border-bottom: 1px solid #334155; margin-bottom: 20px;}
        .user-profile i { font-size: 40px; color: var(--accent); margin-bottom: 10px;}

        /* Main Content */
        .main-content { margin-left: 260px; padding: 30px 40px; }
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-weight: 700; font-size: 32px; margin: 0; background: -webkit-linear-gradient(45deg, #818cf8, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .clock-widget { background: var(--card-bg); padding: 10px 20px; border-radius: 12px; border: 1px solid #334155; font-weight: 600; color: var(--text-muted);}
        .clock-widget span { color: white; font-size: 18px; }

        /* Premium Cards */
        .glass-card { background: var(--card-bg); border-radius: 16px; padding: 25px; border: 1px solid #334155; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: 0.3s; position: relative; overflow: hidden; }
        .glass-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3); border-color: var(--accent); }
        .glass-card h5 { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 600;}
        .glass-card h1 { font-size: 42px; font-weight: 700; margin-top: 10px; color: white;}
        
        .icon-box { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; }
        .bg-blue { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
        .bg-purple { background: rgba(168, 85, 247, 0.15); color: #a855f7; }
        .bg-green { background: rgba(34, 197, 94, 0.15); color: #22c55e; }

        /* Quick Actions */
        .quick-action-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; border-radius: 12px; text-decoration: none; color: white; transition: 0.3s; border: 1px dashed #475569; height: 100%;}
        .quick-action-btn:hover { background: rgba(99, 102, 241, 0.1); border-color: var(--accent); color: var(--accent); }
        .quick-action-btn i { font-size: 28px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4><i class="fa-solid fa-layer-group text-primary"></i> SAS Premium</h4>
        <div class="user-profile">
            <i class="fa-solid fa-circle-user"></i>
            <h6 class="text-white mb-0"><?= htmlspecialchars($_SESSION['full_name']) ?></h6>
            <small class="text-muted">Administrator</small>
        </div>
        <a href="dashboard.php" class="active"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        <a href="students.php"><i class="fa-solid fa-user-graduate"></i> Manage Students</a>
        <a href="classes.php"><i class="fa-solid fa-book-open"></i> Manage Classes</a>
        <a href="attendance.php"><i class="fa-solid fa-clipboard-user"></i> Record Attendance</a>
        <a href="reports.php"><i class="fa-solid fa-file-lines"></i> Reports</a>
        <div style="position: absolute; bottom: 20px; width: 100%;">
            <a href="logout.php" style="color: #f87171;"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <!-- Header with Live Clock -->
        <div class="top-header">
            <h2 class="page-title">Dashboard Overview</h2>
            <div class="clock-widget">
                <i class="fa-regular fa-calendar-days me-2"></i> <?= date('F d, Y') ?> | <i class="fa-regular fa-clock ms-2 me-2"></i> <span id="live-clock">00:00:00</span>
            </div>
        </div>
        
        <!-- Top Stats -->
        <div class="row g-4 mb-4">
            <?php
            $totalStudents = $conn->query("SELECT COUNT(*) c FROM students")->fetch_assoc()['c'];
            $totalClasses  = $conn->query("SELECT COUNT(*) c FROM classes")->fetch_assoc()['c'];
            $totalToday    = $conn->query("SELECT COUNT(*) c FROM attendance WHERE date = CURDATE()")->fetch_assoc()['c'];
            ?>
            <div class="col-md-4">
                <div class="glass-card">
                    <div class="icon-box bg-blue"><i class="fa-solid fa-users"></i></div>
                    <h5>Total Students</h5>
                    <h1><?= $totalStudents ?></h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <div class="icon-box bg-purple"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h5>Active Classes</h5>
                    <h1><?= $totalClasses ?></h1>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <div class="icon-box bg-green"><i class="fa-solid fa-clipboard-check"></i></div>
                    <h5>Attendance Today</h5>
                    <h1><?= $totalToday ?></h1>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- LEFT COL: Chart & Summary -->
            <div class="col-md-8">
                <div class="glass-card mb-4">
                    <h5 class="mb-4 text-white">Today's Attendance Breakdown</h5>
                    <div style="height: 250px; display: flex; justify-content: center;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                <div class="row g-4">
                    <?php
                    $present = $conn->query("SELECT COUNT(*) c FROM attendance WHERE date = CURDATE() AND status = 'Present'")->fetch_assoc()['c'];
                    $absent  = $conn->query("SELECT COUNT(*) c FROM attendance WHERE date = CURDATE() AND status = 'Absent'")->fetch_assoc()['c'];
                    $late    = $conn->query("SELECT COUNT(*) c FROM attendance WHERE date = CURDATE() AND status = 'Late'")->fetch_assoc()['c'];
                    ?>
                    <div class="col-md-4">
                        <div class="glass-card" style="border-bottom: 4px solid #22c55e;">
                            <h5 class="text-success">Present</h5>
                            <h1><?= $present ?></h1>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card" style="border-bottom: 4px solid #ef4444;">
                            <h5 class="text-danger">Absent</h5>
                            <h1><?= $absent ?></h1>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card" style="border-bottom: 4px solid #eab308;">
                            <h5 class="text-warning">Late</h5>
                            <h1><?= $late ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COL: Quick Actions -->
            <div class="col-md-4">
                <div class="glass-card h-100">
                    <h5 class="mb-4 text-white">Quick Actions</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="students.php" class="quick-action-btn">
                                <i class="fa-solid fa-user-plus"></i>
                                <span class="text-center" style="font-size: 13px;">Add Student</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="classes.php" class="quick-action-btn">
                                <i class="fa-solid fa-folder-plus"></i>
                                <span class="text-center" style="font-size: 13px;">Add Class</span>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="attendance.php" class="quick-action-btn">
                                <i class="fa-solid fa-clipboard-user"></i>
                                <span class="text-center" style="font-size: 13px;">Record Attendance Now</span>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="reports.php" class="quick-action-btn">
                                <i class="fa-solid fa-file-export"></i>
                                <span class="text-center" style="font-size: 13px;">Generate Report</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for Live Clock & Chart -->
    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { hour12: true });
            document.getElementById('live-clock').innerText = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Chart.js Configuration
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Kunin ang data mula sa PHP
        const presentData = <?= $present ?>;
        const absentData = <?= $absent ?>;
        const lateData = <?= $late ?>;

        // Kung 0 lahat, lagyan natin ng mock data para may itsura yung chart (Optional, pwede mong burahin)
        const finalPresent = presentData === 0 && absentData === 0 && lateData === 0 ? 10 : presentData;
        const finalAbsent = presentData === 0 && absentData === 0 && lateData === 0 ? 2 : absentData;
        const finalLate = presentData === 0 && absentData === 0 && lateData === 0 ? 1 : lateData;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Late'],
                datasets: [{
                    data: [finalPresent, finalAbsent, finalLate],
                    backgroundColor: ['#22c55e', '#ef4444', '#eab308'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#94a3b8', font: { family: 'Outfit', size: 14 } }
                    }
                }
            }
        });
    </script>
</body>
</html>