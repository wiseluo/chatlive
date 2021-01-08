<?php
namespace App\HttpController\Api;

use EasySwoole\Redis\Redis;
use EasySwoole\RedisPool\Redis as RedisPool;

use App\HttpController\Base;
use App\Utility\JWTManager;
use App\HttpController\User\Model\UserModel;

class ApiBase extends Base
{
    public $request_user; // public才会根据协程清除
    public $request_user_id; // public才会根据协程清除
    //白名单
    protected $whiteList = ['chat_log_view,logout'];

    function onRequest(?string $action): ?bool
    {
        if (parent::onRequest($action)) {
            //白名单判断
            if (in_array($action, $this->whiteList)) {
                return true;
            }
            $appkey = $this->request()->getRequestParam('appkey');
            $token = $this->request()->getRequestParam('token');
            if($appkey == '') {
                $this->json_res(['code'=>401,'msg'=>'appkey必填']);
                return false;
            }
            if($token == '') {
                $this->json_res(['code'=>401,'msg'=>'token必填']);
                return false;
            }
            //获取登入信息
            if (!$this->getRequestUser($appkey, $token)) {
                $this->json_res(['code'=>401,'msg'=>'登录状态已过期']);
                return false;
            }
            return true;
        }
        return false;
    }

    function getRequestUser($appkey, $token)
    {
        $redis = RedisPool::defer('redis');
        //$redis->del('User_token_'.$token);
        $user  = $redis->get('User_token_'.$token);
        if($user) {
            $user  = json_decode($user,true);
            $this->request()->withAttribute('request_user',$user);
            $this->request_user = $user;
            $this->request_user_id = (int)$user['user_id'];
            return true;
        }else{
            try{
                $jwt = JWTManager::getInstance();
                $jwtObject = $jwt->decode($appkey, $token);
                $user_id = $jwtObject->id;
            }catch(\Exception $e){
                //var_dump($e->getMessage());
                return false;
            }
            if($user_id > 0) {
                //var_dump('user_id:'. $user_id);
                $user = UserModel::create()->where(['user_id'=> $user_id, 'appkey'=> $appkey, 'delete_time'=> 0])->get();
                if($user) {
                    $redis->set('User_token_'.$token,json_encode($user),3600);
                    $this->request()->withAttribute('request_user',$user);
                    $this->request_user = $user;
                    $this->request_user_id = (int)$user['user_id'];
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

}
