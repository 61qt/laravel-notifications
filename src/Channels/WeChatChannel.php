<?php

namespace QT\Notifications\Channels;

use RuntimeException;
use Illuminate\Notifications\Notification;
use QT\Notifications\Contracts\WeChatMessage;
use EasyWeChat\OfficialAccount\Application as EasyWeChatApplication;

class WeChatChannel
{
    /**
     * The EasyWeChat Sdk instance.
     *
     * @var EasyWeChatApplication
     */
    protected $app;

    /**
     * Create a new Slack channel instance.
     *
     * @param EasyWeChatApplication $app
     * @return void
     */
    public function __construct(EasyWeChatApplication $app = null)
    {
        // 不保证微信一定有配置,所以允许sdk为空
        $this->app = $app;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$notification instanceof WeChatMessage) {
            return;
        }

        $app = $notification->getApplication() ?: $this->app;

        if ($app === null) {
            return;
        }

        // 尝试获取openid
        if (is_object($notifiable) && method_exists($notifiable, 'routeNotificationFor')) {
            $toUsers = $notifiable->routeNotificationFor('openId', $notification);
        } elseif (is_string($notifiable)) {
            $toUsers = [$notifiable];
        }

        if (empty($toUsers)) {
            return;
        }

        $msgData = $notification->buildTemplateJson($notifiable);

        // 循环openid 发送消息
        foreach ($toUsers as $user) {
            $this->sendMessage($app, $user, $msgData);
        }
    }

    /**
     * Send the given notification.
     *
     * @param EasyWeChatApplication $app
     * @param string $toUser
     * @param array $data
     * 
     * @throws RuntimeException
     */
    protected function sendMessage(EasyWeChatApplication $app, $toUser, $data)
    {
        $json = array_filter(array_merge($data, [
            'touser' => $toUser,
        ]));

        $response = $app->template_message->send($json);

        if (!empty($response['errcode']) && $response['errcode'] != 0) {
            throw new RuntimeException($response['errmsg'], $response['errcode']);
        }

        return $response;
    }
}
