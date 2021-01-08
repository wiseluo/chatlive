<?php
namespace App\HttpController\Api;

use App\HttpController\Base;
use App\Utility\FdManager;
use EasySwoole\EasySwoole\ServerManager;
class Sdk extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    // 接受跨界宝的推送
    public function sxapp_msg(){
    	// todo 签名验证
        $param = $this->request()->getRequestParam();
        if($param == null){
            return;
        }
        if(isset($param['uids']) && $param['uids'] != ''){
            $uids_arr = array_filter( explode(',',$param['uids']) );
            foreach($uids_arr as $value){
                $param['uid'] = $value;
                $this->_push_to_fd($value,$param);
            }
        }else{
            if((int)$param['uid'] == 0){
                return;
            }
            $to_uid = $param['uid'];
            $this->_push_to_fd($to_uid,$param);
        }
        return $this->writeJson(200, '接受成功');

    }


    private function _push_to_fd($to_uid,$param){
		$data = [
            'type'             => 'message_pull_sxapp_msg',  // todo  更名为 message_pull_sxapp_msg
            'to_uid'           => $to_uid,
            'create_microtime' => (int)(microtime(true)*1000),
            'data'             => $param,
		];
        $server = ServerManager::getInstance()->getSwooleServer();

		$fdm      = FdManager::getInstance();

        $appkey = isset($param['appkey']) ? $param['appkey'] : 'sxim';

		$fd_value = $fdm->getFdByAppkeyUid($appkey.'_'.$to_uid);

        if ($fd_value > 0) {
            $server->push($fd_value, json_encode($data));//发送消息
        } else {
            //这里说明该用户已下线
            common_offline_push_message_auto_save($appkey,0,$to_uid,$data);
        }
    }
    
}
