<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>How to get data from database in json format using ajax and php | Mitrajit's Tech Blog</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->

<script type="text/javascript" src="js/jquery-latest.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<style>
/*body { background-color: #0a5656 }*/
.post-title { font-size:20px; }
.mtb-margin-top { margin-top: 20px; }
.top-margin { border-bottom:2px solid #ccc; margin-bottom:20px; display:block; font-size:1.3rem; line-height:1.7rem;}
.btn-success {
	cursor:pointer;
}
table { display:none; }
</style>
</head>

<body>
    
	<div class="container-fluid mtb-margin-top">
		<div class="row">
			<div class="col-md-12">
				<h1 class="top-margin">Read the full article on -- <a href="" target="_blank" title="how to get data from database in json format using ajax and php">how to get data from database in json format using ajax and php</a> in <a href="http://www.mitrajit.com/" title="Mitrajit's Tech Blog">Mitrajit's Tech Blog</a></h1>
			</div>
		</div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-4"></div>
            <div class="col-xs-12 col-md-4 text-center">
                <div class="form-group">
                    <label>Roller : </label>
                    <input type="text" id="roll" class="text-center" name="roll" value="" size="3">
                    <button class="btn btn-success" id="btnOk">Detaljer om roller i databasen
					</button>
                </div>
            </div><!-- .col -->
            <div class="col-xs-12 col-md-4"></div>
        </div><!-- .row -->

        <div class="row">
            <div class="col-xs-12 col-md-4"></div>
            <div class="col-xs-12 col-md-4">
                <table class="table">
                    <tbody>
                        <tr><th>username</th><td id="username"></td></tr>
                        <tr><th>email</th><td id="email"></td></tr>
                        <tr><th>role</th><td id="role"></td></tr>
                    </tbody>
                </table>
                <h6 class="msg text-danger text-center"></h6>
            </div><!-- .col -->
            <div class="col-xs-12 col-md-4"></div>
        </div><!-- .row -->
    </div><!-- .container-fluid -->


    <script type="text/javascript">
        $(document).ready(function() {
            $('#btnOk').click(function() {
                var role = $("#role").val();

                if(role != '') {
                    $.ajax({
                        url: 'get-data.php',
                        type: 'post',
                        dataType: "json",
                        data: {role:role},
                        success: function(data) {
                            if(data.error == '') {
                                $(".msg").html("");
                                $("table").show();
                                $("#username").html(data.users.username);
                                $("#email").html(data.users.email);
                                $("#role").html(data.users.role);
                            } else {
                                $("table").hide();
                                $(".msg").html("No record found!");
                            }
                        }
                    });
                }   
            });
        });
    </script>
	
</body>
</html>
