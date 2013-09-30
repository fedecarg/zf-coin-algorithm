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
     * @throws InvalidArgumentException
     */
    public function getMinimumNumberOfCoins($targetAmount)
    {
        if (empty($targetAmount)) {
            throw new \InvalidArgumentException('The amount cannot be empty');
        }

        $pence = $this->convertAmountToPence($targetAmount);

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
     * Converts a given amount to an integer number.
     *
     * @param string $amount
     * @return integer
     * @throws InvalidArgumentException
     */
    protected function convertAmountToPence($amount)
    {
        $currencySign = $this->getCurrencySign();
        $hasCurrencySign = mb_substr($amount, 0, 1, 'utf-8') === $currencySign;

        // search and replace
        $amount = str_replace(array($currencySign, 'p'), '', $amount);
        if (! is_numeric($amount)) {
            throw new \InvalidArgumentException('The amount contains invalid characters');
        }

        // cast string to number
        $amount += 0;
        if (is_float($amount) || $hasCurrencySign) {
            $amount = sprintf("%01.2f", round($amount, 2)) * 100;
        }

        return intval($amount);
    }
}
