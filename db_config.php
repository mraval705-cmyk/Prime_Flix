<?php

$table ="
CREATE TABLE register (
    id INT(10) AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(50),
    email VARCHAR(50),
    phone VARCHAR(12),
    password VARCHAR(255),
    gender VARCHAR(10),
    profile_picture TEXT,
    role VARCHAR(20) DEFAULT 'user',
    status VARCHAR(20) DEFAULT 'Inactive',
    token VARCHAR(255)
);

";
?>