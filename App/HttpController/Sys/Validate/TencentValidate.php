<?php
namespace App\HttpController\Sys\Validate;

use EasySwoole\Component\Singleton;
use EasySwoole\Validate\Validate;

class TencentValidate
{
    use Singleton;
    
    protected function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action) {
            case 'wxAuthLogin':
                $v->addColumn('code', '授权码')->required('不能为空');
                break;
            case 'wxAccessLogin':
                $v->addColumn('openid', '用户唯一标识')->required('不能为空');
                break;
            case 'qqLogin':
                $v->addColumn('access_token', '接口调用凭证')->required('不能为空');
                $v->addColumn('openid', '用户唯一标识')->required('不能为空');
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