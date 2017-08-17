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
        $encryptedArray = $this->getEncryptedArray($orderTransfer);

        $len = $encryptedArray[ComputopConstants::LEN_F_N];
        $data = $encryptedArray[ComputopConstants::DATA_F_N];
        $merchantId = $this->getMerchantId($orderTransfer);

        return $this->buildRequestData($data, $len, $merchantId);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getEncryptedArray(OrderTransfer $orderTransfer)
    {
        $computopCreditCardPaymentTransfer = $this->getComputopPaymentTransfer($orderTransfer);

        $encryptedArray = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopCreditCardPaymentTransfer),
            $this->config->getBlowfishPass()
        );

        return $encryptedArray;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    protected function getComputopPaymentTransfer(OrderTransfer $orderTransfer)
    {
        $computopCreditCardPaymentTransfer = $orderTransfer->getComputopCreditCard();

        $computopCreditCardPaymentTransfer->setMac(
            $this->computopService->getComputopMacHashHmacValue($computopCreditCardPaymentTransfer)
        );

        return $computopCreditCardPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function getMerchantId(OrderTransfer $orderTransfer)
    {
        return $orderTransfer->getComputopCreditCard()->getMerchantId();
    }

    /**
     * @param string $data
     * @param string $len
     * @param string $merchantId
     *
     * @return array
     */
    protected function buildRequestData($data, $len, $merchantId)
    {
        $requestData = [
            ComputopConstants::MERCHANT_ID_F_N => $merchantId,
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
