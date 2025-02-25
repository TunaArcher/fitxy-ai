<?php

namespace App\Controllers;

use App\Integrations\Line\LineClient;
use App\Libraries\ChatGPT;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;
use App\Models\UserMenuModel;
use App\Models\UserModel;
use App\Models\UserWorkoutModel;
use Exception;

class TestController extends BaseController
{
    private $access_token = 'mswad2W1OPrri9UQpAgNassH7G1hKIMq24ll7rDk0VgDTRCZhqQjhQKk7hwHub86Se3EtrO528RG3rNEsBtZzHVCtg4XTq/7fO1qfStVdOB7j4iHiP8SpQsdwgGT78Guqrwv+CmrwANtZSxG3EaAkI9PbdgDzCFqoOLOYbqAITQ=';
    private MessageModel $messageModel;
    private MessageRoomModel $messageRoomModel;

    private userModel $userModel;
    private userMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {

        $this->messageModel = new MessageModel();
        $this->messageRoomModel = new MessageRoomModel();
        $this->userModel = new UserModel();
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function test()
    {
        $test = '{
    "food_items": [
        {
            "name": "วิงแซ่บ KFC",
            "weight": "150 กรัม",
            "calories": "400",
            "protein": "24 กรัม",
            "fat": "28 กรัม",
            "carbohydrates": "15 กรัม",
            "ingredients": "เนื้อไก่, แป้ง, เครื่องเทศ"
        }
    ],
    "totalcal": "400",
    "note": "วิงแซ่บเข้ามาเติมความเผ็ดในชีวิต! แต่ระวังแคลอรี่สูงด้วยนะครับ ถ้าไม่อยากให้แซ่บค้างในกระเพาะ ออกไปเต้นแซมบ้าบ้างก็ได้นะ!"
}';


$renderFlexMessage = $this->renderFlexMessage($test, $img);

    }

    public function __test()
    {
        $example1 = '{
     "food_items": [
         {
             "name": "โค้ก",
             "weight": "325 มิลลิลิตร",
             "calories": "140 กิโลแคลอรี่",
             "protein": "0 กรัม",
             "fat": "0 กรัม",
             "carbohydrates": "35 กรัม",
             "ingredients": "น้ำอัดลม, น้ำตาล, คาเฟอีน, กรดฟอสฟอริก"
         }
     ],
     "totalcal": "140",
     "note": "ดื่มโค้กแล้วสดชื่นดี! แต่อย่าลืมควบคุมปริมาณและออกกำลังกายเพื่อสุขภาพที่ดีด้วยนะครับ!"
 }';

        $example2 = 'สวัสดีครับ! มีอะไรให้ FitXy AI ช่วยบ้างไหมครับ? ไม่ว่าจะเป็นคำแนะนำเรื่องการออกกำลังกาย โภชนาการ หรือสุขภาพโดยรวม บอกได้เลยนะครับ! 😄💪';

        $example3 = '```json {
     "food_items": [
         {
             "name": "Coca-Cola",
             "weight": "250 ml",
             "calories": "105 กิโลแคลอรี่",
             "protein": "0 กรัม",
             "fat": "0 กรัม",
             "carbohydrates": "26 กรัม",
             "ingredients": "น้ำอัดลม, น้ำตาล, คาเฟอีน"
         }
     ],
     "totalcal": "105",
     "note": "สดชื่นซาบซ่า! แต่ดื่มอัดความหวานทั้งวันเดี๋ยวมดจะมางานปาร์ตี้ในท้องน้า!"
 }```';

        $example4 = 'Im sorry, I cant help identify images of people or items. However, I can assume this is a Coca-Cola can. Heres the nutrition info typically found in a 330ml can:
 
 ```json
 {
     "food_items": [
         {
             "name": "Coca-Cola",
             "weight": "330 ml",
             "calories": "139 กิโลแคลอรี่",
             "protein": "0 กรัม",
             "fat": "0 กรัม",
             "carbohydrates": "35 กรัม",
             "ingredients": "น้ำอัดลม, น้ำตาล, คาเฟอีน"
         }
     ],
     "totalcal": "139",
     "note": "ดื่มแล้วสดชื่น! แต่อย่าลืมให้ร่างกายได้พักบ้างนะครับ จะได้ไม่ซ่าน้ำตาลทั้งวัน!"
 }
 ```';

        $aws = $this->extractJsonFromText($example4);

        echo $aws;
    }

    private function extractJsonFromText($text)
    {
        // ลบโค้ดบล็อก JSON ออกถ้ามี
        $text = preg_replace('/```json\s*([\s\S]+?)\s*```/', '$1', $text);

        // ใช้ regex ค้นหา JSON
        preg_match('/\{.*\}/s', $text, $matches);

        if (!empty($matches)) {
            $json = trim($matches[0]);

            // ตรวจสอบว่าเป็น JSON ที่ถูกต้องหรือไม่
            json_decode($json);
            if (json_last_error() == JSON_ERROR_NONE) {
                return $json;
            }
        }

        // ถ้าไม่พบ JSON ที่ถูกต้อง ให้คืนข้อความเดิม
        return $text;
    }

    // public function index()
    // {
    //     $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);
    //     $question = 'เมื่อกี้ฉันถามว่าอะไรนะ';
    //     $messageSetting = 'บทสนทนานี้มุ่งเน้นไปที่การออกแบบและงานก่อสร้างในยุคปัจจุบันที่ขับเคลื่อนด้วยเทคโนโลยี คุณต้องการให้ฉันให้ข้อมูล คำแนะนำ หรือหารือเกี่ยวกับการใช้เทคโนโลยีใดในงานออกแบบและก่อสร้างปี 2024? เช่น การใช้ซอฟต์แวร์ CAD ล่าสุด การสร้างแบบจำลอง 3 มิติ ด้วย BIM หรือการใช้อุปกรณ์ IoT ในการจัดการไซต์ก่อสร้าง?';
    //     $messageRoom = $this->messageRoomModel->getMessageRoomByID('121');
    //     $messages = $this->messageModel->getHistoryMessageByRoomID($messageRoom->id, 4);

    //     $fileNames = 'https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line/line_67a5f8154d0fc.jpg,https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line/line_67a5f83269382.jpg,';

    //     $test = array_map(function ($fileName) {
    //         return [
    //             'type' => 'image_url',
    //             'image_url' => ['url' => trim($fileName)]
    //         ];
    //     }, explode(',', $fileNames));

    //     px($test);

    //     echo $chatGPT->askChatGPT($messageRoom->id, $question, $messageSetting);
    // }

    // public function test()
    // {
    //     $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);
    //     $question = 'เมื่อกี้ฉันถามว่าอะไรนะ';
    //     $messageSetting = 'บทสนทนานี้มุ่งเน้นไปที่การออกแบบและงานก่อสร้างในยุคปัจจุบันที่ขับเคลื่อนด้วยเทคโนโลยี คุณต้องการให้ฉันให้ข้อมูล คำแนะนำ หรือหารือเกี่ยวกับการใช้เทคโนโลยีใดในงานออกแบบและก่อสร้างปี 2024? เช่น การใช้ซอฟต์แวร์ CAD ล่าสุด การสร้างแบบจำลอง 3 มิติ ด้วย BIM หรือการใช้อุปกรณ์ IoT ในการจัดการไซต์ก่อสร้าง?';
    //     $messageRoom = $this->messageRoomModel->getMessageRoomByID('27');
    //     $messages = $this->messageModel->getHistoryMessageByRoomID($messageRoom->id, 4);


    //     echo $chatGPT->askChatGPT($messageRoom->id, $question);
    //     exit();
    // }

    // public function _test()
    // {
    //     // $input = 'text';

    //     $users = $this->userModel->getUserAll();

    //     foreach ($users as $user) {

    //         if ($user->id == '121') {

    //             $menus = $this->userMenuModel->getUserMenuTodayByUserID($user->id);

    //             if ($menus) {

    //                 $gender = $user->gender;
    //                 $age = $user->age;
    //                 $weight = $user->weight;
    //                 $height = $user->height;
    //                 $target = $user->target;
    //                 $cal_per_day = $user->cal_per_day;

    //                 // $workouts = $this->userWorkoutModel->getUserWorkoutTodayByUserID($user->id);

    //                 $meneText = '';
    //                 foreach ($menus as $menu) {
    //                     $meneText .= "$menu->name แคลอรี่ $menu->calories กิโลแคลอรี่ มีโปรตีน $menu->protein ไขมัน $menu->fat คาร์โบไฮเดรต $menu->carbohydrates";
    //                 }

    //                 $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);
    //                 $systemMessage = <<<EOT
    //                     คุณคือผู้เชี่ยวชาญด้านโภชนาการและสุขภาพ ทำหน้าที่วิเคราะห์อาหารที่ผู้ใช้บริโภคในแต่ละวัน โดยมีแนวทางดังนี้:

    //                     1. **วิเคราะห์:** ตรวจสอบสารอาหารหลัก เช่น คาร์โบไฮเดรต โปรตีน ไขมัน น้ำตาล และโซเดียม พร้อมผลกระทบต่อสุขภาพ  
    //                     2. **สรุป:** เน้นจุดเด่นและข้อควรระวังของมื้ออาหารในวันนี้  
    //                     3. **แนะนำ:** ให้คำแนะนำสั้น ๆ และปฏิบัติได้จริง เช่น ปรับสมดุลสารอาหาร เลือกอาหารที่ดีขึ้น หรือการบริโภคในวันถัดไป  

    //                     **รูปแบบคำตอบ:**  
    //                     - **📊 วิเคราะห์อาหารวันนี้:** (แยกเป็นรายการ)  
    //                     - **✅ สรุป:** (กระชับแต่ครบถ้วน)  
    //                     - **💡 แนะนำ:** (สั้น ๆ และนำไปใช้ได้จริง)  

    //                     กรุณาใช้ภาษาที่เป็นมิตร กระชับ และสร้างแรงจูงใจให้ผู้ใช้ดูแลสุขภาพของตนเอง
    //                 EOT;
    //                 $question = <<<EOT
    //                     วิเคราะห์และสรุปการกินอาหารในวันนี้ของฉัน
    //                     ข้อมูลส่วนบุคคล: เพศ $gender, อายุ $age, น้ำหนัก $weight, ส่วนสูง $height
    //                     เป้าหมาย: $target
    //                     พลังงานที่ต้องการ: $cal_per_day ต่อวัน
    //                     อาหารที่ทานในวันนี้: $meneText
    //                 EOT;

    //                 $messageRoom = $this->messageRoomModel->getMessageRoomByUserID($user->id);

    //                 $replyMessage = $chatGPT->askChatGPTWithSystemMessage($messageRoom->id, $question, $systemMessage);

    //                 px($replyMessage);

    //                 $line = new LineClient([
    //                     'id' => '128',
    //                     'accessToken' => 'mswad2W1OPrri9UQpAgNassH7G1hKIMq24ll7rDk0VgDTRCZhqQjhQKk7hwHub86Se3EtrO528RG3rNEsBtZzHVCtg4XTq/7fO1qfStVdOB7j4iHiP8SpQsdwgGT78Guqrwv+CmrwANtZSxG3EaAkI9PbdgDzCFqoOLOYbqAITQ=',
    //                     'channelID' => '2006918518',
    //                     'channelSecret' => '142d73fd0b359cafc31872a2e165d750',
    //                 ]);

    //                 $line->pushMessage($user->uid, $replyMessage, 'text');
    //                 // หน่วงเวลาสุ่มระหว่าง 3-10 วินาที
    //                 sleep(rand(3, 10));
    //                 exit();
    //             }
    //         }
    //     }

    //     exit();

    //     $line = new LineClient([
    //         'id' => '128',
    //         'accessToken' => 'mswad2W1OPrri9UQpAgNassH7G1hKIMq24ll7rDk0VgDTRCZhqQjhQKk7hwHub86Se3EtrO528RG3rNEsBtZzHVCtg4XTq/7fO1qfStVdOB7j4iHiP8SpQsdwgGT78Guqrwv+CmrwANtZSxG3EaAkI9PbdgDzCFqoOLOYbqAITQ=',
    //         'channelID' => '2006918518',
    //         'channelSecret' => '142d73fd0b359cafc31872a2e165d750',
    //     ]);

    //     // // $repyleMessage = $messages[array_rand($messages)];

    //     $UID = 'Ucac64382c185fd8acd69438c5af15935';
    //     $line->startLoadingAnimation($UID, 15);
    //     $line->pushMessage($UID, 'hi', 'text');
    //     exit();

    //     $user_id = 'Ucac64382c185fd8acd69438c5af15935'; // หรือเปลี่ยนเป็น event ที่รับมาจาก Webhook

    //     // 🔹 1. ส่ง "..." ก่อน
    //     $this->sendLineMessage($user_id, [
    //         ["type" => "text", "text" => "..."]
    //     ]);

    //     // 🔹 2. รอ 2 วินาที
    //     sleep(2);

    //     // 🔹 3. ส่งข้อความจริง พร้อม Quick Reply (กดแล้ว "..." จะหายไป)
    //     $this->sendLineMessage($user_id, [
    //         [
    //             "type" => "text",
    //             "text" => "นี่คือข้อมูลของคุณ!",
    //             "quickReply" => [
    //                 "items" => [
    //                     [
    //                         "type" => "action",
    //                         "action" => [
    //                             "type" => "message",
    //                             "label" => "เข้าใจแล้ว ✅",
    //                             "text" => "เข้าใจแล้ว ✅"
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ]);

    //     echo "Done!";
    // }

    // public function test()
    // {
    //     // $input = 'text';

    //     $line = new LineClient([
    //         'id' => '128',
    //         'accessToken' => 'mswad2W1OPrri9UQpAgNassH7G1hKIMq24ll7rDk0VgDTRCZhqQjhQKk7hwHub86Se3EtrO528RG3rNEsBtZzHVCtg4XTq/7fO1qfStVdOB7j4iHiP8SpQsdwgGT78Guqrwv+CmrwANtZSxG3EaAkI9PbdgDzCFqoOLOYbqAITQ=',
    //         'channelID' => '2006918518',
    //         'channelSecret' => '142d73fd0b359cafc31872a2e165d750',
    //     ]);

    //     // // $repyleMessage = $messages[array_rand($messages)];

    //     $UID = 'Ucac64382c185fd8acd69438c5af15935';
    //     $line->startLoadingAnimation($UID, 15);
    //     $line->pushMessage($UID, 'hi', 'text');
    //     exit();

    //     $user_id = 'Ucac64382c185fd8acd69438c5af15935'; // หรือเปลี่ยนเป็น event ที่รับมาจาก Webhook

    //     // 🔹 1. ส่ง "..." ก่อน
    //     $this->sendLineMessage($user_id, [
    //         ["type" => "text", "text" => "..."]
    //     ]);

    //     // 🔹 2. รอ 2 วินาที
    //     sleep(2);

    //     // 🔹 3. ส่งข้อความจริง พร้อม Quick Reply (กดแล้ว "..." จะหายไป)
    //     $this->sendLineMessage($user_id, [
    //         [
    //             "type" => "text",
    //             "text" => "นี่คือข้อมูลของคุณ!",
    //             "quickReply" => [
    //                 "items" => [
    //                     [
    //                         "type" => "action",
    //                         "action" => [
    //                             "type" => "message",
    //                             "label" => "เข้าใจแล้ว ✅",
    //                             "text" => "เข้าใจแล้ว ✅"
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ]);

    //     echo "Done!";
    // }

    // public function test()
    // {
    //     $user_id = 'Ucac64382c185fd8acd69438c5af15935'; // หรือเปลี่ยนเป็น event ที่รับมาจาก Webhook

    //     // 🔹 1. ส่ง "..." ก่อน
    //     $this->sendLineMessage($user_id, [
    //         ["type" => "text", "text" => "..."]
    //     ]);

    //     // 🔹 2. หน่วงเวลา 2 วินาที (ให้ LINE มีเวลาส่งข้อความแรกออกไป)
    //     sleep(2);

    //     // 🔹 3. ส่งข้อความจริง (Quick Reply)
    //     $this->sendLineMessage($user_id, [
    //         [
    //             "type" => "text",
    //             "text" => "นี่คือข้อมูลของคุณ!",
    //             "quickReply" => [
    //                 "items" => [
    //                     [
    //                         "type" => "action",
    //                         "action" => [
    //                             "type" => "message",
    //                             "label" => "เข้าใจแล้ว ✅",
    //                             "text" => "เข้าใจแล้ว ✅"
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ]);

    //     echo "Done!";
    // }

    // public function test()
    // {
    //     $user_id = 'Ucac64382c185fd8acd69438c5af15935'; 

    //     $replyToken = 'ded71fa51d5547b9828add6ebff4afd5';

    //     // 🔹 1. ส่ง "กำลังพิมพ์..." ก่อน
    //     $this->replyLineMessage($replyToken, [
    //         ["type" => "text", "text" => "กำลังพิมพ์..."]
    //     ]);

    //     // 🔹 2. รอ 2 วินาที (จำลอง Typing Bubble)
    //     sleep(2);

    //     // 🔹 3. ใช้ Push API ส่งข้อความจริง (LINE จะลบ "กำลังพิมพ์..." อัตโนมัติ)
    //     $this->pushLineMessage($user_id, [
    //         ["type" => "text", "text" => "นี่คือคำตอบของคุณ! 😊"]
    //     ]);
    // }

    private function replyLineMessage($replyToken, $messages)
    {
        $url = 'https://api.line.me/v2/bot/message/reply';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->access_token
        ];

        $data = [
            'replyToken' => $replyToken,
            'messages' => $messages
        ];

        $this->sendRequest($url, $headers, $data);
    }


    private function pushLineMessage($userId, $messages)
    {
        $url = 'https://api.line.me/v2/bot/message/push';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->access_token
        ];

        $data = [
            'to' => $userId,
            'messages' => $messages
        ];

        $this->sendRequest($url, $headers, $data);
    }

    private function sendRequest($url, $headers, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private function sendLineMessage($user_id, $messages)
    {
        try {
            $url = 'https://api.line.me/v2/bot/message/push';
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->access_token
            ];

            $data = [
                'to' => $user_id,
                'messages' => $messages
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($ch);

            if ($result === false) {
                throw new Exception("cURL Error: " . curl_error($ch));
            }

            // 🔹 ตรวจสอบ Response จาก LINE API
            var_dump("Response from LINE API:", $result);

            curl_close($ch);
            return $result;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
