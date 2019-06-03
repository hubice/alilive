<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:13
 */

namespace AliLive;

class Task extends Base
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
}