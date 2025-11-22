<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location='login';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? "Unknown";

date_default_timezone_set("Asia/Kolkata");

if (isset($_POST['save_log'])) {
    $medicine_name = trim($_POST['medicine_name']);
    $datetime = $_POST['take_time'];

    $tablet_map = [
        "Amlodipine"   => 1,
        "Metformin"    => 2,
        "Pantoprazole" => 3,
        "Vitamin-D"    => 4,
        "Shelcal-500"  => 5,
        "Ferrofit"     => 6
    ];

    // Default value if not found
    $tablet_no = $tablet_map[$medicine_name] ?? 0;
    $stmt = $conn->prepare("
        INSERT INTO medicine_log 
        (user_id, user_name, medicine_name, taken_datetime, tablet_no) 
        VALUES (?,?,?,?,?)
    ");
    $stmt->bind_param("isssi", $user_id, $user_name, $medicine_name, $datetime, $tablet_no);
    $stmt->execute();

    echo "<script>alert('Saved successfully!'); window.location='index';</script>";
    exit();
}


// fetch tablets
$meds = $conn->query("SELECT * FROM medicines ORDER BY id DESC");

include 'header.php';
?>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --success: #27ae60;
            --warning: #f39c12;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --border-radius: 16px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--text-dark);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 20px;
            color: white;
        }

        .page-header h2 {
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .page-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .user-welcome {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 10px 25px;
            display: inline-block;
            margin-top: 15px;
            font-weight: 600;
        }

        /* Medicine Cards Grid */
       .medicines-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin: 40px 0;
}


        .medicine-card {
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
            cursor: pointer;
            border: none;
            text-align: center;
            padding: 0;
            position: relative;
        }

        .medicine-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .medicine-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--secondary), var(--accent));
        }

        .medicine-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 25px auto 15px;
            border: 3px solid #f1f1f1;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .medicine-card:hover .medicine-image {
            transform: scale(1.1);
            border-color: var(--secondary);
        }

        .medicine-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
            padding: 0 15px;
        }

        .medicine-action {
            background: linear-gradient(135deg, var(--secondary), #2980b9);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 15px auto 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: var(--transition);
        }

        .medicine-card:hover .medicine-action {
            background: linear-gradient(135deg, var(--accent), #c0392b);
        }

        /* Modal Styles */
        .modal-bg {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-box {
            width: 100%;
            max-width: 450px;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .modal-header h4 {
            font-weight: 600;
            margin: 0;
            font-size: 1.4rem;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid #e1e5eb;
            border-radius: 10px;
            padding: 12px 15px;
            transition: var(--transition);
            font-size: 15px;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }

        .btn {
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #219653);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--accent), #c0392b);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-buttons .btn {
            flex: 1;
        }

        /* Stats Section */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }

        .stat-card {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 20px;
            text-align: center;
            color: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 1.1rem;
            opacity: 0.8;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .medicines-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }
            
            .page-header h2 {
                font-size: 2.2rem;
            }
            
            .modal-box {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="page-header">
            <h2><i class="fas fa-pills"></i> Medicine Tracker</h2>
            <p>Select a medicine to log when you've taken it</p>
            <div class="user-welcome">
                <i class="fas fa-user-circle"></i> Welcome, <?php echo htmlspecialchars($user_name); ?>
            </div>
        </div>

       

        <!-- Medicines Grid -->
        <?php if ($meds->num_rows > 0): ?>
            <div class="medicines-grid">
                <?php while($m = $meds->fetch_assoc()): ?>
                    <button class="medicine-card" onclick="openModal('<?= $m['medicine_name'] ?>', '<?= $m['image'] ?>')">
                        <img src="<?= $m['image'] ?>" alt="<?= $m['medicine_name'] ?>" class="medicine-image">
                        <div class="medicine-name"><?= $m['medicine_name']; ?></div>
                        <div class="medicine-action">
                            <i class="fas fa-plus-circle"></i> Log Dose
                        </div>
                    </button>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-pills"></i>
                <h3>No Medicines Available</h3>
                <p>Medicines will appear here once they are added to the system.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal-bg" id="takeModal">
        <div class="modal-box">
            <div class="modal-header">
                <h4 id="modal-title"></h4>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group text-center">
                    <img id="modal-image" src="" alt="" class="medicine-image" style="margin: 0 auto 20px;">
                </div>
                <form method="post">
                    <input type="hidden" name="medicine_name" id="medicine_input">
                    
                    <div class="form-group">
                        <label class="form-label">Select Date & Time</label>
                        <input type="datetime-local" name="take_time" class="form-control" required 
                               value="<?php echo date('Y-m-d\TH:i'); ?>">
                    </div>
                    
                    <div class="action-buttons">
                        <button type="button" class="btn btn-danger" onclick="closeModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" name="save_log" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
   



   
   
    <script>
    
        // Update stats
        document.addEventListener('DOMContentLoaded', function() {
            const medicineCards = document.querySelectorAll('.medicine-card');
            document.getElementById('total-medicines').textContent = medicineCards.length;
            
            // Simulate some stats for demo
            document.getElementById('today-logs').textContent = Math.floor(Math.random() * 5);
            document.getElementById('week-logs').textContent = Math.floor(Math.random() * 20);
        });

        function openModal(name, image) {
            document.getElementById("modal-title").innerText = "Log: " + name;
            document.getElementById("medicine_input").value = name;
            document.getElementById("modal-image").src = image;
            document.getElementById("takeModal").style.display = "flex";
            
            // Set default time to current time
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            document.querySelector('input[name="take_time"]').value = localDateTime;
        }

        function closeModal() {
            document.getElementById("takeModal").style.display = "none";
        }

        // Close modal when clicking outside
        document.getElementById('takeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Add keyboard support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>


