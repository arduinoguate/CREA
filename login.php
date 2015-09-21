<?php
  include_once 'lib/locale.php';
  session_start();
  $lang = "";

	if (isset($_SESSION['lang'])){
		$lang = $_SESSION['lang'];
    include 'locale/'.$lang.'.php';
  }else{
    $locale = new Locale();
		$lang = $locale->getCountryLanguageByIp($_SERVER['REMOTE_ADDR']);
    include 'locale/'.$lang.'.php';
  }
?>
<!DOCTYPE html>

<html>

<head>
  <title>CREA - Dashboard</title>
  <meta charset="">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" media="screen" href="css/login.css">
  <!-- Custom Fonts -->
  <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
  <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">


  <link rel="shortcut icon" type="img/png" href="img/favicon.png">
  <script src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/login-<?php echo $lang; ?>.js"></script>
</head>

<body>
  <div class="container">
    <div class="page-header" style="color:#fff;">
      <center><h1 class="logo">CREA Dashboard<small> <?php echo $locale['login_credentials']; ?></small></h1></center>
    </div>
    <div class="login-container">
      <div id="output"></div>
      <div class="avatar"></div>
      <div class="form-box">
        <form id="loginfrm" action="" method="">
          <input name="user" type="text" placeholder="<?php echo $locale['form_username']; ?>">
          <div class="password">
            <input type="password" name="passwordfield" id="passwordfield" placeholder="<?php echo $locale['form_password']; ?>">
            <span class="glyphicon glyphicon-eye-open"></span>
          </div>
          <button class="btn btn-info btn-block login" type="submit"><?php echo $locale['login']; ?></button>
        </form>
      </div>
    </div>
  </div>

  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-7853765-8', 'auto');
    ga('send', 'pageview');

  </script>
</body>

</html>
