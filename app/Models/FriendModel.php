<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

class FriendModel
{

    protected $db;

    public function __construct()
    {
        $db = \Config\Database::connect();
        $this->db = &$db;
    }

    public function getFriendAll()
    {
        $builder = $this->db->table('friends');

        return $builder
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();
    }

    public function getFriendByID($id)
    {
        $builder = $this->db->table('friends');

        return $builder->where('id', $id)->get()->getRow();
    }

    public function insertFriend($data)
    {
        $builder = $this->db->table('friends');

        return $builder->insert($data) ? $this->db->insertID() : false;
    }

    public function updateFriendByID($id, $data)
    {
        $builder = $this->db->table('friends');

        return $builder->where('id', $id)->update($data);
    }

    public function deleteFriendByID($id)
    {
        $builder = $this->db->table('friends');

        return $builder->where('id', $id)->delete();
    }

    public function getFriendByUserID($userID)
    {
        $builder = $this->db->table('friends');

        $sql = "
            SELECT users.* FROM friends
            JOIN users ON users.id = friends.friend_id
            WHERE user_id = '$userID'
        ";

        $builder = $this->db->query($sql);

        return $builder->getResult();

    }

    public function getAlreadyFriend($userID, $inviteCode)
    {
        $builder = $this->db->table('friends');

        return $builder->where('user_id', $userID)->where('friend_id', $inviteCode)->get()->getRow();
    }
}
