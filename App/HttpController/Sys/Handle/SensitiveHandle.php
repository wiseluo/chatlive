<?php
namespace App\HttpController\Sys\Handle;

use EasySwoole\FastCache\Cache;
use App\HttpController\Sys\Common\SensitiveHelper;

class SensitiveHandle extends BaseHandle
{
    /**
     * 替换符号
     * @var string
     */
    private $replaceChar = "*";

    //建立敏感词树
    public function buildTree()
    {
        $sensitive = $this->SensitiveDatamanager->select();
        $sensitiveHelper = SensitiveHelper::getInstance();
        foreach($sensitive as $k => $v) {
            $sensitiveHelper->addKeyWord($v['word']);
        }
        Cache::getInstance()->set('sensitive_hash_map', $sensitiveHelper->getHashMap());
        return $sensitiveHelper->getHashMap();
    }

    //敏感词屏蔽
    public function sensitiveScreen($content)
    {
        $badWordList = $this->checkWordTree($content);

        // 未检测到敏感词，直接返回
        if (empty($badWordList)) {
            return $content;
        }

        foreach ($badWordList as $badWord) {
            $badWordLength = mb_strlen($badWord, 'UTF-8');
            $hasReplacedChar = str_repeat($this->replaceChar, $badWordLength);
            $content = str_replace($badWord, $hasReplacedChar, $content);
        }
        return $content;
    }

    /**
     * 检查敏感词树是否合法
     * @param string $content 检查内容
     * @return int 返回不合法字符数组
     */
    private function checkWordTree(string $content)
    {
        $hashMap = Cache::getInstance()->get('sensitive_hash_map');
        if($hashMap == null) {
            $hashMap = $this->buildTree();
        }
        $flag = false;
        $badWordList = array();
        $contentLength = mb_strlen($content, 'UTF-8');
        for ($length = 0; $length < $contentLength; $length++) {
            $matchFlag = 0;
            $flag = false;
            $tempMap = $hashMap;
            for($i = $length; $i < $contentLength; $i++) {
                $keyChar = mb_substr($content, $i, 1, 'UTF-8'); //截取需要检测的文本，和词库进行比对
                //如果搜索字不存在词库中直接停止循环。
                if(!isset($tempMap[$keyChar])) {
                    break;
                }

                // 找到相应key，偏移量+1
                $matchFlag++;

                if($tempMap[$keyChar]['end'] === 0){//检测还未到底
                    $tempMap = $tempMap[$keyChar]; //继续搜索下一层tree
                }else{
                    $flag = true; //未完全匹配到（只匹配到一部分不算屏蔽词）
                }
            }
            if (!$flag) {
                $matchFlag = 0;
            }
            // 找到相应key
            if ($matchFlag <= 0) {
                continue;
            }

            $badWordList[] = mb_substr($content, $length, $matchFlag, 'UTF-8');

            // 需匹配内容标志位往后移
            $length = $length + $matchFlag - 1;
        }
        return $badWordList;
    }
}