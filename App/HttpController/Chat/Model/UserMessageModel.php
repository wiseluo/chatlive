<?php
namespace App\HttpController\Chat\Model;

use EasySwoole\ORM\AbstractModel;

class UserMessageModel extends AbstractModel
{
    protected $tableName  = 'pk_user_message';
    protected $primaryKey = 'id';
}