
<?php
require_once "pdo.php";
require_once "util.php";
session_start();

if ( isset($_POST['cancel']) )
{
    header('Location: index.php');
    return;
}

if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE name="'.$_SESSION['name'].'"');
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = htmlentities($row['user_id']);

if(isset($_POST['artist_name']) && isset($_POST['language'])
       && isset($_POST['gender']) && isset($_POST['album_name'])
       && isset($_POST['no']) && isset($_POST['year'])
       && isset($_POST['genre']) && isset($_POST['label'])
       && isset($_POST['address']) && isset($_POST['producer']))
{
    $msg1=validateInput();
    if(is_string($msg1))
    {
        $_SESSION['error']=$msg1;
        header("Location: add.php");
        return;
    }

    $msg2= validateSong();
    if(is_string($msg2))
    {
        $_SESSION['error']=$msg2;
        header("Location: add.php");
        return;
    }

    $test=$pdo->query("Select * from label where label_name='".$_POST['label']."'");
    $num_rows=$test->rowCount();
    if($num_rows==0)
    {
        $stmt = $pdo->prepare('INSERT INTO label values (:lid, :ln, :addr, :prod)');
        $stmt->execute(array(
            ':lid' => null,
            ':ln' => $_POST['label'],
            ':addr' => $_POST['address'],
            ':prod' => $_POST['producer']
            ));
    }

    $test=$pdo->query("Select * from genre where genre_name='".$_POST['genre']."'");
    $num_rows=$test->rowCount();
    if($num_rows==0)
    {
        $stmt = $pdo->prepare('INSERT INTO genre values (:gid, :gn)');
        $stmt->execute(array(
                    ':gid' => null,
                    ':gn' => $_POST['genre']
                    ));
    }

    $stmt = $pdo->prepare('SELECT * from label where label_name=:ln');
    $stmt->execute(array(':ln' => $_POST['label']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $label_id=htmlentities($row['label_id']);

    $test=$pdo->query("Select * from artist where name='".$_POST['artist_name']."'");
    $num_rows=$test->rowCount();
    if($num_rows==0)
    {
        $stmt = $pdo->prepare('INSERT INTO artist values (:arid, :arn, :lang, :gen, :lid)');
        $stmt->execute(array(
            ':arid' => null,
            ':arn' => $_POST['artist_name'],
            ':lang' => $_POST['language'],
            ':gen' => $_POST['gender'],
            ':lid' => $label_id
            ));
    }

    $stmt = $pdo->prepare('SELECT * from artist where name=:ln');
    $stmt->execute(array(':ln' => $_POST['artist_name']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $artist_id=htmlentities($row['artist_id']);

    $test=$pdo->query("Select * from album where title='".$_POST['album_name']."'");
    $num_rows=$test->rowCount();
    if($num_rows==0)
    {
        $stmt = $pdo->prepare('INSERT INTO album values (:alid, :aln, :num, :year, :arid)');
        $stmt->execute(array(
            ':alid' => null,
            ':aln' => $_POST['album_name'],
            ':num' => $_POST['no'],
            ':year' => $_POST['year'],
            ':arid' => $artist_id
            ));
    }

    $stmt = $pdo->prepare('SELECT * from album where title=:ln');
    $stmt->execute(array(':ln' => $_POST['album_name']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $album_id=htmlentities($row['album_id']);

    $stmt = $pdo->prepare('SELECT * from genre where genre_name=:ln');
    $stmt->execute(array(':ln' => $_POST['genre']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $genre_id=htmlentities($row['genre_id']);

    $rank=1;
    for ($i=1; $i <= 20 ; $i++)
    {
        if(!isset($_POST['song'.$i]))  continue;
        if(!isset($_POST['link'.$i]))  continue;
        if(!isset($_POST['length'.$i]))  continue;

        $song=$_POST['song'.$i];
        $link=$_POST['link'.$i];
        $length=$_POST['length'.$i];

        $stmt = $pdo->prepare('INSERT INTO track
                               VALUES ( :tid, :song, :link, :len, :alid, :gid, :uid)');
        $stmt->execute(array(
                ':tid' => null,
                ':song' => $song,
                ':link' => $link,
                ':len' => $length,
                ':alid' => $album_id,
                ':gid' => $genre_id,
                ':uid' => $user_id)
                );
        $rank++;
    }

    $_SESSION['success'] = "Data Added";
    header("Location: index.php");
    return;
}
?>

<html>
<head>
	<title>Adding Track</title>
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
<body id="font_id" style="background:url('melodi/HTML/img/banner/b2.jpg'); color:white">

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


<h1 id="login_h1_id" class="center">Adding Track</h1>
<?php

    if ( isset($_SESSION['error']) )
    {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
        if ( isset($_SESSION['success']) )
    {
        echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
        unset($_SESSION['success']);
    }

?>

<form id='reduce' style="color:black" method="post">
  <div class="form-group">
    <label id='login_label_id'>Artist Name :</label>
    <input class="form-control" type="text" name="artist_name" size="40">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Language :</label>
    <input class="form-control" type="text" name="language">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Artist Gender (M/F) :</label>
    <input class="form-control" type="text" name="gender">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Album Name :</label>
    <input class="form-control" type="text" name="album_name">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Number of songs in album :</label>
    <input class="form-control" type="number" name="no">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Year of publication :</label>
    <input class="form-control" type="number" name="year">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Genre :</label>
    <input class="form-control" type="text" name="genre">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Label :</label>
    <input class="form-control" type="text" name="label">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Label address :</label>
    <input class="form-control" type="text" name="address">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Producer :</label>
    <input class="form-control" type="text" name="producer">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Songs :</label>
    <input type="submit" id="addSong" value="+">
  </div>


<!-- <p>Artist Name: <input type="text" name="artist_name" size="40"></p>
<p>Language: <input type="text" name="language"></p>
<p>Artist Gender (M/F): <input type="text" name="gender"></p>
<p>Album Name: <input type="text" name="album_name"></p>
<p>Number of songs in album: <input type="number" name="no"></p>
<p>Year of publication: <input type="number" name="year"></p>
<p>Genre: <input type="text" name="genre"></p>
<p>Label: <input type="text" name="label"></p>
<p>Label address: <input type="text" name="address"></p>
<p>Producer: <input type="text" name="producer"></p>

<p>Songs: <input type="submit" id="addSong" value="+"> -->
<div id="extra_fields"></div>
<br>
<input class="btn btn-default" style="font-size:18px" id="login_btn_id" type="submit" value="Add"/>
<input class="btn btn-default" style="font-size:18px" type="submit" name="cancel" value="Cancel">
</form><br>



<script type="text/javascript">
    countPos=0;
    $(document).ready(function(){
        console.log("Document ready called");
        $('#addSong').click(function(event)
        {
            event.preventDefault();
            if(countPos>=20)
            {
                alert("Maximum of twenty position entries exceeded");
                return;
            }
            countPos++;
            console.log("Adding song "+countPos);
            $('#extra_fields').append(
                '<div id="song'+countPos+'"> \
                <p><div class="form-group"><label id="login_label_id">Song title :</label><input class="form-control" type="text" name="song'+countPos+'" value=""></div> <\p>\
                <p><div class="form-group"><label id="login_label_id">Song link :</label><input class="form-control" type="text" name="link'+countPos+'" value=""></div><\p>\
                <p><div class="form-group"><label id="login_label_id">Length :</label><input class="form-control" type="number" name="length'+countPos+'" step=".01" value=""></div><\p>\
                <input type="button" value="-" \
                    onclick="$(\'#song'+countPos+'\').remove();return false;"></p> \
                </div>');
        });
    });
</script>
</body>
</html>
