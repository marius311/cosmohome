<?php
//ini_set("error_reporting", E_ALL);
//ini_set("display_errors", "on");
require_once("../inc/db.inc");
require_once("../inc/util.inc");
require_once("../inc/news.inc");
require_once("../inc/cache.inc");
require_once("../inc/uotd.inc");
require_once("../inc/sanitize_html.inc");
require_once("../inc/translation.inc");
require_once("../inc/text_transform.inc");
//require_once("../inc/replacePngTags.inc");
require_once("../project/project.inc");

function show_nav() {
    $config = get_config();
    $master_url = parse_config($config, "<master_url>");
}

$caching = false;

if ($caching) {
    start_cache(INDEX_PAGE_TTL);
}
$stopped = web_stopped();
$rssname = PROJECT . " RSS 2.0" ;
$rsslink = URL_BASE . "rss_main.php";

if (defined("CHARSET")) {
    header("Content-type: text/html; charset=".tr(CHARSET));
}

page_head(PROJECT, null, null, "yes");

echo <<<END
    <div class="row">
        <div class="col-sm-6" id="leftcol">
            <div class="panel panel-default mb-3">
                <div class="panel-body" id="bigtext">
                Cosmology@Home lets you volunteer your spare computer time (like
                when your screen saver is on) to help better understand our
                universe by searching for the range of theoretical models that
                agree with cutting-edge cosmological and particle physics data. 
                </div>
            </div>
            <div class="panel panel-default mb-3" id="majorlinks">
                <div class="panel-body">
                    <div class="linkbtn"><a href="join.php"><img src="img/navigation/major_links/link_btn.png"/><span>> how to join</span></a></div>
                    <div class="linkbtn"><a href="home.php"><img src="img/navigation/major_links/link_btn.png"/><span>> your account</span></a></div>
                    <div class="linkbtn"><a href="forum_index.php"><img src="img/navigation/major_links/link_btn.png"/><span>> forums</span></a></div>
                    <div class="linkbtn"><a href="downloads.php"><img src="img/navigation/major_links/link_btn.png"/><span>> goodies</span></a></div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class='panel-heading'>FEATURED CONCEPT: Dark Matter</div>
                <div class="panel-body featuretext" id="feature">
                Unlike ordinary matter, dark matter does not emit or absorb
                light--or any other type of electromagnetic radiation.
                Consequently, dark matter cannot be observed directly using a
                telescope or any other astronomical instrument that has been
                developed by humans. If dark matter has these strange
                properties, how do we know that it exists in the first place?
                <br> 
                Like ordinary matter, dark matter interacts gravitationally with
                ordinary matter and radiation. Astronomers study the
                distribution of dark matter through observing its gravitational
                effects on ordinary matter in its vicinity and through its
                gravitational lensing effects on background radiation.  The
                background image shows the <i>bullet cluster</i>, a famous
                example where the visible matter does not follow the mass
                distribution. 
                <br> 
                Combining all the available evidence, dark
                matter represents about 83% of the matter content of the
                universe. Read more about dark matter on  <a
                href=http://teacherlink.ed.usu.edu/tlnasa/reference/ImagineDVD/Files/imagine/docs/science/know_l1/dark_matter.html>this
                web page</a>. Let us know your questions and comments on the <a
                href=http://www.cosmologyathome.org/forum_thread.php?id=926>message
                board.</a></div>
            </div>
        </div>
        
        <div class="col-sm-6" id="rightcol">
END;

if ($stopped) {
    echo "
        <b>".PROJECT." is temporarily shut down for maintenance.
        Please try again later</b>.
    ";
} else {
    db_init();
    // show_nav();
}

if (!$stopped) {
    $profile = get_current_uotd();
    if ($profile) {
        echo "
            <div class='panel panel-default mb-3'>
                <div class='panel-heading'>User of the Day</div>
                <div class='panel-body'>";
        show_uotd($profile);
        echo "</div></div>"; /*END USER OF THE DAY*/
    }
}

echo "
            <div class='panel panel-default mb-3'>
                <div class='panel-heading'>News</div>
                <div class='panel-body'>
                <tr><td class=news>
                  <p>
";
include("motd.php");
show_news(0, 5);
echo "
                </td>
                </tr></table>
            </div>
";

echo <<<END
        </div>
    </div>
  
END;

    include "schedulers.txt";

    page_tail();

?>
