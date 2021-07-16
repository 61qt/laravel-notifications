<?php

namespace QT\Notifications\Contracts;

use EasyWeChat\OfficialAccount\Application as EasyWeChatApplication;

interface WeChatMessage 
{
    /**
     * 生成模板数据
     * 
     * @param $notifiable
     * @return array
     */
    public function buildTemplateJson($notifiable);

    /**
     * 获取Wechat Sdk
     * 
     * @return EasyWeChatApplication|null
     */
    public function getApplication(): ?EasyWeChatApplication;
}
