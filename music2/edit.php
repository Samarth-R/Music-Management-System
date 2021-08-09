<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    require_once "pdo.php";
    require_once "util.php";
    session_start();

    if ( isset($_POST['cancel'] ) )
    {
        // Redirect the browser to index.php
        header("Location: index.php");
        return;
    }

    if ( ! isset($_GET['track_id']) )
    {
      $_SESSION['error'] = "Missing track_id";
      header('Location: index.php');
      return;
    }

    // Retriving values from all tables to insert into form

        $stmt = $pdo->prepare("SELECT * FROM track where track_id = :xyz");
        $stmt->execute(array(":xyz" => $_GET['track_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $row === false )
        {
            $_SESSION['error'] = 'Bad value for track_id';
            header( 'Location: index.php' ) ;
            return;
        }

        $songs=loadSong($pdo, $_REQUEST['track_id']);

        $song = htmlentities($row['song']);
        $link = htmlentities($row['link']);
        $length = htmlentities($row['length']);
        $alid = htmlentities($row['album_id']);
        $gid = htmlentities($row['genre_id']);
        $track_id = $row['track_id'];
        $user_id = $row['user_id'];

        $stmt = $pdo->prepare("SELECT * FROM album WHERE album_id='".$alid."'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $album_name = htmlentities($row['title']);
        $no = htmlentities($row['no_of_songs']);
        $year = htmlentities($row['pub_year']);
        $arid = htmlentities($row['artist_id']);

        $stmt = $pdo->prepare("SELECT * FROM artist WHERE artist_id='".$arid."'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $artist_name = htmlentities($row['name']);
        $lang = htmlentities($row['artist_language']);
        $gender = htmlentities($row['gender']);
        $lid = htmlentities($row['label_id']);

        $stmt = $pdo->prepare("SELECT * FROM label WHERE label_id='".$lid."'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $label_name = htmlentities($row['label_name']);
        $addr = htmlentities($row['address']);
        $prod = htmlentities($row['producer']);

        $stmt = $pdo->prepare("SELECT * FROM genre WHERE genre_id='".$gid."'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $genre = htmlentities($row['genre_name']);

    // End of Retrival

    if ( isset($_SESSION['error']) )
    {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }


    // Actual updation begins here

    if(isset($_POST['artist_name']) && isset($_POST['language'])
           && isset($_POST['gender']) && isset($_POST['album_name'])
           && isset($_POST['no']) && isset($_POST['year'])
           && isset($_POST['genre']) && isset($_POST['label'])
           && isset($_POST['address']) && isset($_POST['producer']))
    {

        $msg1="<br><br><br>".validateInput();
        if(is_string($msg1))
        {
            $_SESSION['error']=$msg1;
            header("Location: edit.php?track_id=".$_REQUEST["track_id"]);
            return;
        }

        $msg2=validateSong();
        if(is_string($msg2))
        {
            $_SESSION['error']=$msg2;
            header("Location: edit.php?track_id=".$_REQUEST["track_id"]);
            return;
        }

        $sql = "CALL update_album(:alid, :alna, :no, :year);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':alid' => $alid,
            ':alna' => $_POST['album_name'],
            ':no' => $_POST['no'],
            ':year' => $_POST['year']
        ));

        $sql = "CALL update_artist(:arid, :arna, :lang, :gender);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':arid' => $arid,
            ':arna' => $_POST['artist_name'],
            ':lang' => $_POST['language'],
            ':gender' => $_POST['gender']
        ));

        $sql = "CALL update_label(:lid, :lna, :addr, :prod);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':lid' => $lid,
            ':lna' => $_POST['label'],
            ':addr' => $_POST['address'],
            ':prod' => $_POST['producer']
        ));

        $sql = "CALL update_genre(:gid, :genre);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':gid' => $gid,
            ':genre' => $_POST['genre']
        ));


        //Deleting values from track and reinserting to make updation easy

        $stmt=$pdo->prepare('DELETE FROM track
                            WHERE track_id=:tid');
        $stmt->execute(array(':tid' => $_REQUEST['track_id']));

        for ($i=1; $i <= 20 ; $i++)
        {
            if(!isset($_POST['song'.$i]))  continue;
            if(!isset($_POST['link'.$i]))  continue;
            if(!isset($_POST['length'.$i]))  continue;

            $song=$_POST['song'.$i];
            $link=$_POST['link'.$i];
            $length=$_POST['length'.$i];

            $stmt = $pdo->prepare('CALL update_track(:tid, :song, :link, :len, :alid, :gid, :uid)');
            $stmt->execute(array(
                    ':tid' => null,
                    ':song' => $song,
                    ':link' => $link,
                    ':len' => $length,
                    ':alid' => $alid,
                    ':gid' => $gid,
                    ':uid' => $user_id)
                    );
        }

        $_SESSION['success'] = 'Data updated';
        header( 'Location: index.php' ) ;
        return;
    }


    ?>
  </body>
</html>



<!DOCTYPE html>
<html>
<head>

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
  <div class="container">

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
    <h1 id="login_h1_id" class="center">Editing track</h1>

<form id='reduce' method="post" style="color:black">
  <div class="form-group">
    <label id='login_label_id'>Artist Name :</label>
    <input class="form-control" type="text" name="artist_name" value="<?= $artist_name ?>" >
  </div>
  <div class="form-group">
    <label id='login_label_id'>Language :</label>
    <input class="form-control" type="text" name="language" value="<?= $lang ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Artist Gender (M/F) :</label>
    <input class="form-control" type="text" name="gender" value="<?= $gender ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Album Name :</label>
    <input class="form-control" type="text" name="album_name" value="<?= $album_name ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Number of songs in album :</label>
    <input class="form-control" type="number" name="no" value="<?= $no ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Year of publication :</label>
    <input class="form-control" type="number" name="year" value="<?= $year ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Genre :</label>
    <input class="form-control" type="text" name="genre" value="<?= $genre ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Label :</label>
    <input class="form-control" type="text" name="label" value="<?= $label_name ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Label address :</label>
    <input class="form-control" type="text" name="address" value="<?= $addr ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Producer :</label>
    <input class="form-control" type="text" name="producer" value="<?= $prod ?>">
  </div>
  <div class="form-group">
    <label id='login_label_id'>Songs :</label>
    <input type="submit" id="addSong" value="+">
  </div>


    <!-- <p>Artist Name: <input type="text" name="artist_name" value="<?= $artist_name ?>" ></p>
    <p>Language: <input type="text" name="language" value="<?= $lang ?>"></p>
    <p>Artist Gender (M/F): <input type="text" name="gender" value="<?= $gender ?>"></p>
    <p>Album Name: <input type="text" name="album_name" value="<?= $album_name ?>"></p>
    <p>Number of songs in album: <input type="number" name="no" value="<?= $no ?>"></p>
    <p>Year of publication: <input type="number" name="year" value="<?= $year ?>"></p>
    <p>Genre: <input type="text" name="genre" value="<?= $genre ?>"></p>
    <p>Label: <input type="text" name="label" value="<?= $label_name ?>"></p>
    <p>Label address: <input type="text" name="address" value="<?= $addr ?>"></p>
    <p>Producer: <input type="text" name="producer" value="<?= $prod ?>"></p>

    <p>Songs: <input type="submit" id="addSong" value="+"> -->


    <div id="extra_fields"></div>

    <?php

        foreach ($songs as $song)
        {

            echo('<div id="song1">'."\n");
            echo('<div class="form-group">');
            echo ("<label id='login_label_id'>Song title :</label>");
            echo('<input class="form-control" type="text" name="song1"');
            echo(' value="'.$song['song'].'"/>'."\n");
            echo "</div>";
            echo('<div class="form-group">');
            echo ("<label id='login_label_id'>Song Link :</label>");
            echo('<input class="form-control" type="text" name="link1" value="'.$song['link'].'"/>'."\n");
            echo "</div>";
            echo('<div class="form-group">');
            echo ("<label id='login_label_id'>Length :</label>");
            echo('<input class="form-control" type="number" name="length1" value="'.$song['length'].'" step=".01"/>'."\n");
            echo "</div>";

            echo('<p><input type="button" value="-" ');

            echo('onclick="$(\'#song1\').remove(); return false;"></p>'."\n");
            echo "\n</textarea>\n</div>\n";
        }
        // echo("</div></p>\n");
    ?>
<br>
    <input class="btn btn-default" style="font-size:18px" id="login_btn_id" type="submit" value="Save"/>
    <input class="btn btn-default" style="font-size:18px" type="submit" name="cancel" value="Cancel">
    <br><br>
</form>

<script type="text/javascript">
    countPos=1;
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
                <p><div class="form-group"><label id="login_label_id">Song title :</label><input class="form-control" type="text" name="song'+countPos+'"  step=".01" value=""></div> <\p>\
                <p><div class="form-group"><label id="login_label_id">Song link :</label><input class="form-control" type="text" name="link'+countPos+'"  step=".01" value=""></div><\p>\
                <p><div class="form-group"><label id="login_label_id">Length :</label><input class="form-control" type="number" name="length'+countPos+'"  step=".01" value=""></div><\p>\
                <input type="button" value="-" \
                    onclick="$(\'#song'+countPos+'\').remove();return false;"></p> \
                </div>');
        });
    });
    </script>

</div>

</body>
</html>
