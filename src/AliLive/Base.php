<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:08
 */

namespace AliLive;

use AliLive\Exceptions\AliLiveException;

class Base
{
    //推流前缀
    protected $prefix = "a";
    //推流防止盗链
    protected $push_key = "123456789";
    //推流url
    protected $push_url = "http://42784.livepush.myqcloud.com";
    //播放url
    protected $play_url = "http://live.pan233.com";
    //播放防止盗链
    protected $play_key = "987654321";

    //时间
    protected $time;
    //鉴权key
    protected $apiKey = "c4fdc564d66db12f5fce594b34bf2d83";
    //账号Appid
    protected $appId = "1256197815"; //账号信息 --> appId
    //Api嗲之
    protected $apiUrl = "http://fcgi.video.qcloud.com/common_access";

    public function __construct(array $config)
    {
        if (empty($config['api_key']) || empty($config['push_key']) || empty($config['push_url']) ||
        empty($config['play_key']) || empty($config['play_url']) || empty($config['app_id'])) {
            throw new AliLiveException("参数错误");
        }
        $this->apiKey = $config['api_key'];
        $this->push_key = $config['push_key'];
        $this->push_url = $config['push_url'];
        $this->play_key = $config['play_key'];
        $this->play_url = $config['play_url'];
        $this->appId = $config['app_id'];
        $this->time = time();
    }

    protected function getSign() {
        return md5($this->apiKey.$this->time);
    }
}