<?php
/**
 * Coin Algorithm Application
 *
 * @link      http://github.com/fedecarg/zf-coin-algorithm
 * @author    Federico Cargnelutti <fedecarg@gmail.com>
 * @license   MIT License http://opensource.org/licenses/MIT
 */

namespace Application\Library\Currency;

use Application\Library\Currency\InvalidCurrencyException;

class CurrencyLoader
{
    /**
     * Singleton instances
     *
     * @var array $currencies
     */
    protected $currencies = array();

    /**
     * ISO 639 locales
     *
     * @var array $locales
     */
    protected $locales = array(
        'en_GB' => 'GBP'
    );


    /**
     * Returns the Currency instance for the given currency code.
     *
     * @param string $currencyCode
     * @return CurrencyAbstract
     * @throws CurrencyNotFoundException
     */
    public function getCurrencyByCode($currencyCode)
    {
        $key = strtoupper($currencyCode);
        if (isset($this->currencies[$key])) {
            return $this->currencies[$key];
        } else {
            $class = sprintf('Application\\Library\\Currency\\%s\\Currency', $key);
            if (false === class_exists($class)) {
                throw new InvalidCurrencyException(sprintf('Invalid currency code "%s"', $currencyCode));
            }
            
            $this->currencies[$key] = new $class();
            return $this->currencies[$key];
        }
    }

    /**
     * Returns the Currency instance for the given locale.
     *
     * @param string $locale
     * @return CurrencyAbstract
     * @throws CurrencyNotFoundException
     */
    public function getCurrencyByLocale($locale)
    {
        // useful, but not in the backlog :)
    }
}
