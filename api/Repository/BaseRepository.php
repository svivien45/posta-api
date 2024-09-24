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

    public function create(array $data): ?int {
        $fields = '';
        $values = '';
        foreach ($data as $field => $value){
            if ($fields > ''){
                $fields .= ',' . $field;
            } else
                $fields .= $field;
            
            if ($values > ''){
                $values .= ',' . "'.$value'";
            } else 
                $values .= "'$value'";
        }
        $sql = "INSERT INTO '%s' (%s) VALUES (%s)";
        $sql = sprintf($sql, $this->tableName, $fields, $values);
        $this->mysqli->query($sql);

        $lastInserted = $this->mysqli->query("SELECT LAST_INSERT_ID() id;");

        return $lastInserted['id'];
    }

    public function delete(int $id){
        $query = "DELETE FROM `{$this->tableName}` WHERE id = $id";

        return $this->mysqli->query($query);
    }

    public function update(int $id, array $data){
        $set = '';
        foreach ($data as $field => $value){
            if ($set > ''){
                $set .= ", $field = '$value'";
            }else
                $set .= "$field = '$value'";
        }
        $query = "UPDATE `{$this->tableName}` SET %s WHERE id = $id;";
        $query = sprintf($query, $set);
        $this->mysqli->query($query);

        return $this->find($id);
    }

}