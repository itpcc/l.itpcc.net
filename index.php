<?php
$cacheFile = __DIR__.'/short-link.csv';
// @brief replace `$sheetUrl` value with your document URL from the "published to web"
//    You can generate it by, on the spreadsheet page, click File > ➕🙍Share > 🌏 Publish to Web
//    Then, on tab "Link", select "Sheet 1" (or what sheet you'd like to share) and "Comma-separated value (CSV)" and click "Publish". 
//    And copy the URL to replace the value.
$sheetUrl = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSBY-3JJai8cRKEcWjc6AiTUOkm-5wN09G9Bw1Vo55tRkU5M56OqMpgq0ckxTZa9lP6NBlMpxURv7ts/pub?output=csv';
// @brief change you default location name here.
$location = 'https://www.itpcc.net';
// @brief change you service root location name here. Keep trailing slash!
$serviceRoot = 'https://l.itpcc.net/';

$extractedQueryList = explode('/', $_SERVER['REDIRECT_URL']);
if(!empty($shortCode = $extractedQueryList[1])){
	if (!file_exists($cacheFile) || (filemtime($cacheFile) < (time() - 60 * 15 ))) {
		// Our cache is out-of-date, so load the data from our remote server,
		// and also save it over our cache for next time.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $sheetUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$data = curl_exec($ch);
		if ($data && !curl_errno($ch) && curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {
			file_put_contents($cacheFile, $data, LOCK_EX);
		}
		curl_close($ch);
	}

	$urlList = [];
	$isFound = false;
	if (($handle = fopen($cacheFile, "r")) !== FALSE) {
		$isHeader = true;
		while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
			if($isHeader) { $isHeader = false; continue; }
			list($code, $url) = $row;
			if ($shortCode === $code) {
				$location = $url;
				$isFound = true;
				break;
			}
		}
		fclose($handle);
	}
	
	if($isFound && $_GET['qrcode'] === '1'){
		$location = sprintf(
			'https://chart.googleapis.com/chart?cht=qr&chs=520x520&chl=%s', 
			urlencode($serviceRoot.$shortCode)
		);
	}
}

header("Location: {$location}");
exit();
