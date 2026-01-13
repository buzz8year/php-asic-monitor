<?php

namespace App\Bootstrap3\Helpers;


use App\Bootstrap3\Helpers\Elements\Select2;
use App\Location;
use App\Views\ViewInterface;

class SelectLocation extends Select2 implements ViewInterface
{
    /**
     * SelectModel constructor.
     * @param Location[] $locations
     * @param array $attributes
     */
    public function __construct(array $locations, array $attributes = null)
    {
        parent::__construct($attributes);

        array_walk($locations, function($location) {
            /* @var $location \App\Location */
            $this->addOption(
                (new Elements\Option())
                    ->setTitle(sprintf("%s (%s)", $location->getName(), $location->getDescription()))
                    ->setValue($location->getId())
            );
        });

    }
}