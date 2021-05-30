<?php

namespace Service\Game;

use Core\Session;
use Model\Building;
use Model\UserResource;
use Session\Message;
use Service\Game\Resource;
use Session\User;

class Construction
{

    private $costs;

    private $inversedResources = [
        'sand' => 1,
        'clay' => 2,
        'metal' => 3,
        'water' => 4,
        'glass' => 5,
        'food' => 6,
        'energy' => 7
    ];

    public function build($level, $position, $buildTypeId, $cityId)
    {
        $message = new Message();
        if ($this->checkResourcesBalance($buildTypeId)) {

            $building = new Building();
            $building->setLevel($level);
            $building->setCityId($cityId);
            $building->setPosition($position);
            $building->setBuildinTypeId($buildTypeId);

            $message->setSuccessMessage('Constructions started');
            $building->save();
            $this->minusResources($buildTypeId, $level);

        } else {
            $message->setErrorMessage('Not Enought resources!');
        }
    }

    public function minusResources($buildindTypeId, $level)
    {
        $userSession = new User();
        $userId = $userSession->getAuthUserId();

        $buildingCost = $this->costs[$buildindTypeId];
        foreach ($buildingCost as $name => $value){
            $resource = new UserResource();

            $resource->loadUserResource($userId, $this->inversedResources[$name]);
            $newValue = $resource->getValue() - $value;
            $resource->setValue($newValue);
            $resource->save();
        }
    }

    public function checkResourcesBalance($buildTypeId)
    {
        $resource = new Resource();
        $this->setCosts();
        $userResources = $resource->getUserResources();
        $biuldingCost = $this->costs[$buildTypeId];
        foreach ($biuldingCost as $name => $value) {
            if ($userResources[$name] < $value) {
                return false;
            }
        }

        return true;
    }

    public function setCosts()
    {
        $this->costs = [
            1 => [
                'sand' => 100,
                'clay' => 200,
                'metal' => 150,
                'energy' => 50
            ],
            2 => [
                'clay' => 20,
                'energy' => 50,
                'water' => 200
            ],
            3 => [
                'clay' => 200,
                'energy' => 20,
                'metal' => 200
            ],
            4 => [
                'clay' => 200,
                'energy' => 20,
                'metal' => 200
            ],
            5 => [
                'clay' => 200,
                'energy' => 10,
                'metal' => 200
            ],
            6 => [
                'clay' => 200,
                'energy' => 20,
                'metal' => 200
            ],
            7 => [
                'clay' => 200,
                'energy' => 20,
                'metal' => 200
            ],
            8 => [
                'clay' => 200,
                'energy' => 200,
                'metal' => 200
            ],
            9 => [
                'clay' => 10,
                'energy' => 20,
                'metal' => 2000
            ]
        ];
    }
}