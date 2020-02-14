<?
$param['answer'] = 'bizproc.event.send?'
    .http_build_query(array(
        "EVENT_TOKEN" => $_REQUEST['event_token'],
        "RETURN_VALUES" => array(
            'BP_ID' => $_REQUEST['workflow_id']
        ),
        "LOG_MESSAGE" => "Получение ID БП"
    ));

$result = get($_REQUEST['auth'], $param);