<?php

namespace App\Handlers;

use App\Integrations\Line\LineClient;
use App\Libraries\ChatGPT;
use App\Models\AccountModel;
use App\Models\CustomerModel;
use App\Models\MenuModel;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;
use App\Models\UserModel;

class LineHandler
{
    private $platform = 'Line';

    private AccountModel $accountModel;
    private CustomerModel $customerModel;
    private MenuModel $menuModel;
    private MessageModel $messageModel;
    private MessageRoomModel $messageRoomModel;
    private UserModel $userModel;

    private $account;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
        $this->customerModel = new CustomerModel();
        $this->menuModel = new MenuModel();
        $this->messageModel = new MessageModel();
        $this->messageRoomModel = new MessageRoomModel();
    }

    public function handleWebhook($input)
    {
        $this->account = $this->accountModel->getAccountByID('128');

        if (getenv('CI_ENVIRONMENT') === 'development') $input = $this->getMockLineWebhookData();

        // ดึงข้อมูล Platform ที่ Webhook เข้ามา
        // ตรวจสอบว่าเป็น Message ข้อความ, รูปภาพ, เสียง และจัดการ
        $message = $this->processMessage($input);

        // ตรวจสอบหรือสร้างลูกค้า
        $customer = $this->customerModel->getCustomerByUID($message['UID']);

        if ($customer) {
            // ตรวจสอบหรือสร้างห้องสนทนา
            $messageRoom = $this->getOrCreateMessageRoom($customer);

            // บันทึกข้อความ
            $this->messageModel->insertMessage([
                'room_id' => $messageRoom->id,
                'send_by' => 'Customer',
                'sender_id' => $customer->id,
                'message_type' => $message['type'],
                'message' => $message['content'],
                'is_context' => '1'
            ]);

            return [
                'UID' => $message['UID'],
                'message_room' => $messageRoom,
                'message_type' => $message['type']
            ];
        } else {
            // TODO:: จัดการเมื่อไม่มียูสเซอร์ให้ทำการสมัครสมาชิก
        }
    }

    public function handleReplyByAI($UID, $messageRoom)
    {
        $this->account = $this->accountModel->getAccountByID('128');

        $messages = $this->messageModel->getMessageNotReplyBySendByAndRoomID('Customer', $messageRoom->id);
        $message = $this->getUserContext($messages);

        // ข้อความตอบกลับ
        $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);
        $repyleMessage = $message['img_url'] == ''
            ? $chatGPT->askChatGPT($messageRoom->id, $message['message'])
            : $chatGPT->askChatGPT($messageRoom->id, $message['message'], $message['img_url']);

        $repyleMessage = $this->filterMessage($repyleMessage);

        $line = new LineClient([
            'id' => $this->account->id,
            'accessToken' =>  $this->account->line_channel_access_token,
            'channelID' =>  $this->account->line_channel_id,
            'channelSecret' =>  $this->account->line_channel_secret,
        ]);

        $this->messageModel->insertMessage([
            'send_by' => 'ADMIN',
            // 'sender_id' => $senderId,
            'message_type' => 'text',
            'message' => $repyleMessage['repyleMessage'],
            // 'is_context' => '1',
            'reply_by' => 'AI'
        ]);

        if ($repyleMessage['json']) {
            $this->menuModel->insertMenu([
                'customer_id' => $messageRoom->customer_id,
                'content' => $message['img_url'],
                'note' => $repyleMessage['repyleMessage'],
                'cal' => $repyleMessage['json'],
            ]);
        }

        $line->pushMessage($UID, $repyleMessage['repyleMessage'], 'text');

        $this->messageModel->clearUserContext($messageRoom->id);
    }

    // -----------------------------------------------------------------------------
    // Helper
    // -----------------------------------------------------------------------------

    private function filterMessage($inputText)
    {
        // ลบ json และ ออกจากข้อความ 
        $cleanText = preg_replace('/```json|```/', '', $inputText);
        $cleanText = trim($cleanText); // ลบช่องว่างที่ไม่จำเป็น

        // ใช้ regex แยก JSON ออกมา
        preg_match('/\{.*\}/s', $cleanText, $jsonMatch);
        $json = !empty($jsonMatch) ? json_decode($jsonMatch[0], true) : null;

        // ตรวจสอบว่ามีข้อความ "พลังงานรวมของมื้ออาหาร" และ JSON ที่มี key "totalcal" หรือไม่
        if (strpos($cleanText, 'พลังงานรวมของมื้ออาหาร') !== false && is_array($json) && isset($json['totalcal'])) {
            // แยกเฉพาะส่วนของข้อความที่ไม่รวม JSON
            $message = trim(str_replace($jsonMatch[0], '', $cleanText));
        } else {
            // เก็บทุกอย่างลงใน $message และให้ $json ว่างเปล่า
            $message = $cleanText;
            $json = [];
        }

        // // แสดงผลลัพธ์
        // echo "Message: \n$message\n\n";
        // echo "JSON: \n" . json_encode($json, JSON_PRETTY_PRINT) . "\n";

        return [
            'repyleMessage' => $message,
            'json' => $json
        ];
    }

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
                $lineAccessToken = $this->account->line_channel_access_token;

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
                    $messageType
                );

                break;

                // เคสเสียง
            case 'audio':
                $messageType = 'audio';

                $messageId = $event->message->id;
                $lineAccessToken = $this->account->line_channel_access_token;

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

    // public function getOrCreateCustomer($UID)
    // {

    //     $customer = $this->customerModel->getCustomerByUID($UID);

    //     if (!$customer) {

    //         $this->account = $this->accountModel->getAccountByID('128');

    //         $lineAPI = new LineClient([
    //             'id' => $this->account->id,
    //             'accessToken' =>  $this->account->line_channel_access_token,
    //             'channelID' =>  $this->account->line_channel_id,
    //             'channelSecret' => $this->account->line_channel_secret,
    //         ]);

    //         $profile = $lineAPI->getUserProfile($UID);

    //         $customerID = $this->customerModel->insertCustomer([
    //             'uid' => $UID,
    //             'name' => $profile->displayName,
    //             'profile' => $profile->pictureUrl
    //         ]);

    //         return $this->customerModel->getCustomerByID($customerID);
    //     }

    //     return $customer;
    // }

    public function getOrCreateMessageRoom($customer)
    {
        $messageRoom = $this->messageRoomModel->getMessageRoomByCustomerID($customer->id);

        if (!$messageRoom) {

            $roomId = $this->messageRoomModel->insertMessageRoom([
                'account_id' => '128',
                'account_name' => 'UNITYxTDEE',
                'customer_id' => $customer->id,
            ]);

            return $this->messageRoomModel->getMessageRoomByID($roomId);
        }

        return $messageRoom;
    }

    private function getUserContext($messages)
    {
        helper('function');

        $contextText = '';
        $imageUrl = '';

        foreach ($messages as $message) {
            switch ($message->message_type) {
                case 'text':
                    $contextText .= $message->message . ' ';
                    break;
                case 'image':
                    $imageUrl .= $message->message . ',';
                    break;
                case 'audio':
                    $contextText .= convertAudioToText($message->message) . ' ';
                    break;
            }
        }

        return  [
            'message' => $contextText,
            'img_url' => $imageUrl,
        ];
    }

    private function getMockLineWebhookData()
    {
        // TEXT
        //         return json_decode(
        //             '{
        //     "destination": "U4289200c7269074fb51b326a7fa30cdf",
        //     "events": [
        //         {
        //             "type": "message",
        //             "message": {
        //                 "type": "text",
        //                 "id": "547666636904595686",
        //                 "quoteToken": "Uj6pC-mig81A7SiH2_WOH_aZ7XouukaC7gBYPSOfmqPAlLsWRADY1qhQZ5GfBXpTOScqr5kfRVSkli37u4FRV27zUXLaaYQ1EKDnvLzdCkvsmSnqDxIdpcQLXQ0ZZiXIGaOFJicaam65Y2ZW9swVlg",
        //                 "text": "อยากอ้วง"
        //             },
        //             "webhookEventId": "01JKT5BSV10VCPYF2AZ6QPPGGJ",
        //             "deliveryContext": {
        //                 "isRedelivery": false
        //             },
        //             "timestamp": 1739266057672,
        //             "source": {
        //                 "type": "user",
        //                 "userId": "U8bf2cbdb6cbbdb8709dc268512abd4a3"
        //             },
        //             "replyToken": "846857eb08e642ae8c019f579fc3e3c2",
        //             "mode": "active"
        //         }
        //     ]
        // }'
        //         );

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
        return json_decode(
            '{
    "destination": "U4289200c7269074fb51b326a7fa30cdf",
    "events": [
        {
            "type": "message",
            "message": {
                "type": "image",
                "id": "548033449140420817",
                "quoteToken": "qm_i9ObtFhv6cZUbqVdZy0611-_bO0_SITfWRfufl1mXlNgs-r1pQrJn5WYjV4mqJtfkgUYh9_-5mB1vXHZQhk40O-J2DhFU6ngUzTAlbi5f45njO2ddl4O2hQMf3oO07Qa1RCVu7ntJvsppKyYTpA",
                "contentProvider": {
                    "type": "line"
                }
            },
            "webhookEventId": "01JM0NW2JEG5863TNFRTYTD968",
            "deliveryContext": {
                "isRedelivery": false
            },
            "timestamp": 1739484694789,
            "source": {
                "type": "user",
                "userId": "U8bf2cbdb6cbbdb8709dc268512abd4a3"
            },
            "replyToken": "204aadd278084e909c08b4f24ada17ad",
            "mode": "active"
        }
    ]
}'
        );

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
