<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | School Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --secondary: #8b5cf6; --dark: #0f172a; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--dark); color: white; overflow-x: hidden; }
        
        /* Navbar */
        .navbar { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 15px 0; }
        .navbar-brand { font-weight: 700; font-size: 24px; color: white !important; }
        .nav-link { color: #94a3b8 !important; font-weight: 500; transition: 0.3s; }
        .nav-link:hover { color: white !important; }
        
        /* Hero Section */
        .hero { height: 90vh; display: flex; align-items: center; position: relative; background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.2), transparent 50%); }
        .hero h1 { font-size: 64px; font-weight: 800; line-height: 1.1; margin-bottom: 20px; }
        .hero h1 span { background: -webkit-linear-gradient(45deg, #818cf8, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 18px; color: #94a3b8; max-width: 500px; margin-bottom: 30px; }
        
        .btn-hero { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 15px 35px; border-radius: 10px; font-weight: 600; font-size: 18px; border: none; text-decoration: none; transition: 0.3s; display: inline-block;}
        .btn-hero:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4); color: white;}
        
        /* Floating Illustration */
        .hero-img { width: 100%; max-width: 500px; animation: float 6s ease-in-out infinite; }
        @keyframes float { 0% { transform: translateY(0); } 50% { transform: translateY(-20px); } 100% { transform: translateY(0); } }

        /* Features Section */
        .features { padding: 100px 0; background: #0b1120; }
        .feature-card { background: #1e293b; border-radius: 16px; padding: 30px; border: 1px solid #334155; transition: 0.3s; height: 100%;}
        .feature-card:hover { transform: translateY(-10px); border-color: var(--primary); }
        .feature-icon { width: 60px; height: 60px; background: rgba(99, 102, 241, 0.1); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-layer-group text-primary"></i> SAS Premium</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item ms-3"><a href="login.php" class="btn btn-outline-light" style="border-radius: 8px;">Login Portal</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1>Smart Attendance <br><span>Made Simple.</span></h1>
                    <p>The modern way to track student attendance. Fast, secure, and designed for the digital age.</p>
                    <a href="login.php" class="btn-hero">Access Dashboard <i class="fa-solid fa-arrow-right ms-2"></i></a>
                </div>
                <div class="col-md-6 text-center d-none d-md-block">
                    <!-- SVG Illustration -->
                    <svg class="hero-img" viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="50" y="50" width="400" height="300" rx="20" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                        <rect x="80" y="80" width="150" height="20" rx="10" fill="#6366f1"/>
                        <rect x="80" y="120" width="340" height="2" fill="#334155"/>
                        <circle cx="100" cy="150" r="15" fill="#22c55e"/>
                        <rect x="130" y="140" width="100" height="10" rx="5" fill="#64748b"/>
                        <circle cx="100" cy="190" r="15" fill="#ef4444"/>
                        <rect x="130" y="180" width="120" height="10" rx="5" fill="#64748b"/>
                        <circle cx="100" cy="230" r="15" fill="#22c55e"/>
                        <rect x="130" y="220" width="80" height="10" rx="5" fill="#64748b"/>
                        <rect x="300" y="130" width="100" height="100" rx="10" fill="#0f172a" stroke="#6366f1" stroke-width="2"/>
                        <path d="M350 160 L370 180 L350 200" stroke="#22c55e" stroke-width="4" fill="none"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Why Choose SAS Premium?</h2>
                <p class="text-muted">Built with modern technology for efficiency.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                        <h4 class="fw-bold">Lightning Fast</h4>
                        <p class="text-muted">Record attendance in seconds with our optimized interface.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <h4 class="fw-bold">Secure Data</h4>
                        <p class="text-muted">Your records are safely stored and protected.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-chart-pie"></i></div>
                        <h4 class="fw-bold">Smart Reports</h4>
                        <p class="text-muted">Generate instant visual reports and export to Excel.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-4 border-top border-secondary text-muted">
        <p>&copy; 2024 SAS Premium. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>