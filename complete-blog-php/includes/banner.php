<?php if (isset($_SESSION['user']['username'])) { ?>
	<div class="logged_in_info">
		<span>Välkommen <?php echo $_SESSION['user']['username'] ?></span>
		|
		<span><a href="logout.php">Logga Ut</a></span>
	</div>
<?php }else{ ?>
	<div class="banner">
		<div class="welcome_msg">
			<h1>Dagens Inspiration</h1>
			<p> 
			    En dag kommer Ditt liv <br> 
			    att spelas upp inför <br>
				Dina ögon. Säkra att<br> 
			    det blir värt att se på! <br>
				<span>~ Gerard Way</span>
			</p>
			<a href="register.php" class="btn">Bli medlem!</a>
		</div>

		<div class="login_div">
			<form action="<?php echo BASE_URL . 'index.php'; ?>" method="post" >
				<h2>Logga In</h2>
				<div style="width: 60%; margin: 0px auto;">
					<?php include(ROOT_PATH . '/includes/errors.php') ?>
				</div>
				<input type="text" name="username" value="<?php echo $username; ?>" placeholder="Användarnamn">
				<input type="password" name="password"  placeholder="Lösenord"> 
				<button class="btn" type="submit" name="login_btn">Logga In</button>
			</form>
		</div>
	</div>
<?php } ?>