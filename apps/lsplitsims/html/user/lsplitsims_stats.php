<?php

require_once("../inc/cache.inc");
require_once("../inc/util.inc");
require_once("../inc/user.inc");
require_once("../inc/team.inc");
require_once("../inc/boinc_db.inc");


if (!defined('TTL')) {
    define('TTL', 3600);
}


page_head(tra("Status of planck_param_sims app"));

$db = BoincDb::get(true);
$users = unserialize(get_cached_data(TTL, "users"));
if (!$users){
    $users = $db->enum_general("BoincUser","select * from (select u.*, sum(granted_credit) as planck_credit from user u inner join result r on r.userid=u.id where r.appid=5 group by u.id order by planck_credit desc) as res where planck_credit>0;");
    set_cached_data(TTL, serialize($users), "users");
}    
$teams = unserialize(get_cached_data(TTL, "teams"));
if (!$teams){
    $teams = $db->enum_general("BoincTeam","select * from (select t.*, sum(granted_credit) as planck_credit from team t inner join result r on r.teamid=t.id where r.appid=5 group by t.id order by planck_credit desc) as res where planck_credit>0;");
    set_cached_data(TTL, serialize($teams), "teams");
}

echo "<h2>Status of the planck_param_sims app</h2>";

echo "<p>The planck_param_sims application analyzes sets of simulated data
from the Planck satellite to help us build up a distribution of how actual
results should differ when we analyze only certain parts of them at a time. It
can help us search for problems with our understanding of the universe,
possibly even pointing to new discoveries! For each simulated realization of
the data, we look about 100 different ways of splitting it up into parts. We
began by looking at 1000 simulations, and now would like to run a full 10000.
The graphic below represents these 10000x100 jobs, each vertical slice will
get darker as the 100 splits for that simulation are completed.</p>
";

echo "<p><img class='rounded' style='display:block; margin:auto;' src='img/lsplitsims_jobplot_phasea.png'></p>";

echo "<p>Update (Apr 10, 2016): The first phase of simulations was finished as
we reached 10k successfully completed simulations. Based on what we've learned
looking at results throughout this process, there is at least one other set of
simulations we would like to do with some slight modifications with respect to
the first batch. Progress on these is shown below. It is possible there is not
time to finish another full 10k simulations by the time of submission of our
article, nevertheless the results we do get in time will be useful (and more
results can be computed during the refereeing process).</p>";

echo "<p><img class='rounded' style='display:block; margin:auto;' src='img/lsplitsims_jobplot.png'></p>";

echo "<p>These results will be used in a forthcoming paper from the Planck
collaboration. In the paper, we will thank by name the top 3 users and top
team who have crunched the most on the planck_param_sims application. Below
are the current standings. We will give a two week notice before picking the
winners.</p>";

start_table_noborder();
echo "
    <tr>
    <th width=48%>".tra("Users")."</th>
    <td width=4%></td>
    <th width=48%>".tra("Teams")."</th>
    </tr>
";

echo "<tr valign=top><td>";
start_table();
echo "
    <tr>
    <th>".tra("Rank")."</th>
    <th>".tra("Name")."</th>
    <th>".tra("Credit")."</th>
    </tr>
";
$i=1;
foreach ($users as $user) {
    $j = $i % 2;
    echo "
        <tr class=row$j>
        <td>$i</td>
        <td>", user_links($user, BADGE_HEIGHT_MEDIUM), "</td>
        <td align=right>", format_credit_large($user->planck_credit), "</td>
        </tr>
    ";
    $i++;
}
echo "</table></td><td></td>";


echo "<td>";
start_table();
echo "
    <tr>
    <th>".tra("Rank")."</th>
    <th>".tra("Name")."</th>
    <th>".tra("Credit")."</th>
    </tr>
";
$i=1;
foreach ($teams as $team) {
    $j = $i % 2;
    echo "
        <tr class=row$j>
        <td>$i</td>
        <td>", team_links($team), "</td>
        <td align=right>", format_credit_large($team->planck_credit), "</td>
        </tr>
    ";
    $i++;
}
echo "</table></td></tr>";





echo "</table>";


page_tail();

?>
