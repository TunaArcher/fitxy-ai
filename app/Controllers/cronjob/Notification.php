<?php

namespace App\Controllers\cronjob;

use App\Controllers\BaseController;
use App\Integrations\Line\LineClient;
use App\Libraries\ChatGPT;
use App\Models\AccountModel;
use App\Models\AnalyzeModel;
use App\Models\MessageRoomModel;
use App\Models\UserMenuModel;
use App\Models\UserModel;
use App\Models\UserWorkoutModel;
use Exception;

class Notification extends BaseController
{

    public function run()
    {
        $this->menu();
    }

    private function menu()
    {

        try {

            $accountModel = new AccountModel();
            $messageRoomModel = new MessageRoomModel();
            $userModel = new UserModel();
            $userMenuModel = new UserMenuModel();
            $userWorkoutModel = new UserWorkoutModel();
            $analyzeModel = new AnalyzeModel();

            $users = $userModel->getUserAll();
            $account = $accountModel->getAccountByID('128');

            foreach ($users as $user) {

                $menus = $userMenuModel->getUserMenuTodayByUserID($user->id);

                if ($menus) {

                    $gender = $user->gender;
                    $age = $user->age;
                    $weight = $user->weight;
                    $height = $user->height;
                    $target = $user->target;
                    $cal_per_day = $user->cal_per_day;
                    // $workouts = $this->userWorkoutModel->getUserWorkoutTodayByUserID($user->id);

                    $meneText = '';
                    foreach ($menus as $menu) {
                        $meneText .= "$menu->name แคลอรี่ $menu->calories กิโลแคลอรี่ มีโปรตีน $menu->protein ไขมัน $menu->fat คาร์โบไฮเดรต $menu->carbohydrates";
                    }

                    $chatGPT = new ChatGPT(['GPTToken' => getenv('GPT_TOKEN')]);
                    $systemMessage = <<<EOT
                        คุณคือผู้เชี่ยวชาญด้านโภชนาการและสุขภาพ ทำหน้าที่วิเคราะห์อาหารที่ผู้ใช้บริโภคในแต่ละวัน โดยมีแนวทางดังนี้:

                        1. **วิเคราะห์:** ตรวจสอบสารอาหารหลัก เช่น คาร์โบไฮเดรต โปรตีน ไขมัน น้ำตาล และโซเดียม พร้อมผลกระทบต่อสุขภาพ  
                        2. **สรุป:** เน้นจุดเด่นและข้อควรระวังของมื้ออาหารในวันนี้  
                        3. **แนะนำ:** ให้คำแนะนำสั้น ๆ และปฏิบัติได้จริง เช่น ปรับสมดุลสารอาหาร เลือกอาหารที่ดีขึ้น หรือการบริโภคในวันถัดไป  

                        **รูปแบบคำตอบ:**  
                        - **📊 วิเคราะห์อาหารวันนี้:** (แยกเป็นรายการ)  
                        - **✅ สรุป:** (กระชับแต่ครบถ้วน)  
                        - **💡 แนะนำ:** (สั้น ๆ และนำไปใช้ได้จริง)  

                        กรุณาใช้ภาษาที่เป็นมิตร กระชับ และสร้างแรงจูงใจให้ผู้ใช้ดูแลสุขภาพของตนเอง
                    EOT;
                    $question = <<<EOT
                        วิเคราะห์และสรุปการกินอาหารในวันนี้ของฉัน
                        ข้อมูลส่วนบุคคล: เพศ $gender, อายุ $age, น้ำหนัก $weight, ส่วนสูง $height
                        เป้าหมาย: $target
                        พลังงานที่ต้องการ: $cal_per_day ต่อวัน
                        อาหารที่ทานในวันนี้: $meneText
                    EOT;

                    $messageRoom = $messageRoomModel->getMessageRoomByUserID($user->id);

                    $replyMessage = $chatGPT->askChatGPTWithSystemMessage($messageRoom->id, $question, $systemMessage);

                    $line = new LineClient([
                        'id' => $account->id,
                        'accessToken' =>  $account->line_channel_access_token,
                        'channelID' =>  $account->line_channel_id,
                        'channelSecret' =>  $account->line_channel_secret,
                    ]);

                    $analyzeModel->insertAnalyze([
                        'user_id' => $user->id,
                        'content' => $replyMessage,
                    ]);

                    $line->pushMessage($user->uid, $replyMessage, 'text');
                    // หน่วงเวลาสุ่มระหว่าง 3-10 วินาที
                    sleep(rand(3, 10));
                }
            }
        } catch (\Exception $e) {
            // echo $e->getMessage() . ' ' . $e->getLine();
        }
    }
}
