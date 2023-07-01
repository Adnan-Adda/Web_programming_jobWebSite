<?php
 


/**
 * Checks if first param is array and the key exists and
 * returns the value of the key. if key is not exists or array is not array
 * it returns empty string.
 * @param $array
 * @param $key
 * @return mixed|string
 */
function get_value($arr, $key){
    if(is_array($arr) && key_exists($key,$arr))
        return $arr[$key];
    return '';
}

/**
 * Set a session with message and message-type to be used to alert user
 * @param $message
 * @param $message_type [error,success]
 * @return void
 */
function set_alert($message, $message_type){
    $_SESSION['alert-message'] = $message;
    $_SESSION['alert-message-type'] = $message_type;
}

/**
 * get the message stored in session[message] and session[message-type].
 * [error] use bootstrap alert danger
 *[success] use bootstrap alert success
 * @return string
 */
function get_alert(){
    $msg='';
    if(isset($_SESSION['alert-message-type']) && isset($_SESSION['alert-message'])){
        $div='<div class="alert %1$s alert-dismissible alert-msg"><button type="button" '.
            'class="btn-close" data-bs-dismiss="alert"></button>%2$s</div>';

        switch ($_SESSION['alert-message-type']){
            case 'success':
                $msg = sprintf($div,'alert-success',$_SESSION['alert-message']);
                break;
            case 'error':
                $msg = sprintf($div,'alert-danger',$_SESSION['alert-message']);
                break;
            default:
                break;
        }
        unset($_SESSION['alert-message-type']);
        unset($_SESSION['alert-message']);
    }
    return $msg;
}

/**
 * Generates and fills the tag <option value=""></option> with values from
 * $options array
 * @param $options array of values to be inserted in <option value=""></option>
 * @return string <option value="value">value</option>
 */
function get_select_options($options = array()){
    $all='';
    foreach($options as $value){
        $all.= sprintf('<option value="%1$s">%1$s</option>', $value);
    }
    return $all;
}

/**
 * get a list of all company's ads with list buttons to manage a created ad
 * @param $all_rows array of ads
 * @param $is_published if true get published ads, if false get unpublished ads
 * @return string lists of ads with buttons
 */
function get_company_ads($all_rows=array(), $is_published=false){
    $url='company.php?action';
    // list buttons to manage a created ad
    $publish_btn = '<a href="%3$s=publish-ads&ad-id=%1$d" class="btn btn-primary me-2 mt-2">Publicera nu</a>';
    $del_btn = '<a href="%3$s=del-ads&ad-id=%1$d" class="btn btn-danger me-2 mt-2">Radera</a>';
    $edit_btn = '<a href="%3$s=create-ads&ad-id=%1$d" class="btn btn-secondary me-2 mt-2">Redigera</a>';
    $preview_btn = '<a href="ads.php?action=view-ad&ad-id=%1$d" class="btn btn-success me-2 mt-2 view-ad-btn" id="%1$d">Visa</a>';
    $unpublish_btn='<a href="%3$s=unpublish-ads&ad-id=%1$d" class="btn btn-warning me-2 mt-2">Avpublicera</a>';

    $div1 ='<div class="ad-container" >' .$publish_btn . $preview_btn .$edit_btn . $del_btn .'</div>';
    $div2 ='<div class="ad-container" >'. $preview_btn.$edit_btn .$unpublish_btn  . $del_btn .'</div>';
    $data='';
    foreach ($all_rows as $row) {
        // ads that were not published
        if(!$row['is_published'] && !$is_published){
            $list ='<li class="list-group-item mt-2"><details class="manage-ads"><summary> Id#%1$d, %2$s</summary><hr/>'
                .$div1.'</details></li>';
            $data.= sprintf($list,$row['ad_id'],$row['title'],$url);
        }
        if($row['is_published'] && $is_published){
            // ads that were published
            $list ='<li class="list-group-item mt-2 "><details class="manage-ads"><summary> Id#%1$d, %2$s</summary><hr/>'
                .$div2.'</details></li>';
            $data.= sprintf($list,$row['ad_id'],$row['title'],$url);
        }
    }
    return $data;
}

/**
 * get list of published ads with title,publish date, company name and ad-title
 * @param $all_rows array of ads
 * @return string lists of ads
 */
function get_published_ads($all_rows=array()){
    $all='';
    $ads='<article>';
    $title='<h5><a href="ads.php?ad-id=%1$d">%2$s</a></h5><div>';
    $company_name='<p class="company-name">%3$s</p>';
    $adress='<span class="d-block plats">Plats: %4$s</span>';
    $publish_date='<span>Publicerad %5$s</span></div>';
    $ads .= $title.$company_name.$adress.$publish_date . '</article><hr/>';
    foreach ($all_rows as $row){
        $all.= sprintf($ads,$row['ad_id'],$row['title'],$row['company_name'],
            $row['province'].'/'.$row['city'],$row['publish_date'] );
    }
    return $all;
}
