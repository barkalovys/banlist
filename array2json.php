<?php
// Json в windows
function array2json($arr) {
    $parts = array();
    $is_list = false;

    if (!is_array($arr)) return;
    if (count($arr)<1) return '{}';

    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr);

    if(($keys[0] == 0) and isset($keys[$max_length]) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }
    foreach($arr as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';
            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);
    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
}