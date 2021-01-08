<?php
namespace App\HttpController\Web;
use App\HttpController\Base;

class Index extends Base
{
    // websocket的测试
    public function ws(){
        $data =  $this->request()->getRequestParam();

        $new_data                           = [];

        $new_data['class'] = $data['class'];
        $new_data['type']  = $data['type'];

        
        $new_data['data']                   = [];
        $new_data['data']['from']           = [];
        $new_data['data']['to']             = [];
        $new_data['data']['content_format'] = [];
        // 
        
        // var_dump($data);

        foreach ($data as $key => $value){
            // var_dump($value);
            // var_dump($key);
            if($key == 'data>>from'){
                continue;
            }
            if($key == 'data>>to'){
                continue;
            }
            if($key == 'data>>content_format'){
                continue;
            }
            if( strstr($key,'data>>from>>') ){

                $new_data['data']['from'][str_replace('data>>from>>','',$key)] = $value;

            }else if(strstr($key,'data>>to>>')){

                $new_data['data']['to'][str_replace('data>>to>>','',$key)] = $value;

            }else if(strstr($key,'data>>content_format>>')){

                $new_data['data']['content_format'][str_replace('data>>content_format>>','',$key)] = $value;

            }else if(strstr($key,'data>>') ){

                $new_data['data'][str_replace('data>>','',$key)] = $value;

            }else{
                // $new_data[$key] = $value;
            }
        }
        // var_dump($new_data);
        $this->response()->write('.    '.json_encode($new_data).' ');
    }
}
