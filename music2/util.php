<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    function validateSong()
    {
        for ($i=1; $i <= 20; $i++)
        {
            if(!isset($_POST['song'.$i]))  continue;
            if(!isset($_POST['link'.$i]))  continue;
            if(!isset($_POST['length'.$i]))  continue;

            $song=$_POST['song'.$i];
            $link=$_POST['link'.$i];
            $length=$_POST['length'.$i];

            if (strlen($song) < 1 || strlen($link) < 1 || strlen($length) < 1 )
            {
              return "All fields are required ";
            }
            if(!is_numeric($length))
            {
                return "Length must be numeric";
            }
            return true;
        }
    }


    function validateInput()
    {
        if ( strlen($_POST['artist_name']) < 1 || strlen($_POST['language']) < 1
            || strlen($_POST['gender']) < 1 || strlen($_POST['album_name']) < 1
            || strlen($_POST['no']) < 1 || strlen($_POST['year']) < 1
            || strlen($_POST['genre']) < 1 || strlen($_POST['label']) < 1
            || strlen($_POST['address'])<1 || strlen($_POST['producer'])<1 )
        {
           return "All fields are required ";
        }

        return true;
    }

    function loadSong($pdo, $track_id)
    {
        $stmt=$pdo->prepare('SELECT * FROM track
                        WHERE track_id=:prof ORDER BY track_id');
        $stmt->execute(array(':prof'=>$track_id));
        $songs=array();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
        {
            $songs[]=$row;
        }
        return $songs;
    }
    ?>
  </body>
</html>
