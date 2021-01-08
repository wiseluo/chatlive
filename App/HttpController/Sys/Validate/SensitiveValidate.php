<?php
namespace App\HttpController\Sys\Validate;

use EasySwoole\Component\Singleton;
use EasySwoole\Validate\Validate;

class SensitiveValidate
{
    use Singleton;
    
    protected function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action) {
            case 'save':
                $v->addColumn('type', '类型')->required('不能为空')->inArray([1,2,3,4,5,6,7], false, '错误');
                $v->addColumn('word', '敏感词')->required('不能为空');
                break;
            case 'update':
                $v->addColumn('type', '类型')->required('不能为空')->inArray([1,2,3,4,5,6,7], false, '错误');
                $v->addColumn('word', '敏感词')->required('不能为空');
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