<?php
/**
* Provides templates for stats page
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 09-01-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/

/**
* Prints out a jump menu for the schedules
* @param array $links array of schedule links
*/
function print_schedule_list($links, $currentid) {
?>
<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" style="padding-bottom: 5px;">
<tr><td style="text-align: center; width: 50%;">
<p style="font-weight: bold; text-align: right;"><?=translate('View stats for schedule')?></p>
</td>
<td align="left">
<select name="choose_schedule" class="textbox" onchange="javascript: changeSchedule(this);">
<?
for ($i = 0; $i < count($links); $i++)
	echo '<option value="' . $links[$i]['scheduleid'] . '"' . ($links[$i]['scheduleid'] == $currentid ? ' selected="selected"' : '') . '>' . $links[$i]['scheduleTitle'] . "</option>\n";
?>
</select>
</td></tr>
</table>
<?
}

/**
* Prints out a quick summary of statistical information about the
*  application and system
* @param object $stats stats object with all properties set
*/
function print_quick_stats(&$stats) {
	$color = 0;
?>
<a name="top"></a>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="tableTitle" colspan="2"><?=translate('At A Glance')?></td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td width="175"><?=translate('Total Users')?></td>
    <td><?=$stats->get_num_users()?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Total Resources')?></td>
    <td><?=$stats->get_num_rs()?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Total Reservations')?></td>
    <td><?=$stats->get_num_res()?>
    </td>
  </tr>
  <tr><td height="1" bgcolor="#666666" colspan="2"></td></tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Max Reservation')?></td>
    <td><?=CmnFns::minutes_to_hours($stats->longest)?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Min Reservation')?></td>
    <td><?=CmnFns::minutes_to_hours($stats->shortest)?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Avg Reservation')?></td>
    <td><?=@CmnFns::minutes_to_hours($stats->get_total_time()/$stats->get_num_res())?>
    </td>
  </tr>
  <tr><td height="1" bgcolor="#666666" colspan="2"></td></tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Most Active Resource')?></td>
    <td><?=$stats->active_resource['name'] . ' : ' . $stats->active_resource['num'] . ' ' . translate('Reservations')?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Most Active User')?></td>
    <td><?=$stats->active_user['name'] . ' : ' . $stats->active_user['num'] . ' ' . translate('Reservations')?>
    </td>
  </tr>
</table>
<?
}

/**
* Prints out information from the config file
* @param object $stats stats object with all properties set
*/
function print_system_stats(&$stats) {
	global $conf;
	$color = 0;
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="tableTitle" colspan="2"><?=translate('System Stats')?></td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td width="175"><?=translate('phpScheduleIt version')?></td>
    <td><?=$conf['app']['version']?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Database backend')?></td>
    <td><?=$conf['db']['dbType']?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Database name')?></td>
    <td><?=$conf['db']['dbName']?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('PHP version')?></td>
    <td><?=phpversion();?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Server OS')?></td>
    <td><?=$_SERVER['SERVER_SOFTWARE'];?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Server name')?></td>
    <td><?=$_SERVER['SERVER_NAME'];?>
    </td>
  </tr>
  <tr><td height="1" bgcolor="#666666" colspan="2"></td></tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('phpScheduleIt root directory')?></td>
    <td><?=$conf['app']['weburi']?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Using permissions')?></td>
    <td><?=($conf['app']['use_perms']) ? translate('Yes') : translate('No')?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Using logging')?></td>
    <td><?=($conf['app']['use_log']) ? translate('Yes') : translate('No')?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Log file')?></td>
    <td><?=$conf['app']['logfile']?>
    </td>
  </tr>
  <tr><td height="1" bgcolor="#666666" colspan="2"></td></tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Admin email address')?></td>
    <td><?=$conf['app']['adminEmail']?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Tech email address')?></td>
    <td><?=$conf['app']['techEmail']?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('CC email addresses')?></td>
    <td><?=$conf['app']['ccEmail']?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Reservation start time')?></td>
    <td><?=CmnFns::formatTime($stats->startDay)?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Reservation end time')?></td>
    <td><?=CmnFns::formatTime($stats->endDay)?>
    </td>
  </tr>
  <tr class="cellColor<?=($color++%2)?>">
    <td><?=translate('Days shown at a time')?></td>
    <td><?=$stats->sched['viewDays']?>
    </td>
  </tr>
</table>
<?
}

/**
* Prints out table and graph of statistical information
* @param object $stats stats object with all properties set
* @param string $index optional index to use for stats that require a 2nd index value
*/
function print_stats(&$stats, $index = NULL) {
	$tot = $stats->get_total();
	$x = 0;
	$bar_style = 'height: ' . $stats->height . ';'
				. 'border: ' . $stats->bar_outline . ';'
				. 'background-color: ' . $stats->bar_color . ';'
				. 'padding-top: 1px; padding-bottom: 1px; position: relative;';
	$color0 = $stats->color0;
	$color1 = $stats->color1;
	
	echo '<h4 align="center">' . $stats->get_title() . '</h4>';
	echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size: ' . $stats->fnt_sz . '; padding: 5px; border: solid 1px #000000; background-color: #FFFFFF;">';
	echo '<tr><td style="text-align: right;" colspan="3">0</td><td style="border-bottom: solid #666666 2px; text-align: right;">' . $tot . '</td></tr>';
	foreach ($stats->labels as $i => $v) {		// Loop through each month
		
		if (empty($index))
			$val = (isset($stats->values[$i])) ? $stats->values[$i] : 0;
		else
			$val = (isset($stats->values[$i][$index])) ? $stats->values[$i][$index] : 0;
		
		$percent = $stats->get_percent($val);		// Store percent
		$c = 'color' . ($x++%2);					// Alternate colors
		
		echo '<tr bgcolor="'. $$c . '"><td width="15%">' . $v . '</td>';
		echo '<td width="7%">' . $percent . '%</td>';
		echo '<td width="10%"> [' . $val . '/' . $tot . ']</td>';
		echo '<td style="border-left: solid #666666 2px; border-right: solid #666666 2px;">';
		echo '<div style="width: '. $percent . '%; ' . $bar_style . 'border-left-width: 0px;">&nbsp;</div>';
		echo '</td></tr>' . "\n";
	}
	echo '<tr><td style="text-align: right;" colspan="3">' . translate('Reservations') . ' 0</td>'
		. '<td style="border-top: solid #666666 2px; text-align: right;">'
		. '<table border="0" width="100%"><tr style="font-size: ' . $stats->fnt_sz . '; text-align: right;"><td width="25%" style="border-right: solid #000000 1px;">' . round($tot * 1/4) . '</td><td width="25%" style="border-right: solid #000000 1px;">' . round($tot * 1/2) . '</td><td width="25%" style="border-right: solid #000000 1px;">' . round($tot * 3/4) . '</td><td width="25%" style="border-right: solid #000000 1px;">' . $tot . '</td></tr></table>'
		. '</td></tr>';
	echo '</table>';
	echo '<p style="text-align: center;"><a href="#top">' . translate('Return to top') . '</a></p>';
}
?>