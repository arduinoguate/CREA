<?php

class CSS{
  private $href;
  private $rel;
  private $attribute;

  public function __construct($file, $cssFolder, $rel) {
    $this->href = $cssFolder.'/'.$file;
    $this->rel = $rel;
    $this->attribute = array();
  }

  public function add_attribute($attr, $value){
    if (array_key_exists($attr, $this->attribute)){
      if (is_array($this->attribute[$attr]))
        $this->attribute[$attr][] = $value;
      else{
        $this->attribute[$attr] = array($this->attribute[$attr]);
        $this->attribute[$attr][] = $value;
      }
    }else{
      $this->attribute[$attr] = $value;
    }
  }

  public function to_html(){
    $attrs = $this->break_attributes();

    $converted = "<link href='$this->href' rel='$this->rel' $attrs>";
    return $converted;
  }

  private function break_attributes(){
    $output = "";
    foreach ($this->attribute as $key => $value) {
      if (is_array($value)){
        $output .= "$key='";
        foreach ($value as $v) {
          $output .= "$v,";
        }
        $output .= "'";
      }else{
        $output .= "$key='$value'";
      }
    }
    return $output;
  }
}

?>
