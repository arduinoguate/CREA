<?php
	session_start();

	$yaml = file_get_contents('docs/lib/api.yml');

	$parsed = yaml_parse($yaml);
	//print_r($parsed);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Crea - Documentacion</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/docs.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index">
	<nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
								<img src="img/crea.png" width="25px">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Crea</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                       Documentación del API
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

	<div class="row">



	<div class="container">
	  <h2>Portal de desarrolladores CREA</h2>
	  <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bhoechie-tab-container">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
              <div class="list-group">
                <a href="#" class="list-group-item active text-center">
                  <h4 class="glyphicon glyphicon-road"></h4><br/>Empieza aquí
                </a>
                <a href="#" class="list-group-item text-center">
                  <h4 class="glyphicon glyphicon-cloud-upload"></h4><br/>API
                </a>
              </div>
            </div>
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 bhoechie-tab">
                <!-- flight section -->
                <div class="bhoechie-tab-content active">
                    <center>
                      <h1 class="glyphicon glyphicon-road" style="font-size:14em;"></h1>
                      <h2 style="margin-top: 0;">Pronto</h2>
                      <h3 style="margin-top: 0;">Información para desarrolladores</h3>
                    </center>
                </div>
                <!-- train section -->
                <div class="bhoechie-tab-content">
                	<center>
                      <h1 class="glyphicon glyphicon-cloud-upload" style="font-size:14em;"></h1>
                      <h2 style="margin-top: 10;">CREA API</h3>
                    </center>

                	<hr/>

                    <p class="lead">
				        A continuación encontrarás la documentación de llamadas al API de CREA. Este es un API <a href="http://es.wikipedia.org/wiki/Representational_State_Transfer" target="_blank">REST</a> para que lo tengas en cuenta.
				        <br /><br />
				        Suerte creando.
				    </p>

				    <?php foreach ($parsed['api-entries'] as $key => $value): ?>
				    	<?php
				    		$alertin = '';
				    		switch ($value['method']) {
								case 'GET':
									$alertin = 'alert-success';
									break;
								case 'PUT':
									$alertin = 'alert-info';
									break;
								case 'POST':
									$alertin = 'alert-warning';
									break;
								case 'DELETE':
									$alertin = 'alert-danger';
									break;
								default:

									break;
							}
				    	?>
						<div class="alert <?php echo $alertin; ?>">
					        <h4><?php echo $value['method'].' '.$value['entry']; ?></h4>
					        <p><?php echo $value['description']; ?></p>
					   </div>

					    <div>
					    	<b>Autenticación: </b><?php echo $value['authentication']; ?>
					    </div>
					    <br />

					    <?php if ($value['parameters'] != 'NONE'): ?>

						<div class="method">
					        <div class="row margin-0 list-header hidden-sm hidden-xs">
					            <div class="col-md-3"><div class="header">Propiedad</div></div>
					            <div class="col-md-2"><div class="header">Tipo</div></div>
					            <div class="col-md-2"><div class="header">Requerido</div></div>
					            <div class="col-md-5"><div class="header">Formato</div></div>
					        </div>

					        <?php foreach ($value['parameters'] as $val): ?>
								<div class="row margin-0">
						            <div class="col-md-3">
						                <div class="cell">
						                    <div class="propertyname">
						                        <?php echo $val['name']; ?>
						                    </div>
						                </div>
						            </div>
						            <div class="col-md-2">
						                <div class="cell">
						                    <div class="type">
						                    	<?php echo $val['type']; ?>
						                    </div>
						                </div>
						            </div>
						            <div class="col-md-2">
						                <div class="cell">
						                    <div class="isrequired">
						                        <?php echo ($val['required'] != 1)?'No':'Si'; ?>
						                    </div>
						                </div>
						            </div>
						            <div class="col-md-5">
						                <div class="cell">
						                    <div class="description">
						                        <code><?php echo $val['format']; ?></code>
						                    </div>
						                </div>
						            </div>
						        </div>
							<?php endforeach ?>
					    </div>

					    <?php endif ?>

					    <hr/>
					<?php endforeach ?>

				    </div>
                </div>


            </div>
        </div>


    </div>


	</div>






	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>


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
