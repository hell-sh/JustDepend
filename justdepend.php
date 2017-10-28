<?php
if(empty($argv[1]))
{
	die("Syntax: php justdepend.php </path/to/dependencies.json>\n");
}
if(!file_exists($argv[1]) || is_dir($argv[1]))
{
	die($argv[1]." doesn't seem to exist.\n");
}
if(($json = json_decode(file_get_contents($argv[1]), true)) == NULL)
{
	die(json_last_error_msg()."\n");
}
foreach($json as $dependency)
{
	if(!isset($dependency["remote"]) || !isset($dependency["local"]))
	{
		continue;
	}
	$fh = fopen($dependency["local"], "w");
	$ch = curl_init();
	curl_setopt_array($ch, [
		CURLOPT_URL => $dependency["remote"],
		CURLOPT_FILE => $fh,
		CURLOPT_FOLLOWLOCATION => 1
	]);
	curl_exec($ch);
	curl_close($ch);
	fclose($fh);
}
?>
