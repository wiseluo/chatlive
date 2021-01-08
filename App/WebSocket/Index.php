<?php
namespace App\WebSocket;
use EasySwoole\Socket\AbstractInterface\Controller;
/**
 * Class Index
 *
 * 此类是默认的 websocket 消息解析后访问的 控制器
 *
 * @package App\WebSocket
 */
class Index extends Controller
{

    public function heartbeat(){
        $this->response()->setMessage('PONG');
    }

    // 老的
    public function ping(){
        $data = [
            "type" => "pong"
        ];
        $this->response()->setMessage(json_encode($data));
    }

}