<?php
namespace App\HttpController\Sys\Model;

use EasySwoole\ORM\AbstractModel;

class SettingModel extends AbstractModel
{
    protected $tableName = 'pk_setting';
    protected $primaryKey = 'id';
    
}