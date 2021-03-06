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
require_once './i18n.php';
require_once './errors_inc.php';

session_start();
session_cache_limiter('nocache');

if(IsSet($_SESSION[user])) {

    error_reporting($error_reporting);

    import_request_variables('g', 'g_');

    @mysql_connect($hostname, $admin, $password_sql) or die($error_connectdb);
    @mysql_select_db($database) or die($error_selectdb);


    $query="select domain, quota, script, ssl, expday, expmonth, expyear, traffic from domains where domain='$g_domain'";
    $result=mysql_query($query) or die($error_select);


    $row=mysql_fetch_array($result);

    echo("<?xml version=\"1.0\" encoding=\"$charset\"?>");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="<?php echo($lang); ?>" xml:lang="<?php echo($lang); ?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo _("Modify Domain") ?></title>
<meta http-equiv="Content-type" content="text/html; charset=<?php echo($charset); ?>" />
<link rel="stylesheet" type="text/css" href="css/<?php echo($stylesheet); ?>/style.css" />
<script type="text/javascript">
<!--
function check()
{
    if(document.form1.traffic.value < <?php echo($inittraffic_subdomain); ?>) {
        alert("<?php echo _("The traffic have to be more than") . " " . $inittraffic_subdomain . " " . _("Mbytes per month!"); ?>");
        return false;
    }
    if(document.form1.quota.value < <?php echo($initquota_subdomain); ?>) {
        alert("<?php echo _("The hard disk usage have to be more than") . " " . $initquota_subdomain . " " . _("Mbytes!"); ?>");
        return false;
    }
    return true;
}
// -->
</script>
</head>
<body>
<div>
<?php
include_once './templates/header.php';

echo _("Fill out the form.")
?>

<form name="form1" action="register_modify_subdomain.php" method="post" accept-charset="ISO-8859-1">

<br /><br />

<table cellpadding="2" cellspacing="2" margin-left="auto"
width="100%" margin-right="0px">
<tbody>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Domain"); ?>:
</td>
<td valign="bottom" width="40%"><?php echo($row['domain']); ?>
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Use PHP and CGI"); ?>:
</td>
<td valign="bottom" width="40%"><input type="checkbox" name="script" <?php if($row['script'] === "on") echo("checked=\"true\""); ?>>
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Months to host your site"); ?>:
</td>
<td valign="top" width="40%">
<select name="months">
<?php
    for($i = 0; $i < (sizeof($hosting_months) - 1); $i++) {
        if ($_COOKIE['months_c'] == $hosting_months[$i]) {
            echo("<option selected=\"true\">" . $hosting_months[$i] . " </option>");
        } elseif(!IsSet($_COOKIE['months_c']) && $hosting_months['initial_selected'] === $hosting_months[$i]) {
            echo("<option selected=\"true\"> " . $hosting_months[$i] . " </option>");
        } else {
            echo("<option> " . $hosting_months[$i] . " </option>");
        }
    }
?>
</select>
</td>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Traffic"); ?>:
</td>
<td valign="bottom" style="width: 40%;"><input value="<?php echo($row['traffic']); ?>"
name="traffic" size="5">  <?php echo _("Mbytes per month."); ?>
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Hard disk usage"); ?>:
</td>
<td valign="bottom" style="width: 40%;"><input value="<?php echo($row['quota']/1024); ?>"
 name="quota" size="5">  <?php echo _("Mbytes"); ?>
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Expiry date"); ?>:
</td>
<td valign="bottom" style="width: 40%;"><?php echo("$row[expday]  $row[expmonth]  $row[expyear]"); ?>
</td>
</tr>
<tr><td> <br /><br /></td></tr>
<tr>
<td valign="top">
</td>
<td valign="top">
<input type="submit" name="Submit" value="<?php echo _("Submit"); ?>"
onclick="if(check()) return true; else return false">
<input type="reset" name="Reset" value="<?php echo _("Reset"); ?>">
</td>
</tr>
</tbody>
</table>
<input type="hidden" name="domain" value="<?php echo($g_domain); ?>">
</form>
<?php
include_once './templates/footer.php';
?>
</div>
</body>
</html>
<?php
}
?>
