<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'max_wait_time' => 3
        ],
        'TASK' => [
           'workerNum' =>  4,
            'maxRunningNum' => 128,
            'timeout' => 15
        ]
    ],
    'TEMP_DIR' => '/tmp/',
    'LOG_DIR' => null,
    'PHAR' => [
        'EXCLUDE' => ['.idea', 'Log', 'Temp', 'easyswoole', 'easyswoole.install']
    ],


    'MYSQL' => [
        'host' => '127.0.0.1',//防止报错,就不切换数据库了
        'port' => '3306',
        'user' => 'root',//数据库用户名
        'password' => 'rootroot',//数据库密码
        'database' => 'sxlive',//数据库
        'timeout' => '5',
        'charset' => 'utf8',
        'POOL_MAX_NUM' => '6',
        'POOL_TIME_OUT' => '0.1'
    ],

    /*################ REDIS CONFIG ##################*/
    'REDIS'         => [
        'host'          => '127.0.0.1',
        'port'          => '6379',
        'auth'          => '',
        'POOL_MAX_NUM'  => '6',
        'POOL_TIME_OUT' => '0.1',
    ],

    'FAST_CACHE' => [//fastCache组件
        'PROCESS_NUM' => 1//进程数,大于0才开启
    ],
    'QINIUYUNDOMAIN' => 'http://qaaucls3i.bkt.clouddn.com/',
    'QINIUYUNaccessKey' => 'WXSlmXsgZNzR7c1uQEyaPd8oZxdihBOCPWH-jgUY',
    'QINIUYUNsecretKey' => 'YXTzt6wv4qqA0JlORXSDGziZot01GI56cqoBAIQf',
    'QINIUYUNbucket'    => 'yuyinzhibo',
    //支付宝账号1797073057@qq.com
    'ali_appid'         => '2021001164626174',

    'ali_public_key'    => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAk7rrxerOMIKNybTqzY1WlQ7KGxSEo2wJeveol+K7iao3zqRPmiBewESWDBegqtxrkInafDCJKLs5Cn696ZK5z90GCNcdamfGKumOTPvR7SeL4hiaU7431EE4bhvH0Q/RX4X5wbNnxrq7SBJbILEty5KtrXLYWWiivZMGycRvIWSRD59AVwF0cVcXeP9KtGgvVsKq8zoDykREagpPcCUkboYhFml5ywq8z7A6sp3Ay0iqyo+Pw9nsJO+jNlaVO/S2Z1fUdc6EU4Vvp+SYUnX1WtORiV+iQRdCFGaPcE4zJNNysaLDDaOIyaW7lowtJliqu9GfCeaVsJ2a9mTeVngjSQIDAQAB',

    'ali_private_key'   => 'MIIEogIBAAKCAQEAk7rrxerOMIKNybTqzY1WlQ7KGxSEo2wJeveol+K7iao3zqRPmiBewESWDBegqtxrkInafDCJKLs5Cn696ZK5z90GCNcdamfGKumOTPvR7SeL4hiaU7431EE4bhvH0Q/RX4X5wbNnxrq7SBJbILEty5KtrXLYWWiivZMGycRvIWSRD59AVwF0cVcXeP9KtGgvVsKq8zoDykREagpPcCUkboYhFml5ywq8z7A6sp3Ay0iqyo+Pw9nsJO+jNlaVO/S2Z1fUdc6EU4Vvp+SYUnX1WtORiV+iQRdCFGaPcE4zJNNysaLDDaOIyaW7lowtJliqu9GfCeaVsJ2a9mTeVngjSQIDAQABAoIBAEs8Bpbk3d0Wn975pBrKwC1pOsRPzrzraGiXd+TiM2AIsCMsyt1TXu3MAB5oagqZ9q3Fht94WGHF92bA5Tbu4nJZvZkC9JgcAXAZQb8y/9YnLbYXkYDUfto53OM2gqyVwatEL83V8CPlNTufHYmosgnayVhsBIKEJ10kY3Cd+XqnVIraLIDeUtOAD2DKtYeLiJQlcH8ODdXTfvTOtG3pwrC8fWXLIB2qZfSWRWtGwiYXHSknW2qXwTXGU/KG/2NAdgVg4UgAzKTULHQ07riaTBubDJBWWJ9SzZhaw+RlV4NTIe+LMK/64Zm4v3IBaxv4y2Rj2RQg0wSEQO4ifEq6kYECgYEA4O778rugebGCaEa5HNGjJimSDLp/vWDR0ek8JH43haldmZ4xpjT/USuKCcNlBJuW/tdfX39SEDVA2jBF3v+ip7aL5n1urauSTGI0vM/VTxqRspsVuPArUxveyWB2owqqQgX0lv04DywNNmfvBJYjgoEkPEbvpf9fEoTpxOoKo2sCgYEAqCI+PusQ0CaHGfVy7LPhoRwliZHV6VuxHzu6877jrM2Cf9fgZwgYpZL/NhOzHA6cSv8uMJ70Apzo4Z+B+G1pLk7u+iyjbFW4GSUIMgx/uTpPzG9e83VnyNdUv+6i0swHCGWgq7um+8oQmH1lOAvh5P+nROD0rCde49qBZqM7dRsCgYAeSfAhYoScfnu6APQCXnRiuixRjHeRD82RP5/6pghh9kmFWxkCcZaD3qIK6IXc1frCPEw6dtGdHx6pguCVw8SqYtl54yAZQMZFhN/nIPFvYnuGGn4VVjnvSgx4/4VaNSByHY0vG9AFsv/geKRl8LW9aBhyCXdR2g56Vu2Ht6BRGwKBgCMyyAU0ANkSt8XfdKfJILsxwUoDldy8rV/0iTbuB4vtmhxZfUkUkNRY6cTQava8uEubEHhtTngdkUNquTLg9NwU+kK1haDwcRIShpyzsSl946bQyff95DRPoBZuRvgKbo57eB97sYGU09SYq9AH7MqcGtB4p8BCncLlw6FRGulxAoGAUHjWkuJJao6N8eQFo2/UJHDHy5N8UK3DqsKREieHxIfpkMtb/BSOzVsYpVkfgSx/yDWaPTH6GzcbHZARBB3AYwnuMAi2Dpp0aHKYnGrJIuBGSItMSCol3l0pUvxD9+RuhlMY50nGKiL1pzomZZAtt0SHNclD6oYP4PyoGCg4qts=',

    'ali_zhifubao_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhVKKlGemaw/LrJu8PeRuTXATfIMfxzGJrbKIu1dbLmOLsUz3xsqx61ivT1aZ//OSsnMD5m6p9JT7f+2tYKF4Jmdi4KvLC+Bzk3KhHeP7LLm903xvuL7vOQ5M/5SNfoF77PGWqe6FpyRoVx956ngGWEiwB5ssh3bGUfoSNDQElnWJ6LHMa0WaWV+U6oRK98SM9aDkfou8L87G2HL7qh8SVK9EuuM/kt1HIGGaP5+7a9SM0ZQ4MBHvSffKF8vlExcmYaA7HvK/PByNmEISWo3KFm0crAOnA5gyH11muBBMzgUsipuzIyycNt5fc9Tm6nrN8IXW9Ivivkwko3cCNF5OvQIDAQAB',

    'ali_notifyurl' => 'http://api.xiangyinapp.com/app/pay_success_recharge',
    'wechat_notifyurl' => 'http://api.xiangyinapp.com/app/pay_success_recharge_for_wechat',
    'wechat_appid' => 'wxc00a34aa118bafe4',
    'wechat_key' => '1HPqYmyv0NvV88fptpsNRYacybSyuJtI',
    'wechat_machid' => '1596911841',
    'wechat_ClientCert' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC9fztkfLcDRGgVPryBsx1sg98Gxbrw3LozpDv5Li4id2SLfezXR23CmrS6A/P2UBAwb6DVYmnobX1msABzxkx8r2vwqHaOIb7hNaD51foasgE9xrfgRfhnu0z5Z920Y0VdRGVwGKUhAVpfD1doBd6sb1h+GawGj3rRE+Ecs4G645E1AfiILKAtGpij3jxRGbwsspHyjhy/BKz8dU+k8/Y36iVw+ua3au0vPl8cPUZ+epPb/YryC4EODT1uOWdK5hjicfuhyT8GbcuYSTbqrFaZnfLfCzvLV3NUIIu5BUtTmoMKDwTZm72Kjq1DjJrhwMiL7oczXiwEv07olda5fwNtAgMBAAECggEAMvtoH0DufBKLbivSy5JE+E0CU/Aeu26nKf2lpQSnpN46Ts0ySzkMbq7wsJsfp8UileVyf/ZaSA4tDtOLS5quOV14zOaF/vdVQ4IKLS1EfKona1drYQtyKXZa3z7yhvWuU8lzARWgV6Dlj22xNmtd3UaXqFV/0sQHZvjSN8aTwJPB8DJo2MBU0mrs3ejybdZLgpLCTyKr/EG1qBcmIY17sWaFGuU9ZFS8aSPFcjVMlRu3pH5KIQy1r+Wj+Q/gPw9CQyOH8TQ4dpFsMuCb1AZ/mHjwx3Wcep4rAGMsNYlm2eMeZUfiDZeLnc8E7K1+uSgF9NV6jFisSRRZQ1tASq88gQKBgQD0yb/kxmdKQMst5/5Wm92Hra3gtQ1aX6IRe4gwbxmIQwB7kk2ARGfSfih2rhuXT+OEaRXFoFUy9tisSlEyqHY2xYA/ANdO2jEI6URPyMBy2CV+/wMH09YySe3O5niNb7954BQ96GuiPXgTWt7slgphoJRJR2GZD3pm+RdYBlkEawKBgQDGLStpjDUcCgyGBIBqQWuxHXm0ABomvm5tXW4Ts3pLl355/pXRK6njbxu8cBD51UO1xq5pVgbRKO2oY7wqMj2vqwfgkTQ8rUFdzQhn/pPLJXhRfPo1IADwru3K3dnrX8L89lEWixETjq0YWGHIrpORtYYoDwSUU5NkG1WOaQzNhwKBgA9sGpPuUBmkhWiKAkMwpL9kmNzca0/zUIGrd8Qda81i80fVyt428ReY7TG0/HYHCX2RJVcDJ/sDBFmugPkhFfmTbeYKRoZoRsiZ8bzZGA7kQVCD/ovis88NS1dKu/OlQ7oI/R19ZDKfjs4hXoQnW9YUyOLpBWQgToToUqkwFTGrAoGBALpnikoBQ4NYQGdenJtVZcJ3Ax64aeC/hocANqX5T7MCkEiXmrvg4i+3NtOMZatZWhMFCtCxxj++y6x0A219TGCL8yDb89KO9MkSLjIDV2jhmcasU3zc2YWKRMlo2611qabe2W7m5+0Jau9XUsK80rlfkQzk813BLwYeX99QGGbJAoGAVMOPO7lz2kErupvQJ7Q/kIAHrWEj3QjnqyMU4+A2jZmy/dsY0Cgq8TZKaMKZ8nQWOXNBMobndQg6cZO73yPz0DkaYJKmi4goSw5S3NwcWLOooguMmr/GKlloq/nOkyj72JGYyG8P3D7Iq2x2rwfj',
    'wechat_ClientKey' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC9fztkfLcDRGgVPryBsx1sg98Gxbrw3LozpDv5Li4id2SLfezXR23CmrS6A/P2UBAwb6DVYmnobX1msABzxkx8r2vwqHaOIb7hNaD51foasgE9xrfgRfhnu0z5Z920Y0VdRGVwGKUhAVpfD1doBd6sb1h+GawGj3rRE+Ecs4G645E1AfiILKAtGpij3jxRGbwsspHyjhy/BKz8dU+k8/Y36iVw+ua3au0vPl8cPUZ+epPb/YryC4EODT1uOWdK5hjicfuhyT8GbcuYSTbqrFaZnfLfCzvLV3NUIIu5BUtTmoMKDwTZm72Kjq1DjJrhwMiL7oczXiwEv07olda5fwNtAgMBAAECggEAMvtoH0DufBKLbivSy5JE+E0CU/Aeu26nKf2lpQSnpN46Ts0ySzkMbq7wsJsfp8UileVyf/ZaSA4tDtOLS5quOV14zOaF/vdVQ4IKLS1EfKona1drYQtyKXZa3z7yhvWuU8lzARWgV6Dlj22xNmtd3UaXqFV/0sQHZvjSN8aTwJPB8DJo2MBU0mrs3ejybdZLgpLCTyKr/EG1qBcmIY17sWaFGuU9ZFS8aSPFcjVMlRu3pH5KIQy1r+Wj+Q/gPw9CQyOH8TQ4dpFsMuCb1AZ/mHjwx3Wcep4rAGMsNYlm2eMeZUfiDZeLnc8E7K1+uSgF9NV6jFisSRRZQ1tASq88gQKBgQD0yb/kxmdKQMst5/5Wm92Hra3gtQ1aX6IRe4gwbxmIQwB7kk2ARGfSfih2rhuXT+OEaRXFoFUy9tisSlEyqHY2xYA/ANdO2jEI6URPyMBy2CV+/wMH09YySe3O5niNb7954BQ96GuiPXgTWt7slgphoJRJR2GZD3pm+RdYBlkEawKBgQDGLStpjDUcCgyGBIBqQWuxHXm0ABomvm5tXW4Ts3pLl355/pXRK6njbxu8cBD51UO1xq5pVgbRKO2oY7wqMj2vqwfgkTQ8rUFdzQhn/pPLJXhRfPo1IADwru3K3dnrX8L89lEWixETjq0YWGHIrpORtYYoDwSUU5NkG1WOaQzNhwKBgA9sGpPuUBmkhWiKAkMwpL9kmNzca0/zUIGrd8Qda81i80fVyt428ReY7TG0/HYHCX2RJVcDJ/sDBFmugPkhFfmTbeYKRoZoRsiZ8bzZGA7kQVCD/ovis88NS1dKu/OlQ7oI/R19ZDKfjs4hXoQnW9YUyOLpBWQgToToUqkwFTGrAoGBALpnikoBQ4NYQGdenJtVZcJ3Ax64aeC/hocANqX5T7MCkEiXmrvg4i+3NtOMZatZWhMFCtCxxj++y6x0A219TGCL8yDb89KO9MkSLjIDV2jhmcasU3zc2YWKRMlo2611qabe2W7m5+0Jau9XUsK80rlfkQzk813BLwYeX99QGGbJAoGAVMOPO7lz2kErupvQJ7Q/kIAHrWEj3QjnqyMU4+A2jZmy/dsY0Cgq8TZKaMKZ8nQWOXNBMobndQg6cZO73yPz0DkaYJKmi4goSw5S3NwcWLOooguMmr/GKlloq/nOkyj72JGYyG8P3D7Iq2x2rwfjoepTLFU/gPEUmHctof6Dars=',

];
