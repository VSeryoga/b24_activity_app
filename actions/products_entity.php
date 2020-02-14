<?
$property = $_REQUEST['properties'];
$param['query'] = 'crm.productrow.list?'
    .http_build_query([
    	'filter' => [
    		'OWNER_ID' => $property['ID'],
        	'OWNER_TYPE' => $property['TYPE_ID']
    	],
    	// 'select' => [
    	// 	'ID'
    	// ]
        
    ]);
$param['currency'] = 'crm.currency.base.get?'
    .http_build_query([]);

$param['arr_currency'] = 'crm.currency.get?'
    .http_build_query([
        'id' => '$result[currency]'
    ]);

$result = get($_REQUEST['auth'], $param);

$cur = str_replace('#', '', $result['result']['result']['arr_currency']['FORMAT_STRING']);

if($result['result']['result']['query']){
    $answer = '';
    if($property['TYPE_RESULT'] == 'TABLE'){
        $answer = '<table>';
    }
    if($property['TYPE_RESULT'] == 'JSON'){
        $answerTemp = [];
    }

    $totalSum = 0;
    foreach ($result['result']['result']['query'] as $key => $product) {
        $totalSum += $product['PRICE'] * $product['QUANTITY'];

        if($property['TYPE_RESULT'] == 'LIST'){
            $answer .= $product['PRODUCT_NAME'];
            if($property['FIELD_QUANTITY'] == 'Y'){
                $answer .= ' '.(int)$product['QUANTITY'].$product['MEASURE_NAME'];
            }
            if($property['FIELD_PRICE'] == 'Y'){
                $answer .= ' '.$product['PRICE'].$cur;
            }
            if($property['FIELD_SUM_PRICE'] == 'Y'){
                $answer .= ' '.$product['PRICE'] * $product['QUANTITY'].$cur;
            }

            $answer .= "\n";
        }
        if($property['TYPE_RESULT'] == 'TABLE'){
            $colspan = 1;
            $answer .= '<tr>';
            $answer .= '<td>'.$product['PRODUCT_NAME'].'</td>';
            if($property['FIELD_QUANTITY'] == 'Y'){
                $answer .= '<td>'.(int)$product['QUANTITY'].$product['MEASURE_NAME'].'</td>';
                $colspan++;
            }
            if($property['FIELD_PRICE'] == 'Y'){
                $answer .= '<td>'.$product['PRICE'].$cur.'</td>';
                $colspan++;
            }
            if($property['FIELD_SUM_PRICE'] == 'Y'){
                $answer .= '<td>'.$product['PRICE'] * $product['QUANTITY'].$cur.'</td>';
                $colspan++;
            }

            $answer .= '</tr>';
       }
       if($property['TYPE_RESULT'] == 'JSON'){
            $answerTemp['PRODUCTS'][$key]['NAME'] = $product['PRODUCT_NAME'];
            if($property['FIELD_QUANTITY'] == 'Y'){
                $answerTemp['PRODUCTS'][$key]['QUANTITY'] = (int)$product['QUANTITY'].$product['MEASURE_NAME'];
            }
            if($property['FIELD_PRICE'] == 'Y'){
                $answerTemp['PRODUCTS'][$key]['PRICE'] = $product['PRICE'].$cur;
            }
            if($property['FIELD_SUM_PRICE'] == 'Y'){
                $answerTemp['PRODUCTS'][$key]['SUM_PRICE'] = $product['PRICE'] * $product['QUANTITY'].$cur;
            }
       }
    }
    if($property['TYPE_RESULT'] == 'LIST' && $property['FIELD_TOTAL'] == 'Y'){
        $answer .= 'Итого: '.$totalSum.$cur;
    }
    if($property['TYPE_RESULT'] == 'TABLE' && $property['FIELD_TOTAL'] == 'Y'){
        $answer .= '<tr><td colspan="'.$colspan.'">'.'Итого: '.$totalSum.$cur.'</td></tr>';
        $answer .= '</table>';
    }
    if($property['TYPE_RESULT'] == 'JSON' && $property['FIELD_TOTAL'] == 'Y'){
        $answerTemp['TOTAL'] = $totalSum.$cur;
        $answer = json_encode($answerTemp);
    }



}else{
    $answer = 'Товары отсутсвуют';
}

$param = [];
$param['answer'] = 'bizproc.event.send?'
    .http_build_query(array(
        "EVENT_TOKEN" => $_REQUEST['event_token'],
        "RETURN_VALUES" => array(
            'PRODUCTS' => $answer
        ),
        "LOG_MESSAGE" => "Получение товаров"
    ));
$result = get($_REQUEST['auth'], $param);

