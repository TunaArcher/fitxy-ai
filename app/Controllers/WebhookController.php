<?php

namespace App\Controllers;

use App\Handlers\LineHandler;
use App\Libraries\RabbitMQPublisher;

class WebhookController extends BaseController
{
    private RabbitMQPublisher $rabbitMQPublisher;

    public function __construct()
    {
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
    // public function webhook($slug)
    // {
    //     $input = $this->request->getJSON();

    //     if (getenv('CI_ENVIRONMENT') === 'development') $input = $this->getMockLineWebhookData();

    //     log_message('info', "ข้อความเข้า Webhook  " . json_encode($input, JSON_PRETTY_PRINT));

    //     if ($slug == 'x') {

    //         $event = $input->events[0];

    //         $eventType = $event->message->type;

    //         if ($eventType == 'text' || $eventType == 'image' || $eventType == 'audio') {

    //             $handler = new LineHandler();
    //             $response = $handler->handleWebhook($input);

    //             $ai = 'on';

    //             switch ($ai) {

    //                 case 'on':

    //                     if ($response['message_type'] == 'text')
    //                         $handler->handleReplyByAI($response['UID'], $response['message_room']);

    //                     else
    //                         $this->rabbitMQPublisher->publishMessage($response['UID'], $response['message_room']);

    //                     break;

    //                 case 'off':
    //                     break;
    //             }
    //         }
    //     }
    // }

    public function webhook($slug)
    {
        $input = $this->request->getJSON();

        if (getenv('CI_ENVIRONMENT') === 'development') $input = $this->getMockLineWebhookData();

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

    private function getMockLineWebhookData()
    {
        // TEXT
        //                 return json_decode(
        //                     '{
        //     "destination": "Uad63a2f680bd53d9d8626333f648e652",
        //     "events": [
        //         {
        //             "type": "message",
        //             "message": {
        //                 "type": "text",
        //                 "id": "548654014133436481",
        //                 "quoteToken": "2ivuZUjyByI0pHBLXGB--KVV70WIsCEon_PCW3AESQ-iKiR4Etot4y5FJClpkmwxhALZSV59a05SxWN4PPlk3GWo_zHI4gT8EOs8qBz_Lbyr0ddWC4W9ePUM3iENQq01oNqeo3KvoglYdLFmOxliTQ",
        //                 "text": "Test text"
        //             },
        //             "webhookEventId": "01JMBPM2J0PRFHZA8E9CBJXNPE",
        //             "deliveryContext": {
        //                 "isRedelivery": false
        //             },
        //             "timestamp": 1739854580178,
        //             "source": {
        //                 "type": "user",
        //                 "userId": "Ucac64382c185fd8acd69438c5af15935"
        //             },
        //             "replyToken": "bbc502d6e7dd44e5964b02c9220476f3",
        //             "mode": "active"
        //         }
        //     ]
        // }'
        //                 );

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
    "destination": "Uad63a2f680bd53d9d8626333f648e652",
    "events": [
        {
            "type": "message",
            "message": {
                "type": "image",
                "id": "548654032437641381",
                "quoteToken": "A7vq8x3emJCw60wxbQdrvYbnlrB5Vw3NrUt4IgXGjs_gMuuovGsu9xbfGhRbepUPvBDAtejEfVvy1WkCgRKkntW99gIyati6hBmosjc-8BuE9pqGr7qcJ7BgaoPBB1VOAsdBXdNfl1h7m-S-SMmIyg",
                "contentProvider": {
                    "type": "line"
                }
            },
            "webhookEventId": "01JMBPMDEQQAFB7MEW58G5A6VV",
            "deliveryContext": {
                "isRedelivery": false
            },
            "timestamp": 1739854591386,
            "source": {
                "type": "user",
                "userId": "Ucac64382c185fd8acd69438c5af15935"
            },
            "replyToken": "245e808a4e57431199fcdf978df7912a",
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
