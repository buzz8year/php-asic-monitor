<?php

$config = [
	'host' => '77.111.250.50',
	'user' => 'monitor',
	'pass' => 'monitor123',
	'db' => 'monitoring',
];

$sql = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);

if (mysqli_connect_errno()) {
	printf('Connection failed', mysqli_connect_error());
	exit;
}



$query = 'SELECT id FROM miners';
$miners = $sql->query($query);  


foreach ($miners as $miner) {

	$records = $sql->query('SELECT uptime FROM journal WHERE miner_id = ' . $miner['id'] . ' ORDER BY dtime ASC');

	// Uptime Break Points
	$ubp = [];
	$recs = [];

	if ($records->num_rows) {

		foreach ($records->fetch_all() as $record) {
			$recs[] = $record[0];
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
		$total = array_sum($ubp);

		// print $miner['id'] . ' - ' . $total . '<br/>';
		print $miner['id'] . ' - ' . ($total / 86400) . '<br/>';

	}

	unset($records, $ubp, $recs, $total);

}


$sql->close();


