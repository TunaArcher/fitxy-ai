<?php

namespace App\Libraries;

use Config\RabbitMQ;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher
{
    public function publishMessage($UID, $messageRoom)
    {
        $connection = RabbitMQ::getConnection();
        $channel = $connection->channel();

        // ประกาศ Queue
        $channel->queue_declare('line_ai_response_queue', false, true, false, false);

        // ✅ ป้องกันปัญหา Null Bytes และ JSON Encode
        $data = json_encode([
            'UID' => $UID,
            'message_room' => (array) $messageRoom
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // ✅ ตรวจสอบ JSON Encode ถูกต้อง
        if (!$data) {
            echo " [!] Error: JSON encoding failed.\n";
            return;
        }

        // สร้าง Message
        $msg = new AMQPMessage($data, ['delivery_mode' => 2]);

        // ส่งไปที่ Queue
        $channel->basic_publish($msg, '', 'line_ai_response_queue');

        echo " [x] Sent to queue: $data\n";

        // ปิด Connection
        $channel->close();
        $connection->close();
    }
}
