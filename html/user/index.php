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
 <div id="maincontent">
   <div id="mainrow">
    <div id="leftcol">
	  <div id="bigtext">
      <p>
      Cosmology@Home lets you volunteer your spare computer time (like when your screen saver is on) 
      to help search for the model which best describes our Universe and to find the range of models 
      that agree with available cosmological and particle physics data. </p>
    </div>
	  <div id="majorlinks">
    <!--[if lte IE 6]>
    <div style="display: none">
    <![endif]-->
		 <div class="linkbtn"><a href="join.php"><img src="img/navigation/major_links/link_btn.png"/><span>> how to join</span></a></div>
		 <div class="linkbtn"><a href="home.php"><img src="img/navigation/major_links/link_btn.png"/><span>> your account</span></a></div>
		 <div class="linkbtn"><a href="forum_index.php"><img src="img/navigation/major_links/link_btn.png"/><span>> forums</span></a></div>
		 <div class="linkbtn"><a href="downloads.php"><img src="img/navigation/major_links/link_btn.png"/><span>> goodies</span></a></div>
    <!--[if lte IE 6]>
    </div>
    <![endif]-->
	  </div>
	  <div id="feature">
	     <span class="h2"><img src="img/concept_icon.png" alt="concept" />FEATURED CONCEPT: Dark Matter</span>
     <div class="featuretext">
     Unlike ordinary matter, dark matter does not emit or absorb light--or any other type of electromagnetic radiation. Consequently, dark matter cannot be observed directly using a telescope or any other astronomical instrument that has been developed by humans. If dark matter has these strange properties, how do we know that it exists in the first place?
     <br> 
      Like ordinary matter, dark matter interacts gravitationally with ordinary matter and radiation. Astronomers study the distribution of dark matter through observing its gravitational effects on ordinary matter in its vicinity and through its gravitational lensing effects on background radiation. 
      The background image shows the <i>bullet cluster</i>, a famous example where the visible matter does not follow the mass distribution.
      <br>Combining all the available evidence, dark matter represents about 83% of the matter content of the universe.
       Read more about dark matter on 
     <a href=http://teacherlink.ed.usu.edu/tlnasa/reference/ImagineDVD/Files/imagine/docs/science/know_l1/dark_matter.html>this web page</a>. Let us know your questions and comments on the <a href=http://www.cosmologyathome.org/forum_thread.php?id=926>message board.</a></div>
	  </div>
END;

if ($stopped) {
    echo "
        <b>".PROJECT." is temporarily shut down for maintenance.
        Please try again later</b>.
    ";
} else {
    db_init();
    show_nav();
}

echo <<<END
       
      </div><!--CLOSE LEFT COLUMN-->
	  <!--<td id="rightcol">-->
	 <div id="rightcol">
END;

if (!$stopped) {
    $profile = get_current_uotd();
    if ($profile) {
        echo "
            <div id='uotd'>
            <h2 class=headline>User of the Day</h2><br/><p>";
        show_uotd($profile);
        echo "</p></div>"; /*END USER OF THE DAY*/
    }
}

echo "
    <tr><td class=news>
    <h2 class=headline>News</h2>
      <p>
";
include("motd.php");

show_news(0, 5);
echo "
    </td>
    </tr></table>
";

echo <<<END

	 </div>  <!--END RIGHT COLUMN-->
  <!--</tr>-->
  <!--</div>--> <!--END MAINROW-->
  <!--</tbody>-->
  <!--</table>-->
  </div>
 </div> <!--END MAINCONTENT-->
 
  
END;

    include "schedulers.txt";

    page_tail_main();

?>
