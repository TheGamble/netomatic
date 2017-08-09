<?php
/* Start of Functions */
function ip2bin($ip) {
	/* Converts octal IP addresses to non-deliminated binary
	  e.g. xxx.xxx.xxx.xxx to 11111111111111111111111111111111 */
	$binip = decbin(ip2long($ip));
	return $binip;
}
function bin2ip($bin) {
	/* Converts non-deliminated binary to octal IP addresses
	e.g. 11111111111111111111111111111111 to xxx.xxx.xxx.xxx */
	$out = long2ip(bindec(str_pad($bin, 32, "0", STR_PAD_LEFT)));
	return $out;
}
function subnet($binip,$mask) {
	/* Returns a shortened subnet mask */
	$network = substr($binip, 0, $mask);
	return $network;
}
function prepare_input($net,$min) {
	/* Sorts all input network addressses, convers to binary, and cleans duplicates */
	sort($net);
	$y = 0;
	foreach($net as $i) {
		$x = explode('/',$i);
		if(!isset($x[1])) { $x[1] = '32'; }
		if($x[1] > $min) { $x[1] = $min; }
		$out[$y] = subnet(ip2bin($x[0]),$x[1]);
		$y++;
	}
	$out = array_unique($out, SORT_NUMERIC);
	ksort($out);
	return $out;
}
function prepare_output($net,$notation) {
	/* Takes the input binary array, formats as IP address, and appends the CIDR notation */
	ksort($net);
	$y = 0;
	if($notation == 1){
		foreach($net as $i) {
			$len = strlen($i);
			$out[$y] = bin2ip($i) . '/' . $len;
			$y++;
		}
	}
	if($notation == 2){
		foreach($net as $i) {
			$len = strlen($i);
			$out[$y] = bin2ip($i) . ' ' . bin2ip(str_repeat("1",$len));
			$y++;
		}
	}
	if($notation == 3){
		foreach($net as $i) {
			$len = strlen($i);
			$out[$y] = bin2ip($i) . ' ' . bin2ip(str_repeat("0",$len).str_repeat("1",32-$len));
			$y++;
		}
	}
	$out = array_unique($out);
	return $out;
}
function summarize($net) {
	$x = 0;
	do {
		$in = $net;
		foreach($net as $i) {
			$lookup = substr($i,0,strlen($i) -1);
			$pattern = '/\b' . $lookup . '[0,1]\b/';
			if(count(preg_grep($pattern,$net)) > 1) {
				$net = array_diff($net,preg_grep($pattern,$net));
				$net[] = $lookup;
			}
		}
		$out = $net;
		$x++;
	} while (($in !== $out) and ($x < 30));
	return $net;
}
?>
