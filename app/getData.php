<?php

include('_configs.php');

$id = "";
$page = 1;
$query = "";
$action = "";
$items = array();

if(isset($_REQUEST['q']))
{
    $query = trim($_REQUEST['q']);
}

if(isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
}

if(isset($_REQUEST['page']))
{
    $page = $_REQUEST['page'];
}

if(isset($_REQUEST['action']))
{
    $action = $_REQUEST['action'];
}

if(!is_numeric($page)) { $page = 1; }

if($action == "channels")
{
    $items_per_page = 24;
    $offset = ($page - 1) * $items_per_page;
    $channels_data = $CHANNEL_LIST;
    if(empty($channels_data))
    {
        response('error', '400', 'Channel Data Not Available. Please Update App', '');
    }
    else
    {
        foreach($channels_data as $intv)
        {
            $items[] = array('id' => $intv['id'],
                             'title' => $intv['title'],
                             'logo' => $intv['logo'],
                             'category' => $intv['category'],
                             'language' => $intv['language']);

        
        }
        $semtalz = array_slice($items, $offset, $items_per_page);
        if(empty($semtalz))
        {
            response('error', '404', 'No More Channels To Show', '');
        }
        else
        {
            response('success', '200', 'Channel List', array('page' => $page, 'items' => $semtalz));
        }
    }
}
elseif($action == "search")
{
    if(empty($query))
    {
        response('error', '454', 'Please Enter Something To Search', '');
    }
    $channels_data = $CHANNEL_LIST;
    if(empty($channels_data))
    {
        response('error', '400', 'Channel Data Not Available. Please Update App', '');
    }
    else
    {
        foreach($channels_data as $intv)
        {
            if(stripos($intv['title'], $query) !== false)
            {
                $items[] = array('id' => $intv['id'],
                                 'title' => $intv['title'],
                                 'logo' => $intv['logo'],
                                 'category' => $intv['category'],
                                 'language' => $intv['language']);
            }
        }
        if(empty($items))
        {
            response('error', '404', 'No Match Found', '');
        }
        else
        {
            response('success', '200', 'Search Query Successful', array('query' => $query, 'items' => $items));
        }
    }
}
elseif($action == "details")
{
    $chtvs = array();
    if(empty($id))
    {
        response('error', '400', 'Channel ID Required', '');
    }
    $ivbz = $CHANNEL_LIST;
    if(!empty($ivbz))
    {
        foreach($ivbz as $yad)
        {
            if($id == $yad['id'])
            {
                $chtvs = $yad;
            }
        }
    }
    else
    {
        response('error', '400', 'Channel List Not Available. Please Update App', '');
    }
    
    if(empty($chtvs))
    {
        response('error', '400', 'Channel ID Invalid', '');
    }
    
    if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
    {
        $playurl = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).'master.php?id='.$chtvs['id'].'&e=.m3u8';
    }
    else
    {
        $playurl = $streamenvproto.'://'.$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).'master.php?id='.$chtvs['id'].'&e=.m3u8';
    }
    
    $uoro = array('id' => $chtvs['id'],
                  'title' => $chtvs['title'],
                  'logo' => $chtvs['logo'],
                  'category' => $chtvs['category'],
                  'language' => $chtvs['language'],
                  'playurl' => $playurl);
    response('success', '200', 'Channel Detail Retrieved Successfully', $uoro);

}
elseif($action == "get_m3ulink")
{
    $playlist_path = "m3uPlaylist.m3u";
    if($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443")
    {
        $outputplaylink = $streamenvproto.'://'.$local_ip.':'.$_SERVER['SERVER_PORT'].str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).$playlist_path.'?v='.time());
    }
    else
    {
        $outputplaylink = $streamenvproto.'://'.$local_ip.str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']).$playlist_path.'?v='.time());
    }
    response('success', '200', 'M3U Link Generated', $outputplaylink);
}
else
{
    response('error', '400', 'Please Provide Valid Action To Execute', '');
}

?>