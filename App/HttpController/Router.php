<?php
namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {   
        // webscocket数据组装
        $routeCollector->get('/ws','/Web/Index/ws');
        // 上传
        $routeCollector->post('/app/upload','/Api/Upload/upload'); // 上传
        // 接受来自跨境宝的消息通知
        $routeCollector->post('/sdk/sxapp_msg', '/Api/Sdk/sxapp_msg');

        //最近联系人列表
        $routeCollector->get('/app/recent_chats', '/Api/Chat/recentChats');
        //群聊天记录
        $routeCollector->get('/app/group_chat_record', '/Api/Chat/groupChatRecord');

        // test
        $routeCollector->get('/test/{id:\d+}', function (Request $request, Response $response) {
            $response->write("this is router test ,your id is {$request->getQueryParam('id')}");//获取到路由匹配的id
            return false;//不再往下请求,结束此次响应
        });
    }
}