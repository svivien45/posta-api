<?php

namespace App\Repository;

use App\Database\DB;

class BaseRepository extends DB
{
    protected $tableName;

    public function getAll() : array
    {
        $query = $this->select() . "ORDER BY name";

        return $this->mysqli
            ->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function select() : string
    {
        return "SELECT * FROM `{$this->tableName}` ";
    }
}