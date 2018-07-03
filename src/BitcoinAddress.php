<?php
declare(strict_types=1);

namespace doxadoxa\phpbitcoinaddress;

use Mdanter\Ecc\Crypto\Key\PrivateKeyInterface;
use Tuupola\Base58;

class BitcoinAddress
{
    const ADDRESS_VERSION = 1;
    const MAINNET_SYMBOL = "00";
    const TESTNET_SYMBOL = "6f";

    private $privateKey;
    private $publicKey;
    private $addressVersion;
    private $address;
    private $test;

    /**
     * BitcoinAddress constructor.
     * @param PrivateKeyInterface $privateKey
     * @param bool $test
     * @param int $addressVersion
     */
    public function __construct(PrivateKeyInterface $privateKey, $test = false, int $addressVersion = self::ADDRESS_VERSION)
    {
        $this->privateKey = $privateKey;
        $this->publicKey = $privateKey->getPublicKey();
        $this->addressVersion = $addressVersion;
        $this->test = $test;
        $this->makeAddressFromPublicKey();
    }

    /**
     * Return private key for Bitcoin address.
     *
     * @param bool $isHex Use *true* to return as HEX-string
     * @return string
     */
    public function privateKey($isHex = false):string
    {
        $hex = gmp_strval($this->privateKey->getSecret(), 16);

        if (!$isHex) {
            return hex2bin($hex);
        } else {
            return $hex;
        }
    }

    /**
     * Return public key for Bitcoin address.
     *
     * @param bool $isHex Use *true* to return as HEX-string
     * @return string
     */
    public function publicKey($isHex = false):string
    {
        $public = gmp_strval($this->publicKey->getPoint()->getX(), 16) .
                gmp_strval($this->publicKey->getPoint()->getY(), 16);

        if ( strlen($public) != 128 ) {
            str_pad($public, 128, "\0");
        }

        $bin = str_pad(hex2bin($public), 65, "\04", STR_PAD_LEFT);

        if ($isHex) {
            return bin2hex($bin);
        } else {
            return $bin;
        }
    }

    /**
     * Get already set address version.
     *
     * @return int
     */
    public function addressVersion():int
    {
        return $this->addressVersion;
    }

    /**
     * Set new address version.
     *
     * @param int $addressVersion
     */
    public function setAddressVersion(int $addressVersion):void
    {
        $this->addressVersion = $addressVersion;
    }

    /**
     * Get address as HEX string
     *
     * @return string
     */
    public function address():string
    {
        if (!$this->test) {
            return $this->addressVersion . $this->address;
        } else {
            return $this->address;
        }
    }

    /**
     * Stringify for object. Return address as a HEX string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->address();
    }

    /**
     * Convert object to array with HEX strings and keys:
     * * address
     * * privateKey
     * * publicKey
     *
     * @return array
     */
    public function toArray():array
    {
        return [
            'address' => $this->address(),
            'privateKey' => $this->privateKey(true),
            'publicKey' => $this->publicKey(true)
        ];
    }

    /**
     * Convert object to JSON format.
     *
     * {"address":"","privateKey":"","publicKey":""}
     *
     * @return string
     */
    public function toJson():string
    {
        return \json_encode($this->toArray());
    }

    private function makeAddressFromPublicKey():void
    {
        $sha256 = hash('sha256', $this->publicKey(), true);
        $ripemd160 = hash('ripemd160', $sha256, true);
        var_dump($this->test);
        $exRipemd160 = hex2bin(($this->test ? self::TESTNET_SYMBOL : self::MAINNET_SYMBOL) . bin2hex($ripemd160));
        //var_dump(bin2hex($exRipemd160));die;
        $sha256_checksum = hash('sha256', $exRipemd160, true);
        $sha256_checksum_next = hash('sha256', $sha256_checksum, true);
        $checkSum = substr($sha256_checksum_next, 0,4);
        $addressIn = $exRipemd160 . $checkSum;
        $b58 = new Base58(['characters' => Base58::BITCOIN]);
        $this->address = $b58->encode($addressIn);
    }
}
