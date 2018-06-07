<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Mapper\PostPlace;

use Generated\Shared\Transfer\ComputopHeaderPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\ComputopToStoreInterface;
use SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface;

abstract class AbstractPostPlaceMapper implements ApiPostPlaceMapperInterface
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
     * @var \SprykerEco\Zed\Computop\Dependency\ComputopToStoreInterface
     */
    protected $store;

    /**
     * @var \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $computopPaymentTransfer
     *
     * @return array
     */
    abstract public function getDataSubArray(TransferInterface $computopPaymentTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    abstract protected function createPaymentTransfer(OrderTransfer $orderTransfer);

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \SprykerEco\Zed\Computop\Dependency\ComputopToStoreInterface $store
     * @param \SprykerEco\Zed\Computop\Persistence\ComputopQueryContainerInterface $queryContainer
     */
    public function __construct(
        ComputopServiceInterface $computopService,
        ComputopConfig $config,
        ComputopToStoreInterface $store,
        ComputopQueryContainerInterface $queryContainer
    ) {
        $this->computopService = $computopService;
        $this->config = $config;
        $this->store = $store;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return array
     */
    public function buildRequest(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $encryptedArray = $this->getEncryptedArray($orderTransfer, $computopHeaderPayment);

        $data = $encryptedArray[ComputopApiConfig::DATA];
        $length = $encryptedArray[ComputopApiConfig::LENGTH];
        $merchantId = $this->config->getMerchantId();

        return $this->buildRequestData($data, $length, $merchantId);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return array
     */
    protected function getEncryptedArray(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $computopPaymentTransfer = $this->getComputopPaymentTransfer($orderTransfer, $computopHeaderPayment);

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
     * @param \Generated\Shared\Transfer\ComputopHeaderPaymentTransfer $computopHeaderPayment
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected function getComputopPaymentTransfer(OrderTransfer $orderTransfer, ComputopHeaderPaymentTransfer $computopHeaderPayment)
    {
        $computopPaymentTransfer = $this->createPaymentTransfer($orderTransfer);
        $computopPaymentTransfer->fromArray($computopHeaderPayment->toArray(), true);
        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setCurrency($this->store->getCurrencyIsoCode());

        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );
        $paymentEntity = $this->queryContainer
            ->queryPaymentByTransactionId($computopHeaderPayment->getTransId())
            ->findOne();
        $computopPaymentTransfer->setReqId($this->computopService->generateReqId($orderTransfer));
        $computopPaymentTransfer->setRefNr($paymentEntity->getReference());

        return $computopPaymentTransfer;
    }
}
