<?php
namespace App\HttpController\Sys\Common;

use EasySwoole\FastCache\Cache;
use EasySwoole\HttpClient\HttpClient;

class QqHelper
{
    private $appid = '101876939'; //应用唯一标识


    /**
       figureurl_qq_1   大小为40×40像素的QQ头像URL。
       figureurl_qq_2  大小为100×100像素的QQ头像URL。需要注意，不是所有的用户都拥有QQ的100x100的头像，但40x40像素则是一定会有。
     */
    public function getQqUserinfo($access_token, $openid)
    {
        $userinfo_res = $this->getUserinfoQqApi($access_token, $openid);
        if(isset($userinfo_res['ret']) && $userinfo_res['ret'] == 0) {
            $userinfo = [
                'openid'=> $openid,
                'sex'=> ($userinfo_res['gender'] == '女') ? 2 : 1,
                'nickname'=> $userinfo_res['nickname'],
                'headimgurl'=> $userinfo_res['figureurl_qq_2'] ? $userinfo_res['figureurl_qq_2'] : $userinfo_res['figureurl_qq_1'],
            ];
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $userinfo];
        }else{
            return ['status'=> 0, 'msg'=> '获取userinfo接口api失败'];
        }
    }

    public function getUserinfoQqApi($access_token, $openid)
    {
        $url = 'https://graph.qq.com/user/get_user_info?access_token='. $access_token .'&oauth_consumer_key='. $this->appid .'&openid='.$openid;
        $client = new HttpClient($url);
        $response = $client->get();
        $json = json_decode($response->getBody(), true);
        //var_dump($json);
        return $json;
    }

}