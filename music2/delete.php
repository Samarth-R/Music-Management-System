<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['cancel'] ) )
{
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}

if ( isset($_POST['delete']) && isset($_POST['track_id']) )
{
    $sql = "DELETE FROM track WHERE track_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['track_id']));
    $_SESSION['success'] = 'Track deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['track_id']) )
{
  $_SESSION['error'] = "Missing track_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT song, link, track_id FROM track where track_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['track_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for track_id';
    header( 'Location: index.php' ) ;
    return;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Track Deletion</title>
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


</head>
<body class="center" id="font_id" style="background:url('melodi/HTML/img/banner/b2.jpg');color:white">

  <header>

          <nav id='nav-color' class="navbar navbar-fixed-top navbar-default">
            <div class="container">
              <!-- Brand and toggle get grouped for better mobile display -->
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <!-- logo area -->
                <!-- <a class="navbar-brand" href="#home"> -->
                  <!-- logo image -->
                  <!-- <img class="img-responsive" src="img/logo/logo.png" alt="" /> -->
                </a>
              </div>

              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="index.php">Back</a></li>
                  <li><a href="logout.php">Log Out</a></li>

                </ul>
              </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
          </nav>
  </header>
<br><br><br>

<h1 style="color:#dbbd23" class="center">Deleting Track</h1><br>
<p>Song name: <?= $row['song']?></p><br>
<p>Link: <?= $row['link']?></p><br>


<form method="post">
<input type="hidden" name="track_id" value="<?= $row['track_id'] ?>">
<input class="btn btn-warning" style="font-size:18px" type="submit" value="Delete" name="delete">
<input class="btn btn-default" style="font-size:18px" type="submit" value="Cancel" name="cancel">
</form>
</body>
</html>
