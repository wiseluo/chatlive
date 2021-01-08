<?php
namespace App\HttpController\Sys\Validate;

use EasySwoole\Component\Singleton;
use EasySwoole\Validate\Validate;

class VersionValidate
{
    use Singleton;
    
    protected function validateRule(?string $action): ?Validate
    {
        $v = new Validate();
        switch ($action) {
            case 'save':
                $v->addColumn('type', '类型')->required('不能为空')->inArray([1,2], false, '错误');
                $v->addColumn('number', '版本号')->required('不能为空');
                $v->addColumn('date', '更新日期')->required('不能为空')->func(function($params, $key) {
                    $info = date_parse_from_format('Y-m-d', $params[$key]);
                    if(!(0 == $info['warning_count'] && 0 == $info['error_count'])) {
                        return '日期格式错误:Y-m-d';
                    }
                    return true;
                }, '日期格式错误:Y-m-d');
                $v->addColumn('detail', '更新说明')->required('不能为空');
                $v->addColumn('address', '附件地址')->required('不能为空');
                $v->addColumn('isforce', '是否强制')->required('不能为空')->inArray([0,1], false, '错误');
                $v->addColumn('size', 'apk大小')->required('不能为空');
                break;
            case 'update':
                $v->addColumn('type', '类型')->required('不能为空')->inArray([1,2], false, '错误');
                $v->addColumn('number', '版本号')->required('不能为空');
                $v->addColumn('date', '更新日期')->required('不能为空');
                $v->addColumn('detail', '更新说明')->required('不能为空');
                $v->addColumn('address', '附件地址')->required('不能为空');
                $v->addColumn('isforce', '是否强制')->required('不能为空')->inArray([0,1], false, '错误');
                $v->addColumn('size', 'apk大小')->required('不能为空');
                break;
            case 'versionQrcode':
                $v->addColumn('type', '类型')->required('不能为空')->inArray([1,2], false, '错误');
                break;
            case 'versionCheck':
                $v->addColumn('type', '类型')->required('不能为空')->inArray([1,2], false, '错误');
                $v->addColumn('number', '版本号')->required('不能为空');
                break;
            case 'versionLogs':
                $v->addColumn('type', '类型')->required('不能为空')->inArray([1,2], false, '错误');
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