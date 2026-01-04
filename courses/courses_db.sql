CREATE DATABASE courses_db;
USE courses_db;

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    max_students INT,
    status VARCHAR(20),
    image LONGBLOB
);
