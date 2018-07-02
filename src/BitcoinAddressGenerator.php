<?php
declare(strict_types=1);

namespace doxadoxa\phpbitcoinaddress;

use Mdanter\Ecc\Crypto\Key\PrivateKey;
use Mdanter\Ecc\Crypto\Key\PrivateKeyInterface;

class BitcoinAddressGenerator
{
    private static $instance;

    private function __construct()
    {
        //
    }

    /**
     * Generate address from already existed private key or with new private key (if $pk not set).
     *
     * @param string|null $pk
     * @return BitcoinAddress
     */
    static public function generate(string $pk = null):BitcoinAddress
    {
        if (!isset(static::$instance)) {
            self::$instance = new BitcoinAddressGenerator;
        }

        return self::$instance->generateAddress($pk);
    }

    public function generateAddress(string $pk):BitcoinAddress {
        if (!$pk) {
            $privateKey = $this->generateNewPrivateKey();
        } else {
            $privateKey = $this->generateExistsPrivateKey($pk);
        }

        return new BitcoinAddress($privateKey);
    }

    public function generateNewPrivateKey()
    {
        $adapter = \Mdanter\Ecc\EccFactory::getAdapter();
        $generator = \Mdanter\Ecc\EccFactory::getSecgCurves($adapter)->generator256k1();
        $pk = $generator->createPrivateKey();
        return $pk;
    }

    public function generateExistsPrivateKey(string $pk):PrivateKeyInterface
    {
        $adapter = \Mdanter\Ecc\EccFactory::getAdapter();
        $generator = \Mdanter\Ecc\EccFactory::getSecgCurves($adapter)->generator256k1();
        return $generator->getPrivateKeyFrom(\gmp_init($pk, 16));
    }
}
