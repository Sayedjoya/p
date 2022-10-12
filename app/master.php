<?php

include('_configs.php');

if(isset($ALLOW_CROSS_DOMAIN_STREAMING))
{
    if($ALLOW_CROSS_DOMAIN_STREAMING == "yes")
    {
        header("Access-Control-Allow-origin: *");
    }
}

$id = ""; $rwLink = "";
$strmrlink = ""; $pm3u = "";

if(isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
}

if(empty($id))
{
    exit('Channel ID Missing');
}

foreach($CHANNEL_LIST as $chnm)
{
    if($id == $chnm['id'])
    {
        $rwLink = $chnm['link'];
    }
}

if(empty($rwLink))
{
    exit('Channel ID Invalid');
}

if(file_exists('_cache/'.$id))
{
    $veck = @file_get_contents('_cache/'.$id);
    if(!empty($veck))
    {
        $strmrlink = $veck.'?token='.$RDC_TOKEN;
    }
}

$streamHeads = array('User-Agent: '.$RDC_USERAGENT);

if(empty($strmrlink))
{
    $streamLink = $rwLink.'&token='.$RDC_TOKEN;
    $process = curl_init($streamLink); 
    curl_setopt($process, CURLOPT_HTTPHEADER, $streamHeads); 
    curl_setopt($process, CURLOPT_HEADER, 1);
    curl_setopt($process, CURLOPT_TIMEOUT, 10); 
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
    //curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
    $result = curl_exec($process); 
    curl_close($process); 
    if (preg_match('~Location: (.*)~i', $result, $match))
    {
        $stonolink = trim($match[1]);
        if(stripos($stonolink, 'index.m3u8') !== false)
        {
            $stcxlink = str_replace('index.m3u8', 'tracks-v1a1/mono.m3u8', $stonolink);
            $stailink = explode('?token=', $stcxlink);
            if(isset($stailink[0]))
            {
                $strmrlink = $stailink[0].'?token='.$RDC_TOKEN;
            }
            @file_put_contents('_cache/'.$id, $stailink[0]);
        }
    }
}

if(empty($strmrlink))
{
    if(file_exists('_cache/'.$id))
    {
        unlink('_cache/'.$id);
    }
    exit('500 : Streamer Error');
}

$process = curl_init($strmrlink); 
curl_setopt($process, CURLOPT_HTTPHEADER, $streamHeads); 
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_TIMEOUT, 10); 
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
$response = curl_exec($process); 
curl_close($process);
if(stripos($response, '#EXTM3U') !== false)
{
    $eine = explode("\n", $response);
    foreach($eine as $zine)
    {
        if(stripos($zine, ".ts") !== false)
        {
            $oine = explode('?token', $zine);
            $pm3u .= 'media.ts?id='.$id.'&f='.$oine[0]."\n";
        }
        else
        {
            $pm3u .= $zine."\n";
        }
    }
}

if(!empty($pm3u))
{
    header("Content-Type: application/vnd.apple.mpegurl");
    print(trim($pm3u));
}
else
{
    if(file_exists('_cache/'.$id))
    {
        unlink('_cache/'.$id);
    }
    if(!isset($_GET['retry']))
    {
        $redos = "?id=".$id."&e=.m3u8&retry";
        http_response_code(307);
        header("Location: $redos");
        exit();
    }
    else
    {
        http_response_code(404);
        exit();
    }
}

?>