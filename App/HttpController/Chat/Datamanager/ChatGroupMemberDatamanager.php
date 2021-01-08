<?php
namespace App\HttpController\Chat\Datamanager;

use App\HttpController\Chat\Model\ChatGroupMemberModel;

class ChatGroupMemberDatamanager
{
    public function get($id)
    {
        return ChatGroupMemberModel::create()->where(['id'=> $id, 'delete_time'=> 0])->get();
    }

    public function find($where)
    {
        return ChatGroupMemberModel::create()->where($where)->where('delete_time', 0)->get();
    }

    public function save($data)
    {
        return ChatGroupMemberModel::create($data)->save();
    }

    public function update($data, $where)
    {
        $chat_group_member = ChatGroupMemberModel::create();
        $res = $chat_group_member->update($data, $where);
        if($res) {
            return $chat_group_member->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function delete($where)
    {
        return ChatGroupMemberModel::create()->destroy($where);
    }

    public function softDelete($where)
    {
        $chat_group_member = ChatGroupMemberModel::create();
        $res = $chat_group_member->update(['delete_time'=> time()], $where);
        if($res) {
            return $chat_group_member->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    //获取群中的用户列表
    public function getGroupUsers($param)
    {
        $where['appkey'] = $param['appkey'];
        $where['group_id'] = $param['group_id'];
        $where['delete_time'] = 0;
        return ChatGroupMemberModel::create()->field('uid')->where($where)->all();
    }

    public function list($param)
    {
        $where['delete_time'] = 0;
        $model = ChatGroupMemberModel::create()->page($param['page'], $param['page_size']);
        // 列表数据
        $list = $model->where($where)->order('id', 'DESC')->withTotalCount()->all();
        $total = $model->lastQueryResult()->getTotalCount();
        return ['total'=> $total, 'list'=> $list];
    }

}
