<?php
namespace App\HttpController\Chat\Service;

class ChatService extends BaseService
{
    public function singleChatSetGroup($param)
    {
        $min_uid = min($param['from']['uid'], $param['to']['uid']);
        $max_uid = max($param['from']['uid'], $param['to']['uid']);
        $chat_group_no = $param['appkey'] .'-'. $min_uid .'-'. $max_uid;

        $group_id = common_trans_chat_group_no_to_chat_group_id($chat_group_no, 'single_chat');
        if($group_id == 0) {
            $group_data = [
                'appkey'=> $param['appkey'],
                'chat_group_type'=> 'single_chat',
                'chat_group_name'=> '',
                'chat_group_no'=> $chat_group_no,
            ];
            $group_id = $this->ChatGroupDatamanager->save($group_data);
            $from_user_group_data = [
                'appkey'=> $param['appkey'],
                'chat_group_id'=> $group_id,
                'user_id'=> $param['from']['uid'],
                'nickname_in_group'=> $param['from']['nickname'],
            ];
            $this->ChatGroupMemberDatamanager->save($from_user_group_data);
            $to_user_group_data = [
                'appkey'=> $param['appkey'],
                'chat_group_id'=> $group_id,
                'user_id'=> $param['to']['uid'],
                'nickname_in_group'=> $param['to']['nickname'],
            ];
            $this->ChatGroupMemberDatamanager->save($to_user_group_data);

            //设置用户
            $this->UserService->setUserService($param['appkey'], $param['from']);
            $this->UserService->setUserService($param['appkey'], $param['to']);
            
        }
        return $group_id;
    }

    public function setGroupLastCid($group_id, $msg_id)
    {
        return $this->ChatGroupDatamanager->update(['last_cid'=> $msg_id], ['id'=> $group_id]);
    }

    //获取最近联系人列表
    public function recentChatsService($param)
    {
        $res = $this->ChatGroupDatamanager->recentChatsList($param);
        $list = [];
        foreach($res['list'] as $k => $v) {
            $item = [
                'chat_group_id' => $v['chat_group_id'],
                'chat_group_no' => $v['chat_group_no'],
                'chat_group_type' => $v['chat_group_type'],
                'chat_time' => date('y/m/d', $v['update_time']),
                'chat_uid'  => $v['msg_from_uid'] == $param['user_id'] ? $v['msg_to_uid'] : $v['msg_from_uid'],
            ];
            if(mb_strlen($v['content'], 'utf8') > 10) {
                $content = mb_substr($v['content'], 0, 10, 'utf-8') .'...';
            }else{
                $content = $v['content'];
            }
            $item['content'] = $content;
            if($v['chat_group_type'] == 'single_chat') {
                $user = $this->getSingleChatTargetInfo($v['chat_group_no'], $param['user_id']);
                $item['chat_group_name'] = $user['nickname'];
                $item['chat_group_avatar'] = $user['avatar'];
            }else{
                $item['chat_group_name'] = $v['chat_group_name'];
                $item['chat_group_avatar'] = '';
            }
            $list[] = $item;
        }
        return ['total'=> $res['total'], 'list'=> $list];
    }

    //获取单聊对象信息
    public function getSingleChatTargetInfo($chat_group_no, $user_id)
    {
        $arr = explode("-", $chat_group_no);
        $target_user_id = ($user_id == $arr[1]) ? $arr[2] : $arr[1];
        return $this->UserService->getUserInfoByUserIdService($target_user_id);
    }

    public function groupChatRecordService($param)
    {
        return $this->UserMessageDatamanager->groupChatRecordlist($param);
    }
}