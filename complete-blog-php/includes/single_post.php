<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php 
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
	}
	$topics = getAllTopics();
?>
<?php include('includes/head_section.php'); ?>
<title> <?php echo $post['title'] ?> | Bloggen</title>
</head>
<body>
<div class="container">
	<!-- Navigering -->
		<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
	<!-- // Navigering -->
	
	<div class="content" >
		<!-- Sid-div -->
		<div class="post-wrapper">
			<!-- Full post-div -->
			<div class="full-post-div">
			<?php if ($post['published'] == false): ?>
				<h2 class="post-title">Sorry... Detta inlägg har inte publicerats</h2>
			<?php else: ?>
				<h2 class="post-title"><?php echo $post['title']; ?></h2>
				<div class="post-body-div">
					<?php echo html_entity_decode($post['body']); ?>
				</div>
			<?php endif ?>
			</div>
			<!-- // Full post-div -->
			
	
		</div>
		<!-- // Sid-div -->

		<!-- Inläggs sidofält -->
		<div class="post-sidebar">
			<div class="card">
				<div class="card-header">
					<h2>Ämnen</h2>
				</div>
				<div class="card-content">
					<?php foreach ($topics as $topic): ?>
						<a 
							href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>">
							<?php echo $topic['name']; ?>
						</a> 
					<?php endforeach ?>
				</div>
			</div>
		</div>
		<!-- // Inläggs sidofält -->
	</div>
</div>
<!-- // Innehåll - slut -->

<?php include( ROOT_PATH . '/includes/footer.php'); ?>