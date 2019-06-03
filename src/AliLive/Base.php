<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:08
 */

namespace AliLive;

class Base
{
    //推流前缀
    protected $prefix = "a";
    //推流防止盗链
    protected $push_key = "123456789";
    //推流url
    protected $push_url = "http://42784.livepush.myqcloud.com";
    //推流默认过期时间
    protected $push_expire = 86400; //24*3600
    //播放url
    protected $play_url = "http://live.pan233.com";
    //播放防止盗链
    protected $play_key = "987654321";
    //播放默认过期时间
    protected $play_expire = 86400; //24*3600

    //时间
    protected $time = 0;
    //鉴权key
    protected $apiKey = "c4fdc564d66db12f5fce594b34bf2d83";
    //账号Appid
    protected $appId = "1256197815"; //账号信息 --> appId
    //Api嗲之
    protected $apiUrl = "http://fcgi.video.qcloud.com/common_access";

    public function __construct($apiKey, $push_url, $play_url, $push_key = "", $play_key = "")
    {
//        $this->apiKey = $apiKey;
//        $this->push_key = $push_key;
//        $this->push_url = $push_url;
//        $this->play_key = $play_key;
//        $this->play_url = $play_url;
        $this->time = time();
    }

    protected function getSign() {
        return md5($this->apiKey.$this->time);
    }
}