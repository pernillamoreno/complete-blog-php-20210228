<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/admin_functions.php'); ?>
<?php 
	// Hämtar alla admin från DB.
	$admins = getAdminUsers();
	$roles = ['Admin', 'Author'];				
?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
	<title>Admin | Hantera admin</title>
</head>
<body>
	<!-- admin-navigation -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
	<div class="container content">
		<!-- Vänster sidas meny. -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>
		<!-- Mellanformulär - för att skapa och redigera. -->
		<div class="action">
			<h1 class="page-title">Skapa/Redigera Admin</h1>

			<form method="post" action="<?php echo BASE_URL . 'admin/users.php'; ?>" >

				<!-- Validering. -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>

				<!-- Vid redigering av användare krävs id för identifiering. -->
				<?php if ($isEditingUser === true): ?>
					<input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
				<?php endif ?>

				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Användarnamn">
				<input type="email" name="email" value="<?php echo $email ?>" placeholder="Mejl">
				<input type="password" name="password" placeholder="Lösenord">
				<input type="password" name="passwordConfirmation" placeholder="Bekräfta Lösenord">
				<select name="role">
					<option value="" selected disabled>Tilldela Roll</option>
					<?php foreach ($roles as $key => $role): ?>
						<option value="<?php echo $role; ?>"><?php echo $role; ?></option>
					<?php endforeach ?>
				</select>

				<!-- Vid redigering av användare, visas uppdateringsknappen istället för skapaknappen. -->
				<?php if ($isEditingUser === true): ?> 
					<button type="submit" class="btn" name="update_admin">UPPDATERA</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_admin">Skapa Admin/Author</button>
				<?php endif ?>
			</form>
		</div>
		<!-- // Mellanformulär - för att skapa och redigera. -->

		<!-- Visar poster från DB -->
		<div class="table-div">
			<!-- Visar meddelande. -->
			<?php include(ROOT_PATH . '/includes/messages.php') ?>

			<?php if (empty($admins)): ?>
				<h1>Inga Admin i databasen.</h1>
			<?php else: ?>
				<table class="table">
					<thead>
						<th>Nr:</th>
						<th>Admin</th>
						<th>Roll</th>
						<th colspan="2">Åtgärd</th>
					</thead>
					<tbody>
					<?php foreach ($admins as $key => $admin): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td>
								<?php echo $admin['username']; ?>, &nbsp;
								<?php echo $admin['email']; ?>	
							</td>
							<td><?php echo $admin['role']; ?></td>
							<td>
								<a class="fa fa-pencil btn edit"
									href="users.php?edit-admin=<?php echo $admin['id'] ?>">
								</a>
							</td>
							<td>
								<a class="fa fa-trash btn delete" 
								    href="users.php?delete-admin=<?php echo $admin['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
	</div>
</body>
</html>