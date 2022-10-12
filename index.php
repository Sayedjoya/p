<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Home | aki live</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&display=swap" rel="stylesheet" />
<link rel="shortcut icon" href="https://www.youtube.com/s/desktop/fc793d5a/img/favicon.ico"/>
<script type='text/javascript' src='https://content.jwplatform.com/libraries/IDzF9Zmk.js'></script>
<script type="text/javascript">jwplayer.key = 'Khpp2dHxlBJHC8MCmLnBuV2jK/DwDnJMniwF6EO9HC/riJ712ZmbHg==';</script>

<style>
body { background-color: #000000; font-family: 'Open Sans', sans-serif; }
.fire_head { background-color: #000000; padding: 20px; }
#fireTxtLogo { font-weight: bold; font-size: 18px; user-select: none; color:#FFFFFF;}
.fire_menu { background-color: #C5CDCD; padding-top: 4px; padding-bottom: 4px; padding-left: 12px; padding-right: 12px;}
.fire_row_load { display: none; margin-bottom: 30px; margin-top: 20px; justify-content: center; text-align: center;}
@media (min-width:1025px) { .row { overflow: hidden; margin-left: 13px; margin-right: 13px; } }
#inerr { color: #FFFFFF; }
#main_err_holder { height: 100vh; display: none !important;}

.sectionName{ color: #FFFFFF; font-weight: bold; font-size: 20px; }
.latest_items { margin-top: 12px; }
#item_holder_text { color: #FFFFFF; margin-top: 3px !important; padding: 3px; text-align:center !important; font-size: 14px;}
.albumdemoholder { margin-bottom: 3px; }
.acmlaDF { font-size: 13.5px; margin-bottom: 5px;}
</style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center" id="main_err_holder">
    <div class="inline-block align-middle">
    	<h2 class="font-weight-normal lead" id="inerr">Unable To Process Your Request</h2>
    </div>
</div>

<div class="fire_head">
    <img src="logo.png" width="35" height="35" alt=""/>&nbsp;&nbsp;&nbsp;&nbsp;<span id="fireTxtLogo">AKI TV</span>
    <div id="open_settings" style="float: right;"><button class="btn btn-light btn-sm"> Settings </button></div>
</div>

<div class="fire_menu">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Channel Here" aria-label="Search Channel Here" id="look_tv_here">
        <button class="btn btn-danger btn-sm" type="button" id="look_tv_button">Search</button>
    </div>
</div>

<div class="fire_catalouge row mt-4"></div>
<div class="fire_row_load" id="fire_row_load"><button id="load_more_anbtn" data-page="" style="padding-left: 25px; padding-right: 25px; padding-top: 10px; padding-bottom: 10px;" class="btn btn-danger"> Load More</button></div>

<!-- TV Details API -->
<div class="modal fade" id="movieDtlModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="movieDtlModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="stopJWPlayer()"></button>
        </div>
        <div class="modal-body">
  
          <div class="video-container">
              <div id="vplayer" style="height: auto; text-align: center;"></div>
          </div>
  
        </div>
      </div>
    </div>
  </div>
  <!-- //TV Details API -->
  
<!-- Settings Modal -->
<div class="modal fade" id="fireSetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="fireSetModalLabel">Settings</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="stopJWPlayer()"></button>
        </div>
        <div class="modal-body">
            <div class="">
                <label class="acmlaDF"><b>M3U Playlist Link</b></label>
                <input type="text" id="meu_link" class="form-control" placeholder="M3U Playlist Link" autocomplete="off"/>
            </div>
        </div>
      </div>
    </div>
  </div>
 <!-- //Settings Modal -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script>

$(document).ready(function(){
    load_tv('list', 1);
});

$("#look_tv_button").on("click", function(){
    load_tv('search', '');
});

/*
$("#look_tv_here").keyup(function(){
  load_tv('search', '');
});
*/

function load_tv(action, page)
{
    let data_payload = "";
    let data_api = "app/getData.php";
    let squery = $("#look_tv_here").val();
    
    if(page == null || page == undefined || page == "")
    {
        page = 1;
    }
    if(action == "search")
    {
        data_payload = "action=search&q=" + squery;
    }
    else
    {
        data_payload = "action=channels&page=" + page;
    }
    
    $.ajax({
        "url": data_api,
        "type": "GET",
        "data": data_payload,
        "beforeSend": function(data)
        {
            
        },
        "success":function(data)
        {
            try { data = JSON.parse(data); }catch(err){}
            if(data.status == "success")
            {
                let uere = "";
                $.each(data.data.items, function(k, v){
                    uere = uere + '<div class="col-6 col-sm-4 col-lg-3 col-xl-2" data-id="' + v.id +'" onclick="play_the_video(this)">';
                    uere = uere + '<div class="card" style="background-color: #000000; border: none !important;"><img src="' + v.logo + '" class="card-img-top" style="border-radius: 12px;" width="140" height="130" alt="' + v.title + '" />';
                    uere = uere + '<div class="card-body" style="background-color: #000000; text-align: center !important;">';
                    uere = uere + '<h5 class="card-title" style="color: #FFFFFF !important; font-size: 15px;">' + v.title + '</h5>';
                    uere = uere + '</div></div></div>';
                });
                if(page > 1)
                {
                    $(".fire_catalouge").append(uere);
                }
                else
                {
                    $(".fire_catalouge").html(uere);
                }
                
                $("#load_more_anbtn").attr("data-page", page);
                if(action == "search")
                {
                    $("#fire_row_load").fadeOut();
                }
                else
                {
                    $(".fire_row_load").fadeIn();
                }
                
            }
            else
            {
                if(action == "search")
                {
                    if(data.code == "454")
                    {
                        load_tv('list', 1);
                    }
                    else
                    {
                        alert(data.message);
                    }
                    
                }
                else
                {
                    if(page > 1)
                    {
                        $(".fire_row_load").fadeOut();
                    }
                    else
                    {
                        $("#main_err_holder").fadeIn();
                    }
                }
            }
            
            
        },
        "error":function(data)
        {
            if(page > 1)
            {
                $(".fire_row_load").fadeIn();
            }
            else
            {
                $("#main_err_holder").fadeIn();
            }
        }
    });
}

$("#load_more_anbtn").on("click", function(){
    let current_page = $("#load_more_anbtn").attr("data-page");
    let next_page = Number(current_page) + Number(1);
    $(".fire_row_load").fadeOut();
   load_tv('list', next_page);
});

function play_the_video(e)
{
    let media_id = $(e).attr("data-id");
    $.ajax({
        "url": "app/getData.php",
        "type": "GET",
        "data": "action=details&id=" + media_id,
        "beforeSend": function(xhr)
        {
            
        },
        "success":function(data)
        {
            try { data = JSON.parse(data); }catch(err){}
            if(data.status == "success")
            {
                renderTVModal(data.data);
            }
            else
            {
                if(data.status == "error")
                {
                    alert(data.message);
                }
                else
                {
                    alert("Something Went Wrong");
                }
            }
        },
        "error":function(data)
        {
            alert("Failed To Connect With Server");
        }
    });
}

function renderTVModal(data)
{
    $("#movieDtlModalLabel").html(data.title);
    $("#movieDtlModal").modal("show");
    setupplayer(data.playurl, data.logo);
}

function setupplayer(playurl, poster)
{
  jwplayer("vplayer").setup(
    {
        sources:
        [
            { file:playurl}
        ],
        autostart: false,
        width:"100%",
        image: poster,
        height:"auto",
        stretching:"uniform",
        duration:"",
        preload:"metadata",
        androidhls:"true",
        hlshtml:"false",
        primary:"html5",
        startparam:"start",
        playbackRateControls:[0.25,0.5,0.75,1,1.25,1.5,2],
        logo:
        {
            file:poster,
            link:"",
            position:"top-right",
            margin:"5",
            hide:true
        }
    });
}

function stopJWPlayer()
{
    jwplayer().stop();
}

$("#open_settings").on("click", function(){
    $.ajax({
        "url" : "app/getData.php",
        "data": "action=get_m3ulink",
        "type": "GET",
        "beforeSend": function(xhr)
        {
            
        },
        "success": function(data)
        {
            try { data = JSON.parse(data); }catch(err){}
            if(data.status == "success")
            {
                $("#meu_link").val(data.data);
            }
        },
        "error":function(data)
        {
            
        }
    });
    $("#fireSetModal").modal("show");
});

</script>
</body>
</html>
