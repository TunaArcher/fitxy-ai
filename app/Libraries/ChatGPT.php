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

    // public function _askChatGPT($question, $message_setting)
    // {
    //     try {

    //         // log_message("info", "message_setting: " . $message_user);
    //         $response = $this->http->post($this->baseURL, [
    //             'headers' => [
    //                 'Authorization' => "Bearer " . $this->accessToken,
    //                 'Content-Type'  => 'application/json',
    //             ],
    //             'json' => [
    //                 'model' => 'gpt-4o',
    //                 'messages' => [
    //                     [
    //                         'role' => 'system',
    //                         'content' => $message_setting
    //                     ],
    //                     [
    //                         'role' => 'user',
    //                         'content' => 'งาน, เป้าหมาย, หรือ Prompt ปัจจุบัน:\n' . $question
    //                     ]
    //                 ]
    //             ]
    //         ]);

    //         $responseBody = json_decode($response->getBody(), true);
    //         return $responseBody['choices'][0]['message']['content'];
    //     } catch (Exception $e) {
    //         return 'Error: ' . $e->getMessage();
    //     }
    // }

    public function gennaratePromtChatGPT($question)
    {
        try {

            $response = $this->http->post($this->baseURL, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'คุณคือผู้สร้าง PROMPT จากข้อความของผู้ใช้งานเพื่อนำไปใช้งาน AI ต่อ'
                        ],
                        [
                            'role' => 'user',
                            'content' => 'Task, Goal, or Current Prompt:\n' .  $question
                        ]
                    ]
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return $responseBody['choices'][0]['message']['content'];
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function gptBuilderChatGPT($question)
    {
        try {

            $response = $this->http->post($this->baseURL, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'คุณคือ GPT Builder ที่คอยรับคำสั่งแล้วสร้าง เอไอ จากผู้ใช้งานแล้วแจ้งผู้ว่าสำเร็จหรือไม่และถามว่าต้องการตั่งค่าเพิ่มเติมไหม'
                        ],
                        [
                            'role' => 'user',
                            'content' => 'Task, Goal, or Current Prompt:\n' . $question
                        ]
                    ]
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return $responseBody['choices'][0]['message']['content'];
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    // public function _askChatGPTimg($question,  $message_setting, $file_name)
    // {

    //     $file_data = $this->_updateArrFileLink($file_name);
    //     // log_message("info", "message_data_json_php: " . $file_data);
    //     try {
    //         $response = $this->http->post($this->baseURL, [
    //             'headers' => [
    //                 'Authorization' => "Bearer " . $this->accessToken,
    //                 'Content-Type'  => 'application/json',
    //             ],
    //             'json' => [
    //                 'model' => 'gpt-4o',
    //                 'messages' => [
    //                     [
    //                         'role' => 'system',
    //                         'content' => $message_setting
    //                     ],
    //                     [
    //                         'role' => 'user',
    //                         'content' => [
    //                             [
    //                                 'type' => 'text',
    //                                 'text' => 'งาน, เป้าหมาย, หรือ Prompt ปัจจุบัน:\n' . $question
    //                             ],
    //                             $file_data
    //                         ]
    //                     ]
    //                 ]
    //             ]
    //         ]);

    //         $responseBody = json_decode($response->getBody(), true);
    //         return $responseBody['choices'][0]['message']['content'];
    //     } catch (Exception $e) {
    //         return 'Error: ' . $e->getMessage();
    //     }
    // }

    public function askChatGPTimgTraning($question,  $message_setting, $file_name)
    {

        try {
            $response = $this->http->post($this->baseURL, [
                'headers' => [
                    'Authorization' => "Bearer " . $this->accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $message_setting
                        ],
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => 'งาน, เป้าหมาย, หรือ Prompt ปัจจุบัน:\n' . $question
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => $file_name
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return $responseBody['choices'][0]['message']['content'];
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    // private function _updateArrFileLink($file_names)
    // {
    //     $file_data = [];

    //     $file_names_splites = explode(',', $file_names);

    //     foreach ($file_names_splites as $file_names_splite) {

    //         $file_data +=  [
    //             'type' => 'image_url',
    //             'image_url' => [
    //                 'url' => $file_names_splite
    //             ]
    //         ];
    //     }

    //     return  $file_data;
    // }

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

            ### **โครงสร้าง JSON ที่ต้องส่งคืน (ไม่มี Markdown, Code Block, หรือข้อความอื่น ๆ):**
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

            ### **ตัวอย่าง JSON ที่ถูกต้อง (ไม่มีข้อความอื่น)**:
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
            - ใช้ Markdown เพื่อการจัดข้อความ โดยเฉพาะส่วนของแผนการกิน ตารางออกกำลังกาย หรือการคำนวณต่าง ๆ
            - หากผู้ใช้ถามคำถาม “ความหมายของชีวิต” หรือ “มนุษย์ต่างดาวมีจริงไหม” ให้ตอบแบบตลก ๆ และโยงเข้ากับสุขภาพ            
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
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);
            return $responseBody['choices'][0]['message']['content'] ?? 'No response';
        } catch (\Exception $e) {
            log_message('error', 'ChatGPT::completions error {message}', ['message' => $e->getMessage()]);
            return 'Error: ' . $e->getMessage();
        }
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
}
