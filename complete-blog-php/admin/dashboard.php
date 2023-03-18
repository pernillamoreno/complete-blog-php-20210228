<?php  include('../config.php'); ?>
	<?php include(ROOT_PATH . '/admin/admin_functions.php'); ?>
	<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
	<title>Admin | Panelen</title>
</head>
<body>
	<div class="header">
		<div class="logo">
			<a href="<?php echo BASE_URL .'admin/dashboard.php' ?>">
				<h1>BLOGGEN - Admin</h1>
			</a>
		</div>
		<?php if (isset($_SESSION['user'])): ?>
			<div class="user-info">
				<span><?php echo $_SESSION['user']['username'] ?></span> &nbsp; &nbsp; 
				<a href="<?php echo BASE_URL . '/logout.php'; ?>" class="logout-btn">Logga ut</a>
			</div>
		<?php endif ?>
	</div>
	<div class="container dashboard">
		<h1>Välkommen</h1>
		<div class="stats">
			<a href="users.php" class="first">
				<span>43</span> <br>
				<span>Nyligen registreade användare</span>
			</a>
			<a href="posts.php">
				<span>43</span> <br>
				<span>Publicerade inlägg</span>
			</a>
			<a>
				<span>43</span> <br>
				<span>Publicerade kommentarer</span>
			</a>
		</div>
		<br><br><br>
		<div class="buttons">
			<a href="users.php">Lägg till Användare</a>
			<a href="posts.php">Lägg till Inlägg</a>
		</div>
	</div>
</body>
</html>