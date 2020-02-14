<?
$param['query'] = 'crm.activity.list?'
    .http_build_query(array(
    	'filter' => [
    		'OWNER_ID' => $_REQUEST['properties']['ID'],
        	'OWNER_TYPE_ID' => $_REQUEST['properties']['TYPE_ID']
    	],
    	'select' => [
    		'ID'
    	]
        
    ));
$result = get($_REQUEST['auth'], $param);

$answer = implode(',', array_column($result['result']['result']['query'], 'ID'));

$param = [];
$param['answer'] = 'bizproc.event.send?'
    .http_build_query(array(
        "EVENT_TOKEN" => $_REQUEST['event_token'],
        "RETURN_VALUES" => array(
            'LIST_ID' => $answer
        ),
        "LOG_MESSAGE" => "Получение ID дел"
    ));
$result = get($_REQUEST['auth'], $param);

