<?php
  include_once 'config/config.php';

  class Locale extends GCConfig{

    public function getCountryLanguageByIp($ip){
      $data = $this->getLocationInfoByIp($ip);
      $q_list = $this->country->fetch("nombre = '".$data['country']."'");
      if (count($q_list) > 0) {
        print_r($q_item);
        return $q_item->columns['crea_lang'];
      }else {
        return 'es';
      }
    }

    public function getLocationInfoByIp($ip_addr){
      $return_data  = array('country'=>'', 'city'=>'');
      $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip_addr));
      if($ip_data && $ip_data->geoplugin_countryName != null){
          $return_data['country'] = $ip_data->geoplugin_countryCode;
          $return_data['city'] = $ip_data->geoplugin_city;
      }
      return $return_data;
    }
  }

?>
