<?php

    require_once "pdo.php";
    require_once "util.php";
    session_start();

    if ( isset($_POST['cancel']) )
    {
        header('Location: index.php');
        return;
    }

    if(isset($_POST['done']))
    {
        $test=$pdo->query("Select * from users where email='".$_POST['email']."'");
        $num_rows=$test->rowCount();
        if($num_rows==0)
        {
            $salt = 'XyZzy12*_';
            $pass=hash('md5', $salt.$_POST['password']);
            $stmt = $pdo->prepare('INSERT INTO users values (:uid, :name, :email, :pass)');
            $stmt->execute(array(
                ':uid' => null,
                ':name' => $_POST['name'],
                ':email' => $_POST['email'],
                ':pass' => $pass
                ));

        }
        else
        {
            $_SESSION['error'] = "Email already exists";
            header("Location: login.php");
            return;
        }


        header("Location: login.php");
        $_SESSION['success'] = "Data Added";
        return;
    }

?>

<html>
<head>
	<title>Adding User</title>

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


  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous"> -->
</head>
<body id="font_id" class="bg" style="background:url('melodi/HTML/img/banner/b2.jpg')">
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
                    <li><a href="login.php">Log In</a></li>
                    <li><a href="index.php">Home</a></li>

                  </ul>
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>
    </header>
<br><br><br>

<h1 id="login_h1_id" class="center">Sign Up Here</h1>
<br>
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


<form id="reduce" method="post">
<div class="form-group">
  <label id="login_label_id" for="">Enter User Name :</label>
  <input class="form-control" type="text" id="name"  name="name">
</div>
<div class="form-group">
  <label id="login_label_id" for="">Enter User Email :</label>
  <input class="form-control" type="email" id="email" name="email">
</div>
<div class="form-group">
  <label id="login_label_id" for="">Enter Password :</label>
  <input class="form-control" type="password" id="password" name="password">
</div>
<div class="form-group">
  <label id="login_label_id" for="">Confirm Password :</label>
  <input class="form-control" type="password" id="confirm" name="confirm">
</div>
<br>
<input class="btn btn-default" style="font-size:18px" id="login_btn_id" type="submit" onclick="return doValidate();" name="done" value="Validate & Finish">
<input class="btn btn-default" style="font-size:18px" type="submit" name="cancel" value="Cancel">

</form>



<script type="text/javascript">
        function doValidate()
        {
            console.log('Validating...');
            try
            {
                name = document.getElementById('name').value;
                cpw = document.getElementById('confirm').value;
                pw = document.getElementById('password').value;
                email = document.getElementById('email').value;
                console.log("Validating passwords and email");
                if (name == null || name == "" || email == null || email == "" || pw == null || pw == "" || cpw == null || cpw == "")
                {
                    window.alert("All fields must be filled out");
                    return false;
                }
                if(pw != cpw)
                {
                    window.alert("Passwords do not match");
                    return false;
                }
                if ( email.indexOf('@') == -1 )
                {
                    window.alert("Invalid email address");
                    return false;
                }
                if(pw.length < 8)
                {
                  window.alert("Password must be at least 8 characters long");
                  return false;
                }

                window.alert('Validation complete, data okay');
                return true;
            }
            catch(e)
            {
                return false;
            }
            return false;
        }
</script>
</div>
</body>
