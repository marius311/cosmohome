<?php

require_once("../inc/util.inc");
require_once("../inc/phpmailer/PHPMailerAutoload.php");

$master_url = parse_config(get_config(), "<master_url>");
define("PROJECT", "Cosmology@Home");
define("URL_BASE", $master_url);
define("SECURE_URL_BASE",str_replace("http","https",$master_url));
define("IMAGE_PATH", "../user_profile/images/");
define("IMAGE_URL", "user_profile/images/");
define("PROFILE_PATH", "../user_profile/");
define("PROFILE_URL", "user_profile/");
define("LANGUAGE_FILE", "languages.txt");
define("STYLESHEET", "cosmohome.css");
define("FORUM_QA_MERGED_MODE", true);
define("COPYRIGHT_HOLDER", "Department of Astronomy at the <a href=\"http://www.illinois.edu\">University of Illinois at Urbana-Champaign</a>");
define("SYS_ADMIN_EMAIL", "mariusmillea@gmail.com");
define("UOTD_ADMIN_EMAIL", "mariusmillea@gmail.com"); # who gets user of the day pool running low e-mails?

//-------------- Project-specific preferences

define("COLOR_PREFS", false);
    // user can select screensaver color scheme
define("GFX_CPU_PREFS", false);
    // user can limit % CPU used by screensaver
    // (lower frame rate if exceeded)
    // This is probably irrelevant if your screensaver uses OpenGL
define("APP_SELECT_PREFS", true);
    // user can choose which apps to run
define("NON_GRAPHICAL_PREF", false);
    // user can choose to run faster non-graphical app versions if available
define("MAX_JOBS_PREF", true);
    // user can choose max # of jobs in progress
define("MAX_CPUS_PREF", true);
    // user can choose max # of CPUs to use


// Email addresses separated by pipe ( | ) that will receive user reported 
// offensive forum posts.
define("POST_REPORT_EMAILS", "mariusmillea@gmail.com|bwandelt@illinois.edu|jrenkar@hughes.net|tutta55@boinc.be|elst93@gmail.com");

function page_head(
    $title,
        // page title. Put in <title>, used as title for browser tab.
    $body_attrs=null,
        // <body XXXX>
        // e.g. Javascript to put focus in an input field
        // (onload="document.form.foo.focus()")
        // or to jump to a particular post (onload="jumpToUnread();")
    $is_main = false,
        // if set, include schedulers.txt.
        // also pass to project_banner() in case you want a different
        // header for your main page.
    $url_prefix="",
        // prepend this to links.
        // Use for web pages not in the top directory
    $head_extra=null
        // extra stuff to put in <head>. E.g.:
        // reCAPTCHA code (create_profile.php)
        // bbcode javascript (forums)
) {
    global $caching, $cache_control_extra, $did_page_head;
    global $is_login_page, $fixed_navbar;

    $did_page_head = true;
    $url_base = url_base();

    $rssname = "RSS 2.0";
    $rsslink = $url_base."rss_main.php";

    if (!$caching) {
        header("Content-type: text/html; charset=utf-8");
        header ("Expires: Mon, 26 Jul 1997 05:00:00 UTC");    // Date in the past
        header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " UTC"); // always modified
        header ("Cache-Control: $cache_control_extra no-cache, must-revalidate, post-check=0, pre-check=0");  // HTTP/1.1
        header ("Pragma: no-cache");                          // HTTP/1.0
    }

    echo '<!DOCTYPE html>
        <html lang="en">
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    ';
    if ($head_extra) {
        echo "\n$head_extra\n";
    }
    if ($is_main && (!defined('NO_COMPUTING')||!NO_COMPUTING)) {
        readfile("schedulers.txt");
    }

    $t = $title?$title:PROJECT;
    echo "<title>$t</title>\n";
    echo '
        <meta charset="utf-8">
        <link type="text/css" rel="stylesheet" href="'.url_base().'/bootstrap.min.css" media="all">
    ';
    if (defined('STYLESHEET')) {
        $stylesheet = $url_base.STYLESHEET;
        echo "
            <link rel=stylesheet type=\"text/css\" href=\"$stylesheet\">
        ";
    }
    if (defined('STYLESHEET2')) {
        $stylesheet2 = $url_base.STYLESHEET2;
        echo "
            <link rel=stylesheet type=\"text/css\" href=\"$stylesheet2\">
        ";
    }

    if (defined("SHORTCUT_ICON")) {
        echo '<link rel="icon" type="image/x-icon" href="'.SHORTCUT_ICON.'"/>
';
    }

    echo "
        <link rel=alternate type=\"application/rss+xml\" title=\"$rssname\" href=\"$rsslink\">
        
        </head>
    ";
    if ($fixed_navbar) {
        $body_attrs .= ' style="padding-top:70px"';
    }
    echo "<body $body_attrs>";
    display_cvs_versions();
    echo '<div class="container">
    ';

    switch($title) {    //kludge
    case tra("Log in"):
    case tra("Create an account"):
    case tra("Server status page"):
        $is_login_page = true;
        break;
    default:
        break;
    }
    project_banner($title, $url_prefix, $is_main);
    
    echo "<div class='content'>";

}


function project_banner($title, $prefix) {
    // Put your project title and logo here
    $url_base = url_base();
echo <<<EOL
   <!-- Navigation -->
    <a href='$url_base'><img class="banner" src='/img/banner.jpg'/></a>
    
    <nav class="navbar navbar-default" id="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#important-id-for-collapsing" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Cosmology@Home</a>
            </div>
            <div class="collapse navbar-collapse" id="important-id-for-collapsing">
                <ul class="nav navbar-nav">
                      <li class='nav-item'>
                        <a class='nav-link' href='/join.php'>Join</a>
                      </li>
                      <li class='nav-item'>
                        <a class='nav-link' href='/faq.php'>FAQ</a>
                      </li>
                      <li class='nav-item'>
                        <a class='nav-link' href='/forum_index.php'>Forums</a>
                      </li>
                      <li class='dropdown'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Community
                        <span class="caret"></span></a>
                        <ul class='dropdown-menu'>
                          <li><a class='dropdown-item' href='/home.php'>Your Account</a></li>
                          <li><a class='dropdown-item' href='/team.php'>Teams</a></li>
                          <li><a class='dropdown-item' href='/downloads.php'>Desktop Backgrounds</a></li>
                          <li><a class='dropdown-item' href='/profile_menu.php'>Participant Profiles</a></li>
                          <li><a class='dropdown-item' href='/wandelt_letter.php'>Ben's Welcome Letter</a></li>
                        </ul>
                      </li>
                      <li class='nav-item dropdown'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Server
                        <span class="caret"></span></a>
                        <ul class='dropdown-menu'>
                          <li><a class='dropdown-item' href='/apps.php'>Applications</a></li>
                          <li><a class='dropdown-item' href='/server_status.php'>Server Status</a></li>
                          <li><a class='dropdown-item' href='https://github.com/marius311/cosmohome'>Development</a></li>
                        </ul>
                      </li>
                      <li class='nav-item dropdown'>
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Stats
                        <span class="caret"></span></a>
                        <ul class='dropdown-menu'>
                          <li><a class='dropdown-item' href='/lsplitsims_stats.php'>Planck Sim Stats</a></li>
                          <li><a class='dropdown-item' href='/top_users.php'>Top Participants</a></li>
                          <li><a class='dropdown-item' href='/top_hosts.php'>Top Computers</a></li>
                          <li><a class='dropdown-item' href='/top_teams.php'>Top Teams</a></li>
                          <li><a class='dropdown-item' href='stats.php'>Other Statistics</a></li>
                        </ul>
                      </li>
                </ul>
EOL;
            
    $user = get_logged_in_user(false);
    global $is_login_page;
    echo '
      <ul class="nav navbar-nav navbar-right">
    ';
    if ($user) {
        echo sprintf('
            <li><a href=%s%s>%s</a></li>
            ', url_base(), USER_HOME, $user->name
        );
        $url_tokens = url_tokens($user->authenticator);
        echo sprintf('<li><a href="%slogout.php?%s">Log out</a></li>',
            url_base(), $url_tokens
        );
    } else {
        echo sprintf('
            <li><a href="%screate_account_form.php">Sign Up</a></li>
            <li><a href="%slogin_form.php">Login</a></li>
            ', url_base(), url_base()
        );
    }
    
echo <<<EOL
            </div>
        </div>
    </nav>    
EOL;
   
}

function project_footer($show_return, $show_date) {
    echo "</div>";
    
    // TODO: enable sticky navbar
    // echo "
    // <script>
    // // When the user scrolls the page, execute myFunction 
    // window.onscroll = function() {myFunction()};
    // 
    // // Get the navbar
    // var navbar = document.getElementById('navbar');
    // 
    // // Get the offset position of the navbar
    // var sticky = navbar.offsetTop;
    // 
    // // Add the sticky class to the navbar when you reach its scroll position. Remove 'sticky' when you leave the scroll position
    // function myFunction() {
    //   if (window.pageYOffset >= sticky) {
    //     navbar.classList.add('sticky')
    //   } else {
    //     navbar.classList.remove('sticky');
    //   }
    // }
    // </script>
    // ";  
    
    
    echo " 
          <hr>
          <div id='footer'>";

    if ($show_return) {
        echo "<a href=".URL_BASE.">Return to ".PROJECT." main page</a>\n";
    }
    echo "<br><br>Copyright &copy; ".date("Y ").COPYRIGHT_HOLDER."<br/>\n";

	echo <<<END

If you have any questions or comments regarding the content of this site, please contact <a href='http://www.cosmicmar.com'>Marius Millea</a><br/>
This material is based upon work supported by the National Science Foundation under Grant No. AST0507676 and the Labex ILP (reference ANR-10-LABX-63).<br/>
Links to external sites are provided as a convenience to our users. The Department of Astronomy does not control or endorse the content of external sites.<br\>
Any opinions, findings, and conclusions or recommendations expressed in this material are those of the author(s) and do not necessarily reflect the views of the National Science Foundation.<br/>
We would like to thank both NASA and the Planck project for their support of Cosmology@Home</br>
END;
    if ($show_date) {
        echo "<font size=-2 color=bbbbbb>Generated ",time_str(time()),"</font>\n";
    }
	echo " 
	<div id='sponsors'>
<a href='http://boinc.berkeley.edu' target='_blank'><img class='logo' src='/img/logos/boinc.png' alt='boinc'></a>
<a href='http://www.esa.int' target='_blank'><img class='logo' src='/img/logos/esa.png' alt='esa'></a>
<a href='http://www.jpl.nasa.gov' target='_blank'><img class='logo' src='/img/logos/jpl.png' alt='JPL'></a>
<a href='http://ilp.upmc.fr' target='_blank'><img class='logo' src='/img/logos/logo-ILP-2.png' alt='ILP'></a>
<a href='http://www.nasa.gov' target='_blank'><img class='logo' src='img/logos/nasa.png' alt='nasa'></a>
<a href='http://www.nsf.gov' target='_blank'><img class='logo' src='/img/logos/nsf.jpg' alt='nsf'></a>
<a href='http://planck.caltech.edu' target='_blank'><img class='logo' src='/img/logos/planck-logo.gif' alt='planck'></a>
</div></div>";
}

function show_profile_heading1() {
    return tra("Your personal background.");
	}

function show_profile_question1() {
    return tra("Tell us about yourself. You could tell us where you're from, your age, occupation, hobbies, or anything else about yourself.");
}

function show_profile_heading2() {
    return tra("Your opinions about %1", PROJECT);
}

function show_profile_question2() {
    return tra("Tell us your thoughts about %1<ol>
    <li>Why do you run %1?
        <li>What are your views about the project?
        <li>Any suggestions?
    </ol>", PROJECT);
}

function project_workunit($wu){
    // shown in the workunit page
}

function project_user_summary($user){
    // shown in the user summary page
}

function project_user_page_private($user){
    // shown in the private account page
}
if (0) {

// show project-specific credit on user/team pages
//
function show_app_credit_user($user, $app_name, $appids) {
    $t = 0;
    $a = 0;
    $n = 0;
    foreach ($appids as $appid) {
        $cu = BoincCreditUser::lookup(
            "userid=$user->id and appid=$appid and credit_type=0"
        );
        if ($cu) {
            $t += $cu->total;
            $a += $cu->expavg;
            $n += $cu->njobs;
        }
    }
    row2("$app_name credit",
        format_credit_large($t)." total, ".
        format_credit($a)." average".
        " ($n tasks)"
    );
}
function show_app_credit_team($team, $app_name, $appids) {
    $t = 0;
    $a = 0;
    $n = 0;
    foreach ($appids as $appid) {
        $ct = BoincCreditTeam::lookup(
            "teamid=$team->id and appid=$appid and credit_type=0"
        );
        if ($ct) {
            $t += $ct->total;
            $a += $ct->expavg;
            $n += $ct->njobs;
        }
    }
    row2("$app_name credit",
        format_credit_large($t)." total, ".
        format_credit($a)." average".
        " ($n tasks)"
    );
}

// a list of "sub-projects", used in the display of per-app credit and badges.
// A subproject is:
// - a set of 1 or more apps; an app can belong to at most 1 subproject.
// - a name, shown on the web site
// - a short name, used in badge names.  Don't use "total".
//
$sub_projects = array(
    array("name" => "Remote Test", "short_name" => "RT", "appids" => array(16)),
    array("name" => "Uppercase", "short_name" => "UC", "appids" => array(1, 25)),
);

function project_user_credit($user){
    global $sub_projects;
    foreach ($sub_projects as $sp) {
        show_app_credit_user($user, $sp["name"], $sp["appids"]);
    }
}

function project_team_credit($team) {
    global $sub_projects;
    foreach ($sub_projects as $sp) {
        show_app_credit_team($team, $sp["name"], $sp["appids"]);
    }
}

}   // if(0)

function project_forum_post_rules() {
    // additional forum post rules
    return "";
}


function make_php_mailer() {

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.iap.fr";
    $mail->Port = 465;
    $mail->Username = "millea";
    $mail->Password = "${mailpass}";
    $mail->SetFrom('admin@cosmologyathome.org', 'Cosmologyathome');
    $mail->AddReplyTo("admin@cosmologyathome.org", "Cosmologyathome");

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    return $mail;
}

function server_status_project_info(){

  list($short,$date) = explode(',',file_get_contents(project_dir()."/.gittag"));
  echo "Server version: <a href='https://github.com/marius311/cosmohome/commits/$short'>$short</a> ($date UTC)";

}

?>
