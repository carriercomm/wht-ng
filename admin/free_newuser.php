<?php
require_once '../conf_inc.php';
require_once '../i18n.php';
require_once '../errors_inc.php';

error_reporting($error_reporting);

if ($dir = opendir("$DocumentRoot/$version/advertise")) {
    while(($file = readdir($dir)) !== false) {
        if($file !== "." && $file !== ".." && $file !== "default" && $file !== "CVS") {
            $dir_content[] = $file;
        }
    }
    closedir($dir);
}

echo("<?xml version=\"1.0\" encoding=\"$charset\"?>");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="<?php echo($lang); ?>" xml:lang="<?php echo($lang); ?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo _("New User") ?></title>
<meta http-equiv="Content-type" content="text/html; charset=<?php echo($charset); ?>" />
<link rel="stylesheet" type="text/css" href="../css/<?php echo($stylesheet); ?>/style.css" />
<script type="text/javascript">
<!--
function check()
{
    if(document.form1.user.value == "") {
        alert("<?php echo _("Fill the User field!"); ?>");
        return false;
    }
    if(document.form1.password.value == "") {
        alert("<?php echo _("Fill the Password field!"); ?>");
        return false;
    }
    if(document.form1.confpass.value == "") {
        alert("<?php echo _("Fill the Confirm password field!"); ?>");
        return false;
    }
    if(document.form1.domain.value == "" && document.form1.local_domain.value == "") {
        alert("<?php echo _("Fill the Domain or Local domain field!"); ?>");
        return false;
    }
    if(document.form1.domain.value != "" && document.form1.local_domain.value != "") {
        alert("<?php echo _("Fill only one of the two fields - Local domain or Domain!"); ?>");
        return false;
    }
    if(document.form1.email.value == "") {
        alert("<?php echo _("Fill the Email field!") ?>");
        return false;
    }
    if(document.form1.password.value != document.form1.confpass.value) {
        alert("<?php echo _("Password and Confirm password fields must contain the same password!"); ?>");
        return false;
    }
    if(document.form1.password.value.length < 8) {
        alert("<?php echo _("Password must be at least 8 characters long!"); ?>");
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
echo _("Fill out the form.");
?>
<form name="form1" action="register_free_newuser.php" method="post" accept-charset="ISO-8859-1">          
<?php

if(IsSet($HTTP_GET_VARS['error']) && $HTTP_GET_VARS['error'] == "error_fill") {
    echo($error_fill);
}
if(IsSet($HTTP_GET_VARS['error']) && $HTTP_GET_VARS['error'] == "error_same_user") {
    echo($error_same_user);
}
if(IsSet($HTTP_GET_VARS['error']) && $HTTP_GET_VARS['error'] == "error_same_domain") {
    echo($error_same_domain);
}
if(IsSet($HTTP_GET_VARS['error']) && $HTTP_GET_VARS['error'] == "error_short_password") {
    echo($error_short_password);
}
?>
<br />

<table cellpadding="2" cellspacing="2" margin-left="auto"
style="width: 100%;" margin-right="0px">
<tbody>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("User"); ?>: *
</td>
<td valign="bottom" width="40%" style="text-align: left;"><input
name="user" size="8" value="<?php echo($_COOKIE['user_c']) ?>" maxlength="8">
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Password"); ?>: *
</td>
<td valign="bottom" width="40%"><input type="password"
name="password" size="15" maxlength="15">
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Confirm password"); ?>: *
</td>
<td valign="bottom" width="40%"><input type="password"
name="confpass" size="15" maxlength="15">
</td>
</tr>
<tr>
<td valign="bottom" align="right"><?php echo _("Contact email"); ?>*
</td>
<td valign="bottom"><input name="email" size="30" value="<?php echo($_COOKIE['email_c']) ?>">
</td>
</tr>
</tbody>
</table>
<br />
<?php echo _("You must fill one of the next two fields. Fill the first one if you have not registered domain name.<br />")
. _("Fill the second one if you allready have a registered domain. Can be of any type ( your_domain.com, your_domain.net ...). We will not register your domain name."); ?>
<br /> <br />
<table cellpadding="2" cellspacing="2" margin-left="auto"
style="width: 100%;" margin-right="0px">
<tbody>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Local domain name"); ?>:
</td>
<td valign="bottom" width="40%">
<input name="local_domain" size="20" value="<?php echo($_COOKIE['local_domain_c']) ?>">
.<select name="sel_domain">
<?php
for($i = 0; $i < sizeof($domain_name); $i++) {
    if($_COOKIE['sel_domain_c'] === $domain_name[$i]) {
        echo("<option selected=\"true\">".$domain_name[$i]."  </option>");
    } else {
        echo("<option> ".$domain_name[$i]." </option>");
     }
 }
 ?>
 </select>
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Your domain name ( must be registered )"); ?>:
</td>
<td valign="bottom" width="40%"><input name="domain" size="30" value="<?php echo($_COOKIE['domain_c']); ?>">
</td>
</tr>

<?php
if($enable_qmail === "on") {
?>
<tr>
<td valign="bottom" width="40%" align="right">
<?php echo _("Email accounts ( will be of type somename@your_domain.com )"); ?>:
</td>
<td valign="bottom" width="40%">
<input name="num_emails" size="2" value="<?php if(IsSet($_COOKIE['num_emails_c'])) echo($_COOKIE['num_emails_c']); else echo($free_email_accounts); ?>" maxlength="2">
</td>
</tr>

<?php
}
?> 

<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Traffic"); ?>:
</td>
<td valign="bottom" style="width: 40%;">
<input value="<?php if(IsSet($_COOKIE['traffic_c']))  echo($_COOKIE['traffic_c']); else echo($free_traffic); ?>"
name="traffic" size="5">  <?php echo _("Mbytes per month."); ?>
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Hard disk usage"); ?>:
</td>
<td valign="bottom" style="width: 40%;"><input value="<?php if(IsSet($_COOKIE['quota_c']))  echo($_COOKIE['quota_c']); else echo($free_quota); ?>"
name="quota" size="5">  <?php echo _("Mbytes"); ?>
</td>
</tr>
<tr>
<td valign="bottom" width="40%" align="right"><?php echo _("Category"); ?>:
</td>
<td valign="bottom" width="40%">
<select name="category">
<?php
for($i =0; $i < sizeof($dir_content); $i++) {
    if ($_COOKIE['category_c'] === $dir_content[$i]) {
        echo("<option selected>" . $dir_content[$i] . "  </option>");
    } else {
        echo("<option> " . $dir_content[$i] . " </option>");
    }
}
?>
</select>
</td>
</tr>
<tr>
<td valign="top"><br />
</td>
<td valign="top"><br />
<input type="submit" name="Submit" value="<?php echo _("Register"); ?>"
onclick="if(check()) return true; else return false">
<input type="reset" value="<?php echo _("Reset"); ?>">
</td>
</tr>
</tbody>
</table>
<input type="hidden" name="hidden" value="newuser">     </form>
</form>
</div>
</body>
</html>