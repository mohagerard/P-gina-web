<?php
session_start();
$host = "root"; /* Host name */
$user = "servergym"; /* User */
$password = "servergym123"; /* Password */
$dbname = "servergym"; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}
