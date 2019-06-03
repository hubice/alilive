<?php
/**
 * Created by PhpStorm.
 * User: skyline
 * Date: 2019/5/16
 * Time: 17:01
 */
namespace AliLive\Exceptions;

class AliLiveException extends \Exception
{
    function __construct($message = "")
    {
        parent::__construct($message);
    }
}