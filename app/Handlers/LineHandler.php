<?php

namespace App\Handlers;

use App\Integrations\Line\LineClient;
use App\Libraries\ChatGPT;
use App\Models\AccountModel;
use App\Models\UserModel;
use App\Models\MenuModel;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;

class LineHandler
{
    private AccountModel $accountModel;
    private UserModel $userModel;
    private MenuModel $menuModel;
    private MessageModel $messageModel;
    private MessageRoomModel $messageRoomModel;

    private $account;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
        $this->userModel = new UserModel();
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
        $user = $this->userModel->getUserByUID($message['UID']);

        if ($user) {
            // ตรวจสอบหรือสร้างห้องสนทนา
            $messageRoom = $this->getOrCreateMessageRoom($user);

            // บันทึกข้อความ
            $this->messageModel->insertMessage([
                'room_id' => $messageRoom->id,
                'send_by' => 'User',
                'sender_id' => $user->id,
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

            $event = $input->events[0];
            $UID = $event->source->userId;

            $messages = [
                "ก่อนจะคุยกับผมช่วย Unity X FitAI  มาสมัครสมาชิกก่อนนะ! แล้วผมจะมีแรง สมัครคลิกเลยที่นี่ 👉 http://line.autoconx.app/",
                "อยากให้ผม ตอบแบบรู้ใจ? สมัครสมาชิกก่อนนะ 😄 สมัครง่ายมากที่นี่ 👉 http://line.autoconx.app/",
                "Unity X Unity X FitAI  พร้อมจะช่วยคุณ แต่ก่อนอื่น... สมัครสมาชิกก่อนเถอะ! 😆 กดเลย 👉 http://line.autoconx.app/",
                "รู้มั้ย? สมัครสมาชิกแล้ว Unity X FitAI  จะฉลาดขึ้น 10% (จากไหนก็ไม่รู้ 🤣) สมัครเลย! 👉 http://line.autoconx.app/",
                "เฮ้! อยากได้คำตอบดีๆ จาก Unity X FitAI  ต้องสมัครก่อนนะ สมัครง่ายๆ ที่นี่เลย 👉 http://line.autoconx.app/",
                "FitAI ไม่ใช่แค่ AI แต่เป็นเพื่อนของคุณ! สมัครสมาชิกก่อนเพื่อรู้จักกันให้ดีขึ้น 😊 👉 http://line.autoconx.app/",
                "สมัครสมาชิกตอนนี้ รับสิทธิพิเศษเพียบ! (แต่จริงๆ คือสมัครก่อนคุยได้ 🤣) คลิกเลย 👉 http://line.autoconx.app/",
                "สมัครแล้วคุยกับ Unity X FitAI  ได้เลย! ไม่สมัคร... ก็รอ Unity X FitAI  มาเกาหัวแป๊บนะ 🤔😆 👉 http://line.autoconx.app/",
                "ไม่ต้องร่ายมนต์! แค่สมัครสมาชิกก็เข้าถึง Unity X FitAI  ได้แล้ว 🎩✨ คลิกที่นี่เลย 👉 http://line.autoconx.app/",
                "อยากให้ Unity X FitAI  ทักทายด้วยรอยยิ้ม? 😊 สมัครสมาชิกก่อนเลย! 👉 http://line.autoconx.app/",
                "FitAI พร้อมคุย แต่คุณพร้อมรึยัง? ถ้าพร้อม กดสมัครเลย! 👉 http://line.autoconx.app/",
                "สมัครสมาชิก = ได้คุยกับ Unity X FitAI  สมัครง่ายมาก ไม่ต้องพิมพ์รหัสผ่าน 18 หลัก! 😆 👉 http://line.autoconx.app/",
                "ก่อนจะให้ Unity X FitAI  ช่วย มาช่วยตัวเองด้วยการสมัครสมาชิกก่อนนะ! คลิกเลย 👉 http://line.autoconx.app/",
                "สมัครก่อน คุยก่อน ได้เปรียบกว่า! Unity X FitAI  รออยู่ สมัครเลย 👉 http://line.autoconx.app/",
                "AI อัจฉริยะก็ต้องมีการเตรียมตัว คนฉลาดอย่างคุณก็ต้องสมัครก่อน! 😆 👉 http://line.autoconx.app/",
                "สมัครก่อนจะคุยกับ Unity X FitAI  นะ ไม่งั้น AI จะงอนเอา! 🤖💢 คลิกเลย 👉 http://line.autoconx.app/",
                "FitAI พร้อมเป็นเพื่อนคุณ แต่ก่อนอื่น... มาเป็นสมาชิกกันก่อนเถอะ! 😊 สมัครเลย 👉 http://line.autoconx.app/",
                "ไม่ต้องรอคิว! สมัครปุ๊บ คุยกับ Unity X FitAI  ได้ปั๊บ คลิกเลย 👉 http://line.autoconx.app/",
                "แค่สมัครก็ได้เปิดประตูสู่โลกของ AI! 🚀 มาสมัครสมาชิกกันเถอะ 👉 http://line.autoconx.app/",
                "สมัครก่อน ได้ใช้ก่อน แถมได้รู้จัก AI ก่อนใคร! 😏 คลิกเลย 👉 http://line.autoconx.app/",
            ];

            $line = new LineClient([
                'id' => $this->account->id,
                'accessToken' =>  $this->account->line_channel_access_token,
                'channelID' =>  $this->account->line_channel_id,
                'channelSecret' =>  $this->account->line_channel_secret,
            ]);

            $repyleMessage = $messages[array_rand($messages)];

            $line->pushMessage($UID, $repyleMessage, 'text');
        }
    }

    public function handleReplyByAI($UID, $messageRoom)
    {
        $this->account = $this->accountModel->getAccountByID('128');

        $messages = $this->messageModel->getMessageNotReplyBySendByAndRoomID('User', $messageRoom->id);
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
                'user_id' => $messageRoom->user_id,
                'content' => $this->cleanUrl($message['img_url']),
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

    private function cleanUrl($text)
    {
        $urls = explode(',', $text); // แยกเป็นอาร์เรย์โดยใช้ ,
        return trim($urls[0]); // คืนค่าเฉพาะตัวแรกและตัดช่องว่างออก
    }

    private function filterMessage($inputText)
    {
        // ลบ json และ ออกจากข้อความ 
        $cleanText = preg_replace('/```json|```/', '', $inputText);
        $cleanText = trim($cleanText); // ลบช่องว่างที่ไม่จำเป็น

        // ใช้ regex แยก JSON ที่มี single quote หรือ double quote ออกมา
        preg_match('/\{.*\}/s', $cleanText, $jsonMatch);

        $json = null;
        if (!empty($jsonMatch)) {
            // แปลง ' (single quote) เป็น " (double quote) เพื่อให้ json_decode() ใช้ได้
            $jsonString = str_replace("'", '"', $jsonMatch[0]);

            // ถอดรหัส JSON
            $json = json_decode($jsonString, true);
        }

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

    public function getOrCreateMessageRoom($user)
    {
        $messageRoom = $this->messageRoomModel->getMessageRoomByUserID($user->id);

        if (!$messageRoom) {

            $roomId = $this->messageRoomModel->insertMessageRoom([
                'account_id' => '128',
                'account_name' => 'UNITYxTDEE',
                'user_id' => $user->id,
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
