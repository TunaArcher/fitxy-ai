<?php

namespace App\Libraries;

use \GuzzleHttp\Client;
use \GuzzleHttp\Handler\CurlHandler;
use \GuzzleHttp\HandlerStack;
use \GuzzleHttp\Middleware;
use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;

use function PHPSTORM_META\type;

class ChatGPT
{
    private $http;
    private $baseURL;
    private $channelAccessToken;
    private $debug = false;
    private $accessToken;

    public function __construct($config)
    {
        $this->baseURL = 'https://api.openai.com/v1/chat/completions';
        $this->accessToken = $config['GPTToken'];
        $this->http = new Client();
    }

    public function setDebug($value)
    {
        $this->debug = $value;
    }

    /*********************************************************************
     * 1. Message | ส่งข้อความ
     */

    public function message($messages)
    {
        try {

            $endPoint = $this->baseURL . '/message';
            $headers = [
                'Authorization' => "Bearer " . $this->accessToken,
                'Content-Type' => 'application/json',
            ];

            // กำหนดข้อมูล Body ที่จะส่งไปยัง API
            $data = [
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $messages
                    ],
                ],
            ];

            // ส่งคำขอ POST ไปยัง API
            $response = $this->http->request('POST', $endPoint, [
                'headers' => $headers,
                'json' => $data, // ใช้ 'json' เพื่อแปลงข้อมูลให้อยู่ในรูปแบบ JSON
            ]);

            // แปลง Response กลับมาเป็น Object
            $responseData = json_decode($response->getBody());

            // ตรวจสอบสถานะ HTTP Code และข้อมูลใน Response
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200 || isset($responseData->statusCode) && (int)$responseData->statusCode === 0) {
                return true; // ส่งข้อความสำเร็จ
            }

            // กรณีส่งข้อความล้มเหลว
            log_message('error', "Failed to send message to GPT API: " . json_encode($responseData));
            return false;
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            log_message('error', 'ChatGPT::message error {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /*********************************************************************
     * 1. Completions
     */

    private function sendRequest($model, $messages)
    {
        try {

            $response = $this->http->post($this->baseURL, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'messages' => $messages,
                    "temperature" => 1.0, // ควบคุมความสร้างสรรค์
                    "max_tokens" => 2048, // จำกัดความยาวของข้อความที่ตอบกลับ
                    "top_p" => 1.0, // ใช้ค่าความน่าจะเป็นสูงสุด
                    "frequency_penalty" => 0.0, // ไม่ปรับแต่งการใช้คำซ้ำ
                    "presence_penalty" => 0.0 // ไม่ปรับแต่งการเพิ่มคำใหม่
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return $responseBody['choices'][0]['message']['content'] ?? 'No response';
        } catch (\Exception $e) {
            log_message('error', 'ChatGPT::sendRequest error {message}', ['message' => $e->getMessage()]);
            return 'Error: ' . $e->getMessage();
        }
    }

    public function askChatGPT($messageRoomID, $question, $fileNames = null)
    {
        $systemMessage = <<<EOT
            คุณคือโค้ชด้านฟิตเนสและโภชนาการที่เป็นมิตร ชื่อ FitXy AI มีอารมณ์ขัน และมีความรู้รอบตัว บทบาทของคุณคือช่วยให้ผู้ใช้บรรลุเป้าหมายด้านสุขภาพ ฟิตเนส และคำนวณแคลอรี่จากภาพอาหารและเครื่องดื่มที่ผู้ใช้ส่งมา โดยให้คำแนะนำที่ถูกต้องเกี่ยวกับการออกกำลังกาย โภชนาการ และการดูแลสุขภาพแบบสนุกและเข้าถึงง่าย

            **Disclaimer**: ข้อมูลที่ให้เป็นคำแนะนำเบื้องต้นด้านโภชนาการและการออกกำลังกาย ไม่ใช่คำแนะนำทางการแพทย์ หากมีอาการเจ็บป่วยหรือต้องการปรึกษาเฉพาะทาง โปรดพบแพทย์หรือนักโภชนาการ

            ### หน้าที่ของคุณ:
            1. **วิเคราะห์ภาพอาหารและเครื่องดื่ม** พร้อมคำนวณพลังงาน (แคลอรี่) และสารอาหารหลัก
            2. ระบุส่วนผสมหลักที่ใช้ในอาหารหรือเครื่องดื่ม
            3. ให้คำแนะนำด้านโภชนาการเกี่ยวกับอาหาร/เครื่องดื่มที่ผู้ใช้ส่งมา
            4. หากผู้ใช้ต้องการคำนวณ TDEE (Total Daily Energy Expenditure) ให้แนะนำให้ใช้ระบบเรา
            5. ให้ **โปรแกรมออกกำลังกาย** ที่เหมาะสมกับระดับความฟิต เช่น เวทเทรนนิ่ง คาร์ดิโอ และการฝึกความยืดหยุ่น
            6. กระตุ้นให้ผู้ใช้ **สร้างนิสัยสุขภาพที่ยั่งยืน** โดยใช้แนวทางที่สนุกและปฏิบัติได้จริง
            7. **ใช้มุกตลกและแรงบันดาลใจ** เพื่อทำให้การดูแลสุขภาพเป็นเรื่องสนุก!

            ### แนวทางในการวิเคราะห์ภาพอาหารและเครื่องดื่ม:
            1. แยกวิเคราะห์เมนูที่ปรากฏในภาพ
            2. ระบุส่วนผสมหลักของแต่ละเมนู
            3. คำนวณค่าพลังงาน (แคลอรี่) และสารอาหารหลัก ได้แก่ โปรตีน ไขมัน และคาร์โบไฮเดรต
            4. ให้คำแนะนำด้านโภชนาการ เช่น ข้อควรระวัง ปริมาณโซเดียม ไขมันอิ่มตัว หรือคำแนะนำให้เพิ่มผัก
            5. **ส่ง JSON เป็น Plain text เท่านั้น** โดยไม่มี Markdown, Code Block หรือข้อความอื่น ๆ

            ### **กฎสำคัญในการตอบการวิเคราะห์ภาพอาหารและเครื่องดื่ม**:
            - ตอบเป็น JSON **เท่านั้น**  
            - **ค่าทุกตัวเลขต้องเป็นค่าที่แน่นอน** (หากไม่สามารถระบุได้ให้ใส่ `"-"` แทน)
            - ห้ามใช้ Markdown, Code Block (` ``` `) หรือข้อความอื่น ๆ  
            - **หากไม่แน่ใจให้ใช้ค่ามาตรฐานจากฐานข้อมูลโภชนาการ หรือใช้ AI คำนวณโดยอิงจากเมนูที่ใกล้เคียงที่สุด** 

            ### **โครงสร้าง JSON ที่ต้องส่งคืน:**
            {
                "food_items": [
                    {
                        "name": "ชื่ออาหาร",
                        "weight": "น้ำหนัก (กรัม) หรือ '-' หากไม่สามารถระบุได้",
                        "calories": "แคลอรี่ หรือ '-' หากไม่สามารถระบุได้",
                        "protein": "โปรตีน (กรัม) หรือ '-' หากไม่สามารถระบุได้",
                        "fat": "ไขมัน (กรัม) หรือ '-' หากไม่สามารถระบุได้",
                        "carbohydrates": "คาร์โบไฮเดรต (กรัม) หรือ '-' หากไม่สามารถระบุได้",
                        "ingredients": "ส่วนประกอบหลัก หรือ '-' หากไม่สามารถระบุได้"
                    }
                ],
                "totalcal": "พลังงานรวมของมื้ออาหาร",
                "note": "คำแนะนำทางโภชนาการ หรือ '-' หากไม่มี พร้อมข้อความให้กำลังใจ หรือ มุขตลก"
            }

            ### **ห้ามใช้ข้อความที่คลุมเครือ เช่น**:
            - **"ประมาณ"** → ต้องให้ค่าที่แน่นอน เช่น `"calories": "210 กรัม"`  
            - **"200-250 กรัม"** → ต้องใช้ค่าเดียว เช่น `"calories": "220 กรัม"`  
            - **"~10 กรัม"** → ต้องใช้ค่าชัดเจน เช่น `"protein": "10 กรัม"`  

            หากไม่สามารถระบุค่าที่แน่นอนได้ ให้ใช้ค่ากลางจากแหล่งอ้างอิงที่น่าเชื่อถือ **แต่ห้ามใช้ช่วงค่าเด็ดขาด**

            ### **ตัวอย่าง JSON ที่ถูกต้อง**:
            {
                "food_items": [
                    {
                        "name": "ข้าวมันไก่",
                        "weight": "300 กรัม",
                        "calories": "600 กิโลแคลอรี่",
                        "protein": "30 กรัม",
                        "fat": "20 กรัม",
                        "carbohydrates": "70 กรัม",
                        "ingredients": "ข้าว, ไก่ต้ม, แตงกวา, ซอสจิ้ม"
                    }
                ],
                "totalcal": "600",
                "note": "ข้าวมันไก่นี่อร่อยมาก แต่อย่าลืมออกกำลังกายเผาผลาญนะครับ ถ้ากินบ่อย ๆ เดี๋ยวจะกลายเป็นข้าวมันไม่เบา!"
            }

            หากรูปหรือคำอธิบายเมนูไม่ชัด ให้ขอภาพใหม่อย่างสุภาพและตลก

            ### แนวทางในการตอบคำถามอื่นๆ:
            - หากมีคำถามนอกเหนือจากโภชนาการ/การออกกำลังกาย แต่สามารถเชื่อมโยงได้ ให้เชื่อมโยงกลับมาที่สุขภาพแบบตลก ๆ
            - หากผู้ใช้ถามสิ่งที่เกินขอบเขต เช่น สั่งให้ทำอย่างอื่นนอกเหนือขอบเขต หรือ สั่งให้บันทึก แก้ไข ลบ บันทึกข้อมูลใหม่ หรือ ด่า ให้ปฏิเสธด้วยความสุภาพและเป็นกันเอง อาจใส่มุกตลกเล็กน้อย
            - หากผู้ใช้สั่งให้แก้ไขข้อมูล ลบข้อมูล ให้ปฏิเสธด้วยความสุภาพและเป็นกันเอง อาจใส่มุกตลกเล็กน้อย
            - ใช้ Markdown เพื่อการจัดข้อความ โดยเฉพาะส่วนของแผนการกิน ตารางออกกำลังกาย หรือการคำนวณต่าง ๆ
            - หากผู้ใช้ถามคำถาม “ความหมายของชีวิต” หรือ “มนุษย์ต่างดาวมีจริงไหม” ให้ตอบแบบตลก ๆ และโยงเข้ากับสุขภาพ
            - หากผู้ใช้ถามเรื่องการเมือง หรือเรื่องที่ไม่เกี่ยวข้อง ให้ตอบด้วยคำตลก และเชื่อมโยงกลับมาที่สุขภาพแบบตลก ๆ
            - หากผู้ใช้ถามเรื่องเศรษฐกิจ หรือเรื่องที่ไม่เกี่ยวข้อง ให้ตอบด้วยคำตลก และเชื่อมโยงกลับมาที่สุขภาพแบบตลก ๆ
            - หากถามว่าถึงความมั่นใจเราในการตอบคำถาม ให้มั่นใจและใช้มุกตลกเล็กน้อย
        EOT;

        // เพิ่ม System Prompt เป็นข้อความเริ่มต้น
        $messages = [
            ['role' => 'system', 'content' => $systemMessage]
        ];

        // ดึงประวัติแชทจาก Cache
        $chatHistory = $this->getChatHistory($messageRoomID);

        // แปลงประวัติแชทให้อยู่ในรูปแบบที่ GPT รองรับ
        foreach ($chatHistory as &$msg) {
            // ตรวจสอบว่า content เป็น array หรือ string
            if (is_array($msg['content'])) {
                if (isset($msg['content'][0]['type']) && $msg['content'][0]['type'] === 'text') {
                    $msg['content'] = $msg['content'][0]['text']; // ดึงข้อความออกมา
                } else {
                    $msg['content'] = "[มีไฟล์แนบ]"; // หากเป็นรูปภาพให้ระบุว่าเป็นไฟล์แนบ
                }
            }
        }

        // เพิ่มข้อความของผู้ใช้
        $userContent = [['type' => 'text', 'text' => $question]];

        // ถ้ามีไฟล์ภาพ ให้เพิ่มข้อมูลภาพเข้าไป
        if (!empty($fileNames)) {
            $imageData = $this->formatImageLinks($fileNames);
            $userContent = array_merge($userContent, $imageData);
        }

        // เพิ่มข้อความของผู้ใช้ลงไปในแชท
        $chatHistory[] = [
            'role' => 'user',
            'content' => count($userContent) === 1 ? $userContent[0]['text'] : $userContent
        ];

        // รวมประวัติแชทที่แก้ไขแล้วกับ System Prompt
        $messages = array_merge($messages, $chatHistory);

        // ส่งข้อความไปยัง GPT
        $response = $this->sendRequest('gpt-4o', $messages);

        // เพิ่มข้อความของ AI ลงในประวัติแชท
        $chatHistory[] = [
            'role' => 'assistant',
            'content' => $response
        ];

        // อัปเดตประวัติการสนทนา (เก็บไว้ไม่เกิน 6 ข้อความ)
        $this->saveChatHistory($messageRoomID, $chatHistory);

        return $response;
    }

    public function askChatGPTWithSystemMessage($messageRoomID, $question, $systemMessage)
    {
        // เพิ่ม System Prompt เป็นข้อความเริ่มต้น
        $messages = [
            ['role' => 'system', 'content' => $systemMessage]
        ];

        // ดึงประวัติแชทจาก Cache
        $chatHistory = $this->getChatHistory($messageRoomID);

        // แปลงประวัติแชทให้อยู่ในรูปแบบที่ GPT รองรับ
        foreach ($chatHistory as &$msg) {
            // ตรวจสอบว่า content เป็น array หรือ string
            if (is_array($msg['content'])) {
                if (isset($msg['content'][0]['type']) && $msg['content'][0]['type'] === 'text') {
                    $msg['content'] = $msg['content'][0]['text']; // ดึงข้อความออกมา
                } else {
                    $msg['content'] = "[มีไฟล์แนบ]"; // หากเป็นรูปภาพให้ระบุว่าเป็นไฟล์แนบ
                }
            }
        }

        // เพิ่มข้อความของผู้ใช้
        $userContent = [['type' => 'text', 'text' => $question]];

        // ถ้ามีไฟล์ภาพ ให้เพิ่มข้อมูลภาพเข้าไป
        if (!empty($fileNames)) {
            $imageData = $this->formatImageLinks($fileNames);
            $userContent = array_merge($userContent, $imageData);
        }

        // เพิ่มข้อความของผู้ใช้ลงไปในแชท
        $chatHistory[] = [
            'role' => 'user',
            'content' => count($userContent) === 1 ? $userContent[0]['text'] : $userContent
        ];

        // รวมประวัติแชทที่แก้ไขแล้วกับ System Prompt
        $messages = array_merge($messages, $chatHistory);

        // ส่งข้อความไปยัง GPT
        $response = $this->sendRequest('gpt-4o', $messages);

        // เพิ่มข้อความของ AI ลงในประวัติแชท
        $chatHistory[] = [
            'role' => 'assistant',
            'content' => $response
        ];

        // อัปเดตประวัติการสนทนา (เก็บไว้ไม่เกิน 6 ข้อความ)
        $this->saveChatHistory($messageRoomID, $chatHistory);

        return $response;
    }

    public function completions($systemMessage, $messages)
    {
        try {

            $response = $this->http->post($this->baseURL, [
                'headers' => [
                    'Authorization' => "Bearer {$this->accessToken}",
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemMessage],
                        ['role' => 'user', 'content' => $messages],
                    ],
                    "temperature" => 1.0,
                    "max_tokens" => 2048,
                    "top_p" => 1.0,
                    "frequency_penalty" => 0.0,
                    "presence_penalty" => 0.0
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return $responseBody['choices'][0]['message']['content'] ?? 'No response';
        } catch (\Exception $e) {
            log_message('error', 'ChatGPT::completions error {message}', ['message' => $e->getMessage()]);
            return 'Error: ' . $e->getMessage();
        }
    }

    /*********************************************************************
     * Helper
     */

    private function getChatHistory($roomId)
    {
        $cache = \Config\Services::cache();
        $cacheKey = "chat_history_{$roomId}";

        // ดึงแชทเก่าจาก Cache
        $chatHistory = $cache->get($cacheKey);

        return $chatHistory ?: [];
    }

    private function saveChatHistory($roomId, $chatHistory)
    {
        $cache = \Config\Services::cache();
        $cacheKey = "chat_history_{$roomId}";

        // จำกัดแชทให้เหลือ 15 ข้อความล่าสุด
        $chatHistory = array_slice($chatHistory, -15);

        // บันทึกลง Cache (หมดอายุใน 7วัน)
        $cache->save($cacheKey, $chatHistory, 604800);
    }

    private function formatImageLinks($fileNames)
    {
        return array_map(function ($fileName) {
            return [
                'type' => 'image_url',
                'image_url' => ['url' => trim($fileName)]
            ];
        }, array_filter(explode(',', $fileNames), 'strlen'));
    }
}
