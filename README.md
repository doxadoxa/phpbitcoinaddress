phpbitcoinaddress
=====
Uses for generating Bitcoin addresses in PHP.

Requires:
* php >7.2
* gmp

## How to use
Use BitcoinAddressGenerator for generating address object.
```php
$address = BitcoinAddressGenerator::generate();
```
Get address:
```php
$to = $address->address();
```
Get private key as HEX-string:
```php
$pk = $address->privateKey(true);
```