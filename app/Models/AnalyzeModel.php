<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class AnalyzeModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getAnalyzeAll()
    {
        $builder = $this->db->table('analyze');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getAnalyzeByID($id)
    {
        $builder = $this->db->table('analyze');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertAnalyze($data)
    {
        $builder = $this->db->table('analyze');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateAnalyzeByID($id, $data)
    {
        $builder = $this->db->table('analyze');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteAnalyzeByID($id)
    {
        $builder = $this->db->table('analyze');

        return $builder->where('id', $id)->delete();
    }

    public function getAnalyzeByUserID($userID)
    {
        $builder = $this->db->table('analyze');

        return $builder
            ->where('user_id', $userID)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getAnalyzeByUserIDAndDate($userID, $date)
    {
        $builder = $this->db->table('analyze');

        return $builder
            ->where('user_id', $userID)
            ->where("DATE(created_at) =", $date)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getRow();
    }
}
