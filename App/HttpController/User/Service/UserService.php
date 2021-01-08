<?php
namespace App\HttpController\User\Service;

class UserService extends BaseService
{
    public function setUserService($appkey, $param)
    {
        $user = $this->UserDatamanager->find(['appkey'=> $appkey, 'user_id'=> $param['uid']]);
        if($user == null) {
            $user_data = [
                'appkey'=> $appkey,
                'user_id'=> $param['uid'],
                'nickname'=> $param['nickname'],
                'avatar'=> $param['avatar'],
            ];
            $user_id = $this->UserDatamanager->save($user_data);
        }else{
            if((time() - $user['update_time']) > 86400) { //超过一天，更新用户信息
                $user_data = [
                    'nickname'=> $param['nickname'],
                    'avatar'=> $param['avatar'],
                ];
                $user_id = $this->UserDatamanager->update($user_data, ['id'=> $user['id']]);
            }
        }
        return 1;
    }

    public function getUserInfoByUserIdService($user_id)
    {
        return $this->UserDatamanager->find(['user_id'=> $user_id]);
    }
}