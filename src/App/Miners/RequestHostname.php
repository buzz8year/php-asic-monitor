<?php

namespace App\Miners;

use App\Miner;

class RequestHostname
{
    /**
     * @inheritDoc
     */
    public static function sshRequestByMinerId(int $miner_id) : array
    {
    	$miner = Miner::get($miner_id);
    	$error = "Unable to connect to " . $miner->getIp();
    	$hostname = '';

    	try {

    		if (function_exists("ssh2_connect")) {

    			session_write_close();

		        if ($ssh = @ssh2_connect($miner->getIp(), 22)) {

			        ssh2_auth_password($ssh, 'root', 'admin');
			        $stream = ssh2_exec($ssh, 'cat /config/network.conf');
			        
			        if ($string = stream_get_contents($stream)) {

				        // $hostname = explode("=", explode("\n", $string)[0])[1];
				        $hostname = explode("\n", explode("hostname=", $string)[1])[0];
				        ssh2_disconnect($ssh);

				        $error = false;
			        } 

			        else {
				        $error = "Connected. Yet, unable to resolve hostname...";
			        }
			    }
    		}

    		else {
    			$error = "Function ssh2_connect() does not exist...";
    		}

    	} catch (Exception $e) {
    		$error = $e;
    	}

    	return array(
    		'error' => $error,
    		'hostname' => $hostname,
    	);
    }

}