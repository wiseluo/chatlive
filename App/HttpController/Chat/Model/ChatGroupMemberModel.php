<?php
namespace App\HttpController\Chat\Model;

use EasySwoole\ORM\AbstractModel;

class ChatGroupMemberModel extends AbstractModel
{
    protected $tableName  = 'pk_chat_group_member';
    protected $primaryKey = 'id';
    protected $autoTimeStamp = true;
    
}