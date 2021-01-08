<?php
namespace App\HttpController\Chat\Validate;

use EasySwoole\Component\Singleton;
use EasySwoole\Validate\Validate;

class ChatValidate
{
    use Singleton;
    
    protected function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action) {
            case 'groupChatRecord':
                $v->addColumn('chat_group_id', '聊天群id')->required('不能为空')->integer('必须是整数');
                break;
            case 'single_chat': 
                $v->addColumn('from', '来源用户数据')->required('不能为空')->func(function($params, $key) {
                    if(!is_array($params[$key])) {
                        return '必须是数组';
                    }
                    if(!isset($params[$key]['uid']) || !is_numeric($params[$key]['uid'])) {
                        return '来源用户id必填并且为数字';
                    }
                    if(!isset($params[$key]['nickname']) || $params[$key]['nickname'] == '') {
                        return '来源用户昵称必填并且不为空';
                    }
                    if(!isset($params[$key]['avatar'])) {
                        return '来源用户头像必须';
                    }
                    return true;
                });
                $v->addColumn('to', '接收用户数据')->required('不能为空')->func(function($params, $key) {
                    if(!is_array($params[$key])) {
                        return '必须是数组';
                    }
                    if(!isset($params[$key]['uid']) || !is_numeric($params[$key]['uid'])) {
                        return '接收用户id必填并且为数字';
                    }
                    if(!isset($params[$key]['nickname']) || $params[$key]['nickname'] == '') {
                        return '接收用户昵称必填并且不为空';
                    }
                    if(!isset($params[$key]['avatar'])) {
                        return '接收用户头像必须';
                    }
                    return true;
                });
                $v->addColumn('content', '聊天内容')->required('不能为空');
                break;
            
        }
        return $v;
    }

    public function check(?string $action, $param)
    {
        $v = $this->validateRule($action);
        $ret = $v->validate($param);
        return $ret ? true : "{$v->getError()->getField()}@{$v->getError()->getFieldAlias()}:{$v->getError()->getErrorRuleMsg()}";
    }
}