<?php
require('../lib/ldap/attrmap.php3');
if (is_file("../lib/lang/$config[general_prefered_lang]/utf8.php3"))
	include_once("../lib/lang/$config[general_prefered_lang]/utf8.php3");
else
	include_once('../lib/lang/default/utf8.php3');

$cn = '-';
$cn_lang = '-';
$homeaddress = '-';
$homeaddress_lang = '-';
$fax = '-';
$url = '-';
$ou = '-';
$ou_lang = '-';
$title = '-';
$title_lang = '-';
$telephonenumber = '-';
$homephone = '-';
$mobile = '-';
$mail = '-';
$mailalt = '-';

$ds=@ldap_connect("$config[ldap_server]");  // must be a valid ldap server!
if ($ds) {
	$r=@ldap_bind($ds,"$config[ldap_binddn]",$config[ldap_bindpw]);
	$sr=@ldap_search($ds,"$config[ldap_base]", 'uid=' . $login);
	$info = @ldap_get_entries($ds, $sr);
	$dn = $info[0]['dn'];
	if ($dn == '')
		$user_exists = 'no';
	else{
		$user_exists = 'yes';
		unset($item_vals);
		$k = init_decoder();
		$cn = ($info[0]['cn'][0]) ? $info[0]['cn'][0] : '-';
		$cn_lang = $info[0]["cn;lang-$config[general_prefered_lang]"][0];
		$cn_lang = decode_string("$cn_lang", $k);
		$cn_lang = ($cn_lang) ? $cn_lang : '-';
		$telephonenumber = ($info[0]['telephonenumber'][0]) ? $info[0]['telephonenumber'][0] : '-';
		$homephone = ($info[0]['homephone'][0]) ? $info[0]['homephone'][0] : '-';
		$homeaddress = ($info[0]['homepostaladdress'][0]) ? $info[0]['homepostaladdress'][0] : '-';
		$homeaddress_lang = $info[0]["homepostaladdress;lang-$config[general_prefered_lang]"][0];
		$homeaddress_lang = decode_string("$homeaddress_lang", $k);
		$homeaddress_lang = ($homeaddress_lang) ? $homeaddress_lang : '-';
		$mobile = ($info[0]['mobile'][0]) ? $info[0]['mobile'][0] : '-';
		$fax = ($info[0]['facsimiletelephonenumber'][0]) ? $info[0]['facsimiletelephonenumber'][0] : '-';
		$url = ($info[0]['labeleduri'][0]) ? $info[0]['labeleduri'][0] : '-';
		$ou = $info[0]['ou'][0];
		$ou_lang = $info[0]["ou;lang-$config[general_prefered_lang]"][0];
		$ou_lang = decode_string("$ou_lang", $k);
		$ou_lang = ($ou_lang) ? $ou_lang : '-';
		$mail = ($info[0]['mail'][0]) ? $info[0]['mail'][0] : '-';
		$title = ($info[0]['title'][0]) ? $info[0]['title'][0] : '-';
		$title_lang = $info[0]["title;lang-$config[general_prefered_lang]"][0];
		$title_lang = decode_string("$title_lang", $k);
		$title_lang = ($title_lang) ? $title_lang : '-';
		$mailalt = ($info[0]['mailalternateaddress'][0]) ? $info[0]['mailalternateaddress'][0] : '-';
		foreach($attrmap as $key => $val){
			$item_vals["$key"] = $info[0]["$val"];
		}
	}
	@ldap_close($ds);
}
else
	echo "<b>Could not connect to the LDAP server</b><br>\n";
?>
