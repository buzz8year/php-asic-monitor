<?php

namespace App\Miners\CallableControllers;

use App\Bootstrap3\Helpers\Pager;
use App\BreadCrumbs;
use App\ConfiguredDevice;
use App\Datetime;
use App\Db\PDOFactory;
use App\Front\{
    CallableController, CallableControllerInterface, InternalCallableControllerException
};
use App\HttpError4xxException;
use App\Layouts\LayoutInterface;
use App\Locations\GetAllLocations;
use App\Miner;
use App\Miners\{
    GetConfiguredDevices,
    Store,
    StoreConfiguredDevice,
    Views\Html\ConfiguredDevices\CreateFromView,
    Views\Html\ConfiguredDevices\EditFromView,
    Views\Html\ConfiguredDevices\IndexView,
    Views\Html\ConfiguredDevices\ShowDeiceView,
    Views\Json\AddConfiguredDeviceView
};
use App\Models\GetAllModels;
use App\Result;
use App\Strings;
use App\Utils\Request;
use App\Views\ViewInterface;

/**
 * Class ConfiguredDevices
 * @package App\Miners\CallableControllers
 */
class ConfiguredDevices extends CallableController implements CallableControllerInterface
{
    /**
     * ConfiguredDevices constructor.
     * @param array $user_input_data
     * @param LayoutInterface $layout
     * @param ViewInterface $view
     */
    public function __construct(array $user_input_data, LayoutInterface $layout, ViewInterface $view)
    {
        parent::__construct($user_input_data, $layout, $view);
        $this->getLayout()->addBreadCrumbs((new BreadCrumbs("List of active miners", "/Miners/")));
    }

    /**
     * @return IndexView | AddConfiguredDeviceView | ShowDeiceView | CreateFromView | EditFromView | ViewInterface
     */
    public function getView()
    {
        return parent::getView();
    }

    /**
     * @return $this
     */
    public function index()
    {
        $model = (new GetConfiguredDevices());
        $model
            ->setOffset(Request::filter($this->getUserInputData("offset"), Strings::UNSIGNED_INT))
            ->setLimit(20);

        $devices = $model->getDevices();
        $pager = (new Pager($model->getOffset(), $model->getLimit(), $model->getCount()));

        $this->getView()
            ->setDevices($devices)
            ->setPager($pager);

        $this->getLayout()
            ->setWindowTitle("List of reconfigured devices")
            ->setHeaderTitle("List of reconfigured devices")
            ->addBreadCrumbs(new BreadCrumbs("List of reconfigured devices"));

        return $this;
    }

    /**
     * @return $this
     */
    public function add()
    {
        $result = new Result();
        $total_devices = $processed_devices = 0;
        if (Request::isPost()) {
            $data = Request::filter($this->getUserInputData("configs"), Strings::TRIM);
            try {
                if (!mb_strlen($data)) {
                    throw new InternalCallableControllerException("No data presented in request");
                }

                /**
                 * @var array $decoded_data
                 */
                $decoded_data = json_decode($data, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    /*$h = fopen("dump.txt", "a+");
                    fwrite($h, $data);
                    fclose($h);*/
                    throw new InternalCallableControllerException("Invalid format of json data received");
                }

                foreach ($decoded_data as $simple_config) {
                    $total_devices++;

                    if (!is_array($simple_config)) {
                        $result->addError("For device no. {$total_devices} invalid data received: it's not an array");
                        continue;
                    }

                    $configured_device = new ConfiguredDevice();
                    $configured_device
                        ->setIpAddress(Request::filter($simple_config['Ip'] ?? ""))
                        ->setMacAddress(Request::filter($simple_config['MacAddr'] ?? ""))
                        ->setConfiguration(Request::filter(isset($simple_config['Config']) ? json_encode($simple_config) : ""))
                        ->setWorkerName(Request::filter($simple_config['WorkerName'] ?? ""))
                        ->setLocationId(Request::filter($simple_config['Location'] ?? 0));

                    $sub_res = new Result();
                    $sub_res = (new StoreConfiguredDevice($configured_device))->validate($sub_res);

                    if ($sub_res->hasErrors()) {
                        $result->addError($sub_res->getErrors());
                        continue;
                    } else {
                        (new StoreConfiguredDevice($configured_device))->save();
                    }

                    $processed_devices++;
                }
            } catch (InternalCallableControllerException $exc) {
                $result->addError($exc->getMessage());
            } catch (\Exception $exc) {
                $result->addError("Internal server error has occurred" . $exc->getMessage());
            }

            $this->getView()
                ->setTotalDeviceCount($total_devices)
                ->setProcessedDeviceCount($processed_devices)
                ->setResult($result);
        } else {
            $this->getView()
                ->setTotalDeviceCount($total_devices)
                ->setProcessedDeviceCount($processed_devices)
                ->setResult($result);
        }

        return $this;
    }

    /**
     * @param int|string $id
     * @return $this
     * @throws HttpError4xxException
     */
    public function view($id)
    {
        if (!$id || !($conf_device = ConfiguredDevice::get($id))->getId()) {
            throw new HttpError4xxException("Requested device not found", 404);
        }

        $offset = Request::filter($this->getUserInputData("offset"), Strings::UNSIGNED_INT);

        $this->getView()
            ->setConfDevice($conf_device)
            ->setOffset($offset);

        $this->getLayout()
            ->setWindowTitle("Configured device no. " . $conf_device->getId())
            ->setHeaderTitle("Configured device no. " . $conf_device->getId())
            ->addBreadCrumbs(new BreadCrumbs("List of reconfigured devices", "/Miners/ConfiguredDevices?offset=" . $offset))
            ->addBreadCrumbs(new BreadCrumbs("Configured device no. " . $conf_device->getId()));

        return $this;
    }

    /**
     * @param int|string s$id
     * @return ConfiguredDevices
     * @throws HttpError4xxException
     */
    public function createFrom($id)
    {
        if (($conf_device = ConfiguredDevice::get($id))->getId() < 1) {
            throw new HttpError4xxException("Requested device not found", 404);
        }

        try {
            $miner = new Miner();
            $models = (new GetAllModels())->getModels(PDOFactory::getReadPDOInstance());
            $locations = (new GetAllLocations())->getLocations(PDOFactory::getReadPDOInstance());

            $miner
                ->setIp($conf_device->getIpAddress())
                ->setMac($conf_device->getMacAddress())
                ->setAllocationId($conf_device->getLocationId())
                ->setDtime(Datetime::create_force(Strings::trim($conf_device->getAddedAt()))->getTimestamp());

            if (Request::isPost()) {
                $miner
                    ->setIp(Strings::trim($this->getUserInputData("ip")))
                    ->setPort((int)Strings::trim($this->getUserInputData("port")))
                    ->setMac(Strings::trim($this->getUserInputData("mac")))
                    ->setModelId((int)Strings::trim($this->getUserInputData("model_id")))
                    ->setAllocationId((int)$this->getUserInputData("allocation_id"))
                    ->setName(Strings::trim($this->getUserInputData("name")))
                    ->setDescription(Strings::trim($this->getUserInputData("description")))
                    ->setDtime(Datetime::create_force(Strings::trim($this->getUserInputData("dtime")))->getTimestamp())
                    ->setStatus(Strings::trim($this->getUserInputData("status")) ? 1 : 0);

                $store = new Store($miner);
                $result = $store->check($this->getView()->getResult());

                if ($result->isSuccess()) {
                    $store->add(PDOFactory::getWritePDOInstance());
                    $this->getLayout()->setLocationRedirectUri("/Miners");

                    $conf_device->setWasUsed(1);
                    (new StoreConfiguredDevice($conf_device))->save();
                }
            }

            $this->getView()
                ->setConfigDevice($conf_device)
                ->setMiner($miner)
                ->setModels($models)
                ->setLocations($locations);


        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        $this->getLayout()
            ->setWindowTitle(sprintf("Add new miner"))
            ->setHeaderTitle(sprintf("Add new miner"))
            ->addBreadCrumbs(new BreadCrumbs("Miners", "/Miners"))
            ->addBreadCrumbs(new BreadCrumbs("Add miner from configs"));

        return $this;
    }

    /**
     * @param int|string $conf_id
     * @param int|string $miner_id
     * @return $this
     * @throws HttpError4xxException
     */
    public function editFrom($conf_id, $miner_id)
    {
        $miner = Miner::get($miner_id);
        if ($miner->getId() < 1) {
            throw new HttpError4xxException("Miner not found", 404);
        }

        if (($conf_device = ConfiguredDevice::get($conf_id))->getId() < 1) {
            throw new HttpError4xxException("Requested device not found", 404);
        }

        $models = (new GetAllModels())->getModels(PDOFactory::getReadPDOInstance());
        $locations = (new GetAllLocations())->getLocations(PDOFactory::getReadPDOInstance());

        $miner
            ->setIp($conf_device->getIpAddress())
            ->setMac($conf_device->getMacAddress())
            ->setAllocationId($conf_device->getLocationId())
            ->setDtime(Datetime::create_force(Strings::trim($conf_device->getAddedAt()))->getTimestamp());

        try {
            if (Request::isPost()) {
                $miner
                    ->setIp(Strings::trim($this->getUserInputData("ip")))
                    ->setPort((int)Strings::trim($this->getUserInputData("port")))
                    ->setMac(Strings::trim($this->getUserInputData("mac")))
                    ->setModelId((int)Strings::trim($this->getUserInputData("model_id")))
                    ->setAllocationId((int)$this->getUserInputData("allocation_id"))
                    ->setName(Strings::trim($this->getUserInputData("name")))
                    ->setDescription(Strings::trim($this->getUserInputData("description")))
                    ->setDtime(Datetime::create_force(Strings::trim($this->getUserInputData("dtime")))->getTimestamp())
                    ->setStatus(Strings::trim($this->getUserInputData("status")) ? 1 : 0);

                $store = new Store($miner);
                $result = $store->check($this->getView()->getResult());

                if ($result->isSuccess()) {
                    $store->update(PDOFactory::getWritePDOInstance());
                    $this->getLayout()->setLocationRedirectUri("/Miners");

                    $conf_device->setWasUsed(1);
                    (new StoreConfiguredDevice($conf_device))->save();
                }
            }

            $this->getView()
                ->setConfigDevice($conf_device)
                ->setMiner($miner)
                ->setModels($models)
                ->setLocations($locations)
            ;
        } catch (InternalCallableControllerException $exc) {
            $this->getView()->getResult()->addError($exc->getMessage());
        }

        $this->getLayout()
            ->setWindowTitle(sprintf("Edit miner"))
            ->setHeaderTitle(sprintf("Edit miner"))
            ->addBreadCrumbs(new BreadCrumbs("Miners", "/Miners"))
            ->addBreadCrumbs(new BreadCrumbs("Edit miner from configs"));

        return $this;
    }

    /**
     * @param int|string $id
     * @return $this
     */
    public function delete($id)
    {
        if ($id) {
            $config_device = ConfiguredDevice::get($id);
            if ($config_device->getId()) {
                (new StoreConfiguredDevice($config_device))->delete();
            }
        }

        $offset = Request::filter($this->getUserInputData("offset"), Strings::UNSIGNED_INT);
        $ret_path = "/Miners/ConfiguredDevices" . ($offset ? "?offset=" . $offset : "/");
        $this->getLayout()->setLocationRedirectUri($ret_path);

        return $this;
    }
}