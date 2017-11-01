<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;

abstract class AbstractMapper implements ApiMapperInterface
{
    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     */
    protected $computopHeaderPayment;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    abstract public function getDataSubArray(TransferInterface $computopPaymentTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    abstract protected function createPaymentTransfer(OrderTransfer $orderTransfer);

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     */
    public function __construct(
        ComputopServiceInterface $computopService,
        ComputopConfig $config,
        ComputopHeaderPaymentTransfer $computopHeaderPayment
    ) {

        $this->computopService = $computopService;
        $this->config = $config;
        $this->computopHeaderPayment = $computopHeaderPayment;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildRequest(OrderTransfer $orderTransfer)
    {
        $encryptedArray = $this->getEncryptedArray($orderTransfer);

        $data = $encryptedArray[ComputopApiConfig::DATA];
        $length = $encryptedArray[ComputopApiConfig::LENGTH];
        $merchantId = $this->config->getMerchantId();

        return $this->buildRequestData($data, $length, $merchantId);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getEncryptedArray(OrderTransfer $orderTransfer)
    {
        $computopPaymentTransfer = $this->getComputopPaymentTransfer($orderTransfer);

        $encryptedArray = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPass()
        );

        return $encryptedArray;
    }

    /**
     * @param string $data
     * @param string $length
     * @param string $merchantId
     *
     * @return array
     */
    protected function buildRequestData($data, $length, $merchantId)
    {
        $requestData = [
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
            ComputopApiConfig::MERCHANT_ID => $merchantId,
        ];

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayPalPaymentTransfer
     */
    protected function getComputopPaymentTransfer(OrderTransfer $orderTransfer)
    {
        $computopPaymentTransfer = $this->createPaymentTransfer($orderTransfer);
        $computopPaymentTransfer->fromArray($this->computopHeaderPayment->toArray(), true);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());

        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        return $computopPaymentTransfer;
    }
}
