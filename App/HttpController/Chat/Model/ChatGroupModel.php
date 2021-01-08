<?php
namespace App\HttpController\Chat\Model;

use EasySwoole\ORM\AbstractModel;

class ChatGroupModel extends AbstractModel
{
    protected $tableName  = 'pk_chat_group';
    protected $primaryKey = 'id';
    protected $autoTimeStamp = true;
    
}