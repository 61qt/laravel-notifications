<?php

namespace QT\Notifications\Contracts;

use EasyDingTalk\Messages\Message;
use EasyDingTalk\Application as EasyDingTalkApplication;

interface DingTalkMessage
{
    /**
     * 获取微应用id
     * 
     * @return string
     */
    public function getAgentId();

    /**
     * 获取消息
     * 
     * @return Message
     */
    public function getMessage($notifiable) : Message;

    /**
     * 获取DingTalk Sdk
     * 
     * @return string
     */
    public function getApplication(): ?EasyDingTalkApplication;
}
