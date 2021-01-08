<?php
namespace App\HttpController\Api;

use EasySwoole\Component\Di;

use App\HttpController\Api\ApiBase;
use App\HttpController\Chat\Service\ChatService;
use App\HttpController\Chat\Validate\ChatValidate;

class Chat extends ApiBase
{
    public function __construct()
    {
        parent::__construct();
        Di::getInstance()->set('ChatService', ChatService::class);
    }

    public function recentChats()
    {
        $request = $this->request();
        $param = $request->getRequestParam();
        $data['page'] = isset($param['page']) ? $param['page'] : 1;
        $data['page_size'] = isset($param['page_size']) ? $param['page_size'] : 20;
        $data['appkey'] = isset($param['appkey']) ? $param['appkey'] : 0;
        $data['user_id'] = $this->request_user_id;
        $res = Di::getInstance()->get('ChatService')->recentChatsService($data);
        return $this->json_list($res['list'], $res['total'], $data['page'], $data['page_size']);
    }

    public function groupChatRecord()
    {
        $request = $this->request();
        $param = $request->getRequestParam();
        $validate_ret = ChatValidate::getInstance()->check('groupChatRecord', $param);
        if($validate_ret !== true) {
            return $this->writeJson(400, $validate_ret);
        }
        $data['page'] = isset($param['page']) ? $param['page'] : 1;
        $data['page_size'] = isset($param['page_size']) ? $param['page_size'] : 20;
        $data['appkey'] = isset($param['appkey']) ? $param['appkey'] : 0;
        $data['chat_group_id'] = isset($param['chat_group_id']) ? $param['chat_group_id'] : 0;
        $data['user_id'] = $this->request_user_id;
        $res = Di::getInstance()->get('ChatService')->groupChatRecordService($data);
        return $this->json_list($res['list'], $res['total'], $data['page'], $data['page_size']);
    }

}