<?php

namespace App\Controllers;

use App\Integrations\Line\LineClient;
use App\Libraries\ChatGPT;
use App\Models\MessageModel;
use App\Models\MessageRoomModel;
use App\Models\UserModel;
use Exception;

class TestController extends BaseController
{
    private $access_token = 'mswad2W1OPrri9UQpAgNassH7G1hKIMq24ll7rDk0VgDTRCZhqQjhQKk7hwHub86Se3EtrO528RG3rNEsBtZzHVCtg4XTq/7fO1qfStVdOB7j4iHiP8SpQsdwgGT78Guqrwv+CmrwANtZSxG3EaAkI9PbdgDzCFqoOLOYbqAITQ=';
    private MessageModel $messageModel;
    private MessageRoomModel $messageRoomModel;

    public function __construct()
    {
        $this->messageModel = new MessageModel();
        $this->messageRoomModel = new MessageRoomModel();
    }

    // public function test()
    // {
    //     $text1 = 'https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line_agent/line_67ae915b545f3.jpg,';
    //     $text2 = 'https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line_agent/line_67ae94eed9099.jpg,https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line_agent/line_67ae95223151b.jpg,';
    // }

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

    // public function test()
    // {
    //     $input = 'text';

    //     $input = '
    //     {
    //         "food_items": [
    //             {
    //             "name": "ก๋วยเตี๋ยวเรือ",
    //             "weight": "-",
    //             "calories": "350",
    //             "protein": "20 กรัม",
    //             "fat": "10 กรัม",
    //             "carbohydrates": "45 กรัม",
    //             "ingredients": "เส้นหมี่, หมู, เครื่องใน, น้ำซุป, ถั่วงอก, โหระพา"
    //             }
    //         ],
    //         "totalcal": "350",
    //         "note": "ก๋วยเตี๋ยวเรือนี่เอง! อร่อยแบบได้รสชาติเด็ด ๆ ขนาดนี้ ลองใส่ผักเยอะ ๆ เพื่อเพิ่มใยอาหารนะครับ อย่าลืมออกกำลังกายเผาผลาญพลังงานด้วยล่ะ!"
    //     }';

    //     $input = '
    //     {
    //         "food_items": [
    //             {
    //                 "name": "ไข่เจียวหมูสับ",
    //                 "weight": "-",
    //                 "calories": "450",
    //                 "protein": "25 กรัม",
    //                 "fat": "35 กรัม",
    //                 "carbohydrates": "3 กรัม",
    //                 "ingredients": "ไข่, หมูสับ, ต้นหอม"
    //             },
    //             {
    //                 "name": "ไข่ดาว 2 ฟอง",
    //                 "weight": "-",
    //                 "calories": "200",
    //                 "protein": "14 กรัม",
    //                 "fat": "16 กรัม",
    //                 "carbohydrates": "1 กรัม",
    //                 "ingredients": "ไข่"
    //             },
    //             {
    //                 "name": "ขนมปังกรอบ",
    //                 "weight": "-",
    //                 "calories": "120",
    //                 "protein": "3 กรัม",
    //                 "fat": "6 กรัม",
    //                 "carbohydrates": "15 กรัม",
    //                 "ingredients": "ขนมปัง, เนย"
    //             }
    //         ],
    //         "totalcal": "770",
    //         "note": "ชุดนี้เต็มไปด้วยโปรตีนเลยทีเดียว! เพิ่มผักสักหน่อยเพื่อสุขภาพที่แข็งแรงนะครับ ไข่เจียวหมูสับกับไข่ดาวนี่คือคู่หูสุดยอด แต่ระวังไม่ให้ไข่เกินไปในมื้อนี้นะ!"
    //     }';

    //     // เลือกอินพุตที่ต้องการใช้ (เลือกได้ระหว่าง $inputFoodSingle และ $inputFoodMuilti)
    //     $inputData = $input;

    //     // แปลง JSON เป็น PHP Array
    //     $data = json_decode($inputData, true);
    //     $foodItems = $data['food_items'];

    //     // สร้างชื่อเมนูโดยรวม
    //     $menuNames = array_map(fn($item) => $item['name'], $foodItems);
    //     $menuTitle = "เพิ่มข้อมูล: " . implode(" + ", $menuNames);

    //     // สร้างรายการเมนูแยกแต่ละเมนู
    //     $menuContents = [];
    //     foreach ($foodItems as $food) {
    //         $menuContents[] = [
    //             "type" => "text",
    //             "text" => $food['name'],
    //             "weight" => "bold",
    //             "size" => "md",
    //             "margin" => "md"
    //         ];
    //         $menuContents[] = [
    //             "type" => "box",
    //             "layout" => "vertical",
    //             "contents" => [
    //                 [
    //                     "type" => "box",
    //                     "layout" => "horizontal",
    //                     "contents" => [
    //                         ["type" => "text", "text" => "แคลอรี่", "size" => "sm", "color" => "#2ECC71"],
    //                         ["type" => "text", "text" => $food['calories'] . " กิโลแคลอรี่", "size" => "sm", "align" => "end", "color" => "#888888"]
    //                     ]
    //                 ],
    //                 [
    //                     "type" => "box",
    //                     "layout" => "horizontal",
    //                     "contents" => [
    //                         ["type" => "text", "text" => "โปรตีน", "size" => "sm", "color" => "#2ECC71"],
    //                         ["type" => "text", "text" => $food['protein'], "size" => "sm", "align" => "end", "color" => "#888888"]
    //                     ]
    //                 ],
    //                 [
    //                     "type" => "box",
    //                     "layout" => "horizontal",
    //                     "contents" => [
    //                         ["type" => "text", "text" => "ไขมัน", "size" => "sm", "color" => "#2ECC71"],
    //                         ["type" => "text", "text" => $food['fat'], "size" => "sm", "align" => "end", "color" => "#888888"]
    //                     ]
    //                 ],
    //                 [
    //                     "type" => "box",
    //                     "layout" => "horizontal",
    //                     "contents" => [
    //                         ["type" => "text", "text" => "คาร์โบไฮเดรต", "size" => "sm", "color" => "#2ECC71"],
    //                         ["type" => "text", "text" => $food['carbohydrates'], "size" => "sm", "align" => "end", "color" => "#888888"]
    //                     ]
    //                 ],
    //                 ["type" => "separator", "margin" => "md"]
    //             ]
    //         ];
    //     }

    //     // สร้าง Flex Message JSON
    //     $flexMessage = [
    //         "type" => "bubble",
    //         "hero" => [
    //             "type" => "image",
    //             "url" => "https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line_agent/line_67b329ce38e56.jpg",
    //             "size" => "full",
    //             "aspectRatio" => "20:13",
    //             "aspectMode" => "cover"
    //         ],
    //         "header" => [
    //             "type" => "box",
    //             "layout" => "vertical",
    //             "contents" => [
    //                 [
    //                     "type" => "text",
    //                     "text" => $menuTitle,
    //                     "weight" => "bold",
    //                     "size" => "lg"
    //                 ],
    //                 [
    //                     "type" => "text",
    //                     "text" => "สรุปพลังงานทั้งหมด: " . $data['totalcal'] . " กิโลแคลอรี่",
    //                     "size" => "md",
    //                     "color" => "#666666"
    //                 ]
    //             ]
    //         ],
    //         "body" => [
    //             "type" => "box",
    //             "layout" => "vertical",
    //             "contents" => array_merge($menuContents, [
    //                 ["type" => "text", "text" => "คำแนะนำเพื่อสุขภาพ", "weight" => "bold", "margin" => "md"],
    //                 ["type" => "text", "text" => $data['note'], "size" => "sm", "wrap" => true, "color" => "#666666"]
    //             ])
    //         ],
    //         "footer" => [
    //             "type" => "box",
    //             "layout" => "vertical",
    //             "contents" => [
    //                 [
    //                     "type" => "button",
    //                     "style" => "primary",
    //                     "color" => "#1DB446",
    //                     "action" => [
    //                         "type" => "uri",
    //                         "label" => "แก้ไขข้อมูลอาหาร",
    //                         "uri" => "https://line.autoconx.co/"
    //                     ]
    //                 ]
    //             ]
    //         ]
    //     ];
    //     $line = new LineClient([
    //         'id' => '128',
    //         'accessToken' => 'mswad2W1OPrri9UQpAgNassH7G1hKIMq24ll7rDk0VgDTRCZhqQjhQKk7hwHub86Se3EtrO528RG3rNEsBtZzHVCtg4XTq/7fO1qfStVdOB7j4iHiP8SpQsdwgGT78Guqrwv+CmrwANtZSxG3EaAkI9PbdgDzCFqoOLOYbqAITQ=',
    //         'channelID' => '2006918518',
    //         'channelSecret' => '142d73fd0b359cafc31872a2e165d750',
    //     ]);

    //     // $repyleMessage = $messages[array_rand($messages)];

    //     $UID = 'Ucac64382c185fd8acd69438c5af15935';
    //     $repyleMessage = $flexMessage;
    //     $line->pushMessage($UID, $repyleMessage, 'flex');
    //     exit();
    // }

    // public function test()
    // {
    //     // $input = 'text';

    //     // $line = new LineClient([
    //     //     'id' => '128',
    //     //     'accessToken' => 'mswad2W1OPrri9UQpAgNassH7G1hKIMq24ll7rDk0VgDTRCZhqQjhQKk7hwHub86Se3EtrO528RG3rNEsBtZzHVCtg4XTq/7fO1qfStVdOB7j4iHiP8SpQsdwgGT78Guqrwv+CmrwANtZSxG3EaAkI9PbdgDzCFqoOLOYbqAITQ=',
    //     //     'channelID' => '2006918518',
    //     //     'channelSecret' => '142d73fd0b359cafc31872a2e165d750',
    //     // ]);

    //     // // $repyleMessage = $messages[array_rand($messages)];

    //     // $UID = 'Ucac64382c185fd8acd69438c5af15935';
    //     // $repyleMessage = $flexMessage;
    //     // $line->pushMessage($UID, $repyleMessage, 'image');
    //     // exit();

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

    public function test()
    {
        $user_id = 'Ucac64382c185fd8acd69438c5af15935'; 
    
        $replyToken = 'ded71fa51d5547b9828add6ebff4afd5';

        // 🔹 1. ส่ง "กำลังพิมพ์..." ก่อน
        $this->replyLineMessage($replyToken, [
            ["type" => "text", "text" => "กำลังพิมพ์..."]
        ]);

        // 🔹 2. รอ 2 วินาที (จำลอง Typing Bubble)
        sleep(2);

        // 🔹 3. ใช้ Push API ส่งข้อความจริง (LINE จะลบ "กำลังพิมพ์..." อัตโนมัติ)
        $this->pushLineMessage($user_id, [
            ["type" => "text", "text" => "นี่คือคำตอบของคุณ! 😊"]
        ]);
    }
    
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
