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
        $time = time()+60;
        $sign = $this->getSign($time);
        $param = [
            'appid' => $this->appId,
            'interface' => 'Live_Channel_SetStatus',
            'Param.s.channel_id' => $this->prefix . '_' . $channel_id,
            'Param.n.status' => $status,
            't' => $time,
            'sign' => $sign
        ];
        $res = Http::post($this->apiUrl."?".http_build_query($param), "");
        if (isset($res['code']) && $res['code'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    //断流、暂停并延迟恢复 $action：forbid；恢复推流：resume
    public function channelManager($channel_id, $end_time, $action)
    {
        $time = time()+60;
        $sign = $this->getSign($time);
        $param = [
            'appid' => $this->appId,
            'interface' => 'channel_manager',
            't' => $time,
            'sign' => $sign,
            'Param.s.channel_id' => $this->prefix . '_' . $channel_id,
            'Param.n.abstime_end' => $end_time,
            'Param.s.action' => $action
        ];
        $res = Http::post($this->apiUrl."?".http_build_query($param), "");
        if (isset($res['code']) && $res['code'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    // 混流
    public function mixStreamV2($channel_id1, $channel_id2)
    {
        $live_code1 = $this->prefix . '_' . $channel_id1;
        $live_code2 = $this->prefix . '_' . $channel_id2;
        $time = time()+60;
        $sign = $this->getSign($time);
        $param = [
            'appid' => $this->appId,
            'interface' => 'Mix_StreamV2',
            't' => $time,
            'sign' => $sign
        ];
        $body = [
            'timestamp' => $time,
            'eventId' => $time,
            'interface' => [
                "interfaceName" => "Mix_StreamV2",
                "para" => [
                    "app_id" => $this->appId,
                    "interface" => "mix_streamv2.start_mix_stream_advanced",
                    "mix_stream_session_id" => $live_code1.$live_code2,
                    "output_stream_id" => $live_code1,
                    "input_stream_list" => [
                        [
                            "input_stream_id" => "canvas1",
                            "layout_params" => [
                                "image_layer" => 1,
                                "input_type" => 3,
                                "image_width" => 720,
                                "image_height" => 1280,
                                "color" => "0x7A567A"
                            ]
                        ],
                        [
                            "input_stream_id" => "canvas2",
                            "layout_params" => [
                                "image_layer" => 2,
                                "input_type" => 3,
                                "image_width" => 360,
                                "image_height" => 1280,
                                "location_x" => 0,
                                "location_y" => 0,
                                "color" => "0x4F7492"
                            ]
                        ],
                        [
                            "input_stream_id" => $live_code1,
                            "layout_params" => [
                                "image_layer" => 3,
                                "image_width" => 360,
                                "image_height" => 640,
                                "location_x" => 0,
                                "location_y" => 320,
                            ]
                        ],
                        [
                            "input_stream_id" => $live_code2,
                            "layout_params" => [
                                "image_layer" => 4,
                                "image_width" => 360,
                                "image_height" => 640,
                                "location_x" => 360,
                                "location_y" => 320,
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $res = Http::post($this->apiUrl."?".http_build_query($param), $body);
        if (isset($res['code']) && $res['code'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    // 取消混流
    public function cancelMixStreamV2($channel_id1, $channel_id2)
    {
        $live_code1 = $this->prefix . '_' . $channel_id1;
        $live_code2 = $this->prefix . '_' . $channel_id2;
        $time = time()+60;
        $sign = $this->getSign($time);
        $param = [
            'appid' => $this->appId,
            'interface' => 'Mix_StreamV2',
            't' => $time,
            'sign' => $sign
        ];
        $body = [
            'timestamp' => $time,
            'eventId' => $time,
            'interface' => [
                "interfaceName" => "Mix_StreamV2",
                "para" => [
                    "app_id" => $this->appId,
                    "interface" => "mix_streamv2.cancel_mix_stream",
                    "mix_stream_session_id" => $live_code1.$live_code2,
                    "output_stream_id" => $live_code1
                ]
            ]
        ];
        $res = Http::post($this->apiUrl."?".http_build_query($param), $body);
        if (isset($res['code']) && $res['code'] == 0) {
            return true;
        } else {
            return false;
        }
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

    //ACC加速流
    public function getAccPlayUrl($channel_id, $time = null) {
        $time = ($time == null) ? strtotime(date("Y-m-d 23:0:0")) + 3600 * 24 : $time;
        $txTime = strtoupper(base_convert($time, 10, 16));
        $live_code = $this->prefix . '_' . $channel_id;
        $txSecret = md5($this->acc_key . $live_code . $txTime);
        $ext_str = "?" . http_build_query(["txSecret" => $txSecret, "txTime" => $txTime, "bizid" => $this->bizid]);
        return array(
            "rtmp" => "rtmp://" . $this->play_url . "/live/" . $live_code . (isset($ext_str) ? $ext_str : "")
        );
    }
}