<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location='login';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];






if (isset($_POST['update'])) {

    $id = intval($_POST['id']);
    $name = $_POST['medicine_name'];

    if (!empty($_FILES['image']['name'])) {

        $imageName = $_FILES['image']['name'];
        $tmpName   = $_FILES['image']['tmp_name'];

        if (!is_dir("uploads")) {
            mkdir("uploads");
        }

        $newPath = "uploads/" . time() . "_" . $imageName;
        move_uploaded_file($tmpName, $newPath);

        $query = "UPDATE medicines SET medicine_name=?, image=? WHERE id=?";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("ssi", $name, $newPath, $id);

    } else {

        
        $query = "UPDATE medicines SET medicine_name=? WHERE id=?";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("si", $name, $id);
    }

    $stmt->execute();
    header("Location: manage_medicines");
    exit();
}


$editData = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $editData = $conn->query("SELECT * FROM medicines WHERE id=$id")->fetch_assoc();
}


$rows = $conn->query("SELECT * FROM medicines ");

?>

<!DOCTYPE html>

<html>
<head>
    <title>Manage Medicines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


<style>
    body {
        background: #eef1f7;
        font-family: "Segoe UI", sans-serif;
    }
    .page-container {
        max-width: 900px;
        margin: 40px auto;
    }
    table th {
        background: #0d6efd !important;
        color: white;
    }
    .circle-image {
        width:60px;
        height:60px;
        object-fit:cover;
        border-radius:50%;
    }
</style>


</head>
<body>

<?php include 'header.php'; ?>

<div class="page-container">

<h2 class="mb-4 text-center fw-bold">💊 Manage Medicines</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <?php if ($editData) { ?>

            <!-- Edit Form -->
            <h5 class="text-primary mb-3">✏ Edit Medicine</h5>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Medicine Name</label>
                    <input type="text" name="medicine_name" class="form-control" value="<?= $editData['medicine_name'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Change Image (optional)</label>
                    <input type="file" name="image" class="form-control">
                    <p class="mt-2"><b>Current:</b></p>
                    <img src="<?= $editData['image'] ?>" class="circle-image">
                </div>

                <button class="btn btn-success" name="update">Update</button>
                <a href="manage_medicines.php" class="btn btn-secondary ms-2">Cancel</a>
            </form>

        <?php } else { ?>

             <h5 class=" fw-bold text-center mb-2"> You can Edit Medicines</h5>

        <?php } ?>

    </div>
</div>


<!-- Table -->
<div class="card shadow-sm">
    <div class="card-body">

        <h5 class="mb-3 text-center">📋 Medicines List</h5>

        <table class="table table-bordered table-hover align-middle">
            <tr>
                <th width="60">ID</th>
                <th>Name</th>
                <th width="100">Image</th>
                <th width="160">Action</th>
            </tr>

            <?php while($m = $rows->fetch_assoc()) { ?>
            <tr>
                <td><?= $m['id'] ?></td>
                <td><?= htmlspecialchars($m['medicine_name']) ?></td>
                <td>
                    <img src="<?= htmlspecialchars($m['image']) ?>" class="circle-image">
                </td>
                <td>
                    <a href="?edit=<?= $m['id'] ?>" class="btn btn-warning">Edit</a>
                    
                </td>
            </tr>
            <?php } ?>
        </table>

    </div>
</div>


</div>

<?php include 'footer.php'; ?>

</body>
</html>
