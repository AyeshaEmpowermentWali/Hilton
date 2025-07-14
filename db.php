<?php
$host = 'localhost';
$dbname = 'dbvjbamev7kyt4';
$user = 'ugrj543f7lree';
$password = 'cgmq43woifko';

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection error: " . $e->getMessage());
}
?>
