<?php

namespace Model\Collection;

use Core\Db;
use Model\User;
class Users
{
    private $collection;
    private $db;

    public function __construct()
    {
        $this->db = new Db();
        $this->db->select()->from(User::TABLE_NAME);
    }

    public function getCollection()
    {
        $result = $this->db->get();
        foreach ($result as $line){
            $user = new User();
            $this->collection[] = $user->load($line[User::ID_COLUMN]);
        }

        return $this->collection;
    }

    public function addNameFilter($name)
    {
        $this->db->where(User::NAME_COLUMN, $name);
    }




}