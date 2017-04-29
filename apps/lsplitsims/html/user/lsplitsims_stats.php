<?php

require_once("../inc/cache.inc");
require_once("../inc/util.inc");
require_once("../inc/user.inc");
require_once("../inc/team.inc");
require_once("../inc/boinc_db.inc");


if (!defined('TTL')) {
    define('TTL', 600);
}


page_head(tra("Status of planck_param_sims app"));

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


echo "<h2>Archive of the 2016 planck_param_sims contest</h2>";

echo "<p>In 2016, we had a contest to see who could contribute the most
computation power to our Planck application. You can read some about the science
being done with this application here <a
href='https://www.cosmologyathome.org/planck_param_sims.php'>here</a>. Below are
the archived results from the end of the contest. The top three users and top
below were thanked in the <a href='https://arxiv.org/abs/1608.02487'>paper</a>
we ultimately published with these results.</p>";

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
