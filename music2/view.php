<?php
require_once "pdo.php";
require_once "util.php";
session_start();

if ( ! isset($_GET['track_id']) )
{
  $_SESSION['error'] = "Missing track_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("CALL detail_display(:xyz)");
$stmt->execute(array(":xyz" => $_GET['track_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false )
{
    $_SESSION['error'] = 'Bad value for track_id';
    header( 'Location: index.php' ) ;
    return;
}


$name= htmlentities($row['name']);
$lang = htmlentities($row['artist_language']);
$gen = htmlentities($row['gender']);
$title = htmlentities($row['title']);
$no = htmlentities($row['no_of_songs']);
$pub_year = htmlentities($row['pub_year']);
$genre = htmlentities($row['genre_name']);
$label = htmlentities($row['label_name']);
$add = htmlentities($row['address']);
$prod = htmlentities($row['producer']);
$song = htmlentities($row['song']);
$link = htmlentities($row['link']);
$len = htmlentities($row['length']);
$track_id = $row['track_id'];

// $positions=loadPos($pdo, $_REQUEST['track_id']);

?>
<html>
<head>
    <title>View Details</title>

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
    <div class="container center">
<?php if(!isset($_SESSION['name']))
{

  echo '<header>

          <nav id="nav-color" class="navbar navbar-fixed-top navbar-default">
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
                  <li><a href="logout.php">Home</a></li>

                </ul>
              </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
          </nav>
  </header>
<br><br><br>';

}
else{
  echo '<header>

              <nav id="nav-color" class="navbar navbar-fixed-top navbar-default">
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
                      <li><a href="edit.php?track_id='.$row['track_id'].'">Edit</a></li>
                      <li><a href="delete.php?track_id='.$row['track_id'].'">Delete</a></li>
                      <li><a href="logout.php">Log Out</a></li>
                    </ul>
                  </div>
                </div>
              </nav>
      </header>
<br><br><br>';
}
?>
        <h1 id="login_h1_id">Track Information</h1><br>

        <ul style="list-style-type:none;">

        <li><p>Artist Name: <?= $name ?></p></li><br>
        <li><p>Language: <?= $lang ?></p></li><br>
        <li><p>Artist Gender: <?= $gen ?></p></li><br>
        <li><p>Album name: <?= $title ?></p></li><br>
        <li><p>Number of songs in album: <?= $no ?></p></li><br>
        <li><p>Year of publication: <?= $pub_year ?></p></li><br>
        <li><p>Genre: <?= $genre ?></p></li><br>
        <li><p>Label: <?= $label ?></p></li><br>
        <li><p>Label address: <?= $add ?></p></li><br>
        <li><p>Producer: <?= $prod ?></p></li><br>
        <li><p>Song title: <?= $song ?></p></li><br>
        <li><p>Song link: <a href="<?= $link ?>">Go to song (youtube.com)</a></p></li><br>
        <li><p>Length: <?php $min = floor($len / 60);
                             $sec = $len % 60;
                             echo($len." seconds / ".$min." minutes and ".$sec." seconds"); ?></p></li><br>

        <?php
            preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $link, $matches);
            $id=$matches[1];
        ?>
        <p><iframe id="ytplayer" type="text/html" width="600" height="400"
            src="https://www.youtube.com/embed/<?php echo $id ?>".></iframe></p><br>
        <!-- <li><p>Length: <?= $len ?></p></li> -->
        </ul>

        <!-- <a href="index.php">Done</a> -->
    </div>
</body>
</html>
