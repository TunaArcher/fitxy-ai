<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\UserFoodTableModel;
use App\Models\UserMenuModel;
use App\Models\UserWorkoutModel;

class FoodController extends BaseController
{
    private UserFoodTableModel $userFoodTableModel;
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {
        $this->userFoodTableModel = new UserFoodTableModel();
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function foodTable()
    {
        $data = [
            'content' => 'food/table',
            'title' => 'Food',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="' . base_url('app/food/table.js') . '"></script>
            '
        ];

        $data['userMenusToday'] = $this->userMenuModel->getUserMenuTodayByUserID(session()->get('user')->id);
        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['foodTable'] = $this->userFoodTableModel->getUserFoodTableByUserID(session()->get('user')->id);

        echo view('/app', $data);
    }

    public function foodGenerate()
    {

        try {

            if (getenv('CI_ENVIRONMENT') === 'development') {

                $response = [
                    'success' => 1,
                    'message' => 'สำเร็จ',
                    'data' => json_decode('{
                        "sun": {
                            "breakfast": {
                                "url": "url_to_image_sun_breakfast",
                                "menu_name": "ข้าวต้มปลากระพง",
                                "cal": "800"
                            },
                            "lunch": {
                                "url": "url_to_image_sun_lunch",
                                "menu_name": "ข้าวเหนียวไก่ย่างกับส้มตำ",
                                "cal": "1000"
                            },
                            "dinner": {
                                "url": "url_to_image_sun_dinner",
                                "menu_name": "ข้าวสวยกับแกงเขียวหวานไก่",
                                "cal": "900"
                            },
                            "snack": {
                                "url": "url_to_image_sun_snack",
                                "menu_name": "กล้วยหอมและถั่วลิสง",
                                "cal": "300"
                            }
                        },
                        "mon": {
                            "breakfast": {
                                "url": "url_to_image_mon_breakfast",
                                "menu_name": "ข้าวเหนียวหมูทอด",
                                "cal": "900"
                            },
                            "lunch": {
                                "url": "url_to_image_mon_lunch",
                                "menu_name": "ข้าวสวยกับไก่ผัดผงกะหรี่",
                                "cal": "1000"
                            },
                            "dinner": {
                                "url": "url_to_image_mon_dinner",
                                "menu_name": "สุกี้ไก่",
                                "cal": "800"
                            },
                            "snack": {
                                "url": "url_to_image_mon_snack",
                                "menu_name": "โยเกิร์ตและกราโนล่า",
                                "cal": "300"
                            }
                        },
                        "tue": {
                            "breakfast": {
                                "url": "url_to_image_tue_breakfast",
                                "menu_name": "แซนด์วิชไก่",
                                "cal": "700"
                            },
                            "lunch": {
                                "url": "url_to_image_tue_lunch",
                                "menu_name": "ข้าวผัดกุ้ง",
                                "cal": "1000"
                            },
                            "dinner": {
                                "url": "url_to_image_tue_dinner",
                                "menu_name": "ข้าวสวยกับปลาเผา",
                                "cal": "900"
                            },
                            "snack": {
                                "url": "url_to_image_tue_snack",
                                "menu_name": "ผลไม้รวม",
                                "cal": "300"
                            }
                        },
                        "wed": {
                            "breakfast": {
                                "url": "url_to_image_wed_breakfast",
                                "menu_name": "ข้าวต้มไก่",
                                "cal": "800"
                            },
                            "lunch": {
                                "url": "url_to_image_wed_lunch",
                                "menu_name": "ก๋วยเตี๋ยวไก่",
                                "cal": "900"
                            },
                            "dinner": {
                                "url": "url_to_image_wed_dinner",
                                "menu_name": "ข้าวสวยกับแกงส้มปลากะพง",
                                "cal": "900"
                            },
                            "snack": {
                                "url": "url_to_image_wed_snack",
                                "menu_name": "ชีสเค้ก",
                                "cal": "300"
                            }
                        },
                        "thu": {
                            "breakfast": {
                                "url": "url_to_image_thu_breakfast",
                                "menu_name": "โจ๊กไก่",
                                "cal": "700"
                            },
                            "lunch": {
                                "url": "url_to_image_thu_lunch",
                                "menu_name": "ข้าวผัดปลา",
                                "cal": "1000"
                            },
                            "dinner": {
                                "url": "url_to_image_thu_dinner",
                                "menu_name": "ข้าวสวยกับต้มยำไก่",
                                "cal": "900"
                            },
                            "snack": {
                                "url": "url_to_image_thu_snack",
                                "menu_name": "ขนมปังกับแยม",
                                "cal": "300"
                            }
                        },
                        "fri": {
                            "breakfast": {
                                "url": "url_to_image_fri_breakfast",
                                "menu_name": "ไข่เจียว",
                                "cal": "700"
                            },
                            "lunch": {
                                "url": "url_to_image_fri_lunch",
                                "menu_name": "ข้าวสวยกับแกงไก่",
                                "cal": "1000"
                            },
                            "dinner": {
                                "url": "url_to_image_fri_dinner",
                                "menu_name": "ชีสพาสต้า",
                                "cal": "900"
                            },
                            "snack": {
                                "url": "url_to_image_fri_snack",
                                "menu_name": "นมสด",
                                "cal": "300"
                            }
                        },
                        "sat": {
                            "breakfast": {
                                "url": "url_to_image_sat_breakfast",
                                "menu_name": "แพนเค้ก",
                                "cal": "800"
                            },
                            "lunch": {
                                "url": "url_to_image_sat_lunch",
                                "menu_name": "ข้าวสวยกับขาหมูย่าง (ใช้ไก่แทน)",
                                "cal": "1000"
                            },
                            "dinner": {
                                "url": "url_to_image_sat_dinner",
                                "menu_name": "ข้าวเย็นตาโฟ",
                                "cal": "900"
                            },
                            "snack": {
                                "url": "url_to_image_sat_snack",
                                "menu_name": "เครปผลไม้",
                                "cal": "300"
                            }
                        }
                    }', true)
                ];

                $status = 200;

                return $this->response
                    ->setStatusCode($status)
                    ->setContentType('application/json')
                    ->setJSON($response);
            }

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $query = $data->description;

            $gender = session()->get('user')->gender;
            $age = session()->get('user')->age;
            $weight = session()->get('user')->weight;
            $height = session()->get('user')->height;
            $target = session()->get('user')->target;
            $cal_per_day = session()->get('user')->cal_per_day;

            // ข้อความตอบกลับ
            $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);
            $systemMessage = <<<EOT
                คุณเป็นนักโภชนาการ AI ที่เชี่ยวชาญในการออกแบบแผนอาหารเฉพาะบุคคล คุณจะต้องสร้างตารางอาหารประจำสัปดาห์ (อาทิตย์ - เสาร์) ในรูปแบบ JSON โดยพิจารณาจากข้อมูลต่อไปนี้:

                - ข้อมูลส่วนบุคคล: เพศ, อายุ, น้ำหนัก, ส่วนสูง
                - เป้าหมายด้านโภชนาการ: เช่น ต้องการเพิ่มหรือลดน้ำหนักในอัตราที่กำหนด เช่น เพิ่ม 1 กิโลกรัม/สัปดาห์
                - พลังงานที่ต้องการต่อวัน: เช่น 3000 แคลอรี่
                - ข้อจำกัดด้านอาหาร: ไม่กินอาหารบางประเภท เช่น ไม่ชอบผักกาด ไม่กินเนื้อหมู
                - โภชนาการที่สมดุล: จัดสัดส่วนโปรตีน คาร์โบไฮเดรต และไขมันให้เหมาะสม
                - ความหลากหลายของอาหาร: ไม่ให้ซ้ำกันเกินไป และต้องมีความเป็นไปได้ในการทำอาหาร

                ### รูปแบบผลลัพธ์ที่ต้องการ (JSON)
                ให้ผลลัพธ์เป็น JSON ตามโครงสร้างนี้ และ url ภาพ ให้ใช้ตาม template ฉัน ตัวอย่างเช่น breakfast ให้ใช้ url https://cdn-icons-png.flaticon.com/512/6192/6192074.png dinner ให้ใช้ url https://cdn-icons-png.flaticon.com/512/6177/6177165.png:

                {
                    "sun": {
                        "breakfast": {
                        "url": "https://cdn-icons-png.flaticon.com/512/6192/6192074.png",
                        "menu_name": "ชื่อเมนู",
                        "cal": "จำนวน kcal"
                        },
                        "lunch": {
                        "url": "https://cdn-icons-png.flaticon.com/512/3311/3311556.png",
                        "menu_name": "ชื่อเมนู",
                        "cal": "จำนวน kcal"
                        },
                        "dinner": {
                        "url": "https://cdn-icons-png.flaticon.com/512/6177/6177165.png",
                        "menu_name": "ชื่อเมนู",
                        "cal": "จำนวน kcal"
                        },
                        "snack": {
                        "url": "https://cdn-icons-png.flaticon.com/512/859/859293.png",
                        "menu_name": "ชื่อเมนู",
                        "cal": "จำนวน kcal"
                        }
                    }
                }

                ### ข้อกำหนดเพิ่มเติม
                1. ต้องคำนึงถึงพลังงานรวมที่ต้องการต่อวัน
                2. หลีกเลี่ยงอาหารที่ผู้ใช้ไม่สามารถกินได้
                3. ใช้วัตถุดิบที่เข้าถึงได้ง่ายและเหมาะสมกับเป้าหมายทางโภชนาการ
                4. หลีกเลี่ยงเมนูที่ซ้ำกันในหนึ่งสัปดาห์

                ให้ส่งผลลัพธ์เฉพาะ JSON เท่านั้น โดยไม่มีคำอธิบายอื่นเพิ่มเติม และไม่ต้องมี Markdown format
                ให้คืนค่าข้อมูลเป็น JSON object เท่านั้น (ไม่ใช่ string) ห้าม escape JSON (`\\n`, `\\`, หรือ `\"`) หรือใส่เครื่องหมายคำพูดรอบ JSON
                ให้คืนค่า JSON ที่สามารถใช้งานได้ทันที
            EOT;

            $userMessage = <<<EOT
                จัดตารางอาหาร วันอาทิตย์ - เสาร์ (1อาทิตย์)
                ข้อมูลส่วนบุคคล: เพศ $gender, อายุ $age, น้ำหนัก $weight, ส่วนสูง $height
                เป้าหมาย: $target
                พลังงานที่ต้องการ: $cal_per_day ต่อวัน
                ข้อจำกัดด้านอาหาร: $query
            EOT;

            $aws = $chatGPT->completions($systemMessage, $userMessage);

            $response = [
                'success' => 1,
                'message' => 'สำเร็จ',
                'data' =>  json_decode($aws, true)
            ];

            $status = 200;

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            // px($e->getMessage());
        }
    }

    public function saveTable()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $foodTable = $data->foodTable;

            if ($this->updateOrCreateFoodTable($foodTable)) {

                $response = [
                    'success' => 1,
                    'message' => 'สำเร็จ',
                ];

                $status = 200;
            }

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            // px($e->getMessage() . ' ' . $e->getLine());
        }
    }

    public function updateOrCreateFoodTable($listFoodTable)
    {

        $listFoodTable = json_encode($listFoodTable, JSON_UNESCAPED_UNICODE);

        $foodTable = $this->userFoodTableModel->getUserFoodTableTodayByUserID(session()->get('user')->id);

        if (!$foodTable) {

            $foodTableID = $this->userFoodTableModel->insertUserFoodTable([
                'user_id' => session()->get('user')->id,
                'list' => $listFoodTable
            ]);

            return $this->userFoodTableModel->getUserFoodTableByID($foodTableID);
        } else {

            $this->userFoodTableModel->updateUserFoodTableByUserID(session()->get('user')->id, [
                'list' => $listFoodTable
            ]);

            return $foodTable = $this->userFoodTableModel->getUserFoodTableTodayByUserID(session()->get('user')->id);
        }
    }
}
