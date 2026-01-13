<?php

namespace App\EnergyConsumption;

class GenerateEnergyInvoice implements GenerateEnergyInvoiceInterface
{
    /**
     * @inheritdoc
     * @return
     */
    public function generateInvoice(\PDO $pdo, array $data = [])
    {	
    	session_write_close();
    	
		if ($data) {
			$total = 0;
			$count = 0;

			$miners = $pdo->prepare('
				SELECT id 
				FROM miners 
				WHERE allocation_id = :location_id 
			');
			$miners->execute([
				'location_id' => $data['location_id'],
			]);

			$pdo->prepare('INSERT INTO energy_invoice VALUES ()')->execute();
			$invoiceID = $pdo->lastInsertId();

			foreach ($miners as $miner) {
				$records = $pdo->prepare('
					SELECT uptime 
					FROM journal 
					WHERE miner_id = :miner_id 
					AND up = 1 
					AND dtime > :start_date
					AND dtime <= :end_date
					ORDER BY dtime ASC
				');
				$records->execute([
					'miner_id' => $miner['id'],
					'start_date' => $data['from_date'],
					'end_date' => $data['to_date'],
				]);

				// Uptime Break Points
				$ubp = [];
				$recs = [];

				if (count($records = $records->fetchAll())) {
					foreach ($records as $record) {
						if ($record[0] != 0) {
							$recs[] = $record[0];
						}
						unset($record);
					}

					foreach ($recs as $key => $rec) {
						if ($key == 0) {
							$ubp[] = $rec;
							continue;
						}
						
						if ($rec < $recs[$key - 1]) {
							$ubp[] = $recs[$key - 1];
							continue;
						}
						if ($rec == end($recs)) {
							$ubp[] = $rec;
						}

						unset($rec);
					}

					array_shift($ubp);
					$mtotal = array_sum($ubp);

					if ($data['details'] === true) {
						$pdo->prepare('
							INSERT INTO uptime_record (miner_id, record_date, uptime_value, uptime_invoice, invoice_id)
							VALUES (:miner_id, :record_date, :uptime_value, :uptime_invoice, :invoice_id);
						')->execute([
							'miner_id' => $miner['id'],
							'record_date' => time(),
							'uptime_value' => $mtotal,
							'uptime_invoice' => $mtotal,
							'invoice_id' => $invoiceID,
						]);
					}

					$total += array_sum($ubp);
					$count++;

				}

				unset($records, $ubp, $recs);

			}

			if ($total > 0) {
				$pdo->prepare('
					UPDATE energy_invoice 
					SET uptime_cumulative = :uptime_cumulative, location_id = :location_id, start_date = :start_date, invoice_date = :invoice_date, miner_amount = :miner_amount
					WHERE id = :id
				')->execute([
					'uptime_cumulative' => $total,
					'miner_amount' => $count,
					'location_id' => $data['location_id'],
					'start_date' => $data['from_date'],
					'invoice_date' => $data['to_date'],
					'id' => $invoiceID,
				]);
			}

			return $invoiceID;
		}

		return null;




    	// DEPRECATED
    	// BY MEANS OF LAST STATS TABLE

		// $stats = $pdo->query('SELECT miner_id, dtime, current_uptime FROM last_stats')->fetchAll();

		// if (sizeof($stats)) {

		// 	$involvedMiners = 0;
		// 	$invoiceUptime = 0;
		// 	$invoiceID = null;

		// 	foreach ($stats as $stat) {

		// 		$prevEpoch = 0;
		// 		$prevUptime = 0;

		// 		$prevRecord = $pdo->prepare('
		// 			SELECT * FROM uptime_record WHERE miner_id = :miner_id ORDER BY id DESC LIMIT 1;
		// 		');

		// 		$prevRecord->execute([
		// 			'miner_id' => $stat['miner_id'],
		// 		]);

		// 		if ($pr = $prevRecord->fetch()) {
		// 			$prevEpoch = $pr['record_date'];
		// 			$prevUptime = $pr['uptime_value'];
		// 		}

		// 		if ($prevEpoch < $stat['dtime']) {

		// 			if (!$invoiceID) {
		// 				$pdo->prepare('INSERT INTO energy_invoice VALUES ()')->execute();
		// 				$invoiceID = $pdo->lastInsertId();
		// 			}

		// 			$involvedMiners++;
		// 			$uptimeDiff = $stat['current_uptime'] - $prevUptime;

		// 			$insertUptime = $pdo->prepare('
		// 				INSERT INTO uptime_record (miner_id, record_date, uptime_value, uptime_invoice, invoice_id)
		// 				VALUES (:miner_id, :record_date, :uptime_value, :uptime_invoice, :invoice_id);
		// 			');

		// 			$insertUptime->execute([
		// 				'miner_id' => $stat['miner_id'],
		// 				'record_date' => $stat['dtime'],
		// 				'uptime_value' => $stat['current_uptime'],
		// 				'uptime_invoice' => $uptimeDiff,
		// 				'invoice_id' => $invoiceID,
		// 			]);

		// 			$invoiceUptime += $uptimeDiff;

		// 			unset($insertUptime, $uptimeDiff);

		// 		}

		// 		unset($prevRecord, $prevUptime, $prevEpoch);
		// 	}


		// 	// if ($invoiceUptime > 0) {
		// 	if ($invoiceID) {
		// 		$pdo->prepare('
		// 			UPDATE energy_invoice SET uptime_cumulative = :uptime_cumulative, invoice_date = :invoice_date, miner_amount = :miner_amount
		// 			WHERE id = :id
		// 		')->execute([
		// 			'uptime_cumulative' => $invoiceUptime,
		// 			'miner_amount' => $involvedMiners,
		// 			'invoice_date' => time(),
		// 			'id' => $invoiceID,
		// 		]);
		// 	}

		// 	// return $pdo->lastInsertId();
		// 	return $invoiceID;
		// }

		// return null;
    }
}