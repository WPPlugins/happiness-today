<?php
/*
Plugin Name: Happiness Today
Plugin URI: http://www.shrewdies.net/happiness_today
Description: This is not just a Pods plugin package, it symbolizes the hope and enthusiasm of a Yorkshire Pods enthusiast summed up in two words sung most famously by <A title="Scott Kingsley Clark - Soft Charisma" href="http://www.scottkclark.com/" target=_blank>Soft Charisma</A>: Happiness, Today. When activated you will randomly see a lyric from Think Of Happiness Today in the upper center of your admin screen on every page.
Version: 0.1.3
Author: Keith from shrewdies
Author URI: http://shrewdies.com/

 This file is part of Happiness Today

    Happiness Today is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Happiness Today is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Happiness Today.  If not, see http://www.gnu.org/licenses/.
*/

function happiness_today_activate()
{
	// Load package, add hooks.
    if(function_exists("pods_ui_manage")&&function_exists("pod_query"))
    {
        $happiness_today_data_dir = WP_PLUGIN_DIR . "/happiness_today/data/";
        $package = file_get_contents($happiness_today_data_dir."podspackage.dat");
        pods_ui_import_package($package);
    }
}
register_activation_hook(__FILE__,"happiness_today_activate");
function happiness_today_hook() {
if(function_exists("pod_query"))
{
    $adminRecord = new Pod('happiness_today_admin');
    $where = 'slug="default"';
    $adminRecord->findRecords('name ASC',1,$where );
    if($adminRecord->getTotalRows()<1)
    {
        if(function_exists("pods_ui_manage"))
        {
            $happiness_today_data_dir = WP_PLUGIN_DIR . "/happiness_today/data/";
            $admindata = json_decode(file_get_contents($happiness_today_data_dir."admindata.dat"),true);
            $adminrecord = new PodAPI("happiness_today_admin", "php");
            $adminrecord->import($admindata);
            $textdata = json_decode(file_get_contents($happiness_today_data_dir."textdata.dat"),true);
            $textrecord = new PodAPI("happiness_today_text", "php");
            $textrecord->import($textdata);
        }
    } else {
    $Record = new Pod(happiness_today_text);
    $Record->findRecords("RAND()", 1);
    echo $Record->showTemplate("happiness_today_show");
    }
}
}
add_action("admin_footer","happiness_today_hook");
?>