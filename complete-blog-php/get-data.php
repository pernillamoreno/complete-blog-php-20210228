<?php include('db.php'); ?>

<?php
	if(isset($_POST['roll']) && $_POST['roll'] != '') {
		$roll = mysqli_real_escape_string($con, $_POST['roll']);
		$qry = "select * from users where id='".$roll."'";
		$res = mysqli_query($con, $qry);
		if(mysqli_num_rows($res) == 1) {
			$row = mysqli_fetch_assoc($res);
			$data['users'] = $row;
			$data['error'] = '';
		} else {
			$data['error'] = 'not_found';
		}
		echo json_encode($data);
	}
	
?>