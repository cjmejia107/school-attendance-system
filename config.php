<?php
$conn = new mysqli("localhost", "root", "", "school_attendance");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>