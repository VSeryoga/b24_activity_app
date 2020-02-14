<?

function callB24Method(array $auth, $method, $params){
	$c=curl_init('https://'.$auth['domain'].'/rest/'.$method.'.json');
	$params["auth"]=$auth["access_token"];
	curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($c,CURLOPT_POST,true);
	curl_setopt($c,CURLOPT_POSTFIELDS,http_build_query($params));
	$response=curl_exec($c);
	return $response['result'];
}

function get($auth, $param){
    $appParams = http_build_query(array(
        'halt' => 0,
        'cmd' => $param,
        "auth" => $auth["access_token"]
    ));
    $appRequestUrl = 'https://'.$auth['domain'].'/rest/batch';
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $appRequestUrl,
        CURLOPT_POSTFIELDS => $appParams
    ));
    $out = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($out, 1);
    writeToLog($result, 'ANSWER');
    return $result;
}
function writeToLog($data, $title = '') {
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents('/var/www/newtroick.ru/b24/app.optimab24.ru.com/logs/log_'.date('dmY').'.log', $log, FILE_APPEND);
    return true;
}