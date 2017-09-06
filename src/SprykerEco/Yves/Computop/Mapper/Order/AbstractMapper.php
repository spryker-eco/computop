<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order;

use Silex\Application;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToComputopServiceInterface;

abstract class AbstractMapper implements MapperInterface
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
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function createComputopPaymentTransfer(AbstractTransfer $quoteTransfer)
    {
        $computopPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);

        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD)
        );

        $length = $decryptedValues[ComputopConstants::LENGTH_F_N];
        $data = $decryptedValues[ComputopConstants::DATA_F_N];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $computopPaymentTransfer->setUrl($this->getUrlToComputop($computopPaymentTransfer->getMerchantId(), $data, $length));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $cardPaymentTransfer
     *
     * @return array
     */
    abstract protected function getDataSubArray(AbstractTransfer $cardPaymentTransfer);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    abstract protected function createTransferWithUnencryptedValues(AbstractTransfer $quoteTransfer);

    /**
     * TODO:remove after test if need
     *
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return string
     */
    abstract protected function getUrlToComputop($merchantId, $data, $length);

}
