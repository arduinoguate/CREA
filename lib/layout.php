<?php
  include_once 'css.php';

  function addHeader($title = "CREA", $css = array(), $js = array(), $description=""){
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo $description; ?>">
        <meta name="author" content="">

        <link rel="apple-touch-icon" sizes="57x57" href="/img/icons/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/img/icons/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/img/icons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/img/icons/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/img/icons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/img/icons/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/img/icons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/img/icons/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/img/icons/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/img/icons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/img/icons/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <title><?php echo $title; ?></title>

        <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <?php foreach ($css as $key => $value): ?>
          <?php echo $value->to_html(); ?>
        <?php endforeach; ?>

        <!-- Custom Fonts -->
        <link href="css/font-awesome.css" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>


    <?php
  }


  function addFooter($locale = array()){
    ?>


    	<!-- Footer -->
    	<footer class="text-center">
    			<div class="footer-above">
    					<div class="container">
    							<div class="row">
    									<div class="footer-col col-md-4">
    											<h3><?php echo $locale['sponsored_by']; ?></h3>
    											<p>Comunidad Arduino Guatemala</p>
    											<p><a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Licencia Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type"><small>&copy; CREA Engine</small></span>.</p>
    									</div>
    									<div class="footer-col col-md-4">
    											<h3><?php echo $locale['on_web']; ?></h3>
    											<ul class="list-inline">
    													<li>
    															<a href="https://www.facebook.com/ArduinoGuatemala" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
    													</li>
    													<li>
    															<a href="https://plus.google.com/u/0/b/114155441272026687984/114155441272026687984/posts" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
    													</li>
    													<li>
    															<a href="https://twitter.com/arduinoguate" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
    													</li>
    													<li>
    															<a href="https://github.com/arduinoguate" target="_blank" class="btn-social btn-outline"><i class="fa fa-fw fa-github"></i></a>
    													</li>
    											</ul>
    									</div>
    									<div class="footer-col col-md-4">
    											<h3><?php echo $locale['about_the']; ?>Comunidad Arduino Guatemala</h3>
    											<p><?php echo $locale['about_community']; ?></p>
    									</div>
    							</div>
    					</div>
    			</div>
    			<div class="footer-below">
    					<div class="container">
    							<div class="row">
    									<div class="col-lg-12">
    											Copyright &copy; Crea 2015
    									</div>
    							</div>
    					</div>
    			</div>
    	</footer>

    <?php
  }
?>
