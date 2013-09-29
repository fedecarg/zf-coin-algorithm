<?php
/**
 * Coin Algorithm Application
 *
 * @link      http://github.com/fedecarg/zf-coin-algorithm
 * @author    Federico Cargnelutti <fedecarg@gmail.com>
 * @license   MIT License http://opensource.org/licenses/MIT
 */

namespace Application\Library\Currency;

abstract class CurrencyAbstract
{
    /**
     * Get currency code
     *
     * @return string
     */
    abstract public function getCurrencyCode();

    /**
     * Get currency name
     *
     * @return string
     */
    abstract public function getCurrencyName();

    /**
     * Get currency sign
     *
     * @return string
     */
    abstract public function getCurrencySign();

    /**
     * Returns a list of the minimum number of coins needed to make the target amount.
     *
     * @var string $targetAmount
     * @return array
     */
    abstract public function getMinimumNumberOfCoins($targetAmount);
}
