<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: login.php');
    exit;
}
?>

<h1>Hello!</h1>
<p>Hier gehts zum <a href='./../index.php?site=logout'>Logout</a> oder zum <a href="./../index.php?site=edit">bearbeiten</a> der Nutzerdaten. Oder siehe die
    <a href="./../index.php?site=skillmatrix">Skills </a>ein.</p>