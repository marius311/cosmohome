<?php

require_once("../inc/cache.inc");
require_once("../inc/util.inc");
require_once("../inc/user.inc");
require_once("../inc/team.inc");
require_once("../inc/boinc_db.inc");


if (!defined('TTL')) {
    define('TTL', 600);
}


page_head(tra("The Planck app 2017 Pentathlon contest"));

$users = unserialize(get_cached_data(TTL, "users"));
$teams = unserialize(get_cached_data(TTL, "teams"));
if (!$users or !$teams){
    $db = new SQLite3("../../archives/lsplitsims.db");
    if (!$users){
        $result = $db->query("select * from top_user_planck order by planck_credit desc");
        $users = array();
        while($row = $result->fetchArray()){
            $user = BoincUser::lookup_id($row["id"]);
            if ($user){
                $user->planck_credit = $row["planck_credit"]; 
                $users[] = $user; 
            }
        }
        set_cached_data(TTL, serialize($users), "users");
    }
    if (!$teams){
        $result = $db->query("select * from top_team_planck order by planck_credit desc");
        $teams = array();
        while($row = $result->fetchArray()){
            $team = BoincTeam::lookup_id($row["id"]);
            if ($team){ 
                $team->planck_credit = $row["planck_credit"]; 
                $teams[] = $team;
            }
        }
        set_cached_data(TTL, serialize($teams), "teams");
    }
    $db->close();
}


echo "<h2>The Planck app 2017 Pentathlon contest</h2>";

echo "<p>In parallel with the 2017 BOINC Pentathlon, we are are running another
contest for our Planck (planck_param_sims) application! The top three users and the top team
(excluding the winners from the previous contest, see the old results <a
href='https://www.cosmologyathome.org/lsplitsims_stats.php'>here</a>) will
receive thanks in our paper. Only Planck jobs received during the dates of the
Pentathlon, i.e. May 5 to May 19th at 00:00:00 UTC, will count for this contest.
Of course, all Cosmology@Home jobs count for the Pentathlon. Good luck to all!</p>";

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
