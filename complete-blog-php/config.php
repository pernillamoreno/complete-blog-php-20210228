<?php 
	session_start();
	// Anslut till databasen.
	$conn = mysqli_connect("localhost", "root", "", "complete-blog-php");

	if (!$conn) {
		die("Kunde inte ansluta till databasen: " . mysqli_connect_error());
	}
    // Definiera globala konstanter.
	define ('ROOT_PATH', realpath(dirname(__FILE__)));
	define('BASE_URL', 'http://localhost/complete-blog-php/');
?>