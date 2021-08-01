<?php

function CaesarCipher($input_text,$shift=2){
  $pattern = "/^[-+]?[0-9]+$/i" ;
  $str_version_shift = strval($shift);
  $str_version_shift = str_replace(' ','',$str_version_shift);
  if (preg_match($pattern,$str_version_shift)){
    $shift = intval($shift);
    if ($shift < 0 ){ // to the left
        $shiftedstring = "";
        for ($i=0; $i < strlen($input_text); $i++) {
          $value = ord($input_text[$i]) + $shift ;
            if ($input_text[$i] != " ") {
                if (($value <= 90) &&  ($value >= 65) ){
                  $temp = chr($value);
                  $shiftedstring = $shiftedstring . $temp ;}
                else if ($value < 65){
                    if (($input_text[$i] >= '0') &&  ($input_text[$i] <= "9") ){
                      $shiftedstring .= $input_text[$i];
                      } else {
                      $diff = 65 - $value;
                      $temp = chr(90-($diff-1));
                      $shiftedstring = $shiftedstring . $temp ;}}}
            else {$shiftedstring .= $input_text[$i];}}// for loop end
        return $shiftedstring; }
    else if ($shift > 0){# shift right
        $shiftedstring = "";
        for ($i=0; $i < strlen($input_text); $i++) {
            $value = ord($input_text[$i]) + $shift;
            if ($input_text[$i] != " ") {
                if (($input_text[$i] >= '0') &&  ($input_text[$i] <= "9") ){
                      $shiftedstring .= $input_text[$i];}
                  else if  (($value <= 90) &&  ($value >= 65) ){
                    $temp = chr($value);
                    $shiftedstring = $shiftedstring . $temp ; }
                else if (90 < $value){
                      $diff = $value - 90;
                      $temp = chr(65+ $diff-1);
                      $shiftedstring = $shiftedstring . $temp ;}
            } else {$shiftedstring .= $input_text[$i]; }}// end for loop
        return $shiftedstring;}
    else if ($shift == 0){return $input_text ;}}// regex brace
  else{return "You can only enter signed/unsighed numbers.";}}// function close


 ?>
