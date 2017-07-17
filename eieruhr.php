<?php
$stati = array(0 => 'p', 480 => 'w', 505 => 'p', 510 => 'w', 535 => 'p', 540 => 'w', 565 => 'p', 570 => 'w', 595 => 'p', 600 => 'w', 625 => 'p', 630 => 'w', 655 => 'p', 660 => 'w', 685 => 'p', 690 => 'w', 715 => 'p', 720 => 'w', 745 => 'p', 750 => 'w', 775 => 'p', 780 => 'w', 805 => 'p', 810 => 'w', 835 => 'p', 840 => 'w', 865 => 'p', 870 => 'w', 895 => 'p', 900 => 'w', 925 => 'p', 930 => 'w', 955 => 'p', 960 => 'w', 985 => 'p', 990 => 'w', 1015 => 'p', 1020 => 'w', 1045 => 'p', 1050 => 'w', 1075 => 'p', 1080 => 'w', 1105 => 'p', 1110 => 'w', 1135 => 'p', 1140 => 'w', 1165 => 'p', 1920 => 'w');

$busses = array([355, 33, 'S'], [375, 33, 'S'], [386, 20, 'S'], [395, 33, 'S'], [412, 20, 'S'], [415, 33, 'S'], [432, 20, 'S'], [435, 33, 'S'], [452, 20, 'S'], [455, 33, 'S'], [472, 20, 'S'], [475, 33, 'S'], [492, 20, 'S'], [505, 33, 'S'], [512, 20, 'S'], [532, 20, 'S'], [535, 33, 'S'], [552, 20, 'S'], [565, 33, 'S'], [582, 20, 'S'], [595, 33, 'S'], [612, 20, 'S'], [625, 33, 'S'], [642, 20, 'S'], [655, 33, 'S'], [672, 20, 'S'], [685, 33, 'S'], [702, 20, 'S'], [715, 33, 'S'], [732, 20, 'S'], [745, 33, 'S'], [762, 20, 'S'], [775, 33, 'S'], [792, 20, 'S'], [805, 33, 'S'], [822, 20, 'S'], [835, 33, 'S'], [852, 20, 'S'], [855, 33, 'S'], [872, 20, 'S'], [875, 33, 'S'], [892, 20, 'S'], [895, 33, 'S'], [912, 20, 'S'], [915, 33, 'S'], [932, 20, 'S'], [935, 33, 'S'], [952, 20, 'S'], [955, 33, 'S'], [972, 20, 'S'], [975, 33, 'S'], [992, 20, 'S'], [995, 33, 'S'], [1011, 20, 'S'], [1015, 33, 'S'], [1031, 20, 'S'], [1045, 33, 'S'], [1061, 20, 'S'], [1091, 20, 'S'], [1121, 20, 'S'], [1151, 20, 'S'], [1181, 20, 'S'], [1211, 20, 'S'], [1241, 20, 'S'], [1271, 20, 'S'], [1301, 20, 'S'], [1331, 20, 'S'], [1361, 20, 'S'], [1391, 20, 'S'], [1421, 20, 'S']);

$hour = (int) date('G');
$minute = (int) date('i');
$second = (int) date('s');
$current = $hour * 3600 + $minute * 60 + $second;

$status = 'u';
$time_till_change = -1;

$last = 'u';

foreach($stati as $time => $status)
{
	if($time*60 > $current)
	{
		$status = $last;
		$time_till_change = $time*60 - $current;
		break;
	}

	$last = $status;
}

$next_busses = array();
$num_bus = 0;

if(array_key_exists('bus', $_GET))
{
	$num_bus = (int) $_GET['bus'];
	if($num_bus < 0)
	{
		$num_bus = 0;
	}
	elseif($num_bus > 20)
	{
		$num_bus = 20;
	}

	if($num_bus)
	{
		$i = 0;
		foreach($busses as $bus)
		{
			if($bus[0] > $current/60)
			{
				$bus[0] -= floor($current/60);
				array_push($next_busses, implode(':', $bus));
				if($i++ >= $num_bus)
				{
					break;
				}
			}
		}

		if($i < $num_bus)
		{
			$until_day_end = floor(1440 - $current/60);
			for($j = 0; $j < $num_bus - $i; $j++)
			{
				$buf = $busses[$j][0] + $until_day_end;
				if($buf > 60)
				{
					$minpart = $buf%60;
					if($minpart < 10)
					{
						$minpart = '0'.strval($minpart);
					}
					$busses[$j][0] = strval(floor($buf/60)) . '’' . strval($minpart);
				}
				array_push($next_busses, implode(':', $busses[$j]));
			}
		}
	}
}

if($num_bus)
{
	$res = implode(';', $next_busses);
	echo "$status;$time_till_change;$res";
}
else
{
	echo "$status;$time_till_change;";
}
?>
