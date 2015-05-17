<!DOCTYPE html>

<html>

<head>
  <title></title>
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
  <script type="text/javascript" src="js/login.js"></script>
</head>

<body>
  <div class="container">
    <div class="page-header" style="color:#fff;">
      <center><h1 class="logo">CREA Dashboard<small> Ingresa tus credenciales</small></h1></center>
    </div>
    <div class="login-container">
      <div id="output"></div>
      <div class="avatar"></div>
      <div class="form-box">
        <form id="loginfrm" action="" method="">
          <input name="user" type="text" placeholder="usuario">
          <div class="password">
            <input type="password" name="passwordfield" id="passwordfield" placeholder="contraseña">
            <span class="glyphicon glyphicon-eye-open"></span>
          </div>
          <button class="btn btn-info btn-block login" type="submit">Ingresar</button>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
