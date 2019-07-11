<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:09
 */

namespace AliLive;

use AliLive\Exceptions\AliLiveException;

class Http
{
    public static function post($url, $curlPost) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curlPost));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        $return_str = curl_exec($curl);
        $errorCode = curl_errno($curl);
        apiLog($return_str);
        apiLog($errorCode);
        curl_close($curl);
        if ($errorCode) {
            throw new AliLiveException("Post发送异常");
        }
        $res = json_decode($return_str,true) ?? false;
        if ($res === false) {
            throw new AliLiveException("Post发送异常");
        }
        return $res;
    }
}