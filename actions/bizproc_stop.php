<?
$param['query'] = 'bizproc.workflow.terminate?'
    .http_build_query(array(
        'ID' => $_REQUEST['properties']['Id'],
        'STATUS' => 'Остановлено из БП'
    ));
$param['answer'] = 'bizproc.event.send?'
    .http_build_query(array(
        "EVENT_TOKEN" => $_REQUEST['event_token'],
        "RETURN_VALUES" => array(
            'TASK_FIELD' => ''
        ),
        "LOG_MESSAGE" => "Попытка остановить БП №".$_REQUEST['properties']['Id']
    ));

$result = get($_REQUEST['auth'], $param);