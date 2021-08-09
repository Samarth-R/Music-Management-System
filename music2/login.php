<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php // This is the model part of the document
    require_once "pdo.php";
    session_start();

    if ( isset($_POST['cancel']) )
    {
        // Redirect the browser to index.php
        header("Location: index.php");
        return;
    }

    if ( isset($_POST['new']) )
    {
        header("Location: add_user.php");
        return;
    }
    $salt = 'XyZzy12*_';;  // Pw has been changed

    // $failure = false;  // If we have no POST data, ie, no failure

    // Check to see if we have some POST data, if we do process it
    if ( isset($_POST['email']) && isset($_POST['pass']) )
    {
            $check = hash('md5', $salt.$_POST['pass']);
            $stmt = $pdo->prepare('SELECT user_id, name FROM users
                            WHERE email = :em AND password = :pw');
            $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $msg1="Incorrect email-password combination";
            if ( $row !== false )
            {
                $_SESSION['name'] = $row['name'];
                $_SESSION['user_id'] = $row['user_id'];
                // Redirect the browser to index.php
                header("Location: index.php");
                return;
            }
            else
            {
                error_log("Login fail ".$_POST['email']." $check");
                $_SESSION['error'] = $msg1;
                header("Location: login.php");
                return ;
            }
    }

    //End of Model
    ?>
  </body>
</html>


<!-- Beginning of View -->
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

  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"> -->

    <?php require_once "pdo.php"; ?>
    <title>Login Page</title>
</head>

<body id="font_id" style="background:url('melodi/HTML/img/banner/b2.jpg')">
    <div class="container">
        <?php
            if ( isset($_SESSION['error']) )
            {
                echo('<p style="color: white;">'. "<br><br><br><br>" .htmlentities($_SESSION['error'])."</p>\n");
                unset($_SESSION['error']);
            }

            if ( isset($_SESSION['success']) )
            {
                echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
                unset($_SESSION['success']);
            }
        ?>
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
                <li><a href="add_user.php">Sign Up</a></li>
                <li><a href="index.php">Home</a></li>

              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
</header>

    <form style="width: 500px;" id="form_login" method="POST">
      <h1 id="login_h1_id" class="center">Please Log In</h1>
      <br>

    <div class="form-group">
      <label id='login_label_id' for="email">Email</label>
      <input class="form-control" type="email" name="email" id="email">
    </div>
    <br>
    <div class="form-group">
      <label id="login_label_id" for="id_1723">Password</label>
      <input class="form-control" type="password" name="pass" id="id_1723">
    </div>

    <br>
      <input id="login_btn_id" style="font-size:18px" class="btn btn-default" type="submit" onclick="return doValidate();" value="Log In">

      <input class="btn btn-default" style="font-size:18px" type="submit" name="cancel" value="Cancel">

      <!-- <input id="login_btn_id" class="btn btn-default" type="submit" name="new" value="Sign Up"> -->

    </form>

    <script type="text/javascript">
        function doValidate()
        {
            console.log('Validating...');
            try
            {
                addr = document.getElementById('email').value;
                pw = document.getElementById('id_1723').value;
                console.log("Validating addr="+addr+" pw="+pw);
                if (addr == null || addr == "" || pw == null || pw == "")
                {
                    window.alert("Both fields must be filled out");
                    return false;
                }
                if ( addr.indexOf('@') == -1 )
                {
                    window.alert("Invalid email address");
                    return false;
                }
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

</html>
