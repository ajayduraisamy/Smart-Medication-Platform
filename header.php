<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart  Medication Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
   
    
    <style>
        :root {
            --primary-color: #2e7d32;
            --secondary-color: #ff9800;
            --accent-color: #4caf50;
            --dark-color: #1b5e20;
            --light-color: #e8f5e9;
            --text-dark: #333;
            --text-light: #fff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            padding-top: 80px;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        .navbar {
            box-shadow: var(--shadow);
            background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%) !important;
            padding: 12px 20px;
            transition: var(--transition);
        }

        .navbar.scrolled {
            padding: 8px 20px;
            background: rgba(25, 25, 44, 0.95) !important;
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .navbar-brand:hover {
            color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .navbar-brand img {
            height: 50px;
            width: 120px;
            border-radius: 10px;
            margin-right: 10px;
            transition: var(--transition);
        }

        .nav-item .nav-link {
            font-size: 1rem;
            color: white !important;
            border-radius: 4px;
            transition: var(--transition);
            position: relative;
            padding: 8px 15px;
            margin: 0 5px;
        }

        .nav-item .nav-link:before {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: var(--secondary-color);
            transition: var(--transition);
        }

        .nav-item .nav-link:hover {
            color: var(--secondary-color) !important;
            transform: translateY(-2px);
        }

        .nav-item .nav-link:hover:before {
            width: 100%;
            left: 0;
        }

        .dropdown-menu {
            background: rgba(25, 25, 44, 0.95) !important;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: none;
            padding: 10px 0;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            color: white !important;
            font-weight: 500;
            font-size: 1rem;
            padding: 10px 20px;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--secondary-color) !important;
            padding-left: 25px;
        }

        .navbar-toggler {
            border: none;
            padding: 5px 10px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--secondary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar {
            animation: fadeInUp 0.8s ease;
        }

        @media (max-width: 992px) {
            .navbar-collapse {
                background: rgba(25, 25, 44, 0.95);
                padding: 15px;
                border-radius: 10px;
                margin-top: 10px;
                backdrop-filter: blur(10px);
            }

            .navbar-nav {
                width: 100%;
            }

            .nav-item {
                text-align: center;
                margin: 5px 0;
            }
        }

        @media (max-width: 576px) {
            body {
                padding-top: 70px;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .navbar-brand img {
                height: 40px;
                width: 100px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
        <div class="container-fluid d-flex align-items-center">
            <a class="navbar-brand" href="index">
                <i class="fas fa-syringe me-2"></i> Smart  Medication
            </a>

            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_medicines">Manage Medicines</a>
                    </li>
                  
                    
                    <?php if (isset($_SESSION['name'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?php echo $_SESSION['name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                               
                               
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login"><i class="fas fa-sign-in-alt me-1"></i>Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>