<?php

namespace App\Controllers;

use App\Handlers\LineHandler;
use App\Models\MessageModel;
use App\Models\UserModel;
use App\Models\AccountModel;
use App\Libraries\RabbitMQPublisher;
use App\Models\MessageRoomModel;

class WebhookController extends BaseController
{
    private RabbitMQPublisher $rabbitMQPublisher;

    private AccountModel $accountModel;
    private MessageModel $messageModel;
    private MessageRoomModel $messageRoomModel;
    private UserModel $userModel;

    private $account;

    public function __construct()
    {
        $this->rabbitMQPublisher = new RabbitMQPublisher();

        $this->userModel = new UserModel();
        $this->accountModel = new AccountModel();
        $this->messageModel = new MessageModel();
        $this->messageRoomModel = new MessageRoomModel();
    }

    /**
     * ตรวจสอบความถูกต้องของ Webhook ตามข้อกำหนดเฉพาะของแต่ละแพลตฟอร์ม
     */
    public function verifyWebhook($slug)
    {
        echo "what's up yo!";
        exit();
    }

    /**
     * จัดการข้อมูล Webhook จากแพลตฟอร์มต่าง ๆ
     */
    public function webhook($slug)
    {
        $input = $this->request->getJSON();

        log_message('info', "ข้อความเข้า Webhook  " . json_encode($input, JSON_PRETTY_PRINT));

        if ($slug == 'x') {

            $event = $input->events[0];

            $eventType = $event->message->type;

            if ($eventType == 'text' || $eventType == 'image' || $eventType == 'audio') {

                $handler = new LineHandler();
                $response = $handler->handleWebhook($input);

                $ai = 'on';

                switch ($ai) {

                    case 'on':

                        if ($response['message_type'] == 'text')
                            $handler->handleReplyByAI($response['UID'], $response['message_room']);

                        else
                            $this->rabbitMQPublisher->publishMessage($response['UID'], $response['message_room']);

                        break;

                    case 'off':
                        break;
                }
            }
        }
    }
}
