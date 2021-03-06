<?php
	session_start();

	include_once 'lib/layout.php';
	include_once 'lib/locale.php';

	$lang = '';

	if (isset($_SESSION['lang'])){
		$lang = $_SESSION['lang'];
    include 'locale/'.$lang.'.php';
  }else{
    $locale = new Locale();
		$lang = $locale->getCountryLanguageByIp($_SERVER['REMOTE_ADDR']);
    include 'locale/'.$lang.'.php';
  }

  $styles = array();
  $freelancer = new CSS('docs.css','css','stylesheet');
  $styles[] = $freelancer;

	$api_ref = array();
	$cmd_ref = array();

	$files = glob('docs/'.$lang.'/lib/*.{yml}', GLOB_BRACE);
	foreach($files as $file) {
		if (trim($file) != "." || trim($file) != ".."){
			$yaml = file_get_contents($file);

			$parsed = yaml_parse($yaml);
			$api_ref[] = $parsed;
		}
	}

	$files = glob('docs/'.$lang.'/ws/*.{yml}', GLOB_BRACE);
	foreach($files as $file) {
		if (trim($file) != "." || trim($file) != ".."){
			$yaml = file_get_contents($file);

			$parsed = yaml_parse($yaml);
			$cmd_ref[] = $parsed;
		}
	}

	addHeader("CREA - ".$locale['documentation'], $styles);
	//print_r($parsed);
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
                    <li class="page-scroll">
											<?php echo $locale['api_documentation']; ?>
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

	<div class="row">



	<div class="container">
	  <h2><?php echo $locale['crea_developer_gateway']; ?></h2>
	  <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bhoechie-tab-container">
      	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
          <div class="list-group">
            <a href="#" class="list-group-item active text-center">
              <h4 class="glyphicon glyphicon-road"></h4><br/><?php echo $locale['docs_start_here']; ?>
            </a>
            <a href="#" class="list-group-item text-center">
              <h4 class="glyphicon glyphicon-cloud-upload"></h4><br/>API
            </a>
						<a href="#" class="list-group-item text-center">
              <h4 class="fa fa-mobile fa-2x"></h4><br/><?php echo $locale['docs_mobile']; ?>
            </a>
						<a href="#" class="list-group-item text-center">
              <h4 class="fa fa-gears fa-2x"></h4><br/><?php echo $locale['docs_socket']; ?>
            </a>
          </div>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 bhoechie-tab">
        	<!-- flight section -->
          <div class="bhoechie-tab-content active">
            <center>
              <h1 class="glyphicon glyphicon-road" style="font-size:14em;"></h1>
              <h2 style="margin-top: 0;"><?php echo $locale['docs_welcome']; ?></h2>
            </center>

						<hr/>

						<h2><?php echo $locale['docs_ws_before']; ?></h2>

						<p class="lead">
							<?php echo $locale['docs_gs']; ?>
							<br />
							<ul>
								<li><a href="https://www.ibm.com/developerworks/webservices/library/ws-restful/" target"_blank"><?php echo $locale['docs_rest_basic']; ?></a></li>
								<li><a href="https://www.youtube.com/watch?v=hdSrT4yjS1g" target="_blank"><?php echo $locale['docs_rest_video']; ?></a></li>
							</ul>
						</p>

						<h2><?php echo $locale['docs_wc_rest']; ?></h2>

						<p class="">
							REST is the underlying architectural principle of the web. The amazing thing about the web is the fact that clients (browsers) and servers can interact in complex ways without the client knowing anything beforehand about the server and the resources it hosts. The key constraint is that the server and client must both agree on the media used, which in the case of the web is HTML.
							<br /><br />
							An API that adheres to the principles of REST does not require the client to know anything about the structure of the API. Rather, the server needs to provide whatever information the client needs to interact with the service. An HTML form is an example of this: The server specifies the location of the resource, and the required fields. The browser doesn't know in advance where to submit the information, and it doesn't know in advance what information to submit. Both forms of information are entirely supplied by the server. (This principle is called HATEOAS.)
							<br /><br />
							So, how does this apply to HTTP, and how can it be implemented in practice? HTTP is oriented around verbs and resources. The two verbs in mainstream usage are GET and POST, which I think everyone will recognize. However, the HTTP standard defines several others such as PUT and DELETE. These verbs are then applied to resources, according to the instructions provided by the server.
							<br /><br />
							For example, Let's imagine that we have a user database that is managed by a web service. Our service uses a custom hypermedia based on JSON, for which we assign the mimetype application/json+userdb (There might also be an application/xml+userdb and application/whatever+userdb - many media types may be supported). The client and the server has both been programmed to understand this format, but they don't know anything about each other. As Roy Fielding points out:
							<br /><br />
							<blockquote>
							A REST API should spend almost all of its descriptive effort in defining the media type(s) used for representing resources and driving application state, or in defining extended relation names and/or hypertext-enabled mark-up for existing standard media types.
							</blockquote>
							<br />
						</p>
          </div>
          <!-- train section -->
          <div class="bhoechie-tab-content">
          	<center>
              <h1 class="glyphicon glyphicon-cloud-upload" style="font-size:14em;"></h1>
              <h2 style="margin-top: 10;">CREA API</h3>
            </center>

          	<hr/>

            <p class="lead">
							<?php echo $locale['docs_api_lead']; ?>
				    </p>

						<?php foreach ($api_ref as $api_module): ?>
							<hr/>
							<h2><?php echo $api_module['api-section']; ?></h2>
							<legend><?php echo $api_module['api-section-description']; ?></legend>
							<?php foreach ($api_module['api-entries'] as $key => $value): ?>
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
						    	<b><?php echo $locale['authentication']; ?>: </b><?php echo $value['authentication']; ?>
						    </div>
						    <br />

						    <?php if ($value['parameters'] != 'NONE'): ?>

									<div class="method">
						        <div class="row margin-0 list-header hidden-sm hidden-xs">
						            <div class="col-md-3"><div class="header"><?php echo $locale['property']; ?></div></div>
						            <div class="col-md-2"><div class="header"><?php echo $locale['type']; ?></div></div>
						            <div class="col-md-2"><div class="header"><?php echo $locale['required']; ?></div></div>
						            <div class="col-md-5"><div class="header"><?php echo $locale['format']; ?></div></div>
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
							<?php endforeach; ?>
						<?php endforeach; ?>
				    </div>

						<!-- flight section -->
						<div class="bhoechie-tab-content">
							<center>
								<h1 class="fa fa-mobile" style="font-size:14em;"></h1>
								<h2 style="margin-top: 10;"><?php echo $locale['docs_mob_title']; ?></h3>
							</center>

	          	<hr/>

							<p class="lead">
								<?php echo $locale['docs_mob_lead']; ?>
					    </p>

							<h1><?php echo $locale['docs_ws_before']; ?></h1>

							<p class="lead">
			        	<?php echo $locale['docs_gs']; ?>
				        <br />
				        <ul>
									<li><a href="http://developer.android.com/index.html" target"_blank">Sitio para empezar a desarrollar en Android</a></li>
									<li><a href="http://developer.android.com/training/basics/firstapp/index.html" target"_blank">Hola Mundo en Android</a></li>
									<li><a href="http://www.androidhive.info/2012/01/android-json-parsing-tutorial/" target"_blank">Tutorial para parsing de JSON en Android</a></li>
								</ul>
					    </p>


						</div>

						<!-- flight section -->
						<div class="bhoechie-tab-content">
							<center>
								<h1 class="fa fa-gears" style="font-size:14em;"></h1>
								<h2 style="margin-top: 10;"><?php echo $locale['docs_ws_title']; ?></h3>
							</center>

	          	<hr/>

							<p class="lead">
			        	<?php echo $locale['docs_ws_lead']; ?>
					    </p>

							<h1><?php echo $locale['docs_ws_before']; ?></h1>

							<p class="lead">
			        	<?php echo $locale['docs_ws_test']; ?> <a href="http://www.creaengine.com/websocket.php" target="_blank"><?php echo $locale['docs_ws_here']; ?></a>
					    </p>

							<?php foreach ($cmd_ref as $command): ?>
								<hr/>
								<h2><?php echo $command['api-section']; ?></h2>
								<legend><?php echo $command['api-section-description']; ?></legend>
								<?php foreach ($command['api-commands'] as $key => $value): ?>
									<?php
						    		$alertin = 'alert-success';
						    	?>

									<div class="alert <?php echo $alertin; ?>">
							        <h4><?php echo $value['entry']; ?></h4>
							        <p><?php echo $value['description']; ?></p>
							   	</div>

							    <br />

							    <?php if ($value['parameters'] != 'NONE'): ?>

										<div class="method">
							        <div class="row margin-0 list-header hidden-sm hidden-xs">
							            <div class="col-md-2"><div class="header"><?php echo $locale['name']; ?></div></div>
							            <div class="col-md-3"><div class="header"><?php echo $locale['format']; ?></div></div>
							            <div class="col-md-2"><div class="header"><?php echo $locale['required']; ?></div></div>
							            <div class="col-md-3"><div class="header"><?php echo $locale['syntax']; ?></div></div>
													<div class="col-md-2"><div class="header"><?php echo $locale['returns']; ?></div></div>
							        </div>

							        <?php foreach ($value['parameters'] as $val): ?>
												<div class="row margin-0">
								            <div class="col-md-2">
								                <div class="cell">
								                    <div class="propertyname">
								                        <?php echo $val['name']; ?>
								                    </div>
								                </div>
								            </div>
														<div class="col-md-3">
								                <div class="cell">
								                    <div class="description">
								                        <code><?php echo $val['format']; ?></code>
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
								            <div class="col-md-3">
								                <div class="cell">
								                    <div class="description">
								                        <code><?php echo $val['syntax']; ?></code>
								                    </div>
								                </div>
								            </div>
														<div class="col-md-2">
								                <div class="cell">
								                    <div class="description">
								                        <code><?php echo $val['return']; ?></code>
								                    </div>
								                </div>
								            </div>
								        </div>
											<?php endforeach ?>
							    	</div>
							    <?php endif ?>

							    <hr/>
								<?php endforeach; ?>
							<?php endforeach; ?>
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
