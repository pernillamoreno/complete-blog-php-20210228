<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/head_section.php'); ?>

<!-- Hämta alla admins inlägg från DB -->
<?php $posts = getAllPosts(); ?>
	<title>Admin | Hantera Inlägg</title>
</head>
<body>
	<!-- admins navigering -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- Meny på vänster sida -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Visa DB:s poster -->
		<div class="table-div"  style="width: 80%;">
			<!-- Visa meddelande -->
			<?php include(ROOT_PATH . '/includes/messages.php') ?>

			<?php if (empty($posts)): ?>
				<h1 style="text-align: center; margin-top: 20px;">Hoppsan! Inga poster i databasen.</h1>
			<?php else: ?>
				<table class="table">
						<thead>
						<th>Nr:</th>
						<th>Författare</th>
						<th>Titel</th>
						<th>Visningar</th>
						<!-- Endast admin kan publicera ta bort post(er) -->
						<?php if ($_SESSION['user']['role'] == "Admin"): ?>
							<th><small>Publicera</small></th>
						<?php endif ?>
						<th><small>Redigera</small></th>
						<th><small>Radera</small></th>
					</thead>
					<tbody>
					<?php foreach ($posts as $key => $post): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td><?php echo $post['author']; ?></td>
							<td>
								<a 	target="_blank"
								href="<?php echo BASE_URL . 'single_post.php?post-slug=' . $post['slug'] ?>">
									<?php echo $post['title']; ?>	
								</a>
							</td>
							<td><?php echo $post['views']; ?></td>
							
							<!-- Endast admin kan publicera och/eller ta bort post(er) -->
							<?php if ($_SESSION['user']['role'] == "Admin" ): ?>
								<td>
								<?php if ($post['published'] == true): ?>
									<a class="fa fa-check btn unpublish"
										href="posts.php?unpublish=<?php echo $post['id'] ?>">
									</a>
								<?php else: ?>
									<a class="fa fa-times btn publish"
										href="posts.php?publish=<?php echo $post['id'] ?>">
									</a>
								<?php endif ?>
								</td>
							<?php endif ?>

							<td>
								<a class="fa fa-pencil btn edit"
									href="create_post.php?edit-post=<?php echo $post['id'] ?>">
								</a>
							</td>
							<td>
								<a  class="fa fa-trash btn delete" 
									href="create_post.php?delete-post=<?php echo $post['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Visa DB:s poster -->
	</div>
</body>
</html>