<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<!-- Hämta alla ämnen -->
<?php $topics = getAllTopics();	?>
	<title>Admin | Skapa Inlägg</title>
</head>
<body>
	<!-- admin navigation. -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- Vänster sidas meny. -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Mellanformulär - för stt skapa ooch redigera.  -->
		<div class="action create-post-div">
			<h1 class="page-title">Skapa/Redigera Inlägg</h1>
			<form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_post.php'; ?>" >
				<!-- Validering av formulärfel. -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>

				<!-- Vid redigering av inlägg krävs dess id för identifiering. -->
				<?php if ($isEditingPost === true): ?>
					<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
				<?php endif ?>

				<input type="text" name="title" value="<?php echo $title; ?>" placeholder="Titel">
				<label style="float: left; margin: 5px auto 5px;">Förvald Bild</label>
				<input type="file" name="featured_image" >
				<textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>
				<select name="topic_id">
					<option value="" selected disabled>Välj Ämne</option>
					<?php foreach ($topics as $topic): ?>
						<option value="<?php echo $topic['id']; ?>">
							<?php echo $topic['name']; ?>
						</option>
					<?php endforeach ?>
				</select>
				
				<!-- Endast admin kan se publiceringsfältet -->
				<?php if ($_SESSION['user']['role'] == "Admin"): ?>
					<!-- Visa kryssruta beroende på om inlägget publicerats eller inte. -->
					<?php if ($published == true): ?>
						<label for="publish">
							Publicera
							<input type="checkbox" value="1" name="publish" checked="checked">&nbsp;
						</label>
					<?php else: ?>
						<label for="publish">
							Publicera
							<input type="checkbox" value="1" name="publish">&nbsp;
						</label>
					<?php endif ?>
				<?php endif ?>
				
				<!-- Vid redigering av inlägg visas uppdateringsknappen istället för skapa-knappen. -->
				<?php if ($isEditingPost === true): ?> 
					<button type="submit" class="btn" name="update_post">UPPDATERA</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_post">Spara Inlägg</button>
				<?php endif ?>

			</form>
		</div>
		<!-- // Mellanformulär - för att skapa och redigera. -->
	</div>
</body>
</html>

<script>
	CKEDITOR.replace('body');
</script>