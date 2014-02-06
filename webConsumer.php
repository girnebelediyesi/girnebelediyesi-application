<?php
header('Access-Control-Allow-Origin: *');
function obj2array($obj) {
    $out = array();
    foreach ($obj as $key => $val) {
        switch(true) {
            case is_object($val):
                $out[$key] = obj2array($val);
                break;
            case is_array($val):
                $out[$key] = obj2array($val);
                break;
            default:
                $out[$key] = $val;
        }
    }
    return $out;
}
if ($_REQUEST['suBorcAboneTB']==0)
{
    $arr[]=array('genelBorc'=>"0",'su'=>"0",'cop'=>"0",
        'kanal'=>"0",'ayd'=>"0",'saglik'=>"0");
}
else
{
    try {
        $client = new SoapClient("http://212.175.49.18/BorcSorgulama/Service.asmx?WSDL");
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    $arguments->mnoNumber = $_REQUEST['suBorcAboneTB'];//ex: "219577";
    $result = $client->BLD_SuBorc_Get( $arguments );
    $response = obj2array($result);
    //$response = $response["BLD_SuBorc_GetResult"]["any"];


    //$obj = simplexml_load_string($response);

    /*
        $resultcik = $obj->xpath('//Table');
        if (count($resultcik)>0)
        {
            foreach ($resultcik as $value)
            {
                $arr[]=array('sonOdeme'=>$value->sonOdeme,'su'=>$value->su,'cop'=>$value->cop,
    'kanal'=>$value->kanal,'ayd'=>$value->ayd,'saglik'=>$value->saglik,'genelBorc'=>$value->genelBorc);
    }
        }
        else
        {
            $arr[]=array('adi'=>'0');
        }
    }*/
    // json string

}
$data = json_encode($response['BLD_SuBorc_GetResult']);
//echo data as JSONP

if(array_key_exists('callback', $_GET)){
    header('Content-Type: text/javascript; charset=utf8');
    header('Access-Control-Allow-Origin: http://umut.tekguc.info/');
    header('Access-Control-Max-Age: 3628800');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    $callback = $_GET['callback'];
    echo $callback.'('.$data.');';
}else{
// normal JSON string
    header('Content-Type: application/json; charset=utf8');
    echo $data;
}
?>