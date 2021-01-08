<?php
namespace App\WebSocket;


use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\FastCache\Cache;
use EasySwoole\Socket\AbstractInterface\Controller;
use EasySwoole\RedisPool\Redis as RedisPool;
use EasySwoole\Jwt\Jwt;
use EasySwoole\Component\Di;

use App\Utility\FdManager;
use App\HttpController\Sys\Handle\SensitiveHandle;
use App\HttpController\User\Model\UserMessageModel;
use App\HttpController\Chat\Service\ChatService;
use App\HttpController\Chat\Validate\ChatValidate;
/**
 * Class Index
 *
 * 此类是默认的 websocket 消息解析后访问的 控制器
 *
 * @package App\WebSocket
 */
class Chat extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Di::getInstance()->set('SensitiveHandle', SensitiveHandle::class);
        Di::getInstance()->set('ChatService', ChatService::class);
    }

    public function single_chat()
    {
        $args   = $this->caller()->getArgs();
        $data    = $args['data'];
        $validate_ret = ChatValidate::getInstance()->check('single_chat', $args['data']);
        if($validate_ret !== true) {
            $ret_data = [
                "type" => "res_single_chat",
                'result' => 'failed',
                'msg' => $validate_ret
            ];
            $this->response()->setMessage(json_encode($ret_data));
            return;
        }

        if($data['from']['uid'] == $data['to']['uid']) {
            $ret_data = [
                "type" => "res_single_chat",
                'result' => 'failed',
                'msg' => '来源用户与目标用户不能相同'
            ];
            $this->response()->setMessage(json_encode($ret_data));
            return;
        }

        $client = $this->caller()->getClient();
        
        // 找到接收人的fd
        $fdm      = FdManager::getInstance();
        $appkey   = $fdm->getAppkeyByFd($client->getFd());
        $cur_uid  = $fdm->getUidByFd($client->getFd());

        if($cur_uid != $data['from']['uid']){
            return $this->response()->setMessage(json_encode(['type'=>'error', 'msg'=>'不可冒充别人，当前uid:'.$cur_uid.' 发送uid:'.$data['from']['uid']]));
        }

        // 消息内容     
        $msg_data                     = $data;
        $msg_data['create_microtime'] = (int)(microtime(true)*1000);
        $msg_data['read_time']        = 0;
        //屏蔽敏感词
        $sensitive_content   = Di::getInstance()->get('SensitiveHandle')->sensitiveScreen($data['content']);
        $msg_data['content'] = $sensitive_content;
        $msg_data['type']    = 'res_single_chat';
        $msg_data['result']  = 'success';
        if(isset($msg_data['extra'])) {
            $msg_data['extra']['width'] = (int)$msg_data['extra']['width'];
            $msg_data['extra']['height'] = (int)$msg_data['extra']['height'];
        }

        $fd_value = $fdm->getFdByAppkeyUid($appkey.'_'.$data['to']['uid']);

        // var_dump('find appkey_uid ==> fd  :'.$fd_value);
        // var_dump($fd_value);

        //获取swooleServer
        $server = ServerManager::getInstance()->getSwooleServer();

        if ($fd_value == null) {
            //var_dump('未在线');
            $is_offline_msg = 1;
        } else {
            //var_dump($fd_value);
            $is_offline_msg = 0;
            $server->push($fd_value, json_encode($msg_data));//发送消息
        }
        $this->response()->setMessage(json_encode($msg_data));

        //第一次聊天，设置群
        if(!isset($data['chat_group_id']) || $data['chat_group_id'] == '') {
            $group_data = [
                'appkey' => $appkey,
                'from' => $data['from'],
                'to' => $data['to'],
            ];
            $chat_group_id = Di::getInstance()->get('ChatService')->singleChatSetGroup($group_data);
        }else{
            $chat_group_id = $data['chat_group_id'];
        }

        // 记录聊天内容
        $msg_id = UserMessageModel::create([
            'appkey'           => $appkey,
            'from_uid'         => $data['from']['uid'],
            'to_uid'           => $data['to']['uid'],
            'original_content' => $data['content'],
            'content'          => $sensitive_content,
            'data'             => json_encode($msg_data),
            'create_microtime' => $msg_data['create_microtime'],
            'read_time'        => 0,
            'is_offline_msg'   => $is_offline_msg,
            'flag_id'          => $data['flag_id'],
            'chat_group_id'    => $chat_group_id,
        ])->save();

        Di::getInstance()->get('ChatService')->setGroupLastCid($chat_group_id, $msg_id);
    }


    /**
     * 设置消息为已接收,将当前登录人的消息设置为已接受
     *
     * @author hottredpen
     * @date   2020-07-28
     * @return [type]     [description]
     */
    public function message_recv()
    {
          $args   = $this->caller()->getArgs();
          $client = $this->caller()->getClient();
          $data    = $args['data'];
          // 找到接收人的fd
          $fdm      = FdManager::getInstance();
          $appkey   = $fdm->getAppkeyByFd($client->getFd());
          $uid      = $fdm->getUidByFd($client->getFd());


          $flag_ids = $data['flag_ids'];
          $flag_ids_arr = array_filter(explode(',', $data['flag_ids']));

          if( count($flag_ids_arr) > 0 ){
             $res = UserMessageModel::create()->where(['appkey'=>$appkey,'to_uid'=>$uid,'flag_id'=>[$flag_ids_arr,'in'] ])->update(['is_recv'=>1]);
             if($res){
                $this->response()->setMessage(json_encode(['type'=>'success', 'msg'=>'成功']));
             }else{
                $this->response()->setMessage(json_encode(['type'=>'error', 'msg'=>'失败']));
             }
          }else{
            $this->response()->setMessage(json_encode(['type'=>'error', 'msg'=>'无效的flag_ids']));
          }

    }
    /**
     * 设置消息为已阅读，将当前登录人的消息设置为已阅读
     *
     * @author hottredpen
     * @date   2020-07-28
     * @return [type]     [description]
     */
    public function message_read()
    {
          $args   = $this->caller()->getArgs();
          $client = $this->caller()->getClient();
          $data    = $args['data'];
          // 找到接收人的fd
          $fdm      = FdManager::getInstance();
          $appkey   = $fdm->getAppkeyByFd($client->getFd());
          $uid      = $fdm->getUidByFd($client->getFd());


          $flag_ids     = $data['flag_ids'];
          $msg_from_uid = $data['msg_from_uid'];
          $flag_ids_arr = array_filter(explode(',', $data['flag_ids']));

          if( count($flag_ids_arr) > 0 ){
             $read_time = time();
             $res = UserMessageModel::create()->where(['appkey'=>$appkey,'to_uid'=>$uid,'read_time'=>0,'flag_id'=>[$flag_ids_arr,'in'] ])->update(['read_time'=>$read_time]);
             if($res){

                //获取swooleServer
                $server = ServerManager::getInstance()->getSwooleServer();

                // 给信息原发送人告知
                $from_uid_fd = $fdm->getFdByAppkeyUid($appkey.'_'.$msg_from_uid);
                $msg_data = [
                    "type"         => "event_res_message_read", //  todo 改名为 event_res_message_read
                    "flag_ids"     => $flag_ids,
                    "msg_from_uid" => $msg_from_uid
                ];

                if($from_uid_fd){
                    $server->push($from_uid_fd, json_encode($msg_data));//发送消息
                }else{
                  // 离线事件
                  common_offline_event_auto_save($appkey,$uid,$msg_from_uid,$msg_data);
                }
                $this->response()->setMessage(json_encode(['type'=>'success', 'msg'=>'成功']));
             }else{
                $this->response()->setMessage(json_encode(['type'=>'error', 'msg'=>'失败']));
             }
          }else{
            $this->response()->setMessage(json_encode(['type'=>'error', 'msg'=>'无效的flag_ids']));
          }

    }

}