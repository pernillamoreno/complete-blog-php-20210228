<!-- Sida att logga in -->
<?php  include('config.php'); ?>
<?php  include('includes/registration_login.php'); ?>
<?php  include('includes/head_section.php'); ?>
	<title>Bloggen | Inloggning </title>
</head>
<body>
<div class="container">
	<!-- Navigering -->
	<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Navigering -->

	<div style="width: 40%; margin: 20px auto;">
		<form method="post" action="login.php" >
			<h2>Logga In</h2>
			<?php include(ROOT_PATH . '/includes/errors.php') ?>
			<input type="text" name="username" value="<?php echo $username; ?>" value="" placeholder="Användarnamn">
			<input type="password" name="password" placeholder="Lösenord">
			<button type="submit" class="btn" name="login_btn">Logga In</button>
			<p>
				Ännu inte medlem? <a href="register.php"> Registrera Dig</a>
			</p>
		</form>
	</div>
</div>
<!-- // Kontainer -->

<!-- Fot -->
	<?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Fot -->