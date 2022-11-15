<?php

$ALLOW_CROSS_DOMAIN_STREAMING = "yes"; //Possible Values - "yes" or "no"
$ENABLE_CROSS_DOMAIN_REQUEST = "yes"; //Possible Values - "yes" or "no"
$RDC_USERAGENT = "REDLINECLIENT"; // useragent is changeable
$RDC_TOKEN = "RED_8VRQCE7X4p_dxfKhbHZKHg==,".exptoken().".9659699029"; // token is also changeable

$CHANNEL_LIST = array();

if(file_exists('_data/channels'))
{
    $heck = @file_get_contents('_data/channels');
    if(!empty($heck))
    {
        $beck = @json_decode($heck, true);
        if(!empty($beck))
        {
            $CHANNEL_LIST = $beck;
        }
    }
}

function exptoken()
{
    return time() + 1200;
}

function response($status, $code, $message, $data)
{
    if(isset($ENABLE_CROSS_DOMAIN_REQUEST))
    {
        if($ENABLE_CROSS_DOMAIN_REQUEST == "yes")
        {
            header("Access-Control-Allow-Origin: *");
        }
    }
    header("Content-Type: application/json");
    if($status == "success" || $status == "error")
    {
        if($status == "error")
        {
            $data = array();
        }
        $outro = array('status' => $status, 'code' => $code, 'message' => $message, 'data' => $data);
        exit(json_encode($outro));
    }
    else
    {
        http_response_code(500);
        exit('Fatal Error');
    }
}

//---------------------------------------------------------------//

if(!is_dir('_cache'))
{
    mkdir('_cache');
    @file_put_contents('_cache/.htaccess', 'deny from all');
}

$streamenvproto = "http";

if(stripos($_SERVER['HTTP_HOST'], ':') !== false)
{
    $warl = explode(':', $_SERVER['HTTP_HOST']);
    if(isset($warl[0]) && !empty($warl[0]))
    {
        $_SERVER['HTTP_HOST'] = trim($warl[0]);
    }
}

if(stripos($_SERVER['HTTP_HOST'], 'localhost') !== false)
{
    $_SERVER['HTTP_HOST'] = str_replace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']);
}

if($_SERVER['SERVER_ADDR'] !== "127.0.0.1")
{
    $local_ip = $_SERVER['HTTP_HOST']; 
}
else
{
    $local_ip = getHostByName(php_uname('n'));
}

if(isset($_SERVER['HTTPS']))
{
    if($_SERVER['HTTPS'] == "on")
    {
        $streamenvproto = "https";
    }
}

if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']))
{
    if($_SERVER['HTTP_X_FORWARDED_PROTO'] == "https")
    {
        $streamenvproto = "https";
    }
}

?>
