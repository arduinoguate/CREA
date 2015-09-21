<?php
	session_start();

	include_once 'lib/layout.php';
	include_once 'lib/locale.php';

  $styles = array();
	$styles[] = new CSS('docs.css','css','stylesheet');
	$styles[] = new CSS('freelancer.css','css','stylesheet');

	$lang = "";

	if (isset($_SESSION['lang'])){
		$lang = $_SESSION['lang'];
		include 'locale/'.$lang.'.php';
	}else{
		$locale = new Locale();
		$lang = $locale->getCountryLanguageByIp($_SERVER['REMOTE_ADDR']);
		include 'locale/'.$lang.'.php';
	}

	addHeader("CREA - ".$locale['downloads'], $styles);
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
                <a class="navbar-brand" href="/"><span><img src="img/crea.png" width="25px"></span> Crea</a>
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
	                        <a href="logout.php"><?php echo $locale['logout']; ?></a>
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
		  <h2><?php echo $locale['downloads_gateway']; ?></h2>
			<?php if (isset($_GET['plat'])): ?>
				<legend><?php echo $locale['downloads_for']; ?><?php echo $_GET['plat'] ?></legend>
				<hr/>
		  	<div class="row" id="files">

		    </div>

				<script src="js/downloads-<?php echo $lang; ?>.js"></script>
			<?php else: ?>
				<legend><?php echo $locale['not_found']; ?></legend>
			<?php endif; ?>


	  </div>
  </div>

<?php
		addFooter($locale);
?>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-7853765-8', 'auto');
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
