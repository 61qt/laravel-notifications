<?php

namespace QT\Notification;

use Overtrue\EasySms\EasySms;
use Illuminate\Support\ServiceProvider;
use QT\Notifications\Channels\SmsChannel;
use Illuminate\Notifications\ChannelManager;
use QT\Notifications\Channels\WeChatChannel;
use QT\Notifications\Channels\DingTalkChannel;
use QT\Notifications\Channels\EloquentChannel;
use EasyDingTalk\Application as EasyDingTalkApplication;
use EasyWeChat\OfficialAccount\Application as EasyWeChatApplication;

class ChannelServicerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * @var ChannelManager $manger
         */
        $manger = $this->app[ChannelManager::class];

        $manger->extend('eloquent', function () {
            return new EloquentChannel;
        });

        if (class_exists(EasySms::class)) {
            $manger->extend('sms', function () {
                return new SmsChannel($this->app[EasySms::class]);
            });
        }

        if (class_exists(EasyWeChatApplication::class)) {
            $manger->extend('weChat', function () {
                return new WeChatChannel($this->app[EasyWeChatApplication::class]);
            });
        }

        if (class_exists(EasyDingTalkApplication::class)) {
            $manger->extend('dingTalk', function () {
                return new DingTalkChannel($this->app[EasyDingTalkApplication::class]);
            });
        }
    }
}
