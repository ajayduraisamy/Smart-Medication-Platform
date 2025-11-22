<?php
session_start();
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM medi_users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Correct password
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name']; 
         

           
                $_SESSION['success_message'] = "Login successful!";
                header('Location: index');
                exit();
          
        } else {
            $_SESSION['error_message'] = "Invalid password.";
            header('Location: login');
            exit();
        }
    } else {
        $_SESSION['error_message'] = "No user found with that email address.";
        header('Location: login');
        exit();
    }
}
?>

<?php include 'header.php'; ?>

<style>
    :root {
        --primary-color: #2e7d32;
        --secondary-color: #ff9800;
        --accent-color: #4caf50;
        --dark-color: #1b5e20;
        --light-color: #e8f5e9;
        --text-dark: #333;
        --text-light: #fff;
        --shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s ease;
    }
    
    .login-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4efe9 100%);
        position: relative;
        overflow: hidden;
    }
    
    .login-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" opacity="0.05"><path fill="%232e7d32" d="M500,100 C700,50 800,200 900,300 C1000,400 900,600 700,700 C500,800 300,700 200,600 C100,500 200,300 300,200 C400,100 400,150 500,100 Z"/></svg>') no-repeat center center;
        background-size: cover;
        z-index: 0;
    }
    
    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
        z-index: 1;
        position: relative;
        animation: fadeInUp 0.8s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
        color: white;
        padding: 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" opacity="0.1"><path fill="%23ffffff" d="M500,100 C700,50 800,200 900,300 C1000,400 900,600 700,700 C500,800 300,700 200,600 C100,500 200,300 300,200 C400,100 400,150 500,100 Z"/></svg>') no-repeat center center;
        background-size: cover;
    }
    
    .card-header h2 {
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }
    
    .card-header p {
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    .card-body {
        padding: 40px;
    }
    
    .form-floating {
        margin-bottom: 25px;
        position: relative;
    }
    
    .form-control {
        border-radius: 10px;
        padding: 16px 20px;
        border: 2px solid #e0e0e0;
        transition: var(--transition);
        font-size: 16px;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--text-dark);
    }
    
    .btn-login {
        background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-weight: 600;
        font-size: 18px;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        margin-top: 10px;
        width: 100%;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(46, 125, 50, 0.4);
    }
    
    .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 25px;
        animation: slideIn 0.5s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .alert-success {
        background-color: #e8f5e9;
        color: var(--dark-color);
        border-left: 4px solid var(--accent-color);
    }
    
    .alert-danger {
        background-color: #ffebee;
        color: #c62828;
        border-left: 4px solid #f44336;
    }
    
    .register-link {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }
    
    .register-link a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }
    
    .register-link a:hover {
        color: var(--dark-color);
        text-decoration: underline;
    }
    
    .input-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        z-index: 5;
    }
    
    .form-floating.password-field {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        z-index: 10;
    }
    
    .forgot-password {
        text-align: right;
        margin-top: -15px;
        margin-bottom: 20px;
    }
    
    .forgot-password a {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
        transition: var(--transition);
    }
    
    .forgot-password a:hover {
        color: var(--dark-color);
        text-decoration: underline;
    }
    
    .feature-highlights {
        display: flex;
        justify-content: space-around;
        margin-top: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .feature-highlight {
        text-align: center;
        flex: 1;
        min-width: 120px;
    }
    
    .feature-icon {
        width: 50px;
        height: 50px;
        background: rgba(46, 125, 50, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: var(--primary-color);
        font-size: 1.2rem;
    }
    
    .feature-text {
        font-size: 0.85rem;
        color: #666;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .login-container {
            padding: 20px 15px;
        }
        
        .card-body {
            padding: 30px 25px;
        }
        
        .card-header {
            padding: 25px 20px;
        }
        
        .card-header h2 {
            font-size: 1.5rem;
        }
        
        .feature-highlights {
            flex-direction: column;
            gap: 20px;
        }
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 25px 20px;
        }
        
        .card-header {
            padding: 20px 15px;
        }
        
        .form-control {
            padding: 14px 16px;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="card-header">
            <h2>Welcome Back to Medication Platform</h2>
            <p>Sign in to your account to continue</p>
        </div>
        <div class="card-body">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }

            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']); 
            }
            ?>
            
            <form method="POST" action="">
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                
                <div class="form-floating password-field">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <label for="password">Password</label>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
              

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign In
                    </button>
                </div>
            </form>

          

            <div class="register-link">
                <p>Don't have an account? <a href="register">Register here</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    // Form validation animations
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input');
    
    inputs.forEach(input => {
        input.addEventListener('invalid', function() {
            this.style.borderColor = '#f44336';
            this.style.animation = 'shake 0.5s ease';
        });
        
        input.addEventListener('input', function() {
            if (this.checkValidity()) {
                this.style.borderColor = '#4caf50';
            } else {
                this.style.borderColor = '#e0e0e0';
            }
        });
    });
    
    // Add shake animation for invalid fields
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);
    
    // Auto-focus on email field
    document.getElementById('email').focus();
</script>

<?php include 'footer.php'; ?>