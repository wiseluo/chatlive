<?php
namespace App\HttpController\Sys\Common;

use EasySwoole\FastCache\Cache;
use EasySwoole\HttpClient\HttpClient;

class WeixinHelper
{
    private $appid = 'wxc00a34aa118bafe4'; //应用唯一标识
    private $app_secret = '6a7f08616e5e4e6ebc641634bba3e31b'; //应用密钥
    private $state = 'sxlive'; //第三方程序发送时用来标识其请求的唯一性的标志

    /**
     * @param string $code  用户授权码
     * @return Response  微信用户信息
     */
    public function getWxUserinfoByCode($code)
    {
        $token_res = $this->getAccessTokenWxApi($code);
        if(isset($token_res['errcode'])) {
            return ['status'=> 0, 'msg'=> '获取access_token接口api失败'];
        }
        Cache::getInstance()->set('weixin_access_token_'. $token_res['openid'], $token_res['access_token'], $token_res['expires_in']); //2小时
        Cache::getInstance()->set('weixin_refresh_token_'. $token_res['openid'], $token_res['refresh_token'], 216000); //30天

        $userinfo_res = $this->getUserinfoWxApi($token_res['access_token'], $token_res['openid']);
        if(isset($userinfo_res['errcode'])) {
            return ['status'=> 0, 'msg'=> '获取userinfo接口api失败'];
        }else{
            $userinfo = [
                'access_token'=> $token_res['access_token'],
                'refresh_token'=> $token_res['refresh_token'],
                'openid'=> $token_res['openid'],
                'sex'=> $userinfo_res['sex'],
                'nickname'=> $userinfo_res['nickname'],
                'headimgurl'=> $userinfo_res['headimgurl'],
            ];
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $userinfo];
        }
    }

    /**
     * @param string $openid  应用唯一标识
     * @return Response  微信用户信息
     */
    public function getWxUserinfoByOpenid($openid)
    {
        $access_token = Cache::getInstance()->get('weixin_access_token_'. $openid);
        $access_token_invalid = $this->isAccessTokenInvalidWxApi($access_token, $openid);
        if(isset($access_token_invalid['errcode']) && $access_token_invalid['errcode'] != 0) {
            $refresh_token = Cache::getInstance()->get('weixin_refresh_token_'. $openid);
            $refresh_token_res = $this->refreshTokenWxApi($refresh_token);
            if(isset($refresh_token_res['errcode'])) {
                return ['status'=> 0, 'msg'=> '刷新refresh_token接口api失败，请重新授权登录'];
            }else{
                $access_token = $refresh_token_res['access_token'];
                Cache::getInstance()->set('weixin_access_token_'. $openid, $refresh_token_res['access_token'], $refresh_token_res['expires_in']); //2小时
                Cache::getInstance()->set('weixin_refresh_token_'. $openid, $refresh_token_res['refresh_token'], 216000); //30天
            }
        }
        $userinfo_res = $this->getUserinfoWxApi($access_token, $openid);
        if(isset($userinfo_res['errcode'])) {
            return ['status'=> 0, 'msg'=> '获取userinfo接口api失败'];
        }else{
            $userinfo = [
                'access_token'=> $refresh_token_res['access_token'],
                'refresh_token'=> $refresh_token_res['refresh_token'],
                'openid'=> $refresh_token_res['openid'],
                'sex'=> $userinfo_res['sex'],
                'nickname'=> $userinfo_res['nickname'],
                'headimgurl'=> $userinfo_res['headimgurl'],
            ];
            return ['status'=> 1, 'msg'=> '成功', 'data'=> $userinfo];
        }
    }

    public function getAccessTokenWxApi($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='. $this->appid .'&secret='. $this->app_secret .'&code='. $code .'&grant_type=authorization_code';
        $client = new HttpClient($url);
        $response = $client->get();
        $json = json_decode($response->getBody(), true);
        return $json;
    }

    public function getUserinfoWxApi($access_token, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='. $access_token .'&openid='. $openid;
        $client = new HttpClient($url);
        $response = $client->get();
        $json = json_decode($response->getBody(), true);
        return $json;
    }

    public function isAccessTokenInvalidWxApi($access_token, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/auth?access_token='. $access_token .'&openid='. $openid;
        $client = new HttpClient($url);
        $response = $client->get();
        $json = json_decode($response->getBody(), true);
        return $json;
    }

    public function refreshTokenWxApi($refresh_token)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='. $this->appid .'&grant_type=refresh_token&refresh_token='. $refresh_token;
        $client = new HttpClient($url);
        $response = $client->get();
        $json = json_decode($response->getBody(), true);
        return $json;
    }
}