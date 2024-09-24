<?php
namespace App\Database;

class DB
{
    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = null;
    const DATABASE = 'post-api';
    protected $mysqli;

    function __construct(
        $host = 'localhost',
        $user = 'root',
        $password = null,
        $database = 'post-api')
    {
        $this->mysqli = mysqli_connect(
            $host = self::HOST,
            $user = self::USER,
            $password = self::PASSWORD,
            $database = self::DATABASE);

        if (!$this->mysqli){
            die("Connection failed: " . mysqli_connect_error());
        }
        $this->mysqli->set_charset("utf8mb4");
    }
}
