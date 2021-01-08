<?php
namespace App\HttpController\Sys\Service;

use App\HttpController\Sys\Common\QqHelper;
use App\HttpController\User\Service\UserService;

class QqService extends BaseService
{
    public function qqLoginService($param)
    {
        $wxhelper = new QqHelper();
        $wx_userinfo = $wxhelper->getQqUserinfo($param['access_token'], $param['openid']);
        if($wx_userinfo['status']) {
            return $this->userLogin($wx_userinfo['data']);
        }else{
            return ['status'=> 0, 'msg'=> $wx_userinfo['msg']];
        }
    }

    public function userLogin($userinfo)
    {
        $username = 'qq_'. $userinfo['openid'];
        $user = $this->UserDatamanager->find(['username'=> $username]);
        if($user == null) {
            $user_data = [
                'nickname'=> $userinfo['nickname'],
                'username'=> $username,
                'avatar'=> $userinfo['headimgurl'],
                'sex'=> $userinfo['sex'],
            ];
            $res = $this->UserDatamanager->save($user_data);
            if(!$res) {
                return ['status'=> 0, 'msg'=> '添加用户失败'];
            }
        }
        $user_res = UserService::weixinVerifyCode($username);
        if($user_res['code'] == 200) {
            $user_info = $user_res['data'];
            $user_info['openid'] = $userinfo['openid'];
            return ['status'=> 1, 'msg'=> $user_res['msg'], 'data'=> $user_info];
        }else{
            return ['status'=> 0, 'msg'=> $user_res['msg']];
        }
    }
}