<?
$param['task_delete'] = 'tasks.task.delete?'
    .http_build_query(array(
        'taskId' => $_REQUEST['properties']['Id'],
    ));
$param['answer'] = 'bizproc.event.send?'
    .http_build_query(array(
        "EVENT_TOKEN" => $_REQUEST['event_token'],
        "RETURN_VALUES" => array(
            'TASK_FIELD' => ''
        ),
        "LOG_MESSAGE" => "Попытка удалить задачу №".$_REQUEST['properties']['Id']
    ));

$result = get($_REQUEST['auth'], $param);