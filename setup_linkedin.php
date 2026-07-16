<?php
$mysqli = new mysqli("localhost", "root", "", "samhudi");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1. Tambah kolom di tabel users
$alter_sql = "ALTER TABLE `users` 
    ADD COLUMN `open_to_work` TINYINT(1) DEFAULT 0,
    ADD COLUMN `work_role` VARCHAR(100) DEFAULT NULL,
    ADD COLUMN `is_fresh_graduate` TINYINT(1) DEFAULT 0;";

if ($mysqli->query($alter_sql) === TRUE) {
    echo "Columns added to users table successfully.\n";
} else {
    // Abaikan error jika kolom sudah ada
    if ($mysqli->errno == 1060) {
        echo "Columns already exist in users table.\n";
    } else {
        echo "Error altering users table: " . $mysqli->error . "\n";
    }
}

// 2. Buat tabel job_listings
$create_job_sql = "CREATE TABLE IF NOT EXISTS `job_listings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `publisher_name` VARCHAR(100) NOT NULL,
  `company_name` VARCHAR(100) NOT NULL,
  `job_title` VARCHAR(100) NOT NULL,
  `salary` VARCHAR(50) DEFAULT NULL,
  `job_type` VARCHAR(50) DEFAULT NULL,
  `working_hours` VARCHAR(50) DEFAULT NULL,
  `location` VARCHAR(100) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($mysqli->query($create_job_sql) === TRUE) {
    echo "Table job_listings created successfully.\n";
} else {
    echo "Error creating table job_listings: " . $mysqli->error . "\n";
}

$mysqli->close();
