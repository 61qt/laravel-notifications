<?php

namespace QT\Notifications\Traits;

use RuntimeException;
use EasyDingTalk\Messages\File;
use EasyDingTalk\Messages\Link;
use EasyDingTalk\Messages\Text;
use EasyDingTalk\Messages\Image;
use EasyDingTalk\Messages\Voice;
use EasyDingTalk\Messages\Message;

trait DingTalkMessageHelper
{
    protected $messageTypes = [
        'text'  => Text::class,
        'link'  => Link::class,
        'file'  => File::class,
        'image' => Image::class,
        'voice' => Voice::class,
    ];

    /**
     * 获取消息类型
     *
     * @return string
     */
    abstract protected function getType();

    /**
     * 获取消息
     *
     * @return Message
     */
    public function getMessage($notifiable): Message
    {
        $contents = $this->getContents($notifiable);

        if (!is_array($contents)) {
            $contents = [$contents];
        }

        // 钉钉限流规则
        // 1、给同一用户发相同内容消息一天仅允许一次；
        // 2、如果是ISV接入方式，给同一用户发消息一天不得超过100次；如果是企业接入方式，此上限为500.
        // 部分相同消息需要做时间戳后缀作为区别来发送
        return $this->getTypeFrom($this->getType(), ...$contents);
    }

    /**
     * 获取消息对象
     *
     * @param string $type
     * @return Message
     */
    protected function getTypeFrom($type, ...$params): Message
    {
        if (!isset($this->messageTypes[$type])) {
            throw new RuntimeException('消息类型不存在');
        }

        return new $this->messageTypes[$type](...$params);
    }

    /**
     * 获取消息内容
     *
     * @return string|array
     */
    protected function getContents($notifiable)
    {
        return [];
    }
}
