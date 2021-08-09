<?php
	require_once "pdo.php";
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome to Database</title>

	<!-- Styles -->
	<!-- Bootstrap CSS -->
	<link href="melodi/HTML/css/bootstrap.min.css" rel="stylesheet">
	<!-- Animate CSS -->
	<link href="melodi/HTML/css/animate.min.css" rel="stylesheet">
	<!-- Basic stylesheet -->
	<link rel="stylesheet" href="melodi/HTML/css/owl.carousel.css">
	<!-- Font awesome CSS -->
	<link href="melodi/HTML/css/font-awesome.min.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="melodi/HTML/css/style.css" rel="stylesheet">
	<link href="melodi/HTML/css/style-color.css" rel="stylesheet">

<link rel="stylesheet" href="css/main.css">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Gruppo&display=swap" rel="stylesheet">

<script
src="https://code.jquery.com/jquery-3.2.1.js"
integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
crossorigin="anonymous"></script>

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"> -->

</head>
<body class="container" style="background:url('melodi/HTML/img/banner/b2.jpg')" id="font_id">



<?php

	if(!isset($_SESSION['name']))
	{

		echo "<header>

						<nav class='navbar navbar-fixed-top navbar-default' id='nav-color'>
							<div class='container'>
								<!-- Brand and toggle get grouped for better mobile display -->
								<div class='navbar-header'>
									<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
										<span class='sr-only'>Toggle navigation</span>
										<span class='icon-bar'></span>
										<span class='icon-bar'></span>
										<span class='icon-bar'></span>
									</button>

									</a>
								</div>

								<!-- Collect the nav links, forms, and other content for toggling -->
								<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
									<ul class='nav navbar-nav navbar-right'>
										<li><a href='login.php'>Log In</a></li>
										<li><a href='add_user.php'>Sign Up</a></li>

									</ul>
								</div><!-- /.navbar-collapse -->
							</div><!-- /.container-fluid -->
						</nav>
		</header>";

		echo '<br><br><br><h1 id="login_h1_id" class="center">Music Database</h1><br>';

		echo "<div class='container center'>";
		echo('<table style="font-size:25px" class="center container" border="0">'."\n");
		echo("<tr style='color:#3cab49; font-size:28px'><th class='center'>Artist Name</th><th class='center'>Album</th><th class='center'>Song</th><tr>");
		$stmt = $pdo->query("CALL display_all()");
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
    	echo "<tr id='login_label_id'><td><br>";
    	echo '<a style="color:#d2e0d1">';
    	echo(htmlentities($row['name']));
    	echo "</a>";
    	echo("</td><td>");
    	echo(htmlentities($row['title']));
		echo("</td><td>");
		echo '<a style="color:#d2e0d1" href="view.php?track_id='.$row['track_id'].'"><u>';
		echo(htmlentities($row['song']));
		echo "</u></a>";
		echo('</td><td>');
    	// echo('<a href="edit.php?track_id='.$row['track_id'].'">Edit</a> | ');
    	// echo('<a href="delete.php?track_id='.$row['track_id'].'">Delete</a>');
    	echo("</td></tr>\n");
    	// echo "</body></html>";
    	}
		echo("</table><br>");
		echo "</div>";

		// echo '<p class="center"><a href="login.php"><button class="btn btn-default" id="login_btn_id" type="button" name="button">Enter Website</button></a></p>';
	}
	else
	{
		echo "<header>

						<nav class='navbar navbar-fixed-top navbar-default' id='nav-color'>
							<div class='container'>
								<!-- Brand and toggle get grouped for better mobile display -->
								<div class='navbar-header'>
									<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#bs-example-navbar-collapse-1'>
										<span class='sr-only'>Toggle navigation</span>
										<span class='icon-bar'></span>
										<span class='icon-bar'></span>
										<span class='icon-bar'></span>
									</button>

									</a>
								</div>

								<!-- Collect the nav links, forms, and other content for toggling -->
								<div class='collapse navbar-collapse' id='bs-example-navbar-collapse-1'>
									<ul class='nav navbar-nav navbar-right'>
										<li><a href='add.php'>Add A New Track</a></li>
										<li><a href='logout.php'>Log Out</a></li>


									</ul>
								</div><!-- /.navbar-collapse -->
							</div><!-- /.container-fluid -->
						</nav>
		</header>";
		echo '<br><br><br><h1 id="login_h1_id" class="center">Music Database</h1><br>';
		if ( isset($_SESSION['error']) )
		{
    		echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    		unset($_SESSION['error']);
		}
		if ( isset($_SESSION['success']) )
		{
    		echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    		unset($_SESSION['success']);
		}

		$stmt = $pdo->prepare('SELECT * FROM users WHERE name="'.$_SESSION['name'].'"');
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$uid = htmlentities($row['user_id']);

		echo "<div class='container center'>";
		echo('<table style="font-size:25px" class="center container" border="0">'."\n");
		echo("<tr style='color:#3cab49; font-size:28px'><th class='center'>Artist Name</th><th class='center'>Album</th><th class='center'>Song</th><tr>");
		$stmt = $pdo->query("CALL index_display(".$uid.")");
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
    	echo "<tr id='login_label_id'><td><br>";
    	echo(htmlentities($row['name']));
    	echo("</td><td>");
    	echo(htmlentities($row['title']));
		echo("</td><td>");
		echo '<a style="color:#d2e0d1" href="view.php?track_id='.$row['track_id'].'"><u>';
		echo(htmlentities($row['song']));
		echo "</u></a>";
		echo('</td><td>');
    	// echo('<a href="edit.php?track_id='.$row['track_id'].'">Edit</a> | ');
    	// echo('<a href="delete.php?track_id='.$row['track_id'].'">Delete</a>');
    	echo("</td></tr>\n");
    	// echo "</body></html>";
    	}
		echo("</table><br>");
		echo "</div>";
		// echo('<a href="add.php">Add New Entry</a><br>');

	}

?>
</body>
</html>
