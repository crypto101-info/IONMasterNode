<?php
require_once 'coins.php';
$wallet = new jsonRPCClient('http://' . $coin['user'] . ':' . $coin['pass'] . '@' . $coin['ip'] . ':' . $coin['port'] . '/');
if (isset($wallet)) {
	try {
		$blockhash = $wallet->getblockhash((int)$_REQUEST['block']);
		$process   = $wallet->getblock($blockhash);
		foreach ($process['tx'] as $key => $value) {
			$transactionhash = $wallet->getrawtransaction($value);
			$tranX = $wallet->decoderawtransaction($transactionhash);
			if (isset($tranX['vout'])) {
				$process['trans'][$key]['tx'] = $value;
				foreach ($tranX['vout'] as $voutKey => $vout) {
					$process['trans'][$key]['vout'][$voutKey] = $vout;
				}
			}
		}
		echo json_encode($process, JSON_PRETTY_PRINT);
	}
	catch (exception $e) {
		echo $e;
	}
}