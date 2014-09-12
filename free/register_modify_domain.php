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

require_once '../conf_inc.php';
require_once '../errors_inc.php';

session_start();
session_cache_limiter('nocache');

if(IsSet($_SESSION['user'])) {
    import_request_variables('p', 'p_');

    error_reporting($error_reporting);

    @mysql_connect($hostname, $admin, $password_sql) or die($error_connectdb);
    @mysql_select_db($database) or die($error_selectdb);


    $query = "update domains set category='$p_category' where domain='$p_domain';";
    mysql_query($query) or die($error_update);


    header("Location:../allocate.php");
    exit;

}
