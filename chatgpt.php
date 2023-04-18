<?php
ini_set('error_reporting',0);        
if($_POST['prompt']){
    // Array to store messages
    $messages = array();
    $baseurl = 'https://api.openai.com/v1/chat/completions';
    $apikey = '';//填写你自己的api key
    $model = $_POST['model'];
    //处理获取的上下文信息
    $allcontent = $_POST['allcontent'];
    $allcontent=str_replace('[\"',"",$allcontent);
    $allcontent=str_replace('\"]',"",$allcontent);
    $c = explode('\",\"',$allcontent);
   
    $params['model'] = $model?$model:'gpt-3.5-turbo';//语言模型;
    //$params['messages'][] = ['role' => 'user', 'content' => trim($_POST['prompt'])];//['role' => 'user', 'content' => trim($_POST['prompt']];
    //判断是否有上下文内容
    if(!empty($c)){
       foreach($c as $k=>$v){
            if($k%2==0){
               $params['messages'][$k]['role'] = 'user';
               $params['messages'][$k]['content'] = $v;
            } else {
               $params['messages'][$k]['role'] = 'assistant';
               $params['messages'][$k]['content'] = $v; 
            }
        }
        
        $nowmessage = array(
            array(
                'role' => 'user',
                'content' => trim($_POST["prompt"])
            )
            // You can add more messages as needed
        );
        $params['messages']+$nowmessage; 
    } else {
        $params['messages'] = array(
            array(
                'role' => 'user',
                'content' => trim($_POST["prompt"])
            )
            // You can add more messages as needed
        );
    }
    
    //$params['max_tokens'] = 256;
    $params['temperature'] = 0.1;
    //$params['stream'] = true;
    //$params['frequency_penalty'] = 0;
    //$params['presence_penalty'] = 0;
    //$params['stop'] = '["\n"]';
    $params = json_encode($params);
    //print_r($params);die;
    $response2 = post_request($baseurl,$params,$apikey);

    if(isset($response2['choices'][0]['message']['content'])){
        $res['answer'] = trim($response2['choices'][0]['message']['content']);
        $res['prompt'] = trim($_POST['prompt']);
        $res['status'] = 1;
    } else {
        $res['prompt'] = trim($_POST['prompt']);
        $res['answer'] = '请求超时，稍后再试！';
        $res['status'] = 2;
    }
    $res = json_encode($res);
    echo $res;
} else {
    $res['prompt'] = trim($_POST['prompt']);
    $res['answer'] = '系统错误，稍后再试！';
    $res['status'] = 2;
    $res = json_encode($res);
    echo $res;
}

function post_request($api_url,$post_data,$access_token){
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$access_token// 第三方验证
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$api_url);
    //头部验证
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //不输出
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    if($post_data){
        //参数设置
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    $Result = curl_exec($ch);
    if($Result === FALSE ){
       echo "error".curl_error($ch);
    } else {
       $result=json_decode($Result,true); 
    }
    curl_close($ch);
    
    return $result;
}


?>