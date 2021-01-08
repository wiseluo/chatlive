<?php
namespace App\HttpController\Chat\Datamanager;

use App\HttpController\Chat\Model\ChatGroupModel;

class ChatGroupDatamanager
{
    public function get($id)
    {
        return ChatGroupModel::create()->where(['id'=> $id, 'delete_time'=> 0])->get();
    }

    public function find($where)
    {
        return ChatGroupModel::create()->where($where)->where('delete_time', 0)->get();
    }

    public function save($data)
    {
        return ChatGroupModel::create($data)->save();
    }

    public function update($data, $where)
    {
        $chat_group = ChatGroupModel::create();
        $res = $chat_group->update($data, $where);
        if($res) {
            return $chat_group->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function delete($where)
    {
        return ChatGroupModel::create()->destroy($where);
    }

    public function softDelete($where)
    {
        $chat_group = ChatGroupModel::create();
        $res = $chat_group->update(['delete_time'=> time()], $where);
        if($res) {
            return $chat_group->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function select()
    {
        return ChatGroupModel::create()->where('delete_time', 0)->all();
    }

    //最近联系人列表
    public function recentChatsList($param)
    {
        $where['cgm.appkey'] = $param['appkey'];
        $where['cgm.user_id'] = $param['user_id'];
        $where['cg.delete_time'] = 0;
        $model = ChatGroupModel::create()->alias('cg')->page($param['page'], $param['page_size']);
        // 列表数据
        $list = $model->join('pk_chat_group_member cgm', 'cgm.chat_group_id=cg.id', 'left')
            ->join('pk_user_message um', 'um.id=cg.last_cid')
            ->field('cg.id chat_group_id,cg.chat_group_type,cg.chat_group_no,cg.chat_group_name,cg.update_time,cgm.nickname_in_group,um.content,um.from_uid as  msg_from_uid,um.to_uid as msg_to_uid')
            ->where($where)
            ->order('cg.last_cid', 'DESC')
            ->withTotalCount()
            ->all();
        $total = $model->lastQueryResult()->getTotalCount();
        //var_dump($model->lastQuery()->getLastQuery());
        return ['total'=> $total, 'list'=> $list];
    }

}
