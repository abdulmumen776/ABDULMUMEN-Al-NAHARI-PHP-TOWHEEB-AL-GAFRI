<?php
$conn = new mysqli("localhost", "root", "", "courses_db");
if ($conn->connect_error) die("DB Error");

$courses = [
    ["PHP Fundamentals", "Learn PHP from scratch", 40, "Active", "image.png"],
    ["MySQL Database", "Design & SQL queries", 35, "Upcoming", "image1.png"],
    ["Advanced JavaScript", "DOM & ES6+", 50, "Inactive", "image2.png"],
    ["Flutter Mobile Apps", "Cross-platform mobile apps", 30, "Inactive", "image3.png"],
    ["Python Programming", "Python for backend & AI", 45, "Active", "image4.png"],
    ["React Development", "Modern Frontend with React", 40, "Upcoming", "image5.png"]
];
$stmt = $conn->prepare(
    "INSERT INTO courses (title,description,max_students,status,image)
     VALUES (?,?,?,?,?)"
);
foreach ($courses as $c) {
    $img = file_get_contents(__DIR__ . "/" . $c[4]);
    $stmt->bind_param("ssiss", $c[0], $c[1], $c[2], $c[3], $img);
    $stmt->send_long_data(4, $img);
    $stmt->execute();
}
echo "✅ 6 Courses Inserted Successfully";