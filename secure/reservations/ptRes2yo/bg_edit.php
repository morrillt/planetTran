<?php
include_once('lib/DBEngine.class.php');
include_once('lib/Template.class.php');
include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');

$d = new DBEngine();
$t = new Tools();
$temp = new Template('Edit billing group');

$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : (isset($_POST['groupid']) ? $_POST['groupid'] : null);
$edit = isset($_POST['edit']) ? 1 : 0;

$temp->printHTMLHeader();
$temp->linkbar();

if (!$groupid)
	show_select();
else if ($groupid && $edit) {
	edit_group($groupid);
	show_form($groupid);
} else
	show_form($groupid);

$temp->printHTMLFooter();

/****************************************************************/
function show_form($groupid) {
	global $t;
	$group = get_group($groupid);
	$fields = fields();

	?><form name="editBG" action="bg_edit.php" method="post">
	  <table width="100%" cellspacing=0 cellpadding=3>
	  <tr><td width="25%">&nbsp;</td><td width="75%">&nbsp;</td></tr><?

	foreach ($fields as $k=>$v) {
		$display_name = ucwords(str_replace("_", " ", $k));
		echo "<tr><td>$display_name</td><td>";

		if (is_array($v)) {
			$curv = array();
			foreach ($v as $j)
				$curv[$j] = $j;
			
			$t->print_dropdown($curv, $group[$k], $k);
		} else
			echo '<input type="text" name="'.$k.'" value="'.$group[$k].'">';

		echo "</td></tr>";
	}

	?>
	<tr><td>&nbsp;
	<input type="hidden" name="groupid" value="<?=$groupid?>">
	<input type="hidden" name="edit" value="1">
	</td><td><input type="submit" value="Edit"></td></tr>
	</table></form>
	<?
}
function edit_group($groupid) {
	global $d;

	$query = "update billing_groups set ";
	$vals = $qarr = array();
	$post = $_POST;
	if (!$post['domain']) $post['domain'] = null;

	foreach ($post as $k=>$v) {
		if ($k == 'edit' || $k == 'groupid')
			continue;
		$qarr[] = "$k=?";
		$vals[] = $v;
	}

	$query .= implode(", ", $qarr);
	$query .= " where groupid=?";
	$vals[] = $groupid;

	$q = $d->db->prepare($query);
	$result = $d->db->execute($q, $vals);

	?><div style="border: 1px solid black; margin: 20px; padding: 10px; text-align: center;">
	<b>Group updated.</b><br>
	<a href="billgroups.php">Back to billing group manager</a>
	</div><?

}
function show_select() {
	$groups = get_groups();
	
	for ($i=0; $groups[$i]; $i++) {
		$cur = $groups[$i];
		?>	
		<div>
		<a href="bg_edit.php?groupid=<?=$cur['groupid']?>"><?=$cur['group_name']?></a>
		</div>
		<?
	}

}
function get_group($groupid) {
	global $d;
	$query = "select * from billing_groups where groupid=?";
	$q = $d->db->prepare($query);
	$vals = array($groupid);
	$result = $d->db->execute($q, $vals);
	
	return $result->fetchRow();
}
function get_groups() {
	global $d;
	$query = "select * from billing_groups order by group_name";
	$q = $d->db->prepare($query);
	$result = $d->db->execute($q);
	
	$return = array();
	while ($row = $result->fetchRow())
		$return[] = $row;
	return $return;
}
function fields() {
	return array(
		'group_name'=>'',
		'type'=>array('d','c','u','p','e','v'),
		'discount'=>'',
		'domain'=>'',
		'price_type'=>array('g','z','n'),
		'vip_type'=>array('p','V','EV')
		);
}

?>
