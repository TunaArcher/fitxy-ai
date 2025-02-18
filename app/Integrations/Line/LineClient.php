<?php

namespace App\Integrations\Line;

use App\Models\AccountModel;
use \GuzzleHttp\Client;
use \GuzzleHttp\Handler\CurlHandler;
use \GuzzleHttp\HandlerStack;
use \GuzzleHttp\Middleware;
use \Psr\Http\Message\RequestInterface;
use \Psr\Http\Message\ResponseInterface;

class LineClient
{
    private $http;
    private $baseURL;
    private $id;
    private $accessToken;
    private $channelID;
    private $channelSecret;
    private $debug = false;

    public function __construct($config = [])
    {
        $this->baseURL = 'https://api.line.me/v2';

        $this->id = $config['id'] ?? '';

        $this->accessToken = $config['accessToken'] ?? '';

        $this->channelID = $config['channelID'] ?? '';

        $this->channelSecret = $config['channelSecret'] ?? '';

        $stack = new HandlerStack();

        $stack->setHandler(new CurlHandler());

        $stack->push(Middleware::mapRequest(function (RequestInterface $Request) {
            $request = $Request;

            $this->lastRequest = $request;

            return $request;
        }));

        $stack->push(Middleware::mapResponse(function (ResponseInterface $Response) {

            $statusCode = $Response->getStatusCode();

            if ($statusCode === 401) {

                $getAccessToken = $this->accessToken();

                if ($getAccessToken) {

                    $accountModel = new AccountModel();
                    $accountModel->updateAccountByID($this->id, [
                        'line_channel_access_token' => $getAccessToken->access_token,
                        'is_connect' => '1',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    $lastRequest = $this->lastRequest;

                    $url = (string) $lastRequest->getUri();
                    $method = (string) $lastRequest->getMethod();
                    // $header = $lastRequest->getHeaders();
                    $body = $lastRequest->getBody();

                    $response = $this->http->request($method, $url, [
                        'headers' => [
                            'Authorization' => "Bearer " . $this->accessToken
                        ],
                        'body' => $body,
                    ]);

                    return $response;
                } else {
                    $accountModel = new AccountModel();
                    $accountModel->updateAccountByID($this->id, [
                        'is_connect' => '0',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            return $Response;
        }));

        $option = ['handler' => $stack];

        $this->http = new Client($option);
    }

    public function setDebug($value)
    {
        $this->debug = $value;
    }

    /*********************************************************************
     * 1. Message | ส่งข้อความ
     */

    // public function pushMessage($to, $messages, $message_type)
    // {
    //     try {
    //         $message = [];

    //         //  if ($message_type == 'image') {
    //         //      $message = [
    //         //          "type" => "image",
    //         //          "originalContentUrl" => $messages,
    //         //          "previewImageUrl" => $messages
    //         //      ];
    //         //  } elseif ($message_type == 'flex') {
    //         //      $message = [
    //         //          "type" => "flex",
    //         //          "altText" => "ข้อมูลอาหาร", // สามารถเปลี่ยนเป็นข้อความที่ต้องการ
    //         //          "contents" => $messages
    //         //      ];
    //         //  } else {
    //         //      $message = [
    //         //          'type' => 'text',
    //         //          'text' => $messages
    //         //      ];
    //         //  }

    //         switch ($message_type) {
    //             case 'image':
    //                 $message = [
    //                     "type" => "image",
    //                     "originalContentUrl" => $messages,
    //                     "previewImageUrl" => $messages
    //                 ];
    //                 break;
    //             case 'flex':
    //                 $message = [
    //                     "type" => "flex",
    //                     "altText" => "ข้อมูลอาหาร",
    //                     "contents" => $messages
    //                 ];
    //                 break;
    //             default:
    //                 $message = [
    //                     'type' => 'text',
    //                     'text' => $messages,
    //                     'quickReply' => [ // ย้าย quickReply มาที่นี่
    //                         "items" => [
    //                             [
    //                                 "type" => "action",
    //                                 "action" => [
    //                                     "type" => "camera",
    //                                     "label" => "ถ่ายอาหาร"
    //                                 ]
    //                             ],
    //                             [
    //                                 "type" => "action",
    //                                 "action" => [
    //                                     "type" => "uri",
    //                                     "label" => "ออกกำลังกาย",
    //                                     "uri" => base_url('workout')
    //                                 ]
    //                             ],
    //                             [
    //                                 "type" => "action",
    //                                 "action" => [
    //                                     "type" => "uri",
    //                                     "label" => "รายงานการกิน",
    //                                     "uri" => base_url('menu')
    //                                 ]
    //                             ],
    //                             [
    //                                 "type" => "action",
    //                                 "action" => [
    //                                     "type" => "message",
    //                                     "label" => "คิดแปป",
    //                                     "text" => "คิดอีกแปปป"
    //                                 ]
    //                             ],
    //                             [
    //                                 "type" => "action",
    //                                 "action" => [
    //                                     "type" => "message",
    //                                     "label" => "วิเคราะห์",
    //                                     "text" => "กำลังทำเด้อ"
    //                                 ]
    //                             ]
    //                         ]
    //                     ]
    //                 ];
    //         }
    //         $endPoint = $this->baseURL . '/bot/message/push/';

    //         $headers = [
    //             'Authorization' => "Bearer " . $this->accessToken,
    //             'Content-Type' => 'application/json',
    //         ];

    //         $data = [
    //             'to' => $to,
    //             'messages' => [
    //                 $message
    //             ]
    //         ];

    //         $response = $this->http->request('POST', $endPoint, [
    //             'headers' => $headers,
    //             'json' => $data, // ใช้ 'json' เพื่อแปลงข้อมูลให้อยู่ในรูปแบบ JSON
    //         ]);

    //         $responseData = json_decode($response->getBody());

    //         // ตรวจสอบสถานะ HTTP Code และข้อมูลใน Response
    //         $statusCode = $response->getStatusCode();
    //         if ($statusCode === 200 || isset($responseData->statusCode) && (int)$responseData->statusCode === 0) {
    //             return true; // ส่งข้อความสำเร็จ
    //         }

    //         // กรณีส่งข้อความล้มเหลว
    //         log_message('error', "Failed to send message to Line API: " . json_encode($responseData));
    //         return false;
    //     } catch (\Exception $e) {
    //         // จัดการข้อผิดพลาด
    //         log_message('error', 'LineClient::pushMessage error {message}', ['message' => $e->getMessage()]);
    //         return false;
    //     }
    // }

    public function pushMessage($to, $messages, $message_type)
    {
        try {
            $message = [];
            $quickReply = [ // Quick Reply
                "items" => [
                    [
                        "type" => "action",
                        "action" => [
                            "type" => "camera",
                            "label" => "ถ่ายอาหาร"
                        ]
                    ],
                    [
                        "type" => "action",
                        "action" => [
                            "type" => "uri",
                            "label" => "ออกกำลังกาย",
                            "uri" => base_url('workout')
                        ]
                    ],
                    [
                        "type" => "action",
                        "action" => [
                            "type" => "uri",
                            "label" => "รายงานการกิน",
                            "uri" => base_url('menu')
                        ]
                    ],
                    [
                        "type" => "action",
                        "action" => [
                            "type" => "message",
                            "label" => "คิดแปป",
                            "text" => "..."
                        ]
                    ],
                    [
                        "type" => "action",
                        "action" => [
                            "type" => "message",
                            "label" => "วิเคราะห์",
                            "text" => "วิเคราะห์"
                        ]
                    ]
                ]
            ];

            switch ($message_type) {
                case 'image':
                    $message = [
                        "type" => "image",
                        "originalContentUrl" => $messages,
                        "previewImageUrl" => $messages
                        // ❌ **ไม่สามารถใช้ quickReply ได้**
                    ];
                    break;
                case 'flex':
                    $message = [
                        "type" => "flex",
                        "altText" => "ข้อมูลอาหาร",
                        "contents" => $messages,
                        "quickReply" => $quickReply // ✅ ใส่ quickReply ใน flex message
                    ];
                    break;
                default:
                    $message = [
                        'type' => 'text',
                        'text' => $messages,
                        'quickReply' => $quickReply // ✅ ใส่ quickReply ใน text message
                    ];
            }

            $endPoint = $this->baseURL . '/bot/message/push/';

            $headers = [
                'Authorization' => "Bearer " . $this->accessToken,
                'Content-Type' => 'application/json',
            ];

            $data = [
                'to' => $to,
                'messages' => [$message], // ✅ quickReply อยู่ใน messages
            ];

            $response = $this->http->request('POST', $endPoint, [
                'headers' => $headers,
                'json' => $data,
            ]);

            $responseData = json_decode($response->getBody());

            // ตรวจสอบสถานะ HTTP Code และข้อมูลใน Response
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200 || isset($responseData->statusCode) && (int)$responseData->statusCode === 0) {
                return true; // ส่งข้อความสำเร็จ
            }

            // กรณีส่งข้อความล้มเหลว
            log_message('error', "Failed to send message to Line API: " . json_encode($responseData));
            return false;
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            log_message('error', 'LineClient::pushMessage error {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function replyMessage($replyToken, $messages)
    {
        try {

            $message = [
                'type' => 'text',
                'text' => $messages
            ];

            $endPoint = $this->baseURL . '/bot/message/reply/';

            $headers = [
                'Authorization' => "Bearer " . $this->accessToken,
                'Content-Type' => 'application/json',
            ];

            $data = [
                'replyToken' => $replyToken,
                'messages' => [
                    $message
                ],
            ];

            $response = $this->http->request('POST', $endPoint, [
                'headers' => $headers,
                'json' => $data, // ใช้ 'json' เพื่อแปลงข้อมูลให้อยู่ในรูปแบบ JSON
            ]);

            $responseData = json_decode($response->getBody());

            // ตรวจสอบสถานะ HTTP Code และข้อมูลใน Response
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200 || isset($responseData->statusCode) && (int)$responseData->statusCode === 0) {
                return true; // ส่งข้อความสำเร็จ
            }

            // กรณีส่งข้อความล้มเหลว
            log_message('error', "Failed to send message to Line API: " . json_encode($responseData));
            return false;
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            log_message('error', 'LineClient::replyMessage error {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /*********************************************************************
     * 1. Profile | ดึงข้อมูล
     */

    public function getUserProfile($UID)
    {
        try {

            $endPoint = $this->baseURL . '/bot/profile/' . $UID;

            $headers = [
                'Authorization' => "Bearer " . $this->accessToken,
            ];

            // ส่งคำขอ GET ไปยัง API
            $response = $this->http->request('GET', $endPoint, [
                'headers' => $headers
            ]);

            $responseData = json_decode($response->getBody());

            // ตรวจสอบสถานะ HTTP Code และข้อมูลใน Response
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
                return $responseData;
            }

            // กรณีส่งข้อความล้มเหลว
            log_message('error', "Failed to get profile from Line API: " . json_encode($responseData));
            return false;
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            log_message('error', 'LineClient::getProfile error {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /*********************************************************************
     * 1. Access Token | ดึง Token
     * ใช้สำหรับเพื่อที่จะไม่ให้ยูสเซอร์ยุ่งยาก ไปเอา Token มาใส่ในระบบเอง เราสามารถทำเป็น Auto โดยส่ง Channel ID กับ Channel Secret ไป
     * แต่มีอายุใช้งานแค่ 30 วัน
     * จึงใช้ Middleware ว่าถ้าหาก Token หมดอายุ ให้ AccessToekn() อีกครั้ง
     */

    public function accessToken()
    {
        try {

            $endPoint = $this->baseURL . '/oauth/accessToken';

            $response = $this->http->request('POST', $endPoint, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->channelID,
                    'client_secret' => $this->channelSecret,
                ]
            ]);

            $responseData = json_decode($response->getBody());

            // ตรวจสอบสถานะ HTTP Code และข้อมูลใน Response
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200 || isset($responseData->statusCode)) {
                return $responseData;
            }

            // กรณีส่งข้อความล้มเหลว
            log_message('error', "Failed to get access token from Line API: " . json_encode($responseData));
            return false;
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            log_message('error', 'LineClient::accessToken error {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function startLoadingAnimation($userId, $seconds = 5)
    {
        try {
            // ตั้งค่า Endpoint ของ LINE API
            $endPoint = $this->baseURL . '/bot/chat/loading/start';

            $headers = [
                'Authorization' => "Bearer " . $this->accessToken,
                'Content-Type' => 'application/json',
            ];

            $data = [
                'chatId' => $userId, // ระบุ userId หรือ groupId ที่ต้องการแสดง Animation
                'loadingSeconds' => $seconds, // ตั้งค่าระยะเวลาการแสดง (5-60 วินาที)
            ];

            // ส่ง Request ไปที่ LINE API
            $response = $this->http->request('POST', $endPoint, [
                'headers' => $headers,
                'json' => $data, // ใช้ 'json' เพื่อแปลงข้อมูลให้อยู่ในรูปแบบ JSON
            ]);

            $responseData = json_decode($response->getBody());

            // ตรวจสอบสถานะ HTTP Code และข้อมูลใน Response
            $statusCode = $response->getStatusCode();

            if ($statusCode === 202 || isset($responseData->statusCode) && (int)$responseData->statusCode === 200) {
                return true; // ส่งคำขอสำเร็จ
            }

            // กรณีเรียก API ล้มเหลว
            log_message('error', "Failed to start loading animation: " . json_encode($responseData));
            return false;
        } catch (\Exception $e) {
            // จัดการข้อผิดพลาด
            log_message('error', 'LineClient::startLoadingAnimation error {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
