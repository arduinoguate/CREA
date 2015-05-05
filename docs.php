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
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
	
	<div class="row">
		
	
	
	<div class="container">
	  <h2>Mobile-Friendly API Documentation</h2>
	    <p class="lead">
	        Emulate tables that collapse beautifully on mobile devices!
	    </p>
	    
	    <div class="alert alert-info">
	        <h4>Row modifier class included</h4>
	        <p>This feature uses a row modifier I created called ".margin-0". When applied to a ".row" element, it will ensure the margins between the columns will be 0. </p>
	        <p>This is important in order to emulate the table-like effect, but it will work in any environment.</p>
	    </div>
	
	    <hr />
	
	    <div class="method">
	        <div class="row margin-0 list-header hidden-sm hidden-xs">
	            <div class="col-md-3"><div class="header">Property</div></div>
	            <div class="col-md-2"><div class="header">Type</div></div>
	            <div class="col-md-2"><div class="header">Required</div></div>
	            <div class="col-md-5"><div class="header">Description</div></div>
	        </div>
	
	        <div class="row margin-0">
	            <div class="col-md-3">
	                <div class="cell">
	                    <div class="propertyname">
	                        CurrencyCode  <span class="mobile-isrequired">[Required]</span>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="type">
	                        <code>String</code>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="isrequired">
	                        Yes
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-5">
	                <div class="cell">
	                    <div class="description">
	                        The standard ISO 4217 3-letter currency code
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="row margin-0">
	            <div class="col-md-3">
	                <div class="cell">
	                    <div class="propertyname">
	                        PriceType  <span class="mobile-isrequired">[Required]</span>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="type">
	                        <code>Int32</code>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="isrequired">
	                        Yes
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-5">
	                <div class="cell">
	                    <div class="description">
	                        The type of price
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="row margin-0">
	            <div class="col-md-3">
	                <div class="cell">
	                    <div class="propertyname">
	                        WarehouseID  <span class="mobile-isrequired">[Required]</span>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="type">
	                        <code>Int32</code>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="isrequired">
	                        Yes
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-5">
	                <div class="cell">
	                    <div class="description">
	                        The unique identifier for the warehouse
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="row margin-0">
	            <div class="col-md-3">
	                <div class="cell">
	                    <div class="propertyname">
	                        ItemCodes
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="type">
	                        <code>String[]</code>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="isrequired">
	                        <span class="text-muted">No</span>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-5">
	                <div class="cell">
	                    <div class="description">
	
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="row margin-0">
	            <div class="col-md-3">
	                <div class="cell">
	                    <div class="propertyname">
	                        LanguageID
	                        <a class="lookuplink" href="javascript:;">
	                            <i class="glyphicon glyphicon-search"></i>
	                        </a>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="type">
	                        <code>Int32?</code>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-2">
	                <div class="cell">
	                    <div class="isrequired">
	                        <span class="text-muted">No</span>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-5">
	                <div class="cell">
	                    <div class="description">
	                        The customer's preferred language ID (ex. 0 (English), 1 (Spanish), etc.)
	                    </div>
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
	
	</script>
</body>
