<?php
  include_once 'lib/locale.php';
  session_start();
  $lang = (isset($_POST['lang']))?$_POST['lang']:'';
  switch ($lang) {
    case 'es':
    case 'en':
      $_SESSION['lang'] = $lang;
      break;

    default:
      $locale = new Locale();

      $_SESSION['lang'] = $locale->getCountryLanguageByIp($_SERVER['REMOTE_ADDR']);
      break;
  }

  if(isset($_REQUEST["destination"])){
      header("Location: {$_REQUEST["destination"]}");
  }else if(isset($_SERVER["HTTP_REFERER"])){
      header("Location: {$_SERVER["HTTP_REFERER"]}");
  }else{
      header("Location: cosa.php");
  }
?>
