<?php
	session_start();

	$output = "";
	exec('git rev-list HEAD --count', $output);
	$version = $output[0];

	$lang = "";

	if (isset($_SESSION['lang'])){
    include 'locale/'.$_SESSION['lang'].'.php';
		$lang = $_SESSION['lang'];
  }else{
    include 'locale/es.php';
		$lang = 'es';
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Crea - Dashboard</title>

    <!-- Bootstrap Core CSS - Uses Bootswatch Flatly Theme: http://bootswatch.com/flatly/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/dashboard.css" rel="stylesheet">

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
                <a class="navbar-brand" href="#page-top"><span><img src="img/crea.png" width="30px"></span> Crea</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="docs.php"><?php echo $locale['documentation']; ?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#" id="add_mod"><?php echo $locale['add_module']; ?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="logout.php"><?php echo $locale['logout']; ?></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

	<div class="row">



	<div class="container">
	  <input type="hidden" id="sess_token" value="<?php echo $_SESSION['token'] ?>"/>
	  <input type="hidden" id="sess_user" value="<?php echo $_SESSION['username'] ?>"/>
      <div class="row">
        <div class="col-sm-3 col-md-3">
          <div class="panel-group" id="accordion">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-folder-close">
									</span><?php echo $locale['devices']; ?></a>
                </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse in">
                <ul class="list-group" id="devices">

                </ul>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-user">
									</span><?php echo $locale['my_account']; ?></a>
                </h4>
              </div>
              <div id="collapseFour" class="panel-collapse collapse">
                <div class="list-group">
                  <a id="api_access" href="#" class="list-group-item" data-user="<?php echo $_SESSION['username'] ?>">
										<?php echo $locale['api_access']; ?>
                  </a>
                  <a id="user_profile" href="#" data-user="<?php echo $_SESSION['username'] ?>" class="list-group-item">Editar</a>
                  <a id="user_password" href="#" data-user="<?php echo $_SESSION['username'] ?>"  class="list-group-item">Cambiar Contraseña</a>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-heart">
									</span><?php echo $locale['alerts']; ?></a>
                </h4>
              </div>
              <div id="collapseFive" class="panel-collapse collapse">
                <div class="list-group">
                  <a href="#" class="list-group-item">
                    <h4 class="list-group-item-heading">CREA ahora funciona con websockets</h4>
                    <p class="list-group-item-text">Por el momento solo soporta el modulo ESP8266. Sin embargo, tenemos abierto el codigo para que puedas modificarlo al que mas te parezca. Revisa la documentacion para mas informacion.</p>
                  </a>
                </div>
                <div class="list-group">
                  <a href="#" class="list-group-item active">
                    <h4 class="list-group-item-heading">Estaremos agregando mas funcionalidades</h4>
                    <p class="list-group-item-text">Mantente al tanto de las nuevas implementaciones que estaremos agregando</p>
                  </a>
                </div>
                <div class="list-group">
                  <a href="#" class="list-group-item">
                    <h4 class="list-group-item-heading">Edición de perfil listo</h4>
                    <p class="list-group-item-text">Ya puedes editar tu perfil desde la aplicacion. Solamente ve a <b>Mi Cuenta -> Editar</b> y listo.</p>
                  </a>
                </div>
              </div>
            </div>
          </div>
					<div class="row">
						<div class="col-md-12">
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="D2BVD6DXXU4XW">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form>
							<p><center>Puedes apoyarnos realizando una donación</center></p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<small>
								Version: 0.1.<?php echo $version; ?>
							</small>
						</div>
					</div>
        </div>
        <div class="col-sm-9 col-md-9">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Dashboard</h3>
            </div>
            <div class="panel-body" id="dashboard">
              <p><?php echo $locale['dashboard_welcome']; ?></p>
              <div class="alert alert-success"><h3><?php echo $locale['dashboard_welcome_message']; ?></h3></div>
            </div>
						<!-- USER UPDATE -->
            <div class="panel-body" id="user-info" style="display: none;">
              <form class="form-horizontal" id="user-edit-form" role="form" action="#" method="post" novalidate>
				        <fieldset>
				          <!-- Text input-->
				          <div class="form-group">
				            <label class="col-sm-2 control-label" for="textinput"><?php echo $locale['form_name']; ?></label>
				            <div class="col-sm-10">
				              <input type="text" placeholder="<?php echo $locale['form_name']; ?>" class="form-control" id="eu-nombre" required="required">
				            </div>
				          </div>

				          <!-- Text input-->
				          <div class="form-group">
				            <label class="col-sm-2 control-label" for="textinput"><?php echo $locale['form_last_name']; ?></label>
				            <div class="col-sm-10">
				              <input type="text" placeholder="<?php echo $locale['form_last_name']; ?>" class="form-control" id="eu-apellido">
				            </div>
				          </div>

				          <!-- Text input-->
				          <div class="form-group">
				            <label class="col-sm-2 control-label" for="textinput"><?php echo $locale['form_email']; ?></label>
				            <div class="col-sm-10">
				              <input type="text" placeholder="<?php echo $locale['form_email']; ?>" class="form-control" id="eu-email" required="required">
				            </div>
				          </div>

				          <div class="form-group">
				            <div class="col-sm-offset-2 col-sm-10">
				              <div class="pull-right">
				                <button type="submit" id="eu-save" class="btn btn-primary"><?php echo $locale['form_save_changes']; ?></button>
				              </div>
				            </div>
				          </div>

				        </fieldset>
				      </form>
            </div>

						<!-- PASSWORD UPDATE -->
						<div class="panel-body" id="user-password" style="display: none;">
              <form class="form-horizontal" id="password-edit-form" role="form" action="#" method="post" novalidate>
				        <fieldset>
				          <!-- Text input-->
				          <div class="form-group">
				            <label class="col-sm-2 control-label" for="textinput"><?php echo $locale['form_password']; ?></label>
				            <div class="col-sm-10">
				              <input type="password" placeholder="<?php echo $locale['form_password']; ?>" class="form-control" id="cp-pass" required="required">
				            </div>
				          </div>

				          <!-- Text input-->
				          <div class="form-group">
				            <label class="col-sm-2 control-label" for="textinput"><?php echo $locale['form_password_confirm']; ?></label>
				            <div class="col-sm-10">
				              <input type="password" placeholder="<?php echo $locale['form_password_confirm']; ?>" class="form-control" id="cp-pass-confirm">
				            </div>
				          </div>

				          <div class="form-group">
				            <div class="col-sm-offset-2 col-sm-10">
				              <div class="pull-right">
				                <button type="submit" id="cp-save" class="btn btn-primary"><?php echo $locale['form_save_changes']; ?></button>
				              </div>
				            </div>
				          </div>

				        </fieldset>
				      </form>


            </div>
          </div>
        </div>
      </div>
    </div>


	</div>

    <div id="actionModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">✕</button>
    	        <h3><?php echo $locale['modal_create_action']; ?></h3>
    	    </div>
            <div class="modal-body" style="text-align:center;">
                <div class="panel panel-default">
                    <div class="panel-body form-horizontal payment-form">
                    	<input type="hidden" id="act_module" />
                    	<input type="hidden" id="act_cmd" />
                        <div class="form-group">
                            <label for="concept" class="col-sm-3 control-label"><?php echo $locale['form_name']; ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="act_name" name="act_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label"><?php echo $locale['form_action_type']; ?></label>
                            <div class="col-sm-9">
                                <select class="form-control" id="act_type" name="act_type">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="concept" class="col-sm-3 control-label"><?php echo $locale['form_pin']; ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="act_cmda" name="act_cmda">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-right">
                                <button type="button" id="add_action_btn" class="btn btn-default preview-add-button">
                                    <span class="glyphicon glyphicon-plus"></span> <?php echo $locale['form_add']; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
    	    </div>
        </div>
      </div>
    </div>



	<div id="moduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">✕</button>
  	        <h3><?php echo $locale['modal_create_device']; ?></h3>
  	    </div>
          <div class="modal-body" style="text-align:center;">
              <div class="panel panel-default">
                  <div class="panel-body form-horizontal payment-form">
                  	<input type="hidden" id="act_module" />
                  	<input type="hidden" id="act_cmd" />
                      <div class="form-group">
                          <label for="concept" class="col-sm-3 control-label"><?php echo $locale['form_name']; ?></label>
                          <div class="col-sm-9">
                              <input type="text" class="form-control" id="mod_name" name="mod_name">
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="status" class="col-sm-3 control-label"><?php echo $locale['form_module_type']; ?></label>
                          <div class="col-sm-9">
                              <select class="form-control" id="mod_type" name="mod_type">

                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-12 text-right">
                              <button type="button" id="add_module_btn" class="btn btn-default preview-add-button">
                                  <span class="glyphicon glyphicon-plus"></span> <?php echo $locale['form_add']; ?>
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
  	    </div>
      </div>
    </div>
  </div>

	<div id="alertModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">✕</button>
  	        <h3><?php echo $locale['modal_alert']; ?></h3>
  	    </div>
          <div class="modal-body" style="text-align:center;">
            <div class="panel panel-default">
              <div class="panel-body form-horizontal payment-form">
            	  <div class="form-group">
                	<h1 id="mod_alert_msg"></h1>
                </div>
              </div>
            	<div class="form-group">
                <div class="col-sm-12 text-right">
									<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $locale['form_cloe']; ?></button>
                </div>
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
    <script src="js/jqBootstrapValidation.js"></script>
	<script src="js/dashboard-<?php echo $lang; ?>.js"></script>


	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-7853765-6', 'auto');
	  ga('send', 'pageview');

	</script>
</body>
