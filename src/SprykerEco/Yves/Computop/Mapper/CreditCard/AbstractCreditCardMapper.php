<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\CreditCard;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Silex\Application;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface;

abstract class AbstractCreditCardMapper implements CreditCardMapperInterface
{

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \Silex\Application
     */
    protected $application;

    /**
     * CreditCardMapper constructor.
     *
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface $computopService
     * @param \Silex\Application $application
     */
    public function __construct(ComputopToComputopServiceInterface $computopService, Application $application)
    {
        $this->computopService = $computopService;
        $this->application = $application;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    public function createComputopCreditCardPaymentTransfer(AbstractTransfer $quoteTransfer)
    {
        $computopCreditCardPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);

        $computopCreditCardPaymentTransfer->setMac(
            $this->computopService->getComputopMacHashHmacValue($computopCreditCardPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopCreditCardPaymentTransfer),
            Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD_KEY)
        );

        $length = $decryptedValues[ComputopConstants::LENGTH_F_N];
        $data = $decryptedValues[ComputopConstants::DATA_F_N];

        $computopCreditCardPaymentTransfer->setData($data);
        $computopCreditCardPaymentTransfer->setLen($length);
        $computopCreditCardPaymentTransfer->setUrl($this->getUrlToComputop($computopCreditCardPaymentTransfer, $data, $length));

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    abstract protected function getDataSubArray(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    abstract protected function createTransferWithUnencryptedValues(AbstractTransfer $quoteTransfer);

}
