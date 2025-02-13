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
}
