<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:10
 */

namespace AliLive;

class Build extends Base
{
    //获取推流地址
    function getPushUrl($channel_id, $expire = null)
    {
        $time = $expire ? time() + $expire : time() + $this->push_expire;
        $txTime = strtoupper(base_convert($time, 10, 16));
        $live_code = $this->prefix . '_' . $channel_id;
        $txSecret = md5($this->push_key . $live_code . $txTime);
        $ext_str = "?" . http_build_query(["txSecret" => $txSecret, "txTime" => $txTime]);
        return "rtmp://" . $this->push_url . "/live/" . $live_code . (isset($ext_str) ? $ext_str : "");
    }

    //获取播放地址
    function getPlayUrl($channel_id, $expire = null)
    {
        $time = $expire ? time() + $expire : time() + $this->play_expire;
        $txTime = strtoupper(base_convert($time, 10, 16));
        $live_code = $this->prefix . '_' . $channel_id;
        $txSecret = md5($this->play_key . $live_code . $txTime);
        $ext_str = "?" . http_build_query(["txSecret" => $txSecret, "txTime" => $txTime]);
        return array(
            "rtmp://" . $this->play_url . "/live/" . $live_code . (isset($ext_str) ? $ext_str : ""),
            "http://" . $this->play_url . "/live/" . $live_code . ".flv" . (isset($ext_str) ? $ext_str : ""),
            "http://" . $this->play_url . "/live/" . $live_code . ".m3u8" . (isset($ext_str) ? $ext_str : "")
        );
    }


}