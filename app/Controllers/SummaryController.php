<?php

namespace App\Controllers;

use App\Models\AnalyzeModel;
use App\Models\UserMenuModel;
use App\Models\UserWorkoutModel;
use DateTime;

class SummaryController extends BaseController
{
    private AnalyzeModel $analyzeModel;
    private UserMenuModel $userMenuModel;
    private UserWorkoutModel $userWorkoutModel;

    public function __construct()
    {
        $this->analyzeModel = new AnalyzeModel();
        $this->userMenuModel = new UserMenuModel();
        $this->userWorkoutModel = new UserWorkoutModel();
    }

    public function index()
    {
        $data = [
            'content' => 'summary/index',
            'title' => 'Summary',
            'css_critical' => '<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />',
            'js_critical' => '
                <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
                <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.1.0"></script>
                <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
                <script src="' . base_url('/app/summary/index.js') . '"></script>
            '
        ];

        $data['userMenusToday'] = $this->userMenuModel->getUserMenuTodayByUserID(session()->get('user')->id);
        $data['calToDay'] = $this->userMenuModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;
        $data['calBurn'] = $this->userWorkoutModel->getTotalCaloriesTodayByUserID(session()->get('user')->id)->calories_today;

        echo view('/app', $data);
    }

    public function byDate()
    {

        try {

            $response = [
                'success' => 0,
                'message' => '',
            ];

            $status = 500;

            // รับข้อมูล JSON จาก Request
            $data = $this->request->getJSON();
            $dateFormatted = DateTime::createFromFormat('d/m/Y', $data->date)->format('Y-m-d');

            // Menu
            $menuMacronutrientsToday = $this->userMenuModel->getMacronutrientsByUserIDAndDate(session()->get('user')->id, $dateFormatted);
            $menuCalories7days = $this->userMenuModel->getCalories7daysByUserID(session()->get('user')->id, $dateFormatted);

            // Workout
            $workoutSummaryToday = $this->userWorkoutModel->getWorkoutSummaryByUserIDAndDate(session()->get('user')->id, $dateFormatted);

            // analyze
            $analyze = $this->analyzeModel->getAnalyzeByUserIDAndDate(session()->get('user')->id, $dateFormatted);

            if (true) {

                $response = [
                    'success' => 1,
                    'message' => 'สำเร็จ',
                    'data' => [
                        'menuMacronutrientsToday' => $menuMacronutrientsToday,
                        'menuCalories7days' => $menuCalories7days,
                        'workoutSummaryToday' => $workoutSummaryToday,
                        'analyze' => $analyze ? $analyze->content : '',
                        'targets' => session()->get('user')->target,
                        'cal_per_day' => session()->get('user')->cal_per_day,
                        'maintenanceCal' => session()->get('user')->maintenanceCal,
                    ]
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
}
