<?php 
function curl($url, $data = null, $headers = null, $proxy = null) {
	$ch = curl_init();
	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_HEADER => true,
		CURLOPT_TIMEOUT => 30,
	);

	if ($proxy != "") {
		$options[CURLOPT_HTTPPROXYTUNNEL] = true;
		$options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS4;
		$options[CURLOPT_PROXY] = $proxy;
	}


	if ($data != "") {
		$options[CURLOPT_POST] = true;
		$options[CURLOPT_POSTFIELDS] = $data;
	}

	if ($headers != "") {
		$options[CURLOPT_HTTPHEADER] = $headers;
	}

	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;

}

function getcookies($source) {
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $source, $matches);
	$cookies = array();
	foreach($matches[1] as $item) {
		parse_str($item, $cookie);
		$cookies = array_merge($cookies, $cookie);
	}
	return $cookies;
}

function fetch_value($str, $find_start, $find_end) {
	$start = @strpos($str, $find_start);
	if ($start === false) {
		return "";
	}
	$length = strlen($find_start);
	$end    = strpos(substr($str, $start + $length), $find_end);
	return trim(substr($str, $start + $length, $end));
}

?>