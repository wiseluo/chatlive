<?php
namespace App\HttpController\User\Validate;

use EasySwoole\Component\Singleton;
use EasySwoole\Validate\Validate;

class UserValidate
{
    use Singleton;
    
    protected function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action) {
            case 'lastestChat':
                $v->addColumn('appkey', '应用类型')->required('不能为空');
                break;
            case 'apply':
                $v->addColumn('follow_uid', '关注的用户id')->required('不能为空')->integer('必须是整数');
                $v->addColumn('type', '关系类型')->required('不能为空')->inArray([1,2], false, '错误');
                $v->addColumn('from_friend_position')->func(function($params, $key) {
                    if($params['type'] == 2) { //申请挚友
                        if(!isset($params[$key]) || !is_numeric($params[$key])) {
                            return '挚友位必填并且为数字';
                        }
                    }
                    return true;
                });
                break;
            case 'applyReply':
                $v->addColumn('id', '关系申请id')->required('不能为空')->integer('必须是整数');
                $v->addColumn('reply', '回复结果')->required('不能为空')->inArray([1,2], false, '错误');
                break;
            case 'update':
                $v->addColumn('id', '关系申请id')->required('不能为空')->integer('必须是整数');
                $v->addColumn('name', '组合名称')->required('不能为空');
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