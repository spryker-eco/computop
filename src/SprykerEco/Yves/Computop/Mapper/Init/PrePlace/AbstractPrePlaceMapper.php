<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PrePlace;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

abstract class AbstractPrePlaceMapper extends AbstractMapper
{
    /**
     * @return string
     */
    abstract protected function getActionUrl();

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return array
     */
    abstract protected function getDataSubArray(TransferInterface $cardPaymentTransfer);

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createComputopPaymentTransfer(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setUrlNotify(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::NOTIFY_PATH_NAME))
        );
        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            $this->config->getBlowfishPassword()
        );

        $length = $decryptedValues[ComputopApiConfig::LENGTH];
        $data = $decryptedValues[ComputopApiConfig::DATA];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $computopPaymentTransfer->setUrl(
            $this->getUrlToComputop(
                $computopPaymentTransfer->getMerchantId(),
                $data,
                $length
            )
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param string $merchantId
     * @param string $data
     * @param int $length
     *
     * @return string
     */
    protected function getUrlToComputop($merchantId, $data, $length)
    {
        $queryData = [
            ComputopApiConfig::MERCHANT_ID => $merchantId,
            ComputopApiConfig::DATA => $data,
            ComputopApiConfig::LENGTH => $length,
        ];

        return $this->getActionUrl() . '?' . http_build_query($queryData);
    }
}
