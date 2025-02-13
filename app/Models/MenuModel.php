<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class MenuModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getMenuAll()
    {
        $builder = $this->db->table('menus');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getMenuByID($id)
    {
        $builder = $this->db->table('menus');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertMenu($data)
    {
        $builder = $this->db->table('menus');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateMenuByID($id, $data)
    {
        $builder = $this->db->table('menus');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteMenuByID($id)
    {
        $builder = $this->db->table('menus');

        return $builder->where('id', $id)->delete();
    }

    public function getMenuByUserID($userID)
    {
        $builder = $this->db->table('menus');

        return $builder
            ->where('user_id', $userID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getMenuByPlatformAndToken($platform, $data)
    {

        $builder = $this->db->table('menus');

        switch ($platform) {
            case 'Facebook':

                return false;

                break;

            case 'Line':

                return $builder
                    ->where('platform', $platform)
                    ->where('line_channel_id', $data['line_channel_id'])
                    ->where('line_channel_secret', $data['line_channel_secret'])
                    ->get()
                    ->getRow();

                break;

            case 'WhatsApp':

                return $builder
                    ->where('platform', $platform)
                    ->where('whatsapp_token', $data['whatsapp_token'])
                    // ->where('whatsapp_phone_number_id', $data['whatsapp_phone_number_id'])
                    ->get()
                    ->getRow();

                break;

            case 'Instagram':
                break;

            case 'Tiktok':
                break;
        }
    }

    public function getMenuByPageID($platform, $pageID)
    {
        $builder = $this->db->table('menus');

        return $builder
        ->where('platform', $platform)
        ->where('page_id', $pageID)
        ->get()
        ->getRow();
    }

    public function getMenuByTeamID($teamID)
    {
        $builder = $this->db->table('menus');

        return $builder
            ->where('team_id', $teamID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

}
