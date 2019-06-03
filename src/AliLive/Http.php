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
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curlPost));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ));
        $return_str = curl_exec($curl);
        $errorCode = curl_errno($curl);
        if ($errorCode) {
            throw new AliLiveException("Post发送异常");
        }
        curl_close($curl);
        return $return_str;
    }
}