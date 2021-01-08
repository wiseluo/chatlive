<?php
namespace App\HttpController\Sys\Common;

use EasySwoole\Component\Singleton;

class SensitiveHelper
{
    use Singleton;

    /**
     * 哈希表变量
     *
     * @var array|null
     */
    protected $hashMap = array();

    public function getHashMap() {
        return $this->hashMap;
    }

    public function addKeyWord($strWord) {
        $len = mb_strlen($strWord, 'UTF-8');

        // 传址
        $hashMap = &$this->hashMap;
        for ($i=0; $i < $len; $i++) {
            $word = mb_substr($strWord, $i, 1, 'UTF-8');
            // 已存在
            if (isset($hashMap[$word])) {
                if ($i == ($len - 1)) {
                    $hashMap[$word]['end'] = 1;
                }
            } else {
                // 不存在
                if ($i == ($len - 1)) {
                    $hashMap[$word] = [];
                    $hashMap[$word]['end'] = 1;
                } else {
                    $hashMap[$word] = [];
                    $hashMap[$word]['end'] = 0;
                }
            }
            // 传址
            $hashMap = &$hashMap[$word];
        }
    }
}