<?php
/**
 * Coin Algorithm Application
 *
 * @link      http://github.com/fedecarg/zf-coin-algorithm
 * @author    Federico Cargnelutti <fedecarg@gmail.com>
 * @license   MIT License http://opensource.org/licenses/MIT
 */

namespace Application\Library\Currency\GBP;

use Application\Library\Currency\CurrencyAbstract;
use Application\Library\Currency\UnexpectedValueException;

class Currency extends CurrencyAbstract
{
    /**
     * ISO 4217 currency code.
     *
     * @var string $currencyCode
     */
    protected $currencyCode = 'GBP';

    /**
     * ISO 4217 currency name.
     *
     * @var string $currencyName
     */
    protected $currencyName = 'Pound sterling';

    /**
     * Symbol used as a shorthand for the currency's name.
     *
     * @var string $currencySign
     */
    protected $currencySign = '£';

    /**
     * The GBP currency is made up of pound (£) and pence (p) and there are eight
     * coins in general circulation: 1p, 2p, 5p, 10p, 20p, 50p, £1 (100p) and £2 (200p).
     *
     * This variable contains only the common £2, £1, 50p, 20p, 2p and 1p coins.
     *
     * @var array $coins
     */
    protected $coins = array(
        '£2'  => 200,
        '£1'  => 100,
        '50p' => 50,
        '20p' => 20,
        '2p'  => 2,
        '1p'  => 1
    );

    /**
     * Get currency code.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Get currency name.
     *
     * @return string
     */
    public function getCurrencyName()
    {
        return $this->currencyName;
    }

    /**
     * Get currency sign.
     *
     * @return string
     */
    public function getCurrencySign()
    {
        return $this->currencySign;
    }

    /**
     * Get list of coins.
     *
     * @return array
     */
    public function getCoins()
    {
        return $this->coins;
    }

    /**
     * Returns a list of the minimum number of coins needed to make the target amount.
     *
     * @var string $targetAmount
     * @return array
     * @throws UnexpectedValueException
     */
    public function getMinimumNumberOfCoins($targetAmount)
    {
        if (empty($targetAmount)) {
            throw new UnexpectedValueException('The amount cannot be empty');
        }

        try {
            $pence = $this->convertStringToInteger($targetAmount);
        } catch (\InvalidArgumentException $e) {
            throw new UnexpectedValueException('The amount entered contains invalid characters');
        }

        $amount = $pence;
        $coins = array();
        foreach ($this->getCoins() as $coinDenomination => $coinValue) {
            $coins[$coinDenomination] = 0;
            if ($amount && $amount >= $coinValue) {
                $total = intval($amount / $coinValue);
                $amount = $amount - ($coinValue * $total);
                $coins[$coinDenomination] = $total;
            }
        }

        return array(
            'pence' => $pence, 
            'coins' => $coins
        );
    }

    /**
     * Converts a given string to an integer number.
     *
     * @param string $string
     * @return integer
     * @throws InvalidArgumentException
     */
    protected function convertStringToInteger($string)
    {
        $currencySign = $this->getCurrencySign();
        $hasCurrencySign = mb_substr($string, 0, 1, 'utf-8') === $currencySign;

        // search and replace
        $string = str_replace(array($currencySign, 'p'), '', $string);
        if (! is_numeric($string)) {
            throw new \InvalidArgumentException('Invalid number');
        }

        // cast string to number
        $string += 0;
        if (is_float($string) || $hasCurrencySign) {
            $string = sprintf("%01.2f", round($string, 2)) * 100;
        }

        return intval($string);
    }
}
