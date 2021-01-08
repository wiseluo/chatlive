<?php
namespace App\HttpController\Sys\Model;

use EasySwoole\ORM\AbstractModel;

class SensitiveModel extends AbstractModel
{
    protected $tableName = 'pk_sensitive';
    protected $primaryKey = 'id';
    protected $autoTimeStamp = true;
    
    //类型
    const TYPE = [
        0 => '默认',
        1 => '色情',
        2 => '反动',
        3 => '暴恐',
        4 => '民生',
        5 => '贪腐',
        6 => '同行',
        7 => '其他',
    ];
}