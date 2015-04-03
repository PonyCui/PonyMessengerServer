<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Chat_entity
{

}


/**
 *
 */
class Chat_record_entity
{

    public $record_id = null;

    public $from_user_id = 0;

    public $to_user_id = 0;

    public $record_time = 0;

    /**
     *  系统消息       PCUMessageTypeSystem = 0,
     *  文本消息       PCUMessageTypeTextMessage = 1,
     *  语音消息       PCUMessageTypeVoiceMessage = 2,
     *  图片消息       PCUMessageTypeImageMessage = 3,
     *  链接          PCUMessageTypeLinkMessage = 4,
     *  公众号文章推送  PCUMessageTypeArticleMessage = 5
     **/
    public $record_type = 0;

    public $record_title = '';

    public $record_params = '';

    public $record_hash = '';
}
