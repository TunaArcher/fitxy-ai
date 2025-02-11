<?php

namespace App\Controllers;

use App\Factories\HandlerFactory;
use App\Handlers\LineHandler;
use App\Integrations\Line\LineClient;
use App\Libraries\ChatGPT;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;
use App\Models\SubscriptionModel;
use App\Models\UserModel;
use App\Models\AccountModel;
use App\Services\MessageService;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\RabbitMQPublisher;
use App\Models\CustomerModel;

class WebhookController extends BaseController
{
    private MessageService $messageService;

    private UserModel $userModel;
    private AccountModel $accountModel;
    private SubscriptionModel $subscriptionModel;
    private CustomerModel $customerModel;

    private RabbitMQPublisher $rabbitMQPublisher;

    public function __construct()
    {
        $this->messageService = new MessageService();

        $this->customerModel = new CustomerModel();
        $this->accountModel = new AccountModel();
        $this->subscriptionModel = new SubscriptionModel();

        $this->rabbitMQPublisher = new RabbitMQPublisher();
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

        if ($slug == 'x') $this->handleWebhook($input);
    }

    public function handleWebhook()
    {
        if (getenv('CI_ENVIRONMENT') === 'development') {
            $input = $this->getMockLineWebhookData();
        }

        // ดึงข้อมูล Platform ที่ Webhook เข้ามา
        // ตรวจสอบว่าเป็น Message ข้อความ, รูปภาพ, เสียง และจัดการ
        $message = $this->processMessage($input);

        // ตรวจสอบหรือสร้างลูกค้า
        $customer = $this->getOrCreateCustomer($message['UID']);

        // ข้อความตอบกลับ
        $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);

        $repyleMessage = $message['img_url'] == ''
            ? $chatGPT->askChatGPT($messageRoom->id, $message['message'], $dataMessage)
            : $chatGPT->askChatGPT($messageRoom->id, $message['message'], $dataMessage, $message['img_url']);

        $line =  new LineClient([
            'userSocialID' => $userSocial->id,
            'accessToken' => $userSocial->line_channel_access_token,
            'channelID' => $userSocial->line_channel_id,
            'channelSecret' => $userSocial->line_channel_secret,
        ]);

        $line->pushMessage($UID, $message, $messageType);
    }

    // -----------------------------------------------------------------------------
    // Helper
    // -----------------------------------------------------------------------------

    private function processMessage($input)
    {
        $event = $input->events[0];
        $UID = $event->source->userId;
        // $message = $event->message->text;

        $eventType = $event->message->type;

        switch ($eventType) {

                // เคสข้อความ
            case 'text':
                $messageType = 'text';
                $message = $event->message->text;
                break;

                // เคสรูปภาพหรือ attachment อื่น ๆ
            case 'image':

                $messageType = 'image';

                $messageId = $event->message->id;
                $lineAccessToken = $userSocial->line_channel_access_token;

                $url = "https://api-data.line.me/v2/bot/message/{$messageId}/content";
                $headers = ["Authorization: Bearer {$lineAccessToken}"];

                // ดึงข้อมูลไฟล์จาก Webhook LINE
                $fileContent = fetchFileFromWebhook($url, $headers);

                // ตั้งชื่อไฟล์แบบสุ่ม
                $fileName = uniqid('line_') . '.jpg';

                // อัปโหลดไปยัง Spaces
                $message = uploadToSpaces(
                    $fileContent,
                    $fileName,
                    $messageType,
                    $this->platform
                );

                break;

                // เคสเสียง
            case 'audio':
                $messageType = 'audio';

                $messageId = $event->message->id;
                $lineAccessToken = $userSocial->line_channel_access_token;

                $url = "https://api-data.line.me/v2/bot/message/{$messageId}/content";
                $headers = ["Authorization: Bearer {$lineAccessToken}"];

                // ดึงข้อมูลไฟล์จาก Webhook LINE
                $fileContent = fetchFileFromWebhook($url, $headers);

                // ตั้งชื่อไฟล์แบบสุ่ม
                $fileName = uniqid('line_') . '.m4a';

                // อัปโหลดไปยัง DigitalOcean Spaces
                $message = uploadToSpaces(
                    $fileContent,
                    $fileName,
                    $messageType,
                    $this->platform
                );

                break;

            default;
        }

        return [
            'UID' => $UID,
            'type' => $messageType,
            'content' => $message,
        ];
    }

    // Logic การสร้างหรือดึงข้อมูลลูกค้า
    public function getOrCreateCustomer($UID)
    {

        $customer = $this->customerModel->getCustomerByUID($UID);

        if (!$customer) {

            $account = $this->accountModel->getAccountByID(128);

            $lineAPI = new LineClient([
                'id' => $account->id,
                'accessToken' => $account->line_channel_access_token,
                'channelID' => $account->line_channel_id,
                'channelSecret' => $account->line_channel_secret,
            ]);
            
            $profile = $lineAPI->getUserProfile($UID);

            $customerID = $this->customerModel->insertCustomer([
                'uid' => $UID,
                'name' => $profile->displayName,
                'profile' => $profile->pictureUrl
            ]);

            return $this->customerModel->getCustomerByID($customerID);
        }

        return $customer;
    }

    private function getMockLineWebhookData()
    {
        // TEXT
        return json_decode(
            '{
            "destination": "U3cc700ae815f9f7e37ea930b7b66b2c1",
            "events": [
                {
                    "type": "message",
                    "message": {
                        "type": "text",
                        "id": "545655842000077303",
                        "quoteToken": "cGR08Boi4mUH0aJ2IPb11MNt7guGiglOO3XlF2-JDmUxbTXzexfqvXiiHZ3TPfUwhlheSMslhGk-eQPiGsvziGNo4AXvbDhokDglNTnzR0gB0jIkDvCWQQbgIzVyv6D2P-k6zVQXgYl0tyyWNOFMdA",
                        "text": "\u0e23\u0e16"
                    },
                    "webhookEventId": "01JJPEBMSMCCAS02MPW7RGXZWQ",
                    "deliveryContext": {
                        "isRedelivery": false
                    },
                    "timestamp": 1738067530428,
                    "source": {
                        "type": "user",
                        "userId": "U793093e057eb0dcdecc34012361d0217"
                    },
                    "replyToken": "d618defc144e43278bf2d6715ef701e2",
                    "mode": "active"
                }
            ]
        }'
        );

        // return json_decode(
        //     '{
        //     "destination": "U3cc700ae815f9f7e37ea930b7b66b2c1",
        //     "events": [
        //         {
        //             "type": "message",
        //             "message": {
        //                 "type": "text",
        //                 "id": "545655859934921237",
        //                 "quoteToken": "kKZh_dz7HIZBv-ZjBsMUbeKbaGDCyPs9dNff0zcQkGlgmA9l-1PMsg6PLRQtteMGrufJtv2_fdLC0qRSJX_tbu5LQ3gjs4G3QDQJUWwAYiFcvIRV6fD49a_A16xhHvhKv0NTI68dNW0_YG8CWo6l0g",
        //                 "text": "\u0e04\u0e31\u0e19\u0e19\u0e35\u0e49\u0e2d\u0e30\u0e44\u0e23"
        //             },
        //             "webhookEventId": "01JJPEBZHJCEMYFMJXD2WAPNX6",
        //             "deliveryContext": {
        //                 "isRedelivery": false
        //             },
        //             "timestamp": 1738067541066,
        //             "source": {
        //                 "type": "user",
        //                 "userId": "U793093e057eb0dcdecc34012361d0217"
        //             },
        //             "replyToken": "a2edad6d122747cb96c331832e984be5",
        //             "mode": "active"
        //         }
        //     ]
        // }'
        // );

        // Image
        // return json_decode(
        //     '{
        //     "destination": "U3cc700ae815f9f7e37ea930b7b66b2c1",
        //     "events": [
        //         {
        //             "type": "message",
        //             "message": {
        //                 "type": "image",
        //                 "id": "545609780438499330",
        //                 "quoteToken": "2hTD5_GTcCNcOLqEXWrPFD7wqV1mRtIysYrI8USZF7dAoCJeN-tpaoi8b--yRZvrZecvrEZilPtSL75nC8bTPLh2xb_ZiVe_FmbKXZ7_nF8f_sLWreBKDDNB6j6WOUJBe3iABJv1GVv5FFPQIb-fPA",
        //                 "contentProvider": {
        //                     "type": "line"
        //                 }
        //             },
        //             "webhookEventId": "01JJNM5SM145NRFJ1V6KYJQMN8",
        //             "deliveryContext": {
        //                 "isRedelivery": false
        //             },
        //             "timestamp": 1738040075709,
        //             "source": {
        //                 "type": "user",
        //                 "userId": "U793093e057eb0dcdecc34012361d0217"
        //             },
        //             "replyToken": "934747a8fd95442f9b8cfcd032d7dd97",
        //             "mode": "active"
        //         }
        //     ]
        // }'
        // );

        // Audio
        //         return json_decode(
        //             '{
        //     "destination": "U3cc700ae815f9f7e37ea930b7b66b2c1",
        //     "events": [
        //         {
        //             "type": "message",
        //             "message": {
        //                 "type": "audio",
        //                 "id": "546929768709488706",
        //                 "duration": 7534,
        //                 "contentProvider": {
        //                     "type": "line"
        //                 }
        //             },
        //             "webhookEventId": "01JKD2G7T7HGHNR79HYQYR6E71",
        //             "deliveryContext": {
        //                 "isRedelivery": false
        //             },
        //             "timestamp": 1738826850049,
        //             "source": {
        //                 "type": "user",
        //                 "userId": "U793093e057eb0dcdecc34012361d0217"
        //             },
        //             "replyToken": "bd94a1406d99401e8a6934635ef6e317",
        //             "mode": "active"
        //         }
        //     ]
        // }'
        //         );
    }
}
