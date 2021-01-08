<?php
namespace App\WebSocket;

use \swoole_websocket_server;
use \swoole_http_request;
use \Exception;
use App\Utility\FdManager;
use EasySwoole\Jwt\Jwt;
use App\HttpController\User\Model\UserMessageModel;
use App\HttpController\Sys\Model\OfflineEventModel;

class WebSocketEvents
{
    /**
     * 打开了一个链接
     * @param swoole_websocket_server $server
     * @param swoole_http_request $request
     */
    static function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        var_dump(' onOpen fd'.$request->fd);

        if(!isset($request->get['appkey'])){
            $server->push($request->fd, json_encode(['type'=>'error', 'msg'=>'请指定appkey']));
            $server->close($request->fd); // 强制端口本次 链接  // $info = $server->getClientInfo($request->fd);
            return;
        }
        switch ($request->get['appkey']){
            case 'sxim':
                $jwt_secret_key = '609004480662a239155de1a5e4d0262f';
                $appkey        = 'sxim';
                break;
            case 'sximdev':
                $jwt_secret_key = '609004480662a239155de1a5e4d0262f';
                $appkey        = 'sximdev';
                break;
            default:
                $jwt_secret_key     = '';
                $appkey        = 'sxim';
                break;
        }
        if(isset($request->get['token'])){
            $jwtObject = Jwt::getInstance()->setSecretKey($jwt_secret_key)->decode($request->get['token']);

            // var_dump($jwtObject);

            $uid = (int)$jwtObject->id;
        }else{
            $uid = (int)$request->get['uid'];
        }
        if($uid > 0){
            // var_dump('uid:'.$uid);
            $fdm = FdManager::getInstance();
            $fdm->bind($appkey,$request->fd,$uid);
        }else{
            $data = [
                "type" => "token_expired",
                "msg"  => "登录状态过期",
            ];
            $server->push($request->fd, json_encode($data));
            $server->close($request->fd); // 强制端口本次 链接  // $info = $server->getClientInfo($request->fd);
            return;
        }

        // var_dump($uid);
        // return;

        // //获取未读消息盒子数量
        $count = UserMessageModel::create()->where(['is_offline_msg'=>1,'read_time'=>0,'to_uid'=>$uid])->count();
        $msg_count_data = [
            "type"      => "unread_msg_count",
            "count"     => $count,
            'uid'       => $uid
        ];
        // 获取最新的20条离线聊天信息
        $offline_message = UserMessageModel::create()->field('data as json_data,id')->where(['appkey'=>$appkey,'is_offline_msg'=>1,'is_recv'=>0,'read_time'=>0,'to_uid'=>$uid])->order('id','desc')->limit(20)->all();
        $offline_message_ids_arr = [];
        if($offline_message){
            foreach ($offline_message as  $k => $v) {
                $offline_message[$k] = $v->toArray(false,false);
                array_push($offline_message_ids_arr,$offline_message[$k]['id']);
            }
        }
        $offline_message =  array_reverse($offline_message);
        foreach ($offline_message as $k => $v) {
            $server->push($request->fd, $v['json_data']); //发送消息 data需别名
        }
        // 获取最新的20条离线事件信息
        $offline_event_message = OfflineEventModel::create()->field('data as json_data,id')->where(['appkey'=>$appkey,'is_offline_msg'=>1,'to_uid'=>$uid])->order('id','desc')->limit(20)->all();
        $offline_event_message_ids_arr = [];
        if($offline_event_message){
            foreach ($offline_event_message as  $k => $v) {
                $offline_event_message[$k] = $v->toArray(false,false);
                array_push($offline_event_message_ids_arr,$offline_event_message[$k]['id']);
            }
        }
        $offline_event_message =  array_reverse($offline_event_message);
        foreach ($offline_event_message as $k => $v) {
            $server->push($request->fd, $v['json_data']); //发送消息 data需别名
        }

        // 设置已读
        if( count($offline_message_ids_arr) > 0 ){
            UserMessageModel::create()->update(['is_offline_msg'=>0],['appkey'=>$appkey,'to_uid'=>$uid,'id'=>[$offline_message_ids_arr,'in'] ]);
        }
        // 设置已推送事件
        if( count($offline_event_message_ids_arr) > 0 ){
            OfflineEventModel::create()->update(['is_offline_msg'=>0],['appkey'=>$appkey,'to_uid'=>$uid,'id'=>[$offline_event_message_ids_arr,'in'] ]);
        }
        $server->push($request->fd, json_encode($msg_count_data));
    }

    // static function onHandShake(\swoole_http_request $request, \swoole_http_response $response){
    //     $request->fd = 100;
    //     var_dump('握手');
    //     return true;
    // }

    /**
     * 链接被关闭时
     * @param swoole_server $server
     * @param int $fd
     * @param int $reactorId
     * @throws Exception
     */
    static function onClose(\swoole_websocket_server $server, int $fd, int $reactorId)
    {
        var_dump('onClose');
        $info = $server->getClientInfo($fd);
        /**
         * 判断此fd 是否是一个有效的 websocket 连接
         * 参见 https://wiki.swoole.com/wiki/page/490.html
         */
        if ($info && $info['websocket_status'] === WEBSOCKET_STATUS_FRAME) 
        {
            /**
             * 判断连接是否是 server 主动关闭
             * 参见 https://wiki.swoole.com/wiki/page/p-event/onClose.html
             */
            if ($reactorId < 0) {
                echo "server close \n";
            }
            FdManager::getInstance()->delete($fd);
        }
    }
}
