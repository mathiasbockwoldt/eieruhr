<?php
$stati = array(0 => 'p', 25200 => 'w', 28200 => 'p', 28800 => 'w', 31800 => 'p', 32400 => 'w', 35400 => 'p', 36000 => 'w', 39000 => 'p', 39600 => 'w', 43200 => 'p', 46800 => 'w', 49800 => 'p', 50400 => 'w', 53400 => 'p', 54000 => 'w', 57000 => 'p', 57600 => 'w', 60600 => 'p', 61200 => 'w', 64200 => 'p', 64800 => 'w', 67800 => 'p', 68400 => 'w', 71400 => 'p', 72000 => 'w', 75000 => 'p', 75600 => 'w', 78600 => 'p', 111600 => 'w');

$hour = (int) date('G');
$minute = (int) date('i');
$second = (int) date('s');
$current = $hour * 3600 + $minute * 60 + $second;

$status = 'u';
$time_till_change = -1;
$next_status = 'u';
$next_time_till_change = -1;

$last = 'u';
$buffer_time = -1;

foreach($stati as $time => $stat)
{
	if($buffer_time !== -1)
	{
		$next_time_till_change = $time - $buffer_time;
		break;
	}
	if($time > $current)
	{
		$status = $last;
		$time_till_change = $time - $current;
		$next_status = $stat;
		$buffer_time = $time;
	}

	$last = $stat;
}

$next_busses = array();

if(array_key_exists('bus', $_GET))
{
	if(file_exists('buscache') and filemtime('buscache') >= time() - 60)
	{
		$xml = file_get_contents('buscache');
	}
	else
	{
		$url = 'https://rp.tromskortet.no/scripts/TravelMagic/TravelMagicWE.dll/v1DepartureSearchXML?from=UiT+%28Troms%C3%B8%29&date=' . date('j.n.Y') . '&time=' . date('G:i') . '&realtime=1';

		$xml = file_get_contents($url);

		$handle = fopen('buscache', 'w');
		fwrite($handle, $xml);
		fclose($handle);
	}

	$xml_list = explode('>', $xml);

	for($i = 0; $i < count($xml_list); $i++)
	{
		if(strpos($xml_list[$i], '<i') !== false and strpos($xml_list[$i], 'stopnr="2"') !== false)
		{
			$matches = array();

			preg_match('/ d="([^"]+)" /', $xml_list[$i], $matches);
			$time = $matches[1];
			$time = explode(' ', $time)[1];

			preg_match('/ l="([^"]+)" /', $xml_list[$i], $matches);
			$number = $matches[1];

			preg_match('/ nd="([^"]+)" /', $xml_list[$i], $matches);
			$direction = $matches[1];
			if(strpos($direction, 'via') !== false)
			{
				$direction = explode('via ', $direction)[1];
			}
			$direction = ucfirst($direction);

			array_push($next_busses, $time.'|'.$number.'|'.$direction);

			continue;
		}

		if(strpos($xml_list[$i], '</departures') !== false)
		{
			break;
		}
	}
}

if(count($next_busses))
{
	$res = implode(';', $next_busses);
	echo "$status;$time_till_change;$next_status;$next_time_till_change;$res";
}
else
{
	echo "$status;$time_till_change;$next_status;$next_time_till_change";
}
?>
