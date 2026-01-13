<?php

namespace App\Miners\Views\Json;

use App\Views\JsonView;
use App\Views\ViewInterface;

/**
 * Class AddConfiguredDeviceView
 * @package App\Miners\Views\Json
 */
class AddConfiguredDeviceView extends JsonView implements ViewInterface, \JsonSerializable
{
    /**
     * @var int $total_device_count
     */
    protected $total_device_count = 0;

    /**
     * @var int $processed_device_count
     */
    protected $processed_device_count = 0;

    /**
     * @return int
     */
    public function getTotalDeviceCount(): int
    {
        return $this->total_device_count;
    }

    /**
     * @param int $total_device_count
     * @return $this
     */
    public function setTotalDeviceCount(int $total_device_count): AddConfiguredDeviceView
    {
        $this->total_device_count = $total_device_count;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcessedDeviceCount(): int
    {
        return $this->processed_device_count;
    }

    /**
     * @param int $processed_device_count
     * @return $this
     */
    public function setProcessedDeviceCount(int $processed_device_count): AddConfiguredDeviceView
    {
        $this->processed_device_count = $processed_device_count;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array("result" => $this->getResult(), "total_dev_count" => $this->total_device_count, "processed_dev_count" => $this->processed_device_count);
    }

}