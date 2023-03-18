<?php  include('config.php'); ?>
<!-- Källkod för hantering av registrering och inloggning. -->
<?php  include('includes/registration_login.php'); ?>

<?php include('includes/head_section.php'); ?>

<title>Bloggen | Registrering </title>
</head>
<body>
<div class="container">
	<!-- Navigering -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Navigering -->

	<div style="width: 40%; margin: 20px auto;">
		<form method="post" action="register.php" >
			<h2>Registrera Dig</h2>
			<?php include(ROOT_PATH . '/includes/errors.php') ?>
			<input  type="text" name="username" value="<?php echo $username; ?>"  placeholder="Användarnamn">
			<input type="email" name="email" value="<?php echo $email ?>" placeholder="Mejl">
			<input type="password" name="password_1" placeholder="Lösenord">
			<input type="password" name="password_2" placeholder="Bekräfta Lösenord">
			<button type="submit" class="btn" name="reg_user">Sänd</button>
			<p>
				Redan Medlem? <a href="login.php"> Logga In</a>
			</p>
		</form>
	</div>
</div>
<!-- // Kontainer -->
<!-- Fot -->
	<?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Fot -->