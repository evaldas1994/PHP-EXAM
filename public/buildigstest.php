<?php

include_once '../vendor/autoload.php';
include_once '../config.php';

$buildingObject = new \Model\Building();
$building = $buildingObject->load(1);

echo $building->getId();
echo $building->getName();
$building->setLevel(2);
$building->save();
echo $building->getLevel();