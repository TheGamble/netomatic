<?php
include '../includes/summary.php';
$input = $_POST['input'];
$notation = $_POST['notation'];
$minNetwork = $_POST['minNetwork'];

$prefix = $_POST['outputPrefix'];
$prefixEntry = $_POST['outputPrefixEntry'];
$suffix = $_POST['outputSuffix'];
$suffixEntry = $_POST['outputSuffixEntry'];

$input = preg_replace('/\s*\/\s*/', "/", $input);
$input = trim($input);
$input = preg_split('/\s+/',$input);
$net = prepare_input($input,$minNetwork);
$net = summarize($net);
$net = prepare_output($net,$notation);
foreach($net as $line => $value)
{
	if($prefix) { $value = $prefixEntry.$value; }
	if($suffix) { $value = $value.$suffixEntry; }
	echo $value."\n";
}
?>