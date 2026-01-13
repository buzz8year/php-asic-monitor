<?php
/**

 * Time: 16:09
 */

namespace App\Miners;


use App\Miner;
use App\Result;
use App\Validator;

class Store
{
    /**
     * Майнер, который необходимо сохранять
     * @var Miner
     */
    private $miner;

    /**
     * Store constructor.
     * @param Miner $miner
     */
    public function __construct(Miner $miner)
    {
        $this->miner = $miner;
    }

    /**
     * Проверяет объект майнера на наличие ошибок,
     * в случае их нахождения добавлять в объект результата
     * @param Result $result
     * @return Result
     */
    public function check(Result $result): Result
    {

        if (!Validator::is_ipv4($this->miner->getIp())) {
            $result->addError("IP address specified with error");
        }

        if ((int)$this->miner->getPort() <= 0 || (int)$this->miner->getPort() > 0xffff) {
            $result->addError("Port specified with error");
        }

        if (!Validator::is_mac($this->miner->getMac())) {
            $result->addError("MAC specified with error");
        }

        if ($this->miner->getModelId() <= 0) {
            $result->addError("Model specified with error");
        }

        if ($this->miner->getAllocationId() <= 0) {
            $result->addError("Location unspecified");
        }

        if (!strlen($this->miner->getName())) {
            $result->addError("Please, specified name");
        }

        if ($this->miner->getDtime() <= 86400) {
            $result->addError("Mount date and time specified with error. Please, set as mm/dd/yyyy hh:ii:dd");
        }

        if ($this->miner->getStatus() !== 0 && $this->miner->getStatus() !== 1) {
            $result->addError("Status specified with error");
        }

        return $result;
    }

    /**
     * Добавляет новый манер в БД
     * @param \PDO $pdo
     */
    public function add(\PDO $pdo): void
    {
        $sth = $pdo->prepare("
            insert into
              miners
              (
                ip,
                port,
                mac, 
                model_id,
                allocation_id,
                name,
                description,
                dtime,
                status
              ) 
              value 
              (
                :ip,
                :port,
                :mac,
                :model_id,
                :allocation_id,
                :name,
                :description,
                :dtime,
                :status
              ) 
             
        ");

        $sth->execute(array(
            "ip" => $this->miner->getIp(),
            "port" => $this->miner->getPort(),
            "mac" => $this->miner->getMac(),
            "model_id" => $this->miner->getModelId(),
            "allocation_id" => $this->miner->getAllocationId(),
            "name" => $this->miner->getName(),
            "description" => $this->miner->getDescription(),
            "dtime" => $this->miner->getDtime(),
            "status" => $this->miner->getStatus()
        ));

        $this->miner->setId((int)$pdo->lastInsertId());
    }

    /**
     * Обновляет данные в СУБД
     * @param \PDO $pdo
     */
    public function update(\PDO $pdo): void
    {
        $sth = $pdo->prepare("
            update miners
            set
              ip = :ip,
              port = :port,
              mac = :mac,
              model_id = :model_id,
              allocation_id = :allocation_id,
              name = :name,
              description = :description,
              dtime = :dtime,
              status = :status
            where id = :id
        ");

        $sth->execute(array(
            ":ip" => $this->miner->getIp(),
            ":port" => $this->miner->getPort(),
            ":mac" => $this->miner->getMac(),
            ":model_id" => $this->miner->getModelId(),
            ":allocation_id" => $this->miner->getAllocationId(),
            ":name" => $this->miner->getName(),
            ":description" => $this->miner->getDescription(),
            ":dtime" => $this->miner->getDtime(),
            ":status" => $this->miner->getStatus(),
            ":id" => $this->miner->getId()
        ));
    }
}