<?php
namespace YkuServer\Server;

interface Driver
{
    function run();

    function send($client_id, $data);


    function setProtocol($protocol);
}