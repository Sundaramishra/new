<?php
// Doctor Dashboard Sample Page
session_start();
// TODO: Add authentication check
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Cliniva CRM</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="main-header">
        <h1>Doctor Dashboard</h1>
        <nav class="main-nav">
            <ul>
                <li><a href="doctor.php">Dashboard</a></li>
                <li><a href="../index.html">Home</a></li>
                <li><a href="#">My Patients</a></li>
                <li><a href="#">Appointments</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h2>Welcome, Doctor!</h2>
            <p>This is your dashboard. View and manage your patients and appointments.</p>
        </section>
    </main>
</body>
</html>