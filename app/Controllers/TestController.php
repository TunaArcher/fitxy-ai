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

    public function filterMessage()
    {

        $inputText = "
        
        ข้าวมันไก่
- ส่วนผสมหลัก: ข้าวมัน, ไก่ต้ม, แตงกวา, ซอสขิง
- พลังงาน: 600 แคลอรี่

ซุปไก่
- ส่วนผสมหลัก: น้ำซุปไก่, เครื่องเทศ
- พลังงาน: 80 แคลอรี่

สรุป พลังงานรวมของมื้ออาหาร: 680 แคลอรี่

ผมลงในระบบให้แล้วนะครับ !! อร่อยแล้วอย่าลืมย่อยเดินสักรอบสองรอบนะครับ! 😄

```json
{\"totalcal\": 680}
```
        ";

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

        px([
            'repyleMessage' => $message,
            'json' => $json
        ]);

        return [
            'repyleMessage' => $message,
            'json' => $json
        ];
    }

    public function test()
    {
        // ลบ json และ ออกจากข้อความ 
        $cleanText = preg_replace('/json|/', '', $inputText); 
        $cleanText = trim($cleanText); // ลบช่องว่างที่ไม่จำเป็น

        // ใช้ regex แยก JSON ออกมา 
        preg_match('/{.*}/s', $cleanText, $jsonMatch); 
        $json = !empty($jsonMatch) ? json_decode($jsonMatch[0], true) : null;

        // ตรวจสอบว่ามีคำว่า "พลังงานรวมของมื้ออาหาร" และ JSON ที่มี key "totalcal" หรือไม่ 
        if (strpos($cleanText, 'พลังงานรวมของมื้ออาหาร') !== false && is_array($json) && isset($json['totalcal'])) { 
            // แยกเฉพาะส่วนของข้อความที่ไม่รวม JSON 
            $message = trim(str_replace($jsonMatch[0], '', $cleanText)); 
        } else { 
            // เก็บทุกอย่างลงใน $message และให้ $json ว่างเปล่า 
            $message = $cleanText; 
            $json = []; 
            }

        // แสดงผลลัพธ์ 
        echo "Message: \n$message\n\n"; echo "JSON: \n" . json_encode($json, JSON_PRETTY_PRINT) . "\n";
    }
}
