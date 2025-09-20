
CREATE DATABASE IF NOT EXISTS face_attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE face_attendance;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  photo VARCHAR(255) NOT NULL,
  ahash VARCHAR(64) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  status VARCHAR(30) DEFAULT 'Present',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);

-- Default admin (username: admin, password: admin)
INSERT INTO admins (username, password) VALUES ('admin', MD5('admin'));


-- Add username, password, role to users table (if re-creating DB use the full CREATE above which already has fields added)
ALTER TABLE users 
  ADD COLUMN username VARCHAR(100) DEFAULT NULL,
  ADD COLUMN password VARCHAR(255) DEFAULT NULL,
  ADD COLUMN role VARCHAR(20) DEFAULT 'student';

-- Create a sample student (if needed)
INSERT INTO users (name, photo, ahash, username, password, role) VALUES ('Sample Student', '', '', 'student1', 'pass123', 'student');