<?php
// Start of Functions
function prepare_input($net,$min) {
	// Sorts all input network addressses, convers to binary, and cleans duplicates
	$y = 0;
	natsort($net);
	foreach($net as $i) {
		$x = explode('/',$i);
		if(!isset($x[1])) { $x[1] = '32'; }
		if($x[1] > $min) { $x[1] = $min; }
		$out[$y] = subnet(ip2bin($x[0]),$x[1]);
		$y++;
	}
	$out = array_unique($out);
	return $out;
}
function summarize($net) {
	$x = 0;
	do {
		$in = $net;
		foreach($net as $i) {
			// See if we have redundant networks we can trim
			$pattern2 = '/\b' . $i . '[0,1]+\b/';
			if(count(preg_grep($pattern2,$net)) > 2) {
				$net = array_diff($net,preg_grep($pattern2,$net));
				$net[] = $lookup;
			}
			$lookup = substr($i,0,strlen($i) -1);
			$pattern = '/\b' . $lookup . '[0,1]\b/';
			// See if we can consolidate networks
			if(count(preg_grep($pattern,$net)) > 1) {
				$net = array_diff($net,preg_grep($pattern,$net));
				$net[] = $lookup;
			}
		}
		$out = $net;
		$x++;
	} while (($in !== $out) and ($x < 100));
	return $net;
}
function prepare_output($net,$notation) {
	// Takes the input binary array, formats as IP address, and appends the CIDR notation
	$y = 0;
	if ($notation == 2){
		foreach($net as $i) {
			$len = strlen($i);
			$out[$y] = bin2ip($i) . ' ' . bin2ip(str_repeat("1",$len));
			$y++;
		}
	}
	elseif ($notation == 3){
		foreach($net as $i) {
			$len = strlen($i);
			$out[$y] = bin2ip($i) . ' ' . bin2ip(str_repeat("0",$len).str_repeat("1",32-$len));
			$y++;
		}
	}
	else {
		foreach($net as $i) {
			$len = strlen($i);
			$out[$y] = bin2ip($i) . '/' . $len;
			$y++;
		}
	}
	$out = array_unique($out);
	return $out;
}
function ip2bin($ip) {
	/* Converts octal IP addresses to non-deliminated binary
	  e.g. xxx.xxx.xxx.xxx to 11111111111111111111111111111111 */
	$a = explode('.',$ip);
	$binip = octet($a[0]) . octet($a[1]) . octet($a[2]) . octet($a[3]);
	return $binip;
}
function bin2ip($bin) {
	/* Converts non-deliminated binary to octal IP addresses
	e.g. 11111111111111111111111111111111 to xxx.xxx.xxx.xxx */
	$ip = str_split(str_pad($bin,32, '0'),8);
	$x = 0;
	foreach($ip as $i) {
		$ip[$x] = bindec($i);
		$x++;
	}
	$out = implode('.',$ip);
	return $out;
}
function octet($octet) {
	/* Pads binary octets
	Really just has it's own function to keep the other one looking clean */
	return str_pad(decbin($octet),8,'0', STR_PAD_LEFT);
}
function subnet($binip,$mask) {
	// Returns a shortened subnet mask
	$network = substr($binip, 0, $mask);
	return $network;
}
?>
