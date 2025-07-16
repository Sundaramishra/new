<?php
// Admin Dashboard Sample Page
session_start();
// TODO: Add authentication check
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cliniva CRM</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="main-header">
        <h1>Admin Dashboard</h1>
        <nav class="main-nav">
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="../index.html">Home</a></li>
                <li><a href="#">Doctors</a></li>
                <li><a href="#">Patients</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h2>Welcome, Admin!</h2>
            <p>This is your dashboard. Manage doctors, patients, appointments, and more.</p>
        </section>
    </main>
</body>
</html>