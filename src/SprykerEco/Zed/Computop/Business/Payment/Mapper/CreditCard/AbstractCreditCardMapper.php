<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapper;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface;

abstract class AbstractCreditCardMapper extends AbstractMapper implements CreditCardMapperInterface
{

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * CreditCardMapper constructor.
     *
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface $computopService
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     */
    public function __construct(ComputopToComputopServiceInterface $computopService, ComputopConfig $config)
    {
        $this->computopService = $computopService;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return ComputopConstants::PAYMENT_METHOD_CREDIT_CARD;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildRequest(OrderTransfer $orderTransfer)
    {
        $computopCreditCardPaymentTransfer = $orderTransfer->getComputopCreditCard();

        $computopCreditCardPaymentTransfer->setMac(
            $this->computopService->getComputopMacHashHmacValue($computopCreditCardPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopCreditCardPaymentTransfer),
            $this->config->getBlowfishPass()
        );

        $len = $decryptedValues[ComputopConstants::LEN_F_N];
        $data = $decryptedValues[ComputopConstants::DATA_F_N];

        $requestData = [
                ComputopConstants::MERCHANT_ID_F_N => $orderTransfer->getComputopCreditCard()->getMerchantId(),
                ComputopConstants::DATA_F_N => $data,
                ComputopConstants::LEN_F_N => $len,
            ];

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return array
     */
    abstract public function getDataSubArray(ComputopCreditCardPaymentTransfer $cardPaymentTransfer);

}
