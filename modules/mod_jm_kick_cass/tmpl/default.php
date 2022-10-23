<?php
/**
 * @version     1.5
 * @package     mod_jm_kick_cass
 * @copyright   Copyright (C) 2021. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @author      Maarten Blokdijk / www.kickstartcassiopeia.com
 */
//No Direct Access
defined('_JEXEC') or die;
use \Joomla\CMS\Factory;
use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Uri\Uri;

// current URI
$uri = Uri::getInstance();
$base=$uri->base();

$cc_textdecoration      = $params->get('textdedoration', '0');
$cc_cookienotice        = $params->get('CookieNotice');
$cc_popup               = $params->get('Popup');
$cc_showshare           = $params->get('ShowShare');
$cc_hidelogo            = $params->get('HideLogo');
$cc_centerlogo          = $params->get('CenterLogo');
$cc_containerfw         = $params->get('containerfw');
$cc_topbar              = $params->get('enable_topnav');
$cc_customhead          = $params->get('customhead');
$navbarshadow           = $params->get('navbarshadow');
$useupper               = $params->get('useupper');
$sitewidth              = $params->get('sitewidth');
$setfavicon             = $params->get('setfavicon');
$ipblock                = $params->get('setipblock');
$enable_stats           = $params->get('enable_stats');
$menunext               = $params->get('menunext');
$mobilebottombar        = $params->get('mobilebottombar');

$document = Factory::getDocument();
$document->addCustomTag($cc_customhead);

//GET IP FOR STATS EXCLUSION
    //************************************************//
    //************************************************//
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
    }
    $ipstats=" ".$params->get('ipstats');
    $pos=strpos($ipstats, $ip);
    if (!$pos){$pos='0';};

    if ($pos >0 ) {
        $enable_stats='0';
        }


if ($ipblock=='1'){
    //************************************************//
    //************************************************//
        $j1_ip	   	= $params->get('j1_ip');
        $j1_msg	   	= $params->get('j1_msg', 'You are not auhorized');
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (strpos($j1_ip, $ip) === false) {
        die($j1_msg);
        }
}

if ($setfavicon=='1') {
    //************************************************//
    //************************************************//
    //unset favicon icons
    $doc = Factory::getDocument();
    foreach ( $doc->_links as $k => $array ) {
        if ( $array['relation'] == 'icon' ) { unset($doc->_links[$k]);}
        if ( $array['relation'] == 'alternate icon' ) { unset($doc->_links[$k]);  }
        if ( $array['relation'] == 'mask-icon' ) { unset($doc->_links[$k]); }  
    }
    // unset done now we add the custom favicon image
    $document->addCustomTag('<link href="'.$params->get('faviconimage').'" rel="icon" type="image/svg+xml">');
    $document->addCustomTag('<link href="'.$params->get('faviconimage').'" rel="alternate icon" type="image/vnd.microsoft.icon">');
    $document->addCustomTag('<link rel="apple-touch-icon" sizes="180x180" href="'.$base.$params->get('faviconimage').'">');
    $document->addCustomTag('<meta name="msapplication-TileColor" content="'.$params->get('mscolor').'">');
    $document->addCustomTag('<meta name="theme-color" content="'.$params->get('themecolor').'">');
}
    $document->addCustomTag('<link rel="preconnect" href="https://fonts.gstatic.com/">');
    $document->addCustomTag('<link href="https://fonts.googleapis.com/css?family='.$params->get('googleFontNameBody').':'.$params->get('googleFontWeightBody').'" rel="stylesheet">');
    $document->addCustomTag('<link href="https://fonts.googleapis.com/css?family='.$params->get('googleFontNameHeadings').':'.$params->get('googleFontWeightHeadings').'" rel="stylesheet">');

if ($cc_showshare){ 
    //************************************************//
    //************************************************//?>
    <script type='text/javascript'>
    window.addEventListener('load', function (){
        if(typeof addthis_conf == 'undefined'){
            var script = document.createElement('script');
            script.src = '//s7.addthis.com/js/300/addthis_widget.js';
            script.onload = function() { addthis_smart_layers(); }
            document.getElementsByTagName('head')[0].appendChild(script);
            var addthis_product = 'jsl-1.0';
        } else{
            addthis_smart_layers();
        }
    });
    function addthis_smart_layers(){
        addthis.layers({
            'theme' : 'transparent'
            ,'share' : {
                'position' : '<?php echo $params->get('SharePos')?>',
                'numPreferredServices' : <?php echo $params->get('ShareNumber')?>
            }
        });
    };
    </script>

<?php    
}
if ($cc_cookienotice){
    //************************************************//
    //************************************************//
?>
    <style>
    .cookieConsentContainer{text-align:center;z-index:999;width:350px;min-height:20px;box-sizing:border-box;padding:10px 10px 10px 10px;border-radius: <?php echo $params->get('ButtonRadius')?>rem;background:<?php echo $params->get('linkcolor')?>;overflow:hidden;position:fixed;bottom:50px;right:50px;display:none}.cookieConsentContainer .cookieTitle a{color:#FFF;display:block}.cookieConsentContainer .cookieDesc p{margin:0;padding:0;color:#FFF;font-size:13px;line-height:20px;display:block;margin-top:10px}.cookieConsentContainer .cookieDesc a{color:#FFF;text-decoration:underline}.cookieConsentContainer .cookieButton a{display:inline-block;border-radius:<?php echo $params->get('ButtonRadius')?>rem;color:#FFF;font-size:14px;font-weight:700;margin-top:14px;background:#000;box-sizing:border-box;padding:15px 24px;text-align:center;transition:background 0.3s}.cookieConsentContainer .cookieButton a:hover{cursor:pointer;background:#3E9B67;}@media (max-width:980px){.cookieConsentContainer{bottom:0px!important;left:0px!important;width:100%!important}}
    </style>
    <script>
    // --- Config --- //
    var purecookieDesc = "<?php echo $params->get('CookieText')?>"; // Description
    var purecookieLink = '<a href="<?php echo $params->get('CookieLink')?>" target="_blank"><?php echo $params->get('CookieLinkText')?></a>'; // Cookiepolicy link
    var purecookieButton = "<?php echo $params->get('CookieDismiss')?>"; // Button text
    function pureFadeIn(elem, display){var el=document.getElementById(elem); el.style.opacity=0; el.style.display=display || "block"; (function fade(){var val=parseFloat(el.style.opacity); if (!((val +=.02) > 1)){el.style.opacity=val; requestAnimationFrame(fade);}})();};function pureFadeOut(elem){var el=document.getElementById(elem); el.style.opacity=1; (function fade(){if ((el.style.opacity -=.02) < 0){el.style.display="none";}else{requestAnimationFrame(fade);}})();};function setCookie(name,value,days){var expires=""; if (days){var date=new Date(); date.setTime(date.getTime() + (days*24*60*60*1000)); expires="; expires=" + date.toUTCString();}document.cookie=name + "=" + (value || "") + expires + "; path=/";}function getCookie(name){var nameEQ=name + "="; var ca=document.cookie.split(';'); for(var i=0;i < ca.length;i++){var c=ca[i]; while (c.charAt(0)==' ') c=c.substring(1,c.length); if (c.indexOf(nameEQ)==0) return c.substring(nameEQ.length,c.length);}return null;}function eraseCookie(name){document.cookie=name+'=; Max-Age=-99999999;';}function cookieConsent(){if (!getCookie('purecookieDismiss')){document.body.innerHTML +='<div class="cookieConsentContainer" id="cookieConsentContainer"><div class="cookieDesc"><p>' + purecookieDesc + ' ' + purecookieLink + '</p></div><div class="cookieButton"><a onClick="purecookieDismiss();">' + purecookieButton + '</a></div></div>';pureFadeIn("cookieConsentContainer");}}function purecookieDismiss(){setCookie('purecookieDismiss','1',<?php echo $params->get('hidenotice')?>); pureFadeOut("cookieConsentContainer");}window.onload=function(){cookieConsent();};
    </script>
<?php } ?>

<?php 

if ($mobilebottombar){
    //************************************************//
    //************************************************//?>
    <style>
        @media (min-width:200px) and (max-width:768px){
            .bottombar{ position:fixed; bottom:0; z-index:10; width: 100%; height: 60px; display:flex; overflow-x:auto;
            background-color: <?php echo $params->get('mobilebg')?>;
            color: <?php echo $params->get('mobilecolor')?>;
            flex-direction: column;  
            
            align-items: center;
            flex-wrap:wrap;
            justify-content: center;
        }
        .flex-item {padding: 5px; height: 50px; margin-left: 20px; margin-right: 20px; color: <?php echo $params->get('mobilecolor')?>;}
        .bottombar > .row{--gutter-x:0em;}
        .bottombar > .row>*{width:initial;}
        .bottombar ul {padding-inline-start: 0px;}
        }
    </style>

    <div class="bottombar">
    <ul class="bottombar row">
        <?php if($params->get('phone')){?>
            <a href="tel:<?php echo $params->get('phone')?>"><i class="flex-item fas fa-phone-alt fa-2x"></i></a>
        <?php }?>
        <?php if($params->get('whatsapp')){?>
            <a href="https://wa.me/<?php echo $params->get('whatsapp')?>"><i class="flex-item fab fa-whatsapp fa-2x"></i></a>
        <?php }?>
        <?php if($params->get('email')){?>
            <a href="mailto:<?php echo $params->get('email')?>"><i class="flex-item far fa-envelope fa-2x"></i></a>
        <?php }?>
        <?php if($params->get('skype')){?>
            <a href="skype:<?php echo $params->get('skype')?>?call"><i class="flex-item fab fa-skype fa-2x"></i></a>
        <?php }?>
    </ul>
    </div>

<?php }?>
<?php if ($params->get('disablerightclick')=='1'){
    //************************************************//
    //************************************************//?>
    <script>
   var isNS="Netscape"==navigator.appName?1:0;function mischandler(){return!1}function mousehandler(e){var n=isNS?e:event,t=isNS?n.which:n.button;if(2==t||3==t)return!1}"Netscape"==navigator.appName&&document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP),document.oncontextmenu=mischandler,document.onmousedown=mousehandler,document.onmouseup=mousehandler;
    </script>
<?php }?>

<?php if ($cc_popup){ 
    //************************************************//
    //************************************************//
    $popuptext = $params->get('PopupText');
    $popuptext = str_replace("\r", "", $popuptext);
    $popuptext = str_replace("\n", "", $popuptext);
    ?>
    <style>
        #popup { position: fixed; max-width: <?php echo $params->get('popupwidth')?>px; top: 50%; left: 50%; transform: translate(-50%, -50%);z-index: 999;display: none;background-color: <?php echo $params->get('popupbgcolor')?>; color: <?php echo $params->get('popuptextcolor')?>; border-radius: <?php echo $params->get('ContentRadius')?>em;  box-shadow: 0 2px 8px #aaa;  overflow: hidden;   padding: 20px; animation: fadeInAnimation ease <?php echo $params->get('fadeinduration')?>s;  animation-iteration-count: 1;   animation-fill-mode: forwards; } 
        @keyframes fadeInAnimation { 0% { opacity: 0; } 100% { opacity: 1; } }
    </style>

    <script>
    window.onload = addElement;
    function addElement () { 
    var newDiv = document.createElement("div"); 
    newDiv.innerHTML +='<div id="popup" style=""><div class="popup_body" style="  min-height: 100px;"><?php echo $popuptext ?><button class="close_button"onClick="closePopup()"><?php echo $params->get('PopupDismiss')?></button></div>';   
    var currentDiv = document.getElementById("main_container"); 
    document.body.insertBefore(newDiv, currentDiv); 
    openPopup();
    }
    function openPopup() {
        setTimeout(function(){
            var el = document.getElementById('popup');
            el.style.display = 'block';
    },<?php echo $params->get('loadelay')?>);
    }
    function closePopup() {
    var el = document.getElementById('popup');
    el.style.display = 'none';
    }
    </script>
<?php }?>
<style>

    <?php if($cc_hidelogo=='1'){?> .navbar-brand {display:none !important;}<?php } ?>
    <?php if($cc_textdecoration=='0'){?> a {text-decoration:none !important;}<?php } ?>
    <?php if($cc_hidelogo=='1'){?> .navbar-brand {display:none !important;}<?php } ?>
    <?php if($cc_centerlogo=='1'){?> .navbar-brand {margin-left:auto;}<?php } ?>
    <?php if($cc_containerfw=='1'){?> .site-grid>.full-width {grid-column: 2/6;}<?php } ?>
    <?php if($navbarshadow=='1'){?> .header {box-shadow: 0px 1px 2px 0px rgb(60 64 67 / 30%), 0px 2px 6px 2px rgb(60 64 67 / 15%);} <?php } ?>
    <?php if($useupper=='1'){?> .mod-menu, .mod-menu__heading{text-transform:uppercase} <?php } ?>

    <?php 
    
    if ($sitewidth=='1') {?>
        .site-grid{grid-template-columns: [full-start] minmax(0,1fr) [main-start] repeat(4,minmax(0,16.875rem)) [main-end] minmax(0,1fr) [full-end];}
        .header .grid-child {max-width: 70em;}
        .footer .grid-child {max-width: 70em;}
        .topbar .grid-child {max-width: 70em;}
    <?php }
     if ($sitewidth=='2') {?>
        .site-grid{grid-template-columns: [full-start] minmax(0,1fr) [main-start] repeat(4,minmax(0,18.875rem)) [main-end] minmax(0,1fr) [full-end];}
        .header .grid-child {max-width: 78em;}
        .footer .grid-child {max-width: 78em;}
        .topbar .grid-child {max-width: 78em;}
    <?php } 
    if ($sitewidth=='3') {?>
        .site-grid{grid-template-columns: [full-start] minmax(0,1fr) [main-start] repeat(4,minmax(0,21.875rem)) [main-end] minmax(0,1fr) [full-end];}
        .header .grid-child {max-width: 90em;}
        .footer .grid-child {max-width: 90em;}
        .topbar .grid-child {max-width: 90em;}
    <?php } 
    if ($menunext=='1'){
        // set logo next to menu
        //************************************ ?>
    @media (min-width:768px) {
        .container-nav {position:fixed; top:20px;}
        .navbar {margin-left: auto; order: 2; margin-right: 20px !important;}
        .container-header nav {margin-top: 0px}
        .navbar-brand {z-index:10;  top: 20px}
        .header .grid-child {max-width: 100%; padding-bottom: 40px !important; padding-left: <?php echo $params->get('menubarpadding')?>vw; padding-right: <?php echo $params->get('menubarpadding')?>vw; padding-top:0 !important}
        .container-header .container-search {order: 3;}
        
    }
    <?php } ?>
    :root{  
        --cassiopeia-color-primary:<?php echo $params->get('primarycolor')?>;
        --cassiopeia-color-link:<?php echo $params->get('linkcolor')?> ;
        --cassiopeia-color-hover:<?php echo $params->get('hovercolor')?> ;
        --cassiopeia-font-family-body: "<?php echo $params->get('googleFontNameBody')?>" ;
        --cassiopeia-font-family-headings: "<?php echo $params->get('googleFontNameHeadings')?>" ;
        --cassiopeia-font-weight-headings: <?php echo $params->get('googleFontWeightHeadings')?> ;
        --cassiopeia-font-weight-normal: <?php echo $params->get('googleFontWeightBody')?> ;
    }
    html {background: url("<?php echo $base?><?php echo $params->get('BackgroundImage')?>");background-repeat: no-repeat; background-position: center center; background-size: cover;  background-attachment: fixed; }
    p,li,ul,td,table {font-size: <?php echo $params->get('BodyFontSize')?>rem !important}
    .atss {top: <?php echo $params->get('socialtop')?>%}
    body {background-color: <?php echo $params->get('backgroundcolor')?>; }
    .brand-logo {font-family: "<?php echo $params->get('googleFontNameHeadings')?>"}   
    .btn-primary{color: <?php echo $params->get('btn-primary-color')?> ; background-color: <?php echo $params->get('btn-primary-background-color')?>; border-color: <?php echo $params->get('btn-primary-border-color')?>}
    .btn-secondary{color: <?php echo $params->get('btn-secondary-color')?> ; background-color: <?php echo $params->get('btn-secondary-background-color')?>; border-color: <?php echo $params->get('btn-secondary-border-color')?>}
    .btn-info{color: <?php echo $params->get('btn-info-color')?> ; background-color: <?php echo $params->get('btn-info-background-color')?>; border-color: <?php echo $params->get('btn-info-border-color')?>}
    .btn-success{color: <?php echo $params->get('btn-success-color')?> ; background-color: <?php echo $params->get('btn-success-background-color')?>; border-color: <?php echo $params->get('btn-success-border-color')?>}
    .btn-warning{color: <?php echo $params->get('btn-warning-color')?> ; background-color: <?php echo $params->get('btn-warning-background-color')?>; border-color: <?php echo $params->get('btn-warning-border-color')?>}
    .btn-danger{color: <?php echo $params->get('btn-danger-color')?> ; background-color: <?php echo $params->get('btn-danger-background-color')?>; border-color: <?php echo $params->get('')?>}
    .blog-item {background-color: <?php echo $params->get('ContentBackgroundColor')?>}
    .btn, .badge {border-radius: <?php echo $params->get('ButtonRadius')?>rem}
    .card-header{background-color: <?php echo $params->get('BackgroundColorCardHeader')?> }
    .card, .mm-collapse, .breadcrumb, .item-content, .blog-item, .item-image, .item-page, .card-header, .left.item-image img, .category-list, .reset, .remind, .pagination,.page-link, .login, .list-group-item, .finder, .no-card .newsflash-horiz li {border-radius: <?php echo $params->get('ContentRadius')?>em !Important}
    .close_button {float:right; bottom: 5px; border-radius: <?php echo $params->get('ButtonRadius')?>rem; padding: 5px;}
    .container-header .metismenu>li.active>a:after, .container-header .metismenu>li.active>button:before, .container-header .metismenu>li>a:hover:after, .container-header .metismenu>li>button:hover:before {background: <?php echo $params->get('primarycolor')?>; opacity: 1}
    .container-banner .banner-overlay .overlay {background-color: <?php echo $params->get('BannerOverlay')?>;}
    .container-bottom-a>*, .container-bottom-b>*, .container-top-a>*, .container-top-b>* {margin: 0em;}
    .container-top-a {background-color:<?php echo $params->get('TopABackground')?> }
    .container-top-b {background-color:<?php echo $params->get('TopBBackground')?>}
    .container-bottom-a {background-color:<?php echo $params->get('BottomABackground')?> }
    .container-bottom-b {background-color:<?php echo $params->get('BottomBBackground')?>  }
    .container-banner .banner-overlay {height:<?php echo $params->get('BannerHeight')?>vh }
    .container-header .metismenu>li.level-1>ul {min-width: <?php echo $params->get('subwidth')?>rem;}
    .container-header .mod-menu, .container-header .navbar-toggler {color: <?php echo $params->get('MenuTextColor')?>}
    .card-header {color: <?php echo $params->get('CardheaderTextColor')?>;}
    .container-header {background: url(<?php echo $base?><?php echo $params->get('MenuBackgroundImage')?>) ; box-shadow: inset 0 0 0 5000px  <?php echo $params->get('MenubarBackgroundColor')?>; background-size: cover; background-repeat: no-repeat; background-attachment:fixed; background-position:top,50%; }
    .footer {background: url(<?php echo $base?><?php echo $params->get('FooterBackgroundImage')?>) ; box-shadow: inset 0 0 0 5000px  <?php echo $params->get('FooterBackgroundColor')?>;background-size: 100% auto; background-repeat: no-repeat; }
    .footer .grid-child {align-items:flex-start}
    .h1, h1 {font-size:<?php echo $params->get('H1FontSize')?>rem }
    .h2, h2 {font-size:<?php echo $params->get('H2FontSize')?>rem }
    .h3, h3 {font-size:<?php echo $params->get('H3FontSize')?>rem }
    .h4, h4 {font-size:<?php echo $params->get('H4FontSize')?>rem }
    .h5, h5 {font-size:<?php echo $params->get('H5FontSize')?>rem }
    .item-page, .com-users, .com-users-reset, .com-users-remind, .com-users-profile, .com-content-category, .card, .mod-articlesnews-horizontal li, .breadcrumb, .finder, .login {background-color: <?php echo $params->get('ContentBackgroundColor')?> !important; padding: 15px;}
    .item-content {padding: 15px; }
    .metismenu.mod-menu .metismenu-item {flex-wrap: wrap !Important; padding: <?php echo $params->get('menupadding')?>px;}
    .navbar-brand {font-family: <?php echo $params->get('googleFontNameHeadings')?>;padding-top: 0rem; padding-bottom: 0rem;}
    .result__title-text {font-size: 1.286rem; font-size: 1.5rem; color: <?php echo $params->get('primarycolor')?>}
    .result__item>*+* {margin-left: 1em; margin-bottom: 1em;  }
    <?php echo $params->get('customcss')?>
        @media (min-width:200px) and (max-width:768px){.footer .grid-child {display:flex; flex: 1 1 300px; flex-direction: column} <?php echo $params->get('customcssmobile')?>}
        @media (min-width:768px) {.bottombar{display:none;} <?php echo $params->get('customcssdesktop')?>}

    }
</style>

<?php if ($cc_topbar){
    //************************************************//
    //************************************************//?>
    <style>
        .container-header {padding-top: <?php echo $params->get('topnavheight')?>px;} 
        .topbar{ margin-right:auto; z-index: 99; padding: <?php echo $params->get('topnavpadding')?>px; position:fixed; top:0; background-color: <?php echo $params->get('topnavbg')?>; width: 100%; color:<?php echo $params->get('topnavcolor')?>;}
        .topbar a {color: <?php echo $params->get('topnavlinkcolor')?> }
        .topbar p {margin-bottom:0}
        <?php if ($params->get('topnavfloatright')){?>
            .topbar .grid-child {justify-content: flex-end}
        <?php }?>
        <?php if ($menunext=='1'){?>
            @media (min-width:768px) {
                .container-header nav, .container-header .container-search { margin-top: <?php echo $params->get('topnavheight')?>px; } 
                .topbar {padding-left: <?php echo $params->get('menubarpadding')?>vw; padding-right: <?php echo $params->get('menubarpadding')?>vw;} 
            }
        <?php } ?>
    </style>

    <?php if ($menunext=='1'){?>
        <div  class="topbar">
        <?php echo $params->get('topnavcontent')?>
        </div>
    <?php } ?>
    <?php if ($menunext!=='1'){?>
        <div class="topbar">
        <div class="grid-child">
            <?php echo $params->get('topnavcontent')?>
        </div>
        </div>
    <?php } ?>

<?php }?>



