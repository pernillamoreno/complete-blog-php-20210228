<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<!-- Hämta all ämnen från DB. -->
<?php $topics = getAllTopics();	?>
	<title>Admin | Hantera Ämnen</title>
</head>
<body>
	<!-- admin navigering. -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
	<div class="container content">
		<!-- Vänster sidas meny. -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- Mellanformulär - för att skapa och redigera. -->
		<div class="action">
			<h1 class="page-title">Skapa/Redigera</h1>
			<form method="post" action="<?php echo BASE_URL . 'admin/topics.php'; ?>" >
				<!-- Validering av formulärfel. -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>
				<!-- Vid redigering, krävs id för att identifiera ämnet. -->
				<?php if ($isEditingTopic === true): ?>
					<input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
				<?php endif ?>
				<input type="text" name="topic_name" value="<?php echo $topic_name; ?>" placeholder="Ämnen">
				<!-- Vid redigering visas uppdateringsknappen istället för skapaknappen. -->
				<?php if ($isEditingTopic === true): ?> 
					<button type="submit" class="btn" name="update_topic">UPPDATERA</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_topic">Spara Ämne</button>
				<?php endif ?>
			</form>
		</div>
		<!-- // Mellanformulär - för att skapa och redigera. -->

		<!-- Visar DB-poster.-->
		<div class="table-div">
			<!-- Visar meddelande. -->
			<?php include(ROOT_PATH . '/includes/messages.php') ?>
			<?php if (empty($topics)): ?>
				<h1>Inga ämnen i databasen.</h1>
			<?php else: ?>
				<table class="table">
					<thead>
						<th>Nr:</th>
						<th>Ämnesnamn</th>
						<th colspan="2">Åtgärd</th>
					</thead>
					<tbody>
					<?php foreach ($topics as $key => $topic): ?>
						<tr>
							<td><?php echo $key + 1; ?></td>
							<td><?php echo $topic['name']; ?></td>
							<td>
								<a class="fa fa-pencil btn edit"
									href="topics.php?edit-topic=<?php echo $topic['id'] ?>">
								</a>
							</td>
							<td>
								<a class="fa fa-trash btn delete"								
									href="topics.php?delete-topic=<?php echo $topic['id'] ?>">
								</a>
							</td>
						</tr>
					<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Visar DB-poster. -->
	</div>
</body>
</html>