<?php

include('_configs.php');

if(isset($ENABLE_CROSS_DOMAIN_STREAMING))
{
    if($ENABLE_CROSS_DOMAIN_STREAMING == "yes")
    {
        header("Access-Control-Allow-Origin: *");
    }
}

$channelsList = array();
$playlistData = "";

$ivbz = $CHANNEL_LIST;
if(!empty($ivbz))
{
    $channelsList = $ivbz;
}
else
{
    response('error', '400', 'Channel Data Not Available. Please Update App', '');
}

$playlistData .= '#EXTM3U'."\n";
$v = 0;
foreach($channelsList as $mere)
{
    $v++;
    $playlistData .= '#EXTINF:-1 tvg-id="'.$mere['id'].'" tvg-name="'.$mere['title'].'" tvg-country="IN" tvg-logo="'.$mere['logo'].'" tvg-chno="'.$v.'" group-title="'.$mere['category'].' - '.$mere['language'].'",'.$mere['title']."\n";
    if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
    {
        $playlistData .= $streamenvproto.'://'.$local_ip.':'.$_SERVER['SERVER_PORT'].str_replace(" ", "%20", trim(str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).'master.php?id='.$mere['id'].'&e=.m3u8'))."\n";
    }
    else
    {
        $playlistData .= $streamenvproto.'://'.$local_ip.str_replace(" ", "%20", trim(str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).'master.php?id='.$mere['id'].'&e=.m3u8'))."\n";
    }

}    

if(!empty($playlistData))
{
    $fileascasc = 'm3u_output_'.rand(000, 999).rand(000, 999).rand(000, 999).'.m3u';
    header('Content-Disposition: attachment; filename="'.$fileascasc.'"');
    header("Content-Type: application/vnd.apple.mpegurl");
    print($playlistData);
}
else
{
    http_response_code(404);
    exit();
}

?>