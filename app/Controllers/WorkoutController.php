<?php

namespace App\Controllers;

use App\Libraries\ChatGPT;
use App\Models\WorkoutModel;
use App\Models\UserMenuModel;
use App\Models\UserModel;
use App\Models\UserWorkoutModel;
use DateTime;

class WorkoutController extends BaseController
{
    private WorkoutModel $workoutModel;
    private UserModel $userModel;
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {

        $this->workoutModel = new WorkoutModel();
        $this->userModel = new UserModel();
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function index()
    {
        $data = [
            'content' => 'workout/index',
            'title' => 'Workout',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="' . base_url('app/workout/index.js') . '"></script>
            '
        ];

        $data['workouts'] = $this->workoutModel->getWorkoutAll();

        $data['userWorkouts'] = $this->userWorkoutModel->getUserWorkoutTodayByUserID(session()->get('user')->id);
        $data['caloriesToDay'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        echo view('/app', $data);
    }

    public function add()
    {
        $data = [
            'content' => 'workout/add',
            'title' => 'Workout',
            'css_critical' => '',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script src="' . base_url('app/workout/index.js') . '"></script>
            '
        ];

        $data['workouts'] = $this->workoutModel->getWorkoutAll();
        $data['userWorkouts'] = $this->userWorkoutModel->getUserWorkoutTodayByUserID(session()->get('user')->id);

        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        echo view('/app', $data);
    }

    public function save()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            $data = $this->request->getJSON();

            $userWorkout = $this->userWorkoutModel->insertUserWorkout([
                'user_id' => session()->get('user')->id,
                'workout_id' => $data->id,
                'title' => $data->title,
                'calories' => $data->calories,
                'time' => $data->time,
            ]);

            if ($userWorkout) {

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

    public function delete()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $workoutID = $data->workout_id;

            $delete = $this->userWorkoutModel->deleteUserWorkoutByID($workoutID);

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

    public function calculate()
    {

        // if (getenv('CI_ENVIRONMENT') === 'development') {

        //     $data = '{
        //         "minutes": 60,
        //         "calories": 780,
        //         "analysis": "วันนี้คุณออกกำลังกายได้ดีมาก วิ่งและต่อยมวยเป็นกิจกรรมที่ทำให้หัวใจเต้นแรงและเผาผลาญแคลอรีได้สูงเพื่อช่วยในการลดน้ำหนัก จำนวนแคลอรีที่คุณเผาผลาญในวันนี้สอดคล้องกับเป้าหมายในการลดน้ำหนัก ควรดื่มน้ำหลังออกกำลังกาย และหากต้องการเพิ่มการเผาผลาญแนะนำให้ลองเพิ่มวันออกกำลังกายด้วยกิจกรรมที่เน้นความแข็งแรงเช่นเวทเทรนนิ่งในวันอื่นๆ"
        //     }';

        //     $response = [
        //         'success' => 1,
        //         'message' => 'สำเร็จ',
        //         'data' => json_decode($data, true)
        //     ];


        //     $status = 200;

        //     return $this->response
        //         ->setStatusCode($status)
        //         ->setContentType('application/json')
        //         ->setJSON($response);
        // }

        try {

            $systemMessage = <<<EOT
                คุณคือ AI วิเคราะห์การออกกำลังกาย ทำหน้าที่ประเมินกิจกรรมการออกกำลังกายของผู้ใช้โดยอ้างอิงจากข้อมูลส่วนตัวและเป้าหมายของพวกเขา

                เมื่อได้รับข้อมูลจากผู้ใช้ ให้คุณทำการ:

                1. **สรุปการออกกำลังกายในวันนี้**
                - รวมเวลาทั้งหมดของการออกกำลังกาย (minutes)
                - ประเมินพลังงานที่ใช้ไป (calories) ตามประเภทของกิจกรรมที่ทำ

                2. **วิเคราะห์และให้คำแนะนำ (analysis)**
                - เปรียบเทียบปริมาณแคลอรี่ที่เผาผลาญกับเป้าหมายของผู้ใช้
                - แนะนำการปรับปรุงโปรแกรมออกกำลังกายหากจำเป็น
                - หากมีกิจกรรมที่อาจก่อให้เกิดอาการบาดเจ็บ ให้คำแนะนำในการป้องกัน

                **การคำนวณพลังงานที่เผาผลาญ**
                ใช้ค่า MET (Metabolic Equivalent of Task) เป็นตัวช่วยประเมินการเผาผลาญแคลอรี่ โดยสูตรที่ใช้คือ:
                แคลอรี่ที่เผาผลาญ = MET × น้ำหนักตัว (กก.) × เวลา (ชม.)

                ตัวอย่างค่า MET:
                - วิ่ง (8 km/h) = 8.00
                - ต่อยมวย = 7.00
                - ปั่นจักรยาน = 6.80
                - ว่ายน้ำ = 6.00

                **ให้ส่งผลลัพธ์กลับมาในรูปแบบ JSON เท่านั้น โดยมีโครงสร้างดังนี้:**
                {
                    \"minutes\": จำนวนรวมของเวลาการออกกำลังกาย (นาที),
                    \"calories\": จำนวนแคลอรี่ที่เผาผลาญ (kcal),
                    \"analysis\": \"ข้อความวิเคราะห์และคำแนะนำตามข้อมูลที่ได้รับ\"
                }

                **ตัวอย่างเอาต์พุตที่ AI ควรตอบ (เป็น JSON เท่านั้น):**
                {
                    \"minutes\": 60,
                    \"calories\": 700,
                    \"analysis\": \"วันนี้คุณออกกำลังกายได้ดี ครบทั้งคาร์ดิโอและการฝึกกำลัง ควรดื่มน้ำให้เพียงพอและพักผ่อนอย่างเหมาะสม ถ้าต้องการเผาผลาญเพิ่ม ลองเพิ่มเวทเทรนนิ่งเพื่อเสริมสร้างกล้ามเนื้อ ซึ่งจะช่วยให้การเผาผลาญดีขึ้นในระยะยาว!\"
                }

                **ข้อกำหนดในการตอบกลับ:**
                1. **หากวิเคราะห์คำถามแล้วในคำถามไม่ได้ระบุกิจกรรมออกกำลังกายมา ให้ส่ง JSON ว่าง กลับไป**  
            EOT;

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            $data = $this->request->getJSON();
            $query = $data->description;

            $name = session()->get('user')->name;
            $gender = session()->get('user')->gender;
            $age = session()->get('user')->age;
            $weight = session()->get('user')->weight;
            $height = session()->get('user')->height;
            $target = session()->get('user')->target;
            $cal_per_day = session()->get('user')->cal_per_day;

            // ข้อความตอบกลับ
            $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);

            $userMessage = <<<EOT
                ฉันชื่อ $name อายุ $age, น้ำหนัก $weight, ส่วนสูง $height
                มีเป้าหมาย: $target
                วันนี้ฉันออกกำลังกาย: $query
            EOT;

            $aws = $chatGPT->completions($systemMessage, $userMessage);

            // ตรวจสอบข้อมูลที่ได้รับ ถ้า JSON ว่างให้ส่ง response ไม่สำเร็จ
            $awsDecoded = json_decode($aws, true);

            if (empty($awsDecoded)) {
                $response = [
                    'success' => 0,
                    'message' => 'ไม่สำเร็จ',
                    'data' => $awsDecoded,
                ];
                $status = 200;
                return $this->response
                    ->setStatusCode($status)
                    ->setContentType('application/json')
                    ->setJSON($response);
            }

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
            // px($e->getMessage() . ' ' . $e->getLine());
        }
    }

    public function data()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            $data = $this->request->getJSON();
            $dateFormatted = DateTime::createFromFormat('d/m/Y', $data->date)->format('Y-m-d');

            $data = $this->userWorkoutModel->getUserWorkoutByUserIDAndDate(session()->get('user')->id, $dateFormatted);

            $response = [
                'success' => 1,
                'message' => 'สำเร็จ',
                'data' => $data
            ];

            $status = 200;

            return $this->response
                ->setStatusCode($status)
                ->setContentType('application/json')
                ->setJSON($response);
        } catch (\Exception $e) {
            // px($e->getMessage() . ' ' . $e->getLine());
        }
    }
}
