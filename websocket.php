<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Crea - Websocket tester</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
		<style type="text/css">
		.user_name{
    font-size:14px;
    font-weight: bold;
		}
		.comments-list .media{
		    border-bottom: 1px dotted #ccc;
		}
		#log {
			height:400px;
			overflow:auto;
		}
		</style>

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

<body id="page-top" class="index" onload="init()">
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
                    <li class="page-scroll">
                       Documentación del API
                    </li>
										<li class="page-scroll">
												<a href="docs.php">Documentaci&oacute;n</a>
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
			<div class="row">
        <div class="col-md-12">
          <div class="page-header">
            <h1><small class="pull-right">CREA</small> WebSocket Client </h1>
          </div>
					<div id="log" class="comments-list form-control"></div>

					<div class="send-wrap ">
						<input id="msg" type="textbox" class="form-control" onkeypress="onkey(event)"/>
					</div>
					<div class="btn-panel">
						<button class="btn" onclick="send()">Send</button>
						<button class="btn" onclick="quit()">Quit</button>
						<button class="btn" onclick="reconnect()">Reconnect</button>
					</div>

		</div>
	</div>
	<script src="js/websocket.js"></script>
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
