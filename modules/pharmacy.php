<?php
// Pharmacy Dashboard Sample Page
session_start();
// TODO: Add authentication check
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Dashboard - Cliniva CRM</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="main-header">
        <h1>Pharmacy Dashboard</h1>
        <nav class="main-nav">
            <ul>
                <li><a href="pharmacy.php">Dashboard</a></li>
                <li><a href="../index.html">Home</a></li>
                <li><a href="#">Stock</a></li>
                <li><a href="#">Medicines</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h2>Welcome, Pharmacy!</h2>
            <p>This is your dashboard. Manage stock, medicines, and profile.</p>
        </section>
    </main>
</body>
</html>