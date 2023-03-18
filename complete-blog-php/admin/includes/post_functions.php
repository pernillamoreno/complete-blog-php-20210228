<?php 
// Inläggsvariabler.
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

/* - - - - - - - - - - 
-  Inläggsfunktioner
- - - - - - - - - - -*/
// Hämtar alla Inlägg från DB.
function getAllPosts()
{
	global $conn;
	
	// admin har tillgång till alla Inlägg.
	// Författaren har endast tillgång till egna Inlägg.
	if ($_SESSION['user']['role'] == "Admin") {
		$sql = "SELECT * FROM posts";
	} elseif ($_SESSION['user']['role'] == "Författare") {
		$user_id = $_SESSION['user']['id'];
		$sql = "SELECT * FROM posts WHERE user_id=$user_id";
	}
	$result = mysqli_query($conn, $sql);
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['author'] = getPostAuthorById($post['user_id']);
		array_push($final_posts, $post);
	}
	return $final_posts;
}
// Hämtar inläggets författar-/användarnamn.
function getPostAuthorById($user_id)
{
	global $conn;
	$sql = "SELECT username FROM users WHERE id=$user_id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		// Returnerar resultatet
		return mysqli_fetch_assoc($result)['username'];
	} else {
		return null;
	}
}

/* - - - - - - - - - - 
-  Inläggsåtgärder
- - - - - - - - - - -*/
// Om/när användare klickar på Skapa inlägg.
if (isset($_POST['create_post'])) { createPost($_POST); }
// Om/när användare klickar på Redigera inlägg.
if (isset($_GET['edit-post'])) {
	$isEditingPost = true;
	$post_id = $_GET['edit-post'];
	editPost($post_id);
}
// Om/när användare klickar på Uppdatera inlägg.
if (isset($_POST['update_post'])) {
	updatePost($_POST);
}
// Om/när användare klickar på Radera inlägg.
if (isset($_GET['delete-post'])) {
	$post_id = $_GET['delete-post'];
	deletePost($post_id);
}

/* - - - - - - - - - - 
-  Inläggsfunktioner
- - - - - - - - - - -*/
function createPost($request_values)
	{
		global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;
		$title = esc($request_values['title']);
		$body = htmlentities(esc($request_values['body']));
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		if (isset($request_values['publish'])) {
			$published = esc($request_values['publish']);
		}
		// Skapa slägga: om titeln är "The Storm Is Over", returnera "the-storm-is-over" som slägga.
		$post_slug = makeSlug($title);
		// Validerar formulär
		if (empty($title)) { array_push($errors, "Rubrik saknas."); }
		if (empty($body)) { array_push($errors, "Inlägg saknar innehåll"); }
		if (empty($topic_id)) { array_push($errors, "Ämne saknas."); }
		// Hämta Bildnamn.
	  	$featured_image = $_FILES['featured_image']['name'];
	  	if (empty($featured_image)) { array_push($errors, "Förvald Bild saknas."); }
	  	// Bildfilsmapp.
	  	$target = "../static/images/" . basename($featured_image);
	  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
	  		array_push($errors, "Bilden kunde inte laddas upp. V.g. kolla din servers filinställningar");
	  	}
		// Säkrar att inget inlägg sparas flera gånger. 
		$post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
		$result = mysqli_query($conn, $post_check_query);

		if (mysqli_num_rows($result) > 0) { // om inlägget redan finns.
		array_push($errors, "Ett inlägg med den rubriken finns redan.");
		}
		// Skapar inlägg om formuläret är felfritt.
		if (count($errors) == 0) {
			$query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) VALUES(1, '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
			if(mysqli_query($conn, $query)){ // Om inlägg skapas.
				$inserted_post_id = mysqli_insert_id($conn);
				// Skapa relation mellan Inlägg och Ämne.
				$sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
				mysqli_query($conn, $sql);

				$_SESSION['message'] = "Inlägget har skapats.";
				header('location: posts.php');
				exit(0);
			}
		}
	}

	/* * * * * * * * * * * * * * * * * * * * *
	* - Sätter inläggs-id som parameter
	* - Hämtar inlägget från databasen
	* - Lägger inläggsfält till formuläret för redigering.
	* * * * * * * * * * * * * * * * * * * * * */
	function editPost($role_id)
	{
		global $conn, $title, $post_slug, $body, $published, $isEditingPost, $post_id;
		$sql = "SELECT * FROM posts WHERE id=$role_id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$post = mysqli_fetch_assoc($result);
		// Sätter formulärvärden för uppdatering.
		$title = $post['title'];
		$body = $post['body'];
		$published = $post['published'];
	}

	function updatePost($request_values)
	{
		global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

		$title = esc($request_values['title']);
		$body = esc($request_values['body']);
		$post_id = esc($request_values['post_id']);
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		// Skapa slägga: om titeln är "The Storm Is Over", returnera "the-storm-is-over" som slägga.
		$post_slug = makeSlug($title);

		if (empty($title)) { array_push($errors, "Inlägg saknar rubrik."); }
		if (empty($body)) { array_push($errors, "Inlägg saknar innehåll."); }
		// Om ny förvald Bild tillhandahålls.
		if (isset($_POST['featured_image'])) {
			// Hämta bildnamn.
		  	$featured_image = $_FILES['featured_image']['name'];
		  	// Bildfilsmapp.
		  	$target = "../static/images/" . basename($featured_image);
		  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
		  		array_push($errors, "Bilden gick inte att ladda upp. V.g. kolla din servers filinställningar.");
		  	}
		}

		// Registrerar Ämne om formuläret är felfritt.
		if (count($errors) == 0) {
			$query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, image='$featured_image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
			// Fäster Ämne till Inlägg i tabellen: post_topic.
			if(mysqli_query($conn, $query)){ // Om Inlägg skapades.
				if (isset($topic_id)) {
					$inserted_post_id = mysqli_insert_id($conn);
					// Skapa relation mellan Inlägg och Ämne.
					$sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
					mysqli_query($conn, $sql);
					$_SESSION['message'] = "Inlägg har Skapats!";
					header('location: posts.php');
					exit(0);
				}
			}
			$_SESSION['message'] = "Inlägg har Uppdaterats!";
			header('location: posts.php');
			exit(0);
		}
	}
	// Raderar inlägg.
	function deletePost($post_id)
	{
		global $conn;
		$sql = "DELETE FROM posts WHERE id=$post_id";
		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "Inlägget har Raderats";
			header("location: posts.php");
			exit(0);
		}
	}
	
	// Om/när användaren klickar publicera inlägget.
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
	$message = "";
	if (isset($_GET['publish'])) {
		$message = "Inlägg har Publicerats!";
		$post_id = $_GET['publish'];
	} else if (isset($_GET['unpublish'])) {
		$message = "Inlägg har Dragits tillbaka.";
		$post_id = $_GET['unpublish'];
	}
	togglePublishPost($post_id, $message);
}

function togglePublishPost($post_id, $message)
{
	global $conn;
	$sql = "UPDATE posts SET published=!published WHERE id=$post_id";
	
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = $message;
		header("location: posts.php");
		exit(0);
	}
}
?>