<?php
namespace App\HttpController\Sys\Model;

use EasySwoole\ORM\AbstractModel;

class VersionModel extends AbstractModel
{
    protected $tableName = 'pk_version_log';
    protected $primaryKey = 'id';
    protected $autoTimeStamp = true;
    
}