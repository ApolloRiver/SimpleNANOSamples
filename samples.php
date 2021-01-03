<?php

// ---- SETTING NANO NODE Address ----
// Make sure your node has been started and is bound to the right (external) IP
// Set enable_control=true on your nodes config
// Make sure no one else has access to the node or they can withdraw funds!
DEFINE('NODE_ADDRESS', 'IP:7076');

function curl ($post) {

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, NODE_ADDRESS);
	curl_setopt($ch, CURLOPT_HEADER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-length: '.strlen($post)));

	$output = curl_exec($ch);
	curl_close($ch);
	
	return $output;
}


/////////////
//// HOW TO CREATE A NEW USER WALLET

// 1) Generate a key
$post = '{"action": "key_create"}';
$key = json_decode(curl($post));
var_dump($key->private);
var_dump($key->public);
var_dump($key->account);

// 2) Create a new wallet
$post = '{"action": "wallet_create"}';
$wallet = json_decode(curl($post));
var_dump($wallet->wallet);

// 3) Add the generated private key to the wallet
$post = '{"action": "wallet_add", "wallet": "'.$wallet->wallet.'", "key": "'.$key->private.'"}';
$account = json_decode(curl($post));
var_dump($account->account);

//// ---------- YOU HAVE NO SUCCESSFULLY CREATED A WALLET --------------


/////////////
//// OTHER THINGS YOU CAN DO

// After someone sent you NANO it's in a pending state until it's being confirmed by the recipient
// This is how to confirm a pending block
$post = '{"action": "block_confirm", "hash": "XXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}';
$confirm = json_decode(curl($post));
var_dump($confirm);

// This is how you can see how many blocks the node is behind and if it has syncronized completely
$post = '{"action": "block_count"}';
$block_count = json_decode(curl($post));
var_dump($block_count);

// Check balance
$post = '{"action": "account_balance", "account":"nano_XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}';
$balance = json_decode(curl($post));
var_dump($balance->balance);
var_dump($balance->pending);

// List accounts
$post = '{"action": "account_list", "wallet": "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"}';
$account_list = json_decode(curl($post));
var_dump($account_list->accounts);

?>