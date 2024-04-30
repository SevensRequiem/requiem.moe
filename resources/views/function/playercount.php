<?php
require_once '../../gameq/GameQ/Autoloader.php';

function getPlayerCounts() {
    $servers = array(
        array('type' => 'minecraft', 'host' => 'mc1.example.com', 'port' => 25565),
        array('type' => 'minecraft', 'host' => 'mc2.example.com', 'port' => 25565),
        array('type' => '7daystodie', 'host' => '7dtd.example.com', 'port' => 26900),
        array('type' => 'projectzomboid', 'host' => 'pz.example.com', 'port' => 16261)
    );

    $gameq = new \GameQ\GameQ();
    $gameq->addServers($servers);
    $results = $gameq->process();

    $playerCounts = array();
    foreach ($results as $server => $data) {
        if ($data['gq_online']) {
            $playerCounts[$server] = $data['gq_numplayers'];
        } else {
            $playerCounts[$server] = 'Offline';
        }
    }

    return $playerCounts;
}
?>