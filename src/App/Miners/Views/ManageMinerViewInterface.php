<?php
/**

 * Date: 13.06.2018
 * Time: 23:01
 */

namespace App\Miners\Views;


use App\Location;
use App\Miner;
use App\Model;

interface ManageMinerViewInterface
{
    /**
     * Возвращает действие
     * @return string
     */
    public function getAction(): string;

    /**
     * Возвращает miner
     * @see miner
     * @return Miner
     */
    public function getMiner(): Miner;

    /**
     * Устанавливает miner
     * @see miner
     * @param Miner $miner
     * @return ManageMinerViewInterface
     */
    public function setMiner(Miner $miner): ManageMinerViewInterface;

    /**
     * Возвращает models
     * @see models
     * @return Model[]
     */
    public function getModels(): array;

    /**
     * Устанавливает models
     * @see models
     * @param Model[] $models
     * @return ManageMinerViewInterface
     */
    public function setModels(array $models): ManageMinerViewInterface;

    /**
     * Возвращает locations
     * @see locations
     * @return Location[]
     */
    public function getLocations(): array;

    /**
     * Устанавливает locations
     * @see locations
     * @param Location[] $locations
     * @return ManageMinerViewInterface
     */
    public function setLocations(array $locations): ManageMinerViewInterface;
}