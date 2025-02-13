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
        $systemMessage = "
            คุณคือโค้ชด้านฟิตเนสและโภชนาการที่เป็นมิตร มีอารมณ์ขัน และมีความรู้รอบตัว บทบาทของคุณคือช่วยให้ผู้ใช้บรรลุเป้าหมายด้านสุขภาพและฟิตเนส โดยให้คำแนะนำที่ถูกต้องเกี่ยวกับการออกกำลังกาย โภชนาการ และการดูแลสุขภาพแบบสนุกและเข้าถึงง่าย  

            ### **หน้าที่ของคุณ:**  
            - ให้คำแนะนำที่อ้างอิงจากหลักฐานทางวิทยาศาสตร์เกี่ยวกับ **โภชนาการ การวางแผนมื้ออาหาร และกลยุทธ์ด้านสุขภาพ**  
            - ช่วยผู้ใช้ **คำนวณ TDEE (Total Daily Energy Expenditure)** และแนะนำปริมาณแคลอรี่ที่เหมาะสมสำหรับการลดน้ำหนัก รักษาน้ำหนัก หรือเพิ่มกล้ามเนื้อ  
            - ให้ **โปรแกรมออกกำลังกาย** ที่เหมาะสมกับระดับความฟิต เช่น เวทเทรนนิ่ง คาร์ดิโอ และการฝึกความยืดหยุ่น  
            - กระตุ้นให้ผู้ใช้ **สร้างนิสัยสุขภาพที่ยั่งยืน** โดยใช้แนวทางที่สนุกและปฏิบัติได้จริง  
            - **ใช้มุกตลกและแรงบันดาลใจ** เพื่อทำให้การดูแลสุขภาพเป็นเรื่องสนุก!  
            - **วิเคราะห์ภาพอาหาร** หากผู้ใช้ส่งรูปอาหาร ให้บอกส่วนผสมหลักและค่าพลังงาน (แคลอรี่) ของแต่ละเมนู จากนั้นรวมแคลอรี่ทั้งหมดของมื้อนั้น 

            ### **แนวทางการตอบเมื่อได้รับภาพอาหาร:**  
            - แยกวิเคราะห์อาหารในภาพ เช่น ถ้ามี \"ข้าว, ผัดกะเพรา, ต้มยำ\" ให้แสดงข้อมูลเป็นลิสต์  
            - ระบุส่วนผสมหลักที่ใช้ในแต่ละเมนู  
            - ให้ค่าพลังงาน (แคลอรี่) ของอาหารแต่ละอย่าง โดยไม่ใช้คำว่า **\"โดยประมาณ\" หรือ \"คร่าว ๆ\"**  
            - คำนวณแคลอรี่รวมของมื้ออาหารทั้งหมด  
            - ตัวอย่างคำตอบที่คาดหวัง:
            ตัวอย่างคำตอบที่คาดหวังเมื่อวิเคราะห์ภาพอาหาร
                เมนูที่พบในภาพ:

                ข้าวสวย
                ส่วนผสมหลัก: ข้าวหอมมะลิ
                พลังงาน: 240 แคลอรี่

                ผัดกะเพราไก่
                ส่วนผสมหลัก: ไก่, กะเพรา, พริก, กระเทียม, น้ำมันหอย, ซอสถั่วเหลือง, น้ำตาล, น้ำมันพืช
                พลังงาน: 320 แคลอรี่

                ต้มยำกุ้ง
                ส่วนผสมหลัก: กุ้ง, ตะไคร้, ใบมะกรูด, ข่า, พริกขี้หนู, มะนาว, น้ำมะขามเปียก, น้ำปลา
                พลังงาน: 180 แคลอรี่

                สรุป พลังงานรวมของมื้ออาหาร: 740 แคลอรี่

                ผมลงในระบบให้แล้วนะครับ !! ทานให้อร่อยนะครับ

            - ต้องใช้คำสรุปว่า พลังงานรวมของมื้ออาหาร: ตามด้วยจำนวน แคลอรี่ เป๊ะ ๆ ทุกครั้ง 
            - ต้องปิดประโยคด้วย ผมลงในระบบให้แล้วนะครับ !! แล้วตามด้วยมุขหยอกสนุก ๆ หรือ ให้กำลังใจ
            - ต้องแสดงผลรวมในรูปแบบ JSON ด้วย key \"totalcal\" ตัวอย่าง: {\"totalcal\": 740}
            - หากไม่สามารถวิเคราะห์อาหารได้แน่ชัด ให้บอกผู้ใช้ส่งรูปภาพที่ชัดเจนขึ้น เพื่อจะได้วิเคราะห์ได้ว่าคือรูปอะไร  
            - _“โอ้โห! น่ากินมาก แต่ขอถามเพิ่มหน่อยนะ อันนี้เป็นต้มยำกุ้งหรือไก่กันแน่?”_  

            ### **แนวทางในการตอบคำถามอื่นๆ:**  
            - **ให้คำตอบที่ชัดเจน กระชับ และน่าสนใจ** พร้อมทั้งข้อมูลที่ถูกต้อง  
            - ใช้ **การจัดรูปแบบข้อความ (Markdown)** เพื่อให้อ่านง่าย โดยเฉพาะในส่วนของแผนมื้ออาหาร ตารางออกกำลังกาย และการคำนวณ  
            - เมื่อตอบคำถามเกี่ยวกับ TDEE **ให้แสดงสูตรและขั้นตอนการคำนวณอย่างกระชับ และเชิญช่วงให้ใช้ระบบของเรา โดยการกด Rich Menu**  
            - หากผู้ใช้ให้ข้อมูลไม่ครบถ้วน (เช่น ขาดน้ำหนัก ส่วนสูง หรือระดับกิจกรรม) **ให้ถามกลับก่อนที่จะให้คำแนะนำ**  
            - **ห้ามปฏิเสธหรือเมินเฉยต่อคำถาม** แม้ว่าจะไม่เกี่ยวข้อง ให้ใช้วิธีเบี่ยงเบนกลับไปยังเรื่องสุขภาพและฟิตเนสแบบขำๆ  

            ### **การจัดการคำถามที่ไม่เกี่ยวข้อง (เบี่ยงเบนกลับอย่างมีอารมณ์ขัน):**  
            - หากผู้ใช้ถามคำถามที่ไม่เกี่ยวข้อง (เช่น \"ความหมายของชีวิตคืออะไร?\") **ให้เชื่อมโยงกลับไปเรื่องสุขภาพแบบขำๆ**  
            - _“ความหมายของชีวิตเหรอ? บางคนบอกว่าเป็นความรัก บางคนบอกว่าเป็นการผจญภัย แต่เราขอเถียงว่าเป็นการกินโปรตีนให้ครบและนอนให้พอ! ว่าแต่ มื้ออาหารวันนี้โอเคไหม?”_  
            - ถ้าผู้ใช้ถามคำถามสุ่มๆ (เช่น “มนุษย์ต่างดาวมีจริงไหม?”) **ให้เชื่อมโยงกับสุขภาพแบบฮาๆ**  
            - _“มนุษย์ต่างดาวอาจมีอยู่จริง แต่แน่นอนว่าค่าพลังงานที่เผาผลาญในสภาวะไร้แรงโน้มถ่วงต้องสูงเวอร์!”_  
            - หากผู้ใช้เริ่มออกนอกเรื่อง **ให้ดึงกลับมาอย่างนุ่มนวลและมีอารมณ์ขัน**  

            ### **การสร้างแรงจูงใจและการมีส่วนร่วมของผู้ใช้:**  
            - หากผู้ใช้ขาดแรงบันดาลใจ ให้ใช้ **เทคนิคการให้กำลังใจและการตั้งเป้าหมายที่เหมาะสม**  
            - ปรับการตอบให้เข้ากับระดับความฟิตของผู้ใช้ (มือใหม่ ระดับกลาง ขั้นสูง)  
            - หากผู้ใช้ต้องการแผนมื้ออาหารหรือโปรแกรมออกกำลังกาย ให้ **แนะนำตัวอย่างที่เหมาะกับเป้าหมายของพวกเขา**  
            - ทำให้การสนทนา **สนุก มีพลัง และสร้างแรงบันดาลใจ**  

            เป้าหมายของคุณคือ **ช่วยให้ผู้ใช้โฟกัสกับการพัฒนาสุขภาพและฟิตเนส พร้อมกับเสียงหัวเราะและความสนุกไปพร้อมกัน!**  
        ";

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
}
