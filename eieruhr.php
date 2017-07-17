<?php
$stati = array(0 => 'p', 28800 => 'w', 30300 => 'p', 30600 => 'w', 32100 => 'p', 32400 => 'w', 33900 => 'p', 34200 => 'w', 35700 => 'p', 36000 => 'w', 37500 => 'p', 37800 => 'w', 39300 => 'p', 39600 => 'w', 41100 => 'p', 41400 => 'w', 42900 => 'p', 43200 => 'w', 44700 => 'p', 45000 => 'w', 46500 => 'p', 46800 => 'w', 48300 => 'p', 48600 => 'w', 50100 => 'p', 50400 => 'w', 51900 => 'p', 52200 => 'w', 53700 => 'p', 54000 => 'w', 55500 => 'p', 55800 => 'w', 57300 => 'p', 57600 => 'w', 59100 => 'p', 59400 => 'w', 60900 => 'p', 61200 => 'w', 62700 => 'p', 63000 => 'w', 64500 => 'p', 64800 => 'w', 66300 => 'p', 66600 => 'w', 68100 => 'p', 68400 => 'w', 69900 => 'p', 115200 => 'w');

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
