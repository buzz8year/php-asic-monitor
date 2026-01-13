<?php

namespace App\EnergyConsumption;

use App\EnergyInvoice;
use App\User;

class GetEnergyInvoices4User implements GetEnergyInvoicesInterface
{
    /**
     * User for whom invoices are requested
     * @var User
     */
    private $user;

    /**
     * GetEnergyInvoices4User constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritdoc
     * @return EnergyInvoice[]
     */
    public function getInvoices(\PDO $pdo): array
    {
        if (!sizeof($this->user->getAllowedLocations())) {
            return array();
        }

        $allowed_location = $this->user->getAllowedLocations();
        array_walk($allowed_location, "intval");

        $sth = $pdo->query(sprintf("
            select * from energy_invoice 
            where location_id in (%s) 
            order by id desc
          ",
            "'".(implode("', '", $allowed_location))."'"));

        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\EnergyInvoice');
    }
    
}