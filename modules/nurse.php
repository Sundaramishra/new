<?php
// Nurse Dashboard Sample Page
session_start();
// TODO: Add authentication check
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurse Dashboard - Cliniva CRM</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="main-header">
        <h1>Nurse Dashboard</h1>
        <nav class="main-nav">
            <ul>
                <li><a href="nurse.php">Dashboard</a></li>
                <li><a href="../index.html">Home</a></li>
                <li><a href="#">Assigned Patients</a></li>
                <li><a href="#">Vitals</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h2>Welcome, Nurse!</h2>
            <p>This is your dashboard. View assigned patients and update vitals.</p>
        </section>
    </main>
</body>
</html>