<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class AccountModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getAccountAll()
    {
        $builder = $this->db->table('accounts');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getAccountByID($id)
    {
        $builder = $this->db->table('accounts');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertAccount($data)
    {
        $builder = $this->db->table('accounts');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateAccountByID($id, $data)
    {
        $builder = $this->db->table('accounts');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteAccountByID($id)
    {
        $builder = $this->db->table('accounts');

        return $builder->where('id', $id)->delete();
    }

    public function getAccountByUserID($userID)
    {
        $builder = $this->db->table('accounts');

        return $builder
            ->where('user_id', $userID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getAccountByPlatformAndToken($platform, $data)
    {

        $builder = $this->db->table('accounts');

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

    public function getAccountByPageID($platform, $pageID)
    {
        $builder = $this->db->table('accounts');

        return $builder
        ->where('platform', $platform)
        ->where('page_id', $pageID)
        ->get()
        ->getRow();
    }

    public function getAccountByTeamID($teamID)
    {
        $builder = $this->db->table('accounts');

        return $builder
            ->where('team_id', $teamID) 
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

}
