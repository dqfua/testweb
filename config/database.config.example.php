<?php
// ODBC database connection settings for MySQL 2014 SP2

$dsn = 'mysql:host=localhost;dbname=testdb'; // Data Source Name
$username = 'your_username'; // Database username
$password = 'your_password'; // Database password

try {
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connected successfully';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>