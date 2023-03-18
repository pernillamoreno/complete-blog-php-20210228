<?php 
// admin-variabler
$admin_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
// Generella variabler
$errors = [];

// Ämnesvariabler
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";

/* - - - - - - - - - - 
-  adminåtgärder
- - - - - - - - - - -*/
// Om/när admin klickar Skapa admin
if (isset($_POST['create_admin'])) {
	createAdmin($_POST);
}
// Om/när admin klickar Redigera admin
if (isset($_GET['edit-admin'])) {
	$isEditingUser = true;
	$admin_id = $_GET['edit-admin'];
	editAdmin($admin_id);
}
// Om/när admin klickar Uppdatera admin
if (isset($_POST['update_admin'])) {
	updateAdmin($_POST);
}
// Om/när admin klickar Radera admin
if (isset($_GET['delete-admin'])) {
	$admin_id = $_GET['delete-admin'];
	deleteAdmin($admin_id);
}

/* - - - - - - - - - - 
-  Ämnesåtgärder
- - - - - - - - - - -*/
// Om/när användaren klickar Skapa ämne
if (isset($_POST['create_topic'])) { createTopic($_POST); }
// Om/när användaren klickar Redigera ämne
if (isset($_GET['edit-topic'])) {
	$isEditingTopic = true;
	$topic_id = $_GET['edit-topic'];
	editTopic($topic_id);
}
// Om/när användaren klickar Uppdatera ämne
if (isset($_POST['update_topic'])) {
	updateTopic($_POST);
}
//  Om/när användaren klickar Radera ämne
if (isset($_GET['delete-topic'])) {
	$topic_id = $_GET['delete-topic'];
	deleteTopic($topic_id);
}


/* - - - - - - - - - - - -
- adminfunktioner
- - - - - - - - - - - - -*/
/* * * * * * * * * * * * * * * * * * * * * * *
* - Ta emot ny admindata från formulär
* - Skapa ny admin
* - Returnerar alla admin med sina roller 
* * * * * * * * * * * * * * * * * * * * * * */
function createAdmin($request_values){
	global $conn, $errors, $role, $username, $email;
	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);

	if(isset($request_values['role'])){
		$role = esc($request_values['role']);
	}
	// Datavalidering: Säkerställer att formuläret är korrekt ifyllt
	if (empty($username)) { array_push($errors, "Uhmm... Ett användarnamn behövs"); }
	if (empty($email)) { array_push($errors, "Oppsss.. Mejl saknas"); }
	if (empty($role)) { array_push($errors, "Hoppsan! En Roll måste admin ha");}
	if (empty($password)) { array_push($errors, "Ojoj - Du glömde visst Lösenordet"); }
	if ($password != $passwordConfirmation) { array_push($errors, "Lösenorden matchar visst inte"); }
	// Säkrar att ingen admin sparas flera gånger 
	// Mejl och användarnamn måste vara  unika								 
	$user_check_query = "SELECT * FROM users WHERE username='$username' 
							OR email='$email' LIMIT 1";
	$result = mysqli_query($conn, $user_check_query);
	$user = mysqli_fetch_assoc($result);
	if ($user) { // Om användare och/eller mejl redan finns
		if ($user['username'] === $username) {
		  array_push($errors, "Användarnamnet finns redan");
		}

		if ($user['email'] === $email) {
		  array_push($errors, "Mejlet finns redan");
		}
	}
	// Registrerar admin om formuläret är felfritt
	if (count($errors) == 0) {
		$password = md5($password);//Lösenord krypteras innan det sparas till databasen
		$query = "INSERT INTO users (username, email, role, password, created_at, updated_at) 
				  VALUES('$username', '$email', '$role', '$password', now(), now())";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Grattis! Admin har skapats";
		header('location: users.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - Sätter admin-id som parameter
* - Hämtar admin från databasen
* - Lägger adminfält till formuläret för redigering
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($admin_id)
{
	global $conn, $username, $role, $isEditingUser, $admin_id, $email;

	$sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$admin = mysqli_fetch_assoc($result);

	// Sätter formulärvärdena ($username och $email) för uppdatering
	$username = $admin['username'];
	$email = $admin['email'];
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Tar emot admin-förfrågan från formuläret och uppdaterar databasen
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateAdmin($request_values){
	global $conn, $errors, $role, $username, $isEditingUser, $admin_id, $email;
	// Hämtar de admin-värden, vilka ska uppdateras
	$admin_id = $request_values['admin_id'];
	// Sätter redigeringsstatus till falsk
	$isEditingUser = false;


	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);
	if(isset($request_values['role'])){
		$role = $request_values['role'];
	}
	// admin registreras om/när formuläret är felfritt
	if (count($errors) == 0) {
		//Lösenord krypteras innan det sparas till databasen av säkerhetsskäl
		$password = md5($password);

		$query = "UPDATE users SET username='$username', email='$email', role='$role', password='$password' WHERE id=$admin_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Grattis! Admin har uppdaterats";
		header('location: users.php');
		exit(0);
	}
}
// Radera admin 
function deleteAdmin($admin_id) {
	global $conn;
	$sql = "DELETE FROM users WHERE id=$admin_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "Grattis! Admin har raderats";
		header("location: users.php");
		exit(0);
	}
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Returnerar alla admin med sina respektive roller
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function getAdminUsers(){
	global $conn, $roles;
	$sql = "SELECT * FROM users WHERE role IS NOT NULL";
	$result = mysqli_query($conn, $sql);
	$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

	return $users;
}
/* * * * * * * * * * * * * * * * * * * * *
* - Escapar sänt formulärvärde följaktligen förhindras s.k. SQL-injection
* * * * * * * * * * * * * * * * * * * * * */
function esc(String $value){
	// Startar funktionen global DB-anslutning
	global $conn;
	// Tar bort tomt utrymme runt sträng
	$val = trim($value); 
	$val = mysqli_real_escape_string($conn, $value);
	return $val;
}
// Skapar en slägga vilken returnerar 'Titta, En Sträng' som 'titta-en-sträng'
function makeSlug(String $string){
	$string = strtolower($string);
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return $slug;
}

/* - - - - - - - - - - 
-  Ämnesfunktioner
- - - - - - - - - - -*/
// Hämtar alla Ämnen från DB
function getAllTopics() {
	global $conn;
	$sql = "SELECT * FROM topics";
	$result = mysqli_query($conn, $sql);
	$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $topics;
}
function createTopic($request_values){
	global $conn, $errors, $topic_name;
	$topic_name = esc($request_values['topic_name']);
	// Skapar slägga: om ämnet är "Livets Bästa Sidor", returneras "livets-bästa-sidor" som slägga
	$topic_slug = makeSlug($topic_name);
	// Validering
	if (empty($topic_name)) { 
		array_push($errors, "Ämnesrubrik saknas"); 
	}
	// Säkrar att inget ämne sparas flera gånger. 
	$topic_check_query = "SELECT * FROM topics WHERE slug='$topic_slug' LIMIT 1";
	$result = mysqli_query($conn, $topic_check_query);
	if (mysqli_num_rows($result) > 0) { // Om ämnet finns
		array_push($errors, "Ämnet finns redan");
	}
	// Ämnet registreras om/när formuläret är felfritt
	if (count($errors) == 0) {
		$query = "INSERT INTO topics (name, slug) 
				  VALUES('$topic_name', '$topic_slug')";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Grattis! Ämne har skapats";
		header('location: topics.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - Sätter Ämnes-id som parameter
* - Hämtar Ämne från databasen
* - Lägger Ämnesfält till formuläret för redigering
* * * * * * * * * * * * * * * * * * * * * */
function editTopic($topic_id) {
	global $conn, $topic_name, $isEditingTopic, $topic_id;
	$sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	// Hämtar Ämnesvärdet, ($topic_name) vilket ska uppdateras
	$topic_name = $topic['name'];
}
function updateTopic($request_values) {
	global $conn, $errors, $topic_name, $topic_id;
	$topic_name = esc($request_values['topic_name']);
	$topic_id = esc($request_values['topic_id']);
	// Skapar slägga: om ämnet är "Livets Bästa Sidor", returneras "livets-bästa-sidor" som slägga
	$topic_slug = makeSlug($topic_name);
	// validate form
	if (empty($topic_name)) { 
		array_push($errors, "Ämnesrubrik saknas"); 
	}
	// Ämnet uppdateras om/när formuläret är felfritt
	if (count($errors) == 0) {
		$query = "UPDATE topics SET name='$topic_name', slug='$topic_slug' WHERE id=$topic_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Grattis! Ämnet har uppdaterats";
		header('location: topics.php');
		exit(0);
	}
}
// Radera Ämne
function deleteTopic($topic_id) {
	global $conn;
	$sql = "DELETE FROM topics WHERE id=$topic_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "Grattis! Ämnet har raderats";
		header("location: topics.php");
		exit(0);
	}
}
?>