<?php
namespace App\HttpController\Chat\Datamanager;

use App\HttpController\Chat\Model\UserMessageModel;

class UserMessageDatamanager
{
    public function get($id)
    {
        return UserMessageModel::create()->where(['id'=> $id, 'delete_time'=> 0])->get();
    }

    public function find($where)
    {
        return UserMessageModel::create()->where($where)->where('delete_time', 0)->get();
    }

    public function save($data)
    {
        return UserMessageModel::create($data)->save();
    }

    public function update($data, $where)
    {
        $user_message = UserMessageModel::create();
        $res = $user_message->update($data, $where);
        if($res) {
            return $user_message->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function delete($where)
    {
        return UserMessageModel::create()->destroy($where);
    }

    public function softDelete($where)
    {
        $user_message = UserMessageModel::create();
        $res = $user_message->update(['delete_time'=> time()], $where);
        if($res) {
            return $user_message->lastQueryResult()->getAffectedRows();
        }else{
            return false;
        }
    }

    public function select()
    {
        return UserMessageModel::create()->field('word')->where('delete_time', 0)->all();
    }

    public function groupChatRecordlist($param)
    {
        $where['chat_group_id'] = $param['chat_group_id'];
        //$where['delete_time'] = 0;
        $model = UserMessageModel::create()->page($param['page'], $param['page_size']);
        // 列表数据
        $list = $model
            ->where($where)
            ->order('id', 'DESC')
            ->withTotalCount()
            ->all();
        $total = $model->lastQueryResult()->getTotalCount();
        return ['total'=> $total, 'list'=> $list];
    }

}
