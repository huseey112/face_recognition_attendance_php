
# Face Attendance System (PHP, MySQL, HTML/CSS/JS, Bootstrap)

## Overview
Simple prototype of a web-based attendance system using PHP backend and MySQL database.
Face matching is implemented with a basic image average-hash (aHash) comparison in PHP (not deep learning).
This is a lightweight prototype for local testing using XAMPP/WAMP/LAMP.

## Features
- Register users with a photo (webcam capture or upload)
- Mark attendance by capturing webcam photo and matching against registered photos
- Admin dashboard to view users and attendance logs
- Export attendance as CSV
- Frontend uses Bootstrap and vanilla JavaScript

## Requirements
- PHP 7.4+ with GD extension enabled
- MySQL / MariaDB
- XAMPP / WAMP / LAMP stack for local testing
- Browser with camera support

## Setup
1. Place the project folder inside your web server document root (e.g., `C:/xampp/htdocs/face_attendance_php`).
2. Import the database SQL:
   - Open phpMyAdmin or MySQL client and run `database.sql` to create `face_attendance` DB and tables.
3. Edit `db.php` and set your MySQL username/password if needed.
4. Ensure `uploads/` folder is writable by the webserver.
5. Start Apache & MySQL and open `http://localhost/face_attendance_php/`

## Notes
- This prototype uses image hashing (aHash) to compare images. It's simple and not robust for production.
- For production use, integrate a proper face-recognition service or engine (Python OpenCV, commercial APIs).
- Use HTTPS and add authentication for admin pages in real deployments.
