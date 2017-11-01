<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use Silex\Application;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\ComputopServiceInterface;

abstract class AbstractMapper implements MapperInterface
{
    const TRANS_ID_SEPARATOR = '-';

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \Silex\Application
     */
    protected $application;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer|\Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    abstract protected function createTransferWithUnencryptedValues(TransferInterface $quoteTransfer);

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     * @param \Silex\Application $application
     */
    public function __construct(ComputopServiceInterface $computopService, Application $application)
    {
        $this->computopService = $computopService;
        $this->application = $application;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateTransId(TransferInterface $quoteTransfer)
    {
        $parameters = [
            time(),
            rand(100, 1000),
            $quoteTransfer->getCustomer()->getCustomerReference(),
        ];

        return md5(implode(self::TRANS_ID_SEPARATOR, $parameters));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getAbsoluteUrl($path)
    {
        return Config::get(ApplicationConstants::BASE_URL_SSL_YVES) . $path;
    }

    /**
     * @return string
     */
    protected function getClientIp()
    {
        return $this->application['request']->getClientIp();
    }
}
