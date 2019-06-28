<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:13
 */

namespace AliLive;

class Api extends Base
{
    //开启关闭推流 0 表示禁用，1 表示允许推流，2 表示断流
    public function liveChannelSetStatus($channel_id, $status)
    {
        $sign = $this->getSign();
        $param = [
            'appid' => $this->appId,
            'interface' => 'Live_Channel_SetStatus',
            'Param.s.channel_id' => $this->prefix . '_' . $channel_id,
            'Param.n.status' => $status,
            't' => $this->time,
            'sign' => $sign
        ];
        return Http::post($this->apiUrl."?".http_build_query($param), "");
    }

    //断流、暂停并延迟恢复 $action：forbid；恢复推流：resume
    public function channelManager($channel_id, $end_time, $action)
    {
        $sign = $this->getSign();
        $param = [
            'appid' => $this->appId,
            'interface' => 'channel_manager',
            't' => $this->time,
            'sign' => $sign,
            'Param.s.channel_id' => $this->prefix . '_' . $channel_id,
            'Param.n.abstime_end' => $end_time,
            'Param.s.action' => $action
        ];

        return Http::post($this->apiUrl."?".http_build_query($param), "");
    }

    // 混流
    public function mixStreamV2($session_id,$output_stream_id,$input_stream_list)
    {
        $sign = $this->getSign();
        $param = [
            'appid' => $this->appId,
            'interface' => 'Mix_StreamV2',
            't' => $this->time,
            'sign' => $sign
        ];
        $body = [
            'timestamp' => $this->time,
            'eventId' => $this->time,
            'interface' => [
                "interfaceName" => "Mix_StreamV2",
                "para" => [
                    "app_id" => $this->appId,
                    "interface" => "mix_streamv2.start_mix_stream_advanced",
                    "mix_stream_session_id" => $session_id,
                    "output_stream_id" => $output_stream_id,
                    "input_stream_list" => $input_stream_list
                ]
            ]
        ];
        return Http::post($this->apiUrl."?".http_build_query($param), $body);
    }

    // 取消混流
    public function cancelMixStreamV2($session_id,$output_stream_id)
    {
        $sign = $this->getSign();
        $param = [
            'appid' => $this->appId,
            'interface' => 'Mix_StreamV2',
            't' => $this->time,
            'sign' => $sign
        ];
        $body = [
            'timestamp' => $this->time,
            'eventId' => $this->time,
            'interface' => [
                "interfaceName" => "Mix_StreamV2",
                "para" => [
                    "app_id" => $this->appId,
                    "interface" => "mix_streamv2.cancel_mix_stream",
                    "mix_stream_session_id" => $session_id,
                    "output_stream_id" => $output_stream_id
                ]
            ]
        ];
        return Http::post($this->apiUrl."?".http_build_query($param), $body);
    }

    //获取推流地址
    public function getPushUrl($channel_id, $time = null)
    {
        $time = ($time == null) ? strtotime(date("Y-m-d 23:0:0")) + 3600 * 24 : $time;
        $txTime = strtoupper(base_convert($time, 10, 16));
        $live_code = $this->prefix . '_' . $channel_id;
        $txSecret = md5($this->push_key . $live_code . $txTime);
        $ext_str = "?" . http_build_query(["txSecret" => $txSecret, "txTime" => $txTime]);
        return "rtmp://" . $this->push_url . "/live/" . $live_code . (isset($ext_str) ? $ext_str : "");
    }

    //获取播放地址
    public function getPlayUrl($channel_id, $time = null)
    {
        $time = ($time == null) ? strtotime(date("Y-m-d 23:0:0")) + 3600 * 24 : $time;
        $txTime = strtoupper(base_convert($time, 10, 16));
        $live_code = $this->prefix . '_' . $channel_id;
        $txSecret = md5($this->play_key . $live_code . $txTime);
        $ext_str = "?" . http_build_query(["txSecret" => $txSecret, "txTime" => $txTime]);
        if (empty($this->play_key)) {
            return array(
                "rtmp" => "rtmp://" . $this->play_url . "/live/" . $live_code,
                "flv" => "http://" . $this->play_url . "/live/" . $live_code . ".flv",
                "m3u8" => "http://" . $this->play_url . "/live/" . $live_code . ".m3u8"
            );
        } else {
            return array(
                "rtmp" => "rtmp://" . $this->play_url . "/live/" . $live_code . (isset($ext_str) ? $ext_str : ""),
                "flv" => "http://" . $this->play_url . "/live/" . $live_code . ".flv" . (isset($ext_str) ? $ext_str : ""),
                "m3u8" => "http://" . $this->play_url . "/live/" . $live_code . ".m3u8" . (isset($ext_str) ? $ext_str : "")
            );
        }
    }
}