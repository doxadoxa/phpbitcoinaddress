<?php
require "../../vendor/autoload.php";

use tarasdovgal\phpbitcoinaddress\BitcoinAddressGenerator;

$address = BitcoinAddressGenerator::generate('e75aaf18aa03086467bae9d64919ab376b50ac5954046aa8c52ec52ebe2ea240');

echo sprintf("address: %s\n", $address->address());
echo sprintf("private key: %s\n", $address->privateKey(true));
echo sprintf("json: %s\n", $address->toJson());