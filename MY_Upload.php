<?php
class MY_Upload extends CI_Upload {
  function is_allowed_filetype() {
    if (count($this->allowed_types) == 0 OR ! is_array($this->allowed_types)) {
      $this->set_error('upload_no_file_types');
      return FALSE;
    }

    $image_types = array('gif', 'jpg', 'jpeg', 'png', 'jpe', 'eps');

    foreach ($this->allowed_types as $val) {
      $mime = $this->mimes_types(strtolower($val));

      // Images get some additional checks
      if (in_array(strtolower($val), $image_types)) {
        if (strtolower($val) === 'eps') {
          try {
            $img = new Imagick($this->file_temp);
            $geo = $img->getImageGeometry();
          } catch (ImagickException $e) {
            return false;
          }
        } else {
          if (getimagesize($this->file_temp) === false) {
            return false;
          }
        }
      }

      if (is_array($mime)) {
        if (in_array($this->file_type, $mime, true)) {
          return true;
        }
      } else {
        if ($mime == $this->file_type) {
          return true;
        }	
      }		
    }
    
    return false;
    
  }
}
?>
