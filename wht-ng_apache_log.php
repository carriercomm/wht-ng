#!/usr/bin/php -q
<?php
/**
*    Web Hosting Toolkit - Next Generation (WHT-NG)
*    Copyright (C) 2014  Jimmy M. Coleman <hyperclock@ok.de>
*    Copyright (C) 2003  Nikolay Ivanov <nivanov@email.com> (GPLv2)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once './conf_inc.php';
require_once './errors_inc.php';

error_reporting(E_ERROR);

system("./wht-ng_awstats_update.php");

$transfer_logs = "on";

if ($handle = opendir($httpd_logdir)) {
    while (false !== ($file = readdir($handle))) {
        if ($file !== "." && $file !== "..") {
            
            $file = $httpd_logdir . "/" . $file;
            
            if($transfer_logs === "on" && is_file($file) && substr($file, strlen($file) - 7) !== ".old.gz"
            && substr($file, strlen($file) - 4) !== ".old") {

                system("mv $file $file.old");

                $dir_file_external[] = $file . ".old";
            }
        }
    }
    
    closedir($handle);
}



if ($handle = opendir($httpd_logdir . "/bytes")) {
    while (false !== ($file = readdir($handle))) {
        if ($file !== "." && $file !== "..") {

            $file = $httpd_logdir . "/bytes/" . $file;

            if($transfer_logs === "on" && is_file($file) && substr($file, strlen($file) - 7) !== ".old.gz"
            && substr($file, strlen($file) - 4) !== ".old") {

                system("mv $file $file.old");

                $dir_file[] = $file . ".old";
            }
        }
    }
    
    closedir($handle);
}



if($transfer_logs === "on") {

    system("apachectl graceful");
    system("sleep 10");
}


$timestamp = time();

@mysql_connect($hostname, $admin, $password_sql) or die($error_connectdb);
@mysql_select_db($database) or die($error_selectdb);


for($i = 0; $i < sizeof($dir_file); $i++) {
    if(is_file($dir_file[$i])) {
        $fp = fopen($dir_file[$i], "r");

        $sum = 0;

        while(!feof($fp)) {
            $row = fgets($fp, 1024);

            $sum += $row;
        }

        fclose($fp);

        $position = strlen("$httpd_logdir/bytes/");
        $num_simb = strlen($dir_file[$i]) - $position - 4;
        $domain = substr($dir_file[$i], $position, $num_simb);

        $query = "select traffic from domains where domain='$domain'";
        $result = mysql_query($query) or die($error_select);

        $row = mysql_fetch_array($result);

        $sum = $sum / 1048576;

        if($row['traffic'] < ($sum / $rotate_months)) {
            $notify = "traffic $sum > $row[traffic]";

            $query = "insert into admin_notify (domain, notify, timestamp) values ('$domain', '$notify', '$timestamp')";
            mysql_query($query) or die($error_select);
        }


        $query = "update domains set rotate_traffic='$sum' where domain='$domain'";
        mysql_query($query) or die($error_update);

    }

    system("gzip -f $dir_file[$i]");
}

for($i = 0; $i < sizeof($dir_file_external); $i++) {
    system("gzip -f $dir_file_external[$i]");
}

?>
