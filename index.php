<?php
session_abort();
include './vendor/autoload.php';

use App\Html\Request;

Request::handle();