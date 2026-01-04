<?php
$conn = new mysqli("localhost","root","","courses_db");
$id = $_GET['id'];
$q = $conn->prepare("SELECT image FROM courses WHERE id=?");
$q->bind_param("i",$id);
$q->execute();
$q->bind_result($img);
$q->fetch();
header("Content-Type: image/png");
echo $img;