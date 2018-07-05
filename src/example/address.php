<?php
require "../../vendor/autoload.php";

use doxadoxa\phpbitcoinaddress\BitcoinAddressGenerator;

$address = BitcoinAddressGenerator::generate();

echo sprintf("address: %s\n", $address->address());
echo sprintf("private key: %s\n", $address->privateKey(true));
echo sprintf("public key: %s\n", $address->publicKey(true));
echo strlen($address->publicKey()). PHP_EOL;
echo sprintf("json: %s\n", $address->toJson());

$testAddress = BitcoinAddressGenerator::generate(null, true);
echo sprintf("test address: %s\n", $testAddress->address());
echo sprintf("test private key: %s\n", $testAddress->privateKey(true));
echo sprintf("test json: %s\n", $testAddress->toJson());