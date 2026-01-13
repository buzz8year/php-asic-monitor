<?php

namespace App\Bootstrap3\Helpers;


use App\Bootstrap3\Helpers\Elements\Select2;
use App\Model;
use App\Views\ViewInterface;

class SelectModel extends Select2 implements ViewInterface
{
    /**
     * SelectModel constructor.
     * @param Model[] $locations
     * @param array $attributes
     */
    public function __construct(array $locations, array $attributes = null)
    {
        parent::__construct($attributes);

        array_walk($locations, function($model) {
            /* @var $model \App\Model */
            $this->addOption(
                (new Elements\Option())
                    ->setTitle(sprintf("%s (%s)", $model->getName(), $model->getDescription()))
                    ->setValue($model->getId())
            );
        });

    }


}