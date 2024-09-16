<?php
use App\Database\BaseRepository;

class CountyRepository extends BaseRepository{
    function __construct
    (
        $host = self::HOST, 
        $user = self::USER, 
        $password = self::PASSWORD, 
        $database = self::DATABASE){
            parent::__construct(host: $host, user: $user, password: $password, database: $database);
            $this->tableName = 'counties';
        }
}