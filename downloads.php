<?php
	session_start();

	include_once 'lib/layout.php';

  $styles = array();
  $freelancer = new CSS('docs.css','css','stylesheet');
  $styles[] = $freelancer;

	addHeader("CREA - Descargas", $styles);
?>

<body id="page-top" class="index">
	<nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><span><img src="img/crea.png" width="25px"></span> Crea</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <?php if (isset($_SESSION['token'])): ?>

											<li class="page-scroll">
	                        <a href="dashboard.php">Dashboard</a>
	                    </li>
											<li class="page-scroll">
	                        <a href="logout.php">Salir</a>
	                    </li>

										<?php endif; ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>

	<div class="row">
		<div class="container">
		  <h2>Portal de descargas</h2>
			<?php if (isset($_GET['plat'])): ?>
				<legend>Descargas para <?php echo $_GET['plat'] ?></legend>
				<hr/>
		  	<div class="row" id="files">

		    </div>

				<script src="js/downloads.js"></script>
			<?php else: ?>
				<legend>404 sitio no encontrado</legend>
			<?php endif; ?>


	  </div>
  </div>





	<!-- Footer -->
	<footer class="text-center">
			<div class="footer-above">
					<div class="container">
							<div class="row">
									<div class="footer-col col-md-4">
											<h3>Patrocinado por</h3>
											<p>Comunidad Arduino Guatemala</p>
											<p><a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Licencia Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dct:title" rel="dct:type">Plataforma CREA</span> por <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.arduinogt.com" property="cc:attributionName" rel="cc:attributionURL">Oscar Leche</a> se distribuye bajo una <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Licencia Creative Commons Atribución-CompartirIgual 4.0 Internacional</a>.<br />Basada en una obra en <a xmlns:dct="http://purl.org/dc/terms/" href="http://crea.arduinogt.com" rel="dct:source">http://crea.arduinogt.com</a>.</p>
									</div>
									<div class="footer-col col-md-4">
											<h3>En la web</h3>
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
											<h3>Sobre la Comunidad Arduino Guatemala</h3>
											<p>Somos entusiastas del hardware abierto, desarrollamos proyectos basados en el open source y compartimos la cultura do it yourself (DIY)</p>
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




	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-7853765-6', 'auto');
	  ga('send', 'pageview');

	  $(document).ready(function() {
		    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
		        e.preventDefault();
		        $(this).siblings('a.active').removeClass("active");
		        $(this).addClass("active");
		        var index = $(this).index();
		        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
		        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
		    });
	  });

	</script>
</body>
