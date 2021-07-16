<?php

namespace QT\Notifications\Traits;

use EasyWeChat\OfficialAccount\Application as EasyWeChatApplication;

trait WeChatMessageHelper
{
    /**
     * 生成模板数据
     * 
     * @param $notifiable
     * @return array
     */
    public function buildTemplateJson($notifiable)
    {
        $json = [
            'template_id' => $this->getTemplateId(),
            'url'         => $this->getRedirectUrl(),
            'data'        => $this->getParams($notifiable),
        ];

        if ($this->redirectToMiniProgram() && $this->getMiniProgramAppId()) {
            $json['miniprogram'] = [
                'appid'    => $this->getMiniProgramAppId(),
                'pagepath' => $this->getMiniProgramPagePath(),
            ];
        }

        return $json;
    }

    /**
     * 获取Wechat Sdk
     * 
     * @return EasyWeChatApplication|null
     */
    public function getApplication(): ?EasyWeChatApplication
    {
        return null;
    }

    /**
     * 获取微信模板消息id
     *
     * @return string
     */
    abstract protected function getTemplateId();

    /**
     * 获取模板变量
     *
     * @return array
     */
    protected function getParams($notifiable)
    {
        return [];
    }

    /**
     * 是否跳转小程序
     *
     * @return bool
     */
    protected function redirectToMiniProgram()
    {
        return false;
    }

    /**
     * 小程序跳转地址
     *
     * @return string
     */
    protected function getMiniProgramPagePath()
    {
        return '';
    }

    /**
     * 是否跳转至指定链接
     *
     * @return string
     */
    protected function getRedirectUrl()
    {
        return '';
    }

    /**
     * 获取小程序appid
     *
     * @return string
     */
    protected function getMiniProgramAppId()
    {
        return '';
    }
}
