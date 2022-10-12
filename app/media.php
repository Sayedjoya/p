<?php

include('_configs.php');

if(isset($ALLOW_CROSS_DOMAIN_STREAMING))
{
    if($ALLOW_CROSS_DOMAIN_STREAMING == "yes")
    {
        header("Access-Control-Allow-origin: *");
    }
}

$f = ""; $id = ""; $strmrlink = "";

if(isset($_REQUEST['f']))
{
    $f = $_REQUEST['f'];
}

if(isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
}

if(empty($id))
{
    exit('Channel ID Missing');
}

if(empty($f))
{
    exit('Channel File ID Missing');
}

if(file_exists('_cache/'.$id))
{
    $getc = @file_get_contents('_cache/'.$id);
    if(!empty($getc))
    {
        $strmrlink = $getc;
    }
}

if(empty($strmrlink))
{
    exit('Channel ID Invalid');
}

//Baselink
$zasbase = str_replace(basename($strmrlink), "", $strmrlink);;
$streamHeads = array('User-Agent: '.$RDC_USERAGENT);

$smedialink = $zasbase.$f.'?token='.$RDC_TOKEN;
$process = curl_init($smedialink); 
curl_setopt($process, CURLOPT_HTTPHEADER, $streamHeads); 
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_TIMEOUT, 10); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
$response = curl_exec($process);
$httpcode = curl_getinfo($process, CURLINFO_HTTP_CODE);
curl_close($process);

if($httpcode == 200 || $httpcode == 206)
{
    header("Content-Type: video/mp2t");
    exit($response);
}
else
{
    http_response_code(404);
    exit('404 : NOT FOUND');
}

?>