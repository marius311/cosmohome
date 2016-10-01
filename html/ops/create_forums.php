<?php
// This file is part of BOINC.
// http://boinc.berkeley.edu
// Copyright (C) 2008 University of California
//
// BOINC is free software; you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License
// as published by the Free Software Foundation,
// either version 3 of the License, or (at your option) any later version.
//
// BOINC is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with BOINC.  If not, see <http://www.gnu.org/licenses/>.

// Create message boards.
// RUN THIS AS A SCRIPT, NOT VIA A BROWSER.
// TODO: rewrite this using the DB abstraction layer
// First, edit the set of forums (below) and remove the following line


$cli_only = true;
require_once("../inc/forum_db.inc");
require_once("../inc/util_ops.inc");

function create_category($orderID, $name, $is_helpdesk) {
    $q = "(orderID, lang, name, is_helpdesk) values ($orderID, 1, '$name', $is_helpdesk)";
    $db = BoincDB::get();
    $result = $db->insert("category", $q);
    if (!$result) {
        $cat = BoincCategory::lookup("name='$name' and is_helpdesk=$is_helpdesk");
        if ($cat) return $cat->id;
        echo "can't create category\n";
        echo $db->base_error();
        exit();
    }
    return $db->insert_id();
}

function create_forum($category, $orderID, $title, $description, $is_dev_blog=0) {
    $q = "(category, orderID, title, description, is_dev_blog) values ($category, $orderID, '$title', '$description', $is_dev_blog)";
    $db = BoincDB::get();
    $result = $db->insert("forum",$q);
    if (!$result) {
        $forum = BoincForum::lookup("category=$category and title='$title'");
        if ($forum) return $forum->id;
        echo "can't create forum\n";
        echo $db->base_error();
        exit();
    }
    return $db->insert_id();
}

db_init();

$catid = create_category(0, "", 0);
create_forum($catid, 0, "News", "News from this project");

$catid = create_category(1, "General Discussion", 0);
create_forum($catid, 0, "Announcements", "Announcements about Cosmology@Home");
create_forum($catid, 1, "General Topics", "General Discussion related to Cosmology@Home");
create_forum($catid, 2, "Wish list", "What features would you like to see in BOINC and Cosmology@Home");
create_forum($catid, 3, "Technical Support", "Problems with running Cosmology@Home");

$catid = create_category(2, "Cosmology@Home Science", 0);
create_forum($catid, 4, "Cosmology and Astronomy", "Questions about the Science behind Cosmology@Home");

$cvs_version_tracker[]="\$Id$";  //Generated automatically - do not edit
?>
