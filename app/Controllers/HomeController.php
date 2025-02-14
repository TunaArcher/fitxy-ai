<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\FoodTableModel;
use App\Models\MenuModel;

class HomeController extends BaseController
{
    private FoodTableModel $foodTableModel;
    private MenuModel $menuModel;

    public function __construct()
    {
        $this->foodTableModel = new FoodTableModel();
        $this->menuModel = new MenuModel();
    }

    private function Auth()
    {
        // สร้างค่า state แบบสุ่ม
        $state = bin2hex(random_bytes(16));

        // เก็บค่า state ไว้ใน Session โดยใช้ CI4
        session()->set('oauth_state', $state);

        // ใช้ค่า $state ใน URL ของ LINE Login
        $line_login_url = "https://access.line.me/oauth2/v2.1/authorize?" . http_build_query([
            "response_type" => "code",
            "client_id" => getenv('LINE_CLIENT_ID'),
            "redirect_uri" => base_url('/callback'),
            "scope" => "profile openid email",
            "state" => $state
        ]);

        return $line_login_url;
    }

    public function index()
    {
        if (session()->get('customer')) {

            $data = [
                'content' => 'home/index',
                'title' => 'Home',
                'css_critical' => '',
                'js_critical' => '
                    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                    <script src="app/home.js"></script>
                    <script src="assets/js/fitness/fitness-dashboard.js"></script>
                '
            ];

            $data['menuToday'] = $this->menuModel->getMenuTodayByCustomerID(session()->get('customer')->id);
            $data['calToDay'] = $this->menuModel->getTotalCalTodayByCustomerID(session()->get('customer')->id)->cal_today;

            echo view('/app', $data);
        } else {
            return redirect()->to($this->Auth());
        }
    }

    public function report()
    {
        $data = [
            'content' => 'home/report',
            'title' => 'Home',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="app/report.js"></script>
            '
        ];

        $data['menuToday'] = $this->menuModel->getMenuTodayByCustomerID(session()->get('customer')->id);
        $data['calToDay'] = $this->menuModel->getTotalCalTodayByCustomerID(session()->get('customer')->id)->cal_today;

        echo view('/app', $data);
    }

    public function logout()
    {
        try {

            session()->destroy();

            return redirect()->to('/login');
        } catch (\Exception $e) {
            //            echo $e->getMessage();
        }
    }

    public function menuUpdate()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $menuID = $data->menu_id;
            $newCal = $data->cal;

            $update = $this->menuModel->updateMenuByID($menuID, [
                'cal' => $newCal,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($update) {

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
            px($e->getMessage());
        }
    }

    public function menuDelete()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $menuID = $data->menu_id;

            $delete = $this->menuModel->deleteMenuByID($menuID);

            if ($delete) {

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
        }
    }

    public function foodTable()
    {
        $data = [
            'content' => 'food/table',
            'title' => 'Home',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="' . base_url('app/food/table.js') . '"></script>
            '
        ];

        $data['menuToday'] = $this->menuModel->getMenuTodayByCustomerID(session()->get('customer')->id);
        $data['calToDay'] = $this->menuModel->getTotalCalTodayByCustomerID(session()->get('customer')->id)->cal_today;
        $data['foodTable'] = $this->foodTableModel->getFoodTableByCustomerID(session()->get('customer')->id);

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

            echo 'kk';
            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $query = $data->query;

            $gender = session()->get('customer')->gender;
            $age = session()->get('customer')->age;
            $weight = session()->get('customer')->weight;
            $height = session()->get('customer')->height;
            $target = session()->get('customer')->target;
            $cal_per_day = session()->get('customer')->cal_per_day;

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
                ให้ผลลัพธ์เป็น JSON ตามโครงสร้างนี้:

                {
                    "sun": {
                        "breakfast": {
                        "url": "URL รูปภาพอาหาร",
                        "menu_name": "ชื่อเมนู",
                        "cal": "จำนวน kcal"
                        },
                        "lunch": {
                        "url": "URL รูปภาพอาหาร",
                        "menu_name": "ชื่อเมนู",
                        "cal": "จำนวน kcal"
                        },
                        "dinner": {
                        "url": "URL รูปภาพอาหาร",
                        "menu_name": "ชื่อเมนู",
                        "cal": "จำนวน kcal"
                        },
                        "snack": {
                        "url": "URL รูปภาพอาหาร",
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
            px($e->getMessage());
        }
    }

    public function foodSaveTable()
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
            px($e->getMessage() . ' ' . $e->getLine());
        }
    }

    public function updateOrCreateFoodTable($listFoodTable)
    {

        $listFoodTable = json_encode($listFoodTable, JSON_UNESCAPED_UNICODE);

        $foodTable = $this->foodTableModel->getFoodTableTodayByCustomerID(session()->get('customer')->id);

        if (!$foodTable) {

            $foodTableID = $this->foodTableModel->insertFoodTable([
                'customer_id' => session()->get('customer')->id,
                'list' => $listFoodTable
            ]);

            return $this->foodTableModel->getFoodTableByID($foodTableID);
        } else {

            $this->foodTableModel->updateFoodTableByCustomerID(session()->get('customer')->id, [
                'list' => $listFoodTable
            ]);

            return $foodTable = $this->foodTableModel->getFoodTableTodayByCustomerID(session()->get('customer')->id);
        }
    }
}
