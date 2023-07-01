<?php
 


// use filter_var() with email filter
function validate_email(&$arr, $key){
    if(isset($arr[$key])){
        $arr[$key] = filter_var($arr[$key],FILTER_VALIDATE_EMAIL);
        if(!empty($arr[$key])){
            return true;
        }
    }
    return false;
}

/**
 * escape htmlspecialchars and trim spaces for all array's elements
 * @param $arr reference array
 * @param $not_empty default false, when true checks if some keys are empty
 * @return bool false if not_empty is true and one of keys are empty
 */
function escape_specialchars(&$arr,$not_empty=false): bool
{
    foreach($arr as $key=>$value){
        $arr[$key] = htmlspecialchars(stripslashes(trim($value)));
        if($not_empty && empty($arr[$key])){
            return false;
        }
    }
    return true;
}

/**
 * Check if there is an image uploaded and filter and change name of the image.
 * And upload the file to upload folder
 * @param $file_size size of file to be checked, default 10mb
 * @return false|string if success return new unique filename
 */
function upload_img($file_size=1000000)
{
    if(isset($_FILES['fileToUpload']) && !empty($_FILES['fileToUpload']['name'])){
        $path_parts = pathinfo($_FILES['fileToUpload']['name']);
        $file_ext = strtolower($path_parts['extension']);
        $new_filename = sha1($_FILES['fileToUpload']['name']) . '.' . $file_ext; // sha1 to get unique name
        $extensions= array('jpeg','jpg','png','svg');
        if(!in_array($file_ext,$extensions) || $_FILES['fileToUpload']['size']> $file_size){
            return false;
        }
        else{
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],  LOGO_UPLOAD_PATH . $new_filename );
            return $new_filename;
        }
    }
    return false;
}

