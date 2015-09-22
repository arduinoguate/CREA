<?php
  session_start();

  include_once 'lib/layout.php';
  include_once 'lib/locale.php';

  if (isset($_SESSION['lang'])){
    include 'locale/'.$_SESSION['lang'].'.php';
  }else{
    $locale = new Locale();
    $_SESSION['lang'] = $locale->getCountryLanguageByIp($_SERVER['REMOTE_ADDR']);
    include 'locale/'.$_SESSION['lang'].'.php';
  }

  $styles = array();
  $freelancer = new CSS('freelancer.css','css','stylesheet');
  $styles[] = $freelancer;

  addHeader("CREA", $styles);
?>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#page-top">Crea</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <form action="changelang.php" method="post" id="changelang">
                <ul class="nav navbar-nav navbar-left">
                    <input type="hidden" name="lang" id="lang"/>
                    <li class="page-scroll small"><a href="#" id="en">En</a></li>
                    <li class="page-scroll small"><a href="#" id="es">Es</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="page-scroll">
                        <a href="docs.php"><?php echo $locale['documentation']; ?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#portfolio"><?php echo $locale['features']; ?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#about"><?php echo $locale['about_crea']; ?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="#contact"><?php echo $locale['request']; ?></a>
                    </li>
                    <li class="page-scroll">
                        <a href="login.php"><?php echo $locale['login']; ?></a>
                    </li>
                </ul>
              </form>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header class="canvas-wrap">
        <div class="container">
            <div class="row canvas-content">
                <div class="col-lg-12">
                    <img class="img-responsive" src="img/crea.png" alt="">
                    <div class="intro-text">
                        <span class="name"><?php echo $locale['platform_title']; ?></span>
                        <hr class="star-light">
                        <span class="skills"><?php echo $locale['platform_slogan']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div id="canvas" class="gradient"></div>
    </header>

    <!-- Portfolio Grid Section -->
    <section id="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2><?php echo $locale['features']; ?></h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/crea-05.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/crea-06.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/crea-07.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal4" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/crea-08.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal5" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/crea-10.png" class="img-responsive" alt="">
                    </a>
                </div>
                <div class="col-sm-4 portfolio-item">
                    <a href="#portfolioModal6" class="portfolio-link" data-toggle="modal">
                        <div class="caption">
                            <div class="caption-content">
                                <i class="fa fa-search-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/grupo-04.png" class="img-responsive" alt="">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="success" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2><?php echo $locale['about_crea']; ?></h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-2">
                    <p><?php echo $locale['about_col_1']; ?></p>
                </div>
                <div class="col-lg-4">
                    <p><?php echo $locale['about_col_2']; ?></p>
                </div>
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="downloads.php?plat=arduino" class="btn btn-lg btn-outline">
                        <i class="fa fa-download"></i><?php echo $locale['download_for']; ?>Arduino
                    </a>
                </div>
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="downloads.php?plat=launchpad" class="btn btn-lg btn-outline">
                        <i class="fa fa-download"></i><?php echo $locale['download_for']; ?>Launchpad
                    </a>
                </div>
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="downloads.php?plat=beaglebone" class="btn btn-lg btn-outline">
                        <i class="fa fa-download"></i><?php echo $locale['download_for']; ?>Beaglebone
                    </a>
                </div>
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="downloads.php?plat=raspberry" class="btn btn-lg btn-outline">
                        <i class="fa fa-download"></i><?php echo $locale['download_for']; ?>Raspberry Pi
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2><?php echo $locale['request']; ?></h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                    <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label><?php echo $locale['form_name']; ?></label>
                                <input type="text" class="form-control" placeholder="<?php echo $locale['form_name']; ?>" id="name" required data-validation-required-message="<?php echo $locale['form_name_validation']; ?>">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label><?php echo $locale['form_email']; ?></label>
                                <input type="email" class="form-control" placeholder="<?php echo $locale['form_email']; ?>" id="email" required data-validation-required-message="<?php echo $locale['form_email_validation']; ?>">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label><?php echo $locale['form_username']; ?></label>
                                <input type="text" class="form-control" placeholder="<?php echo $locale['form_username']; ?>" id="user" required data-validation-required-message="<?php echo $locale['form_username_validation']; ?>">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label><?php echo $locale['form_password']; ?></label>
                                <input type="password" class="form-control" placeholder="<?php echo $locale['form_password_placeholder']; ?>" id="pass" required data-validation-required-message="<?php echo $locale['form_password_validation']; ?>">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-success btn-lg"><?php echo $locale['form_request']; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php
    		addFooter($locale);
    ?>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-top page-scroll visible-xs visble-sm">
        <a class="btn btn-primary" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <!-- Portfolio Modals -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2><?php echo $locale['modal_md_title']; ?></h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/crea-05.png" class="img-responsive img-centered" alt="">
                            <p><?php echo $locale['modal_md_text']; ?></p>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $locale['close']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="Cerrar-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2><?php echo $locale['modal_ap_title']; ?></h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/crea-06.png" class="img-responsive img-centered" alt="">
                            <p><?php echo $locale['modal_ap_text']; ?></p>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $locale['close']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2><?php echo $locale['modal_cd_title']; ?></h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/crea-07.png" class="img-responsive img-centered" alt="">
                            <p><?php echo $locale['modal_cd_text']; ?></p>

                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $locale['close']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2><?php echo $locale['modal_at_title']; ?></h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/crea-08.png" class="img-responsive img-centered" alt="">
                            <p><?php echo $locale['modal_at_text']; ?></p>

                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $locale['close']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2><?php echo $locale['modal_aa_title']; ?></h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/crea-10.png" class="img-responsive img-centered" alt="">
                            <p><?php echo $locale['modal_aa_text']; ?></p>

                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $locale['close']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2><?php echo $locale['modal_sc_title']; ?></h2>
                            <hr class="star-primary">
                            <img src="img/portfolio/grupo-04.png" class="img-responsive img-centered" alt="">
                            <p><?php echo $locale['modal_sc_text']; ?></p>

                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $locale['close']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main library -->


    <!-- Helpers -->
    <script src="js/vecs/projector.js"></script>
    <script src="js/vecs/canvas-renderer.js"></script>

    <!-- Visualitzation adjustments -->
    <script src="js/vecs/3d-lines-animation.js"></script>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/vecs/color.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/freelancer.js"></script>

    <script type="text/javascript">
      function loadjscssfile(filename, filetype){
        if (filetype=="js"){ //if filename is a external JavaScript file
            var fileref=document.createElement('script')
            fileref.setAttribute("type","text/javascript")
            fileref.setAttribute("src", filename)
        }
        else if (filetype=="css"){ //if filename is an external CSS file
            var fileref=document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
        }
        if (typeof fileref!="undefined")
            document.getElementsByTagName("head")[0].appendChild(fileref)
      }

      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        console.log("mobile");
      }else
      {
        loadjscssfile("js/vecs/three.min.js", "js");
      }

    </script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-7853765-8', 'auto');
      ga('send', 'pageview');

    </script>

    <script type="text/javascript">
        $(document).ready(function(){
          $("#es, #en").click(function(){
            var id = $(this).attr("id");
            $("#lang").val(id);
            $("#changelang").submit();
          });
        });
    </script>

</body>

</html>
