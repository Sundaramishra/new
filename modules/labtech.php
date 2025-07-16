<?php
// Lab Tech Dashboard Sample Page
session_start();
// TODO: Add authentication check
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Tech Dashboard - Cliniva CRM</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="main-header">
        <h1>Lab Tech Dashboard</h1>
        <nav class="main-nav">
            <ul>
                <li><a href="labtech.php">Dashboard</a></li>
                <li><a href="../index.html">Home</a></li>
                <li><a href="#">Assigned Tests</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="hero">
            <h2>Welcome, Lab Tech!</h2>
            <p>This is your dashboard. View and upload assigned tests.</p>
        </section>
    </main>
</body>
</html>