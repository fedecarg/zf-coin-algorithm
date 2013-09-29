<?php
/**
 * Coin Algorithm Application
 *
 * @link      http://github.com/fedecarg/zf-coin-algorithm
 * @author    Federico Cargnelutti <fedecarg@gmail.com>
 * @license   MIT License http://opensource.org/licenses/MIT
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Library\Currency\CurrencyLoader;

class CurrencyController extends AbstractActionController
{
    public function indexAction()
    {
        $currencyLoader = new CurrencyLoader();
        $result = array('coins' => array(), 'pence' => 0);
        $errorMessage = null;

        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $currency = $currencyLoader->getCurrencyByCode('GBP');
                $result = $currency->getMinimumNumberOfCoins($request->getPost('amount'));
            } catch (UnexpectedValueException $e) {
                $errorMessage = 'The amount entered contains invalid characters';
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
            }
        }

        return new ViewModel(array(
            'amount'       => $request->getPost('amount', null),
            'coins'        => $result['coins'],
            'pence'        => $result['pence'],
            'errorMessage' => $errorMessage
        ));
    }
}
