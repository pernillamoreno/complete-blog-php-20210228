<?php 
	// Variabeldeklaration
	$username = "";
	$email    = "";
	$errors = array(); 

	// Registrera användare
	if (isset($_POST['reg_user'])) {
		// Hämtar formulärets alla värden.
		$username = esc($_POST['username']);
		$email = esc($_POST['email']);
		$password_1 = esc($_POST['password_1']);
		$password_2 = esc($_POST['password_2']);

		// Datavalidering: Säkerställer att formuläret är korrekt ifyllt.
		if (empty($username)) {  array_push($errors, "Uhmm... Ditt användarnamn behövs!"); }
		if (empty($email)) { array_push($errors, "Oppsss.. Mejl saknas!"); }
		if (empty($password_1)) { array_push($errors, "Ojoj - Du glömde visst Lösenordet"); }
		if ($password_1 != $password_2) { array_push($errors, "Lösenorden matchar inte!");}

		// Säkerställer att ingen användare har registerats mer än en gång. 
		$user_check_query = "SELECT * FROM users WHERE username='$username' 
								OR email='$email' LIMIT 1";

		$result = mysqli_query($conn, $user_check_query);
		$user = mysqli_fetch_assoc($result);

		if ($user) { // Om användare och/eller mejl redan finns.
			if ($user['username'] === $username) {
			  array_push($errors, "Användarnamnet finns redan!");
			}
			if ($user['email'] === $email) {
			  array_push($errors, "Mejlet finns redan!");
			}
		}
		// Användaren registreras om/när formuläret är felfritt.
		if (count($errors) == 0) {
			$password = md5($password_1);// Lösenord krypteras innan det sparas till databasen.
			$query = "INSERT INTO users (username, email, password, created_at, updated_at) 
					  VALUES('$username', '$email', '$password', now(), now())";
			mysqli_query($conn, $query);

			// Hämtar den skapade användarens id.
			$reg_user_id = mysqli_insert_id($conn); 

			// Lägger inloggad användare till sessionen.
			$_SESSION['user'] = getUserById($reg_user_id);

			// Om inloggad är admin, dirigeras denne till administrationen.
			if ( in_array($_SESSION['user']['role'], ["Admin", "Författare"])) {
				$_SESSION['message'] = "You are now logged in";
				
				header('location: ' . BASE_URL . 'admin/dashboard.php');
				exit(0);
			} else {
				$_SESSION['message'] = "Du är nu inloggad!";
				// Dirigeras till allmänn plats.
				header('location: index.php');				
				exit(0);
			}
		}
	}

	// Logga in användaren.
	if (isset($_POST['login_btn'])) {
		$username = esc($_POST['username']);
		$password = esc($_POST['password']);

		if (empty($username)) { array_push($errors, "Användarnamn saknas!"); }
		if (empty($password)) { array_push($errors, "Lösenord saknas"); }
		if (empty($errors)) {
			$password = md5($password); // Lösenord krypteras
			$sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";

			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				// Hämtar den skapade användarens id.
				$reg_user_id = mysqli_fetch_assoc($result)['id']; 

				// Lägger inloggad användare till sessionen.
				$_SESSION['user'] = getUserById($reg_user_id); 

				// Om inloggad är admin, dirigeras denne till administrationen.
				if ( in_array($_SESSION['user']['role'], ["Admin", "Författare"])) {
					$_SESSION['message'] = "Du är nu inloggad!";
					
					header('location: ' . BASE_URL . '/admin/dashboard.php');
					exit(0);
				} else {
					$_SESSION['message'] = "Du är nu inloggad!";
					// Dirigeras till allmänn plats.
					header('location: index.php');				
					exit(0);
				}
			} else {
				array_push($errors, 'Felaktiga inloggningsdata');
			}
		}
	}
	// Escapar värde från formuläret.
	function esc(String $value)
	{	
		// Startar funktionen global DB-anslutning.
		global $conn;

		$val = trim($value); // Tar bort tomt utrymme runt strängen.
		$val = mysqli_real_escape_string($conn, $value);

		return $val;
	}
	// Hämtar användarinfo från användar-id.
	function getUserById($id)
	{
		global $conn;
		$sql = "SELECT * FROM users WHERE id=$id LIMIT 1";

		$result = mysqli_query($conn, $sql);
		$user = mysqli_fetch_assoc($result);

		// Returnerar användare i ett strängvärdesformat: 
		// ['id'=>1 'username' => 'Användare', 'email'=>'användare@användare.se', 'password'=> 'användarlösen']
		return $user; 
	}
?>