<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;
use App\Models\UserModel;

class TestController extends BaseController
{
    private MessageModel $messageModel;
    private MessageRoomModel $messageRoomModel;

    public function __construct()
    {
        $this->messageModel = new MessageModel();
        $this->messageRoomModel = new MessageRoomModel();

    }

    public function index()
    {
        $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);
        $question = 'เมื่อกี้ฉันถามว่าอะไรนะ';
        $messageSetting = 'บทสนทนานี้มุ่งเน้นไปที่การออกแบบและงานก่อสร้างในยุคปัจจุบันที่ขับเคลื่อนด้วยเทคโนโลยี คุณต้องการให้ฉันให้ข้อมูล คำแนะนำ หรือหารือเกี่ยวกับการใช้เทคโนโลยีใดในงานออกแบบและก่อสร้างปี 2024? เช่น การใช้ซอฟต์แวร์ CAD ล่าสุด การสร้างแบบจำลอง 3 มิติ ด้วย BIM หรือการใช้อุปกรณ์ IoT ในการจัดการไซต์ก่อสร้าง?';
        $messageRoom = $this->messageRoomModel->getMessageRoomByID('121');
        $messages = $this->messageModel->getHistoryMessageByRoomID($messageRoom->id, 4);
        
        $fileNames = 'https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line/line_67a5f8154d0fc.jpg,https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line/line_67a5f83269382.jpg,';

        $test = array_map(function ($fileName) {
            return [
                'type' => 'image_url',
                'image_url' => ['url' => trim($fileName)]
            ];
        }, explode(',', $fileNames));

        px($test);

        echo $chatGPT->askChatGPT($messageRoom->id, $question, $messageSetting);
    }

    private function filterMessage($inputText)
    {

        $inputText = '';
        
        // ใช้ regex แยก JSON ออกมา
        preg_match('/\{.*\}/s', $inputText, $jsonMatch);
        $json = !empty($jsonMatch) ? json_decode($jsonMatch[0], true) : null;

        // ตรวจสอบว่ามีข้อความ "พลังงานรวมของมื้ออาหาร" และ JSON ที่มี key "totalcal" หรือไม่
        if (strpos($inputText, 'พลังงานรวมของมื้ออาหาร') !== false && is_array($json) && isset($json['totalcal'])) {
            // แยกเฉพาะส่วนของข้อความที่ไม่รวม JSON
            $message = trim(str_replace($jsonMatch[0], '', $inputText));
        } else {
            // เก็บทุกอย่างลงใน $message และให้ $json ว่างเปล่า
            $message = $inputText;
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
}
