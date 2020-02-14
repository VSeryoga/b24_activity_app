<?php
	$protocol=$_SERVER['SERVER_PORT']=='443'?'https':'http';
	$host=explode(':',$_SERVER['HTTP_HOST']);
	$host=$host[0];
	define('BP_APP_HANDLER',$protocol.'://'.$host.$_SERVER['REQUEST_URI']);
	header('Content-Type: text/html; charset=UTF-8');



?>
<br/><br/>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<style>	
		.delete{
			display: none;
		}
		#not_admin{
			margin: auto;
		    width: fit-content;
		    color: red;
		    border: 1px solid red;
		    padding: 10px 20px;
		    display: none;
		}
		#bp_table{
			display: none;
		}
	</style>
</head>
<body>

<script src='//api.bitrix24.com/api/v1/'></script>

<script type="text/javascript">
	function getTemplates()
	{
	   	BX24.callMethod(
		   'bizproc.activity.list',
		   {},
		   function(result)
		   {
		   		actionInstall = result.data();
		   		for(var i in actionInstall){
		   			$('.install[data-code="' + actionInstall[i] + '"]').hide();
		   			$('.delete[data-code="' + actionInstall[i] + '"]').show();
		   		}
		   }
		);
	}
	function getTypeEntity() {
		var arr;
		BX24.callMethod(
	        "crm.enum.ownertype", 
	        {}, 
	        function(result) 
	        {
	            if(result.error())
	                console.error(result.error());
	            else{
	                arr = result.data();
	                for(var i in arr){
	                	typeEntity[arr[i]['ID']] = arr[i]['NAME'];
	                }
	                console.log(typeEntity);
	            }
	        }
	    );	
	}
	function uninstallActivity(code){
	  var params={
	    'CODE': code
	  }

		BX24.callMethod(
		  'bizproc.activity.delete',
		  params,
		  function (result)
		  {
		    if (result.error()){
		      alert('Error: '+result.error());
		    }else{
		      	$('.install[data-code="' + code + '"]').show();
		   		$('.delete[data-code="' + code + '"]').hide();
		   	}
		  }
		 );
	}
	function installActivity(code)
	{
		BX24.callMethod(
		  'bizproc.activity.add',
		  paramsActivity[code],
		  function(result)
		  {
			    if (result.error()){
			      alert('Error: '+result.error());
			    }else{
			      	$('.install[data-code="' + code + '"]').hide();
			   		$('.delete[data-code="' + code + '"]').show();
			   	}
		    }
		);
	}



	BX24.init(function(){

		if(!BX24.isAdmin()){
			$('#bp_table').hide();
			$('#not_admin').show();
		}else{
			$('#bp_table').show();
		}
		
		getTemplates();
		getTypeEntity();
		
		$('.install').click(function(event) {
			var code = $(this).attr('data-code')
			installActivity(code);
		});
		$('.delete').click(function(event) {
			var code = $(this).attr('data-code');
			uninstallActivity(code);
		});
		$('.help').click(function(event) {
			BX24.callMethod(
				'tasks.task.getFields',
				{},
				function(result)
				{
					console.info(result.data());
					console.log(result);
				}
			);
		});
		$('.list').click(function(event) {
			BX24.callMethod(
			   'bizproc.activity.list',
			   {},
			   function(result)
			   {
			      if(result.error())
			         alert("Ошибка: " + result.error());
			      else
			         alert("Успешно: " + result.data().join(', '));
			   }
			);
		});

	});
var actionInstall = {};
var typeEntity = {};
var handler = 'https://app.optimab24.ru.com/api.php';
var paramsActivity = {};
paramsActivity.optima_task_delete = {
	'CODE':'optima_task_delete', //уникальный в рамках приложения код
	'HANDLER': handler,
	'AUTH_USER_ID':1,
	'USE_SUBSCRIPTION':'N', //Y - если бизнесс-процесс должен ждать ответа приложения, N - если не должен ждать
	'NAME':'Удаление задачи',
	'DESCRIPTION':'',
	'PROPERTIES':{ //Входные данные для активити
		'Id':{
			'Name':'ID задачи',
			'Type':'integer',
			'Required':'Y',
			'Multiple':'N',
		},
	},
	'RETURN_PROPERTIES':{ 
	}
}
paramsActivity.optima_task_complete = {
	'CODE':'optima_task_complete', //уникальный в рамках приложения код
	'HANDLER': handler,
	'AUTH_USER_ID':1,
	'USE_SUBSCRIPTION':'N', //Y - если бизнесс-процесс должен ждать ответа приложения, N - если не должен ждать
	'NAME':'Завершение задачи',
	'DESCRIPTION':'',
	'PROPERTIES':{ //Входные данные для активити
		'Id':{
			'Name':'ID задачи',
			'Type':'integer',
			'Required':'Y',
			'Multiple':'N',
		},
	},
	'RETURN_PROPERTIES':{}
}
paramsActivity.optima_bizproc_stop = {
	'CODE':'optima_bizproc_stop', //уникальный в рамках приложения код
	'HANDLER': handler,
	'AUTH_USER_ID':1,
	'USE_SUBSCRIPTION':'N', //Y - если бизнесс-процесс должен ждать ответа приложения, N - если не должен ждать
	'NAME':'Завершить БП',
	'DESCRIPTION':'',
	'PROPERTIES':{ //Входные данные для активити
		'Id':{
			'Name':'ID бизнес-процесса',
			'Type':'integer',
			'Required':'Y',
			'Multiple':'N',
		},
	},
	'RETURN_PROPERTIES':{}
}
paramsActivity.optima_bizproc_id = {
	'CODE':'optima_bizproc_id', //уникальный в рамках приложения код
	'HANDLER': handler,
	'AUTH_USER_ID':1,
	'USE_SUBSCRIPTION':'Y', //Y - если бизнесс-процесс должен ждать ответа приложения, N - если не должен ждать
	'NAME':'Получить ID БП',
	'DESCRIPTION':'',
	'RETURN_PROPERTIES':{
		'BP_ID':{
			'Name':'ID бизнес-процесса',
			'Type':'string',
			'Required':'N',
			'Multiple':'N',
		},
	}
}
paramsActivity.optima_activity_list = {
	'CODE':'optima_activity_list', //уникальный в рамках приложения код
	'HANDLER': handler,
	'AUTH_USER_ID':1,
	'USE_SUBSCRIPTION':'Y', //Y - если бизнесс-процесс должен ждать ответа приложения, N - если не должен ждать
	'NAME':'Получить список дел',
	'DESCRIPTION':'',
	'PROPERTIES':{ //Входные данные для активити
		'ID':{
			'Name':'ID сущности',
			'Type':'integer',
			'Required':'Y',
			'Multiple':'N',
		},
		'TYPE_ID':{
			'Name':'Тип сущности',
			'Type':'select',
			'Required':'Y',
			'Multiple':'N',
			'Options': typeEntity
		},
	},
	'RETURN_PROPERTIES':{
		'LIST_ID':{
			'Name':'ID дел',
			'Type':'string',
			'Required':'N',
			'Multiple':'N',
		},
	}
}

</script>
	<div id="not_admin">
		Для внесения изменений в настройки приложения, обратитесь к администратору.
	</div>
	<!-- <button  class='list'>Установленные активити</button> -->
	<table class="table table-sm" id="bp_table">
		<tr>
			<th class="table-light">Название</th>
			<th class="table-light">Описание</th>
			<th class="table-light">Установка</th>
		</tr>
		<!-- <tr>
			<td>Поля задачи</td>
			<td>Активити позволяет получить поле задачи</td>
			<td>
				<button class='install btn btn-success btn-sm'>Установить</button>
				<button class='delete btn btn-danger btn-sm'>Удалить</button>
			</td>
		</tr> -->
		<tr>
			<td>Завершение задачи</td>
			<td>Активити позволяет завершить задачу</td>
			<td>
				<button class='install btn btn-success btn-sm' data-code="optima_task_complete">Установить</button>
				<button class='delete btn btn-danger btn-sm' data-code="optima_task_complete">Удалить</button>
			</td>
		</tr>
		<tr>
			<td>Удаление задачи</td>
			<td>Активити позволяет удалить задачу</td>
			<td>
				<button class='install btn btn-success btn-sm' data-code="optima_task_delete">Установить</button>
				<button class='delete btn btn-danger btn-sm' data-code="optima_task_delete">Удалить</button>
			</td>
		</tr>
		<tr>
			<td>Получить ID БП</td>
			<td>Активити позволяет получить ID бизнес-процесса</td>
			<td>
				<button class='install btn btn-success btn-sm' data-code="optima_bizproc_id">Установить</button>
				<button class='delete btn btn-danger btn-sm' data-code="optima_bizproc_id">Удалить</button>
			</td>
		</tr>
		<tr>
			<td>Завершить БП</td>
			<td>Активити позволяет завершить бизнес-процесса</td>
			<td>
				<button class='install btn btn-success btn-sm' data-code="optima_bizproc_stop">Установить</button>
				<button class='delete btn btn-danger btn-sm' data-code="optima_bizproc_stop">Удалить</button>
			</td>
		</tr>
		<tr>
			<td>Список дел</td>
			<td>Активити позволяет получить список дел сущности</td>
			<td>
				<button class='install btn btn-success btn-sm' data-code="optima_activity_list">Установить</button>
				<button class='delete btn btn-danger btn-sm' data-code="optima_activity_list">Удалить</button>
			</td>
		</tr>
	</table>

</body>
</html>