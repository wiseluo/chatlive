<?php
namespace App\HttpController\Sys\Model;

use EasySwoole\ORM\AbstractModel;

class OfflineEventModel extends AbstractModel
{
    protected $tableName  = 'pk_offline_event';
    protected $primaryKey = 'id';
}