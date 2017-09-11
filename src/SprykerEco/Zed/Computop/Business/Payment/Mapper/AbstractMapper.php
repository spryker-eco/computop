<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Computop\Persistence\SpyPaymentComputop;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\ComputopConfig;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface;

abstract class AbstractMapper implements AbstractMapperInterface
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
     * @var \Orm\Zed\Computop\Persistence\SpyPaymentComputop
     */
    protected $savedComputopEntity;

    /**
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface $computopService
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \Orm\Zed\Computop\Persistence\SpyPaymentComputop $savedComputopEntity
     */
    public function __construct(
        ComputopToComputopServiceInterface $computopService,
        ComputopConfig $config,
        SpyPaymentComputop $savedComputopEntity
    ) {

        $this->computopService = $computopService;
        $this->config = $config;
        $this->savedComputopEntity = $savedComputopEntity;
    }

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
    abstract protected function getComputopPaymentTransfer(OrderTransfer $orderTransfer);

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    public function buildRequest(OrderTransfer $orderTransfer)
    {
        $encryptedArray = $this->getEncryptedArray($orderTransfer);

        $length = $encryptedArray[ComputopConstants::LENGTH_F_N];
        $data = $encryptedArray[ComputopConstants::DATA_F_N];
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
            ComputopConstants::MERCHANT_ID_F_N => $merchantId,
            ComputopConstants::DATA_F_N => $data,
            ComputopConstants::LENGTH_F_N => $length,
        ];

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getAmount(OrderTransfer $orderTransfer)
    {
        return $orderTransfer->getTotals()->getGrandTotal();
    }

}
