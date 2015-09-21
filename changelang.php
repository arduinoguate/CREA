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
?>
