<?php
$conn = new mysqli("localhost","root","","courses_db");
$res = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Courses</title>
<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:Arial, Helvetica, sans-serif;
    background:#f4f6f9;
}

.container{
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:20px;
    padding:30px;
}
.card{
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 6px 15px rgba(0,0,0,.15);
    transition:.3s;
}
.card:hover{transform:translateY(-5px)}
.card img{
    width:100%;
    height:190px;
    object-fit:cover;
}

.card-body{
    padding:16px;
}

.card-body h3{
    margin:0 0 8px;
}
.status{
    float:right;
    font-size:12px;
    color:#fff;
    padding:4px 12px;
    border-radius:20px;
}
.Active{background:#2ecc71}
.Upcoming{background:#f39c12}

.Inactive{background:#e74c3c}

@media (max-width: 992px){
    .container{grid-template-columns:repeat(2,1fr);}
}
@media (max-width: 600px){
    .container{grid-template-columns:1fr;}
}
</style>
</head>
<body>
<div class="container">
<?php while($row=$res->fetch_assoc()): ?>
    <div class="card">
        <img src="image.php?id=<?= $row['id'] ?>">
        <div class="card-body">
            <span class="status <?= $row['status'] ?>">
                <?= $row['status'] ?>
            </span>
            <h3><?= $row['title'] ?></h3>
            <p><?= $row['description'] ?></p>
            <strong>Max Students: <?= $row['max_students'] ?></strong>
        </div>
    </div>
<?php endwhile; ?>
</div>
</body>
</html>