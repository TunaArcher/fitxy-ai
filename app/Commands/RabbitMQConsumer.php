<?php

namespace App\Commands;

use App\Handlers\LineHandler;
use CodeIgniter\CLI\BaseCommand;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;
use Config\RabbitMQ;

class RabbitMQConsumer extends BaseCommand
{
    protected $group       = 'RabbitMQ';
    protected $name        = 'rabbitmq:consume';
    protected $description = 'Consume messages from RabbitMQ and process AI response';

    public function run(array $params)
    {
        $connection = RabbitMQ::getConnection();
        $channel = $connection->channel();

        // ประกาศ Queue
        $channel->queue_declare('line_ai_response_queue', false, true, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        // ฟังก์ชัน Callback เมื่อได้รับข้อความจาก Queue
        $callback = function (AMQPMessage $msg) {
            echo " [x] Processing message: ", $msg->body, "\n";

            // แปลง JSON เป็น Array
            $data = json_decode($msg->body, true);
            $this->processAIResponse($data['UID'], $data['message_room']);

            echo " [✓] AI Response sent successfully!\n";
        };

        // Consumer รอรับข้อความ
        $channel->basic_consume('line_ai_response_queue', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            try {
                $channel->wait();
            } catch (\Throwable $e) {
                log_message('error', "RabbitMQ Error: " . $e->getMessage());
            }
        }

        $channel->close();
        $connection->close();
    }

    // private function processAIResponse($UID, $messageRoom)
    // {
    //     helper('my_hashids');

    //     $messageRoom = json_decode(json_encode($messageRoom));
    //     $messageRoomID = $messageRoom->id;

    //     $messageModel = new MessageModel();

    //     // ดึงข้อความล่าสุดของห้องแชท
    //     $lastContextTimestamp = $messageModel->lastContextTimestamp($messageRoomID);

    //     if (!$lastContextTimestamp) return;

    //     $timeoutSeconds = 5;
    //     sleep($timeoutSeconds);

    //     // ตรวจสอบว่ามีข้อความใหม่หรือไม่
    //     $newContextCount = $messageModel->newContextCount($messageRoomID, $lastContextTimestamp->_time);

    //     log_message('info', "Debug lastContextTimestamp {$lastContextTimestamp->_time} newContextCount: " . json_encode($newContextCount, JSON_PRETTY_PRINT));

    //     if ($newContextCount->_count > 0) {
    //         log_message('info', "timeout: ");
    //         // มีข้อความใหม่เข้ามาในช่วง Timeout
    //         return; // ถ้ามีข้อความใหม่ ให้รอไปก่อน
    //     }

    //     // AI ตอบ และ ลบบริบทหลังจากใช้งาน
    //     $handler = new LineHandler();
    //     $handler->handleReplyByAI($UID, $messageRoom);
    // }


    private function processAIResponse($UID, $messageRoom)
    {
        helper('my_hashids');

        $messageRoom = json_decode(json_encode($messageRoom));
        $messageRoomID = $messageRoom->id;

        $messageModel = new MessageModel();
        $db = \Config\Database::connect();

        try {
            // ตรวจสอบ Connection ก่อนใช้
            if (!$db->simpleQuery('SELECT 1')) {
                log_message('error', 'Database connection lost. Reconnecting...');
                $db->close();
                $db = \Config\Database::connect(true);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Database check error: ' . $e->getMessage());
            $db->close();
            $db = \Config\Database::connect(true);
        }

        $lastContextTimestamp = $messageModel->lastContextTimestamp($messageRoomID);
        if (!$lastContextTimestamp) return;

        $timeoutStart = time();
        $timeoutSeconds = 5;

        do {
            usleep(500000); // 0.5 วินาที เพื่อลดภาระ CPU

            try {
                if (!$db->simpleQuery('SELECT 1')) {
                    log_message('error', 'Database connection lost during waiting. Reconnecting...');
                    $db->close();
                    $db = \Config\Database::connect(true);
                }
            } catch (\Throwable $e) {
                log_message('error', 'Database check error: ' . $e->getMessage());
                $db->close();
                $db = \Config\Database::connect(true);
            }

            $newContextCount = $messageModel->newContextCount($messageRoomID, $lastContextTimestamp->_time);
            log_message('info', "Debug lastContextTimestamp {$lastContextTimestamp->_time} newContextCount: " . json_encode($newContextCount, JSON_PRETTY_PRINT));

            if ($newContextCount->_count > 0) {
                log_message('info', "Message arrived during timeout, skipping AI response.");
                return;
            }
        } while (time() - $timeoutStart < $timeoutSeconds);

        $handler = new LineHandler();
        $handler->handleReplyByAI($UID, $messageRoom);

        // ปิดการเชื่อมต่อ Database เมื่อใช้เสร็จ
        $db->close();
    }
}
