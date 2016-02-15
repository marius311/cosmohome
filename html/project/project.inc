<?php

require_once("../inc/util.inc");
require_once("../inc/phpmailer/PHPMailerAutoload.php");

$master_url = parse_config(get_config(), "<master_url>");
define("PROJECT", "Cosmology@Home");
define("URL_BASE", $master_url);
define("IMAGE_PATH", "../user_profile/images/");
define("IMAGE_URL", "user_profile/images/");
define("PROFILE_PATH", "../user_profile/");
define("PROFILE_URL", "user_profile/");
define("LANGUAGE_FILE", "languages.txt");
define("STYLESHEET", "cosmohome.css");
define("FORUM_QA_MERGED_MODE", true);
define("COPYRIGHT_HOLDER", "Department of Astronomy at the <a href=\"http://www.illinois.edu\">University of Illinois at Urbana-Champaign</a>");
define("SYS_ADMIN_EMAIL", "kpointer@illinois.edu");
define("UOTD_ADMIN_EMAIL", "bwandelt@illinois.edu"); # who gets user of the day pool running low e-mails?


// Email addresses separated by pipe ( | ) that will receive user reported 
// offensive forum posts.
define("POST_REPORT_EMAILS", "bwandelt@illinois.edu|kpointer@illinois.edu|jrenkar@hughes.net|tutta55@boinc.be|elst93@gmail.com");

function page_head($title, $java_onload=null, $prefix="") {
    global $caching;
    global $cache_control_extra;

    $styleSheet = STYLESHEET;
    $rssname = PROJECT . " RSS 2.0";
    $rsslink = URL_BASE . "rss_main.php";
    
    $charset = tra("CHARSET");
    if ($charset != "CHARSET") {
        header("Content-type: text/html; charset=$charset");
    }
    if (!$caching) {
        header ("Expires: Mon, 26 Jul 1997 05:00:00 UTC");    // Date in the past
        header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " UTC"); // always modified
        header ("Cache-Control: $cache_control_extra no-cache, must-revalidate, post-check=0, pre-check=0");  // HTTP/1.1
        header ("Pragma: no-cache");                          // HTTP/1.0
    }
    
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
    ";

        echo "
<html>
  <head>
    <title>".strip_tags($title)."</title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"$styleSheet\">
    <link rel=\"alternate\" type=\"application/rss+xml\" title=\"$rssname\" href=\"$rsslink\">
    <link href='https://fonts.googleapis.com/css?family=Merriweather+Sans' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather+Sans:700' rel='stylesheet' type='text/css'>";
    echo <<<END
    <script type="text/javascript" src="drop_down.js"></script>
    <!--[if lte IE 6]>
      <script type="text/javascript" src="supersleight-min.js"></script>
    <![endif]-->

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-62011923-2', 'auto');
      ga('send', 'pageview');

    </script>
    
  </head>

END;
    if ($java_onload){
        echo "  <body bgcolor=\"#ffffff\" onload=\"".$java_onload."\">"; } else { echo "  <body bgcolor=\"#ffffff\">";
    }
    //display_cvs_versions();
    project_banner($title, $prefix);
}

function project_banner($title, $prefix) {
    // Put your project title and logo here
    $master_url = parse_config(get_config(), "<master_url>");
    echo "<div id='container'>	
   <!-- Navigation -->
   <div id='topmatter'>
    <a href='$master_url'><img src='/img/banner.jpg' border='0' height='200px' width='960px' padding='0px'/></a>
      <div id='navmenu'>
        <ul id='nav_ul'>
          <li><a href='/join.php'>JOIN</a></li>
          <li><a href='/faq.php'>FAQ</a></li>
          <li><a href='/forum_index.php'>FORUMS</a></li>
          <li><a href='#'>COMMUNITY</a>
            <ol>
              <li><a href='/home.php'>Your Account</a></li>
              <li><a href='/team.php'>Teams</a></li>
              <li><a href='/downloads.php'>Desktop Backgrounds</a></li>
              <li><a href='/profile_menu.php'>Participant Profiles</a></li>
              <li><a href='/wandelt_letter.php'>Ben's Welcome Letter</a></li>
            </ol>
          <li><a href='#'>SERVER</a>
            <ol>
              <li><a href='/apps.php'>Applications</a></li>
              <li><a href='/server_status.php'>Server Status</a></li>
              <li><a href='https://github.com/marius311/cosmohome'>Development</a></li> 
            </ol>

          <li><a href='#'>STATS</a>
            <ol>
              <li><a href='/lsplitsims_stats.php'>Planck Sims Stats</a></li>
              <li><a href='/top_users.php'>Top Participants</a></li>
              <li><a href='/top_hosts.php'>Top Computers</a></li>
              <li><a href='/top_teams.php'>Top Teams</a></li>
              <li><a href='/stats.php'>Other Statistics</a></li>
            </ol>
          </li>
        </ul>
      </div>
   </div>
   ";
}

function project_footer($show_return, $show_date) {
    echo " 
          <div id=\"footer\">";
    
    if ($show_return) {
        echo "<a href=".URL_BASE.">Return to ".PROJECT." main page</a>\n";
    }
    echo "<br><br>Copyright &copy; ".date("Y ").COPYRIGHT_HOLDER."<br/>\n";
    
	echo <<<END
   
If you have any questions or comments regarding the content of this site, please contact <a href='http://www.cosmicmar.com'>Marius Millea</a><br/>
This material is based upon work supported by the National Science Foundation under Grant No. AST0507676.<br/>
Links to external sites are provided as a convenience to our users. The Department of Astronomy does not control or endorse the content of external sites.<br\>
Any opinions, findings, and conclusions or recommendations expressed in this material are those of the author(s) and do not necessarily reflect the views of the National Science Foundation.<br/>
We would like to thank both NASA and the Planck project for their support of Cosmology@Home</br>
END;
    if ($show_date) {
        echo "<font size=-2 color=bbbbbb>Generated ",time_str(time()),"</font>\n";
    }
	echo " 
	<div id='sponsors'>
<a href='http://www.nsf.gov' target='_blank'><img src='/img/nsf_watermark.png' alt='nsf' align='left' border='0'></a>
<a href='http://www.nasa.gov' target='_blank'><img src='img/nasa-logo.gif' alt='nasa' border='0'></a>
<a href='http://planck.caltech.edu' target='_blank'><img src='/img/planck-logo.gif' alt='planck' border='0'></a>
<a href='http://www.jpl.nasa.gov' target='_blank'><img src='/img/JPLLogo.gif' alt='JPL' border='0'></a>
<a href='http://boinc.berkeley.edu' target='_blank'><img src='/img/boinc_watermark.png' alt='boinc' border='0'></a></div></div>";

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
    $mail->SMTPAuth = false;
    $mail->SMTPSecure = "";
    $mail->Host = "darkenergy.astro.illinois.edu";
    $mail->Port = 25;
    $mail->Username = "";
    $mail->Password = "";
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
