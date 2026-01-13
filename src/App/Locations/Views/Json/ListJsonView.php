<?php

namespace App\Locations\Views\Json;

use App\Location;
use App\Model;
use App\Views\JsonView;
use App\Views\ViewInterface;

class ListJsonView extends JsonView implements ViewInterface, \JsonSerializable
{
	/**
     * Локации для Select
     * @var Location[]
     */
    protected $locations = array();
	
	/**
	 * @return Location[]
	 */
	public function getLocations(): array
	{
		return $this->locations;
	}
	
	/**
	 * @param Location[] $locations
	 * @return $this
	 */
	public function setLocations(array $locations): ListJsonView
	{
		$this->locations = $locations;
		return $this;
	}
	
	/**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(            
            "locations" => $this->getLocations(),
        );
    }
}