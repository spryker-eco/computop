<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PrePlace;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
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
        $computopPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);

        $computopPaymentTransfer->setMerchantId(Config::get(ComputopConstants::MERCHANT_ID));
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $computopPaymentTransfer->setCapture(ComputopSharedConfig::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setResponse(ComputopConfig::RESPONSE_ENCRYPT_TYPE);
        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );
        $computopPaymentTransfer->setMac(
            $this->computopService->getMacEncryptedValue($computopPaymentTransfer)
        );

        $decryptedValues = $this->computopService->getEncryptedArray(
            $this->getDataSubArray($computopPaymentTransfer),
            Config::get(ComputopConstants::BLOWFISH_PASSWORD)
        );

        $length = $decryptedValues[ComputopApiConfig::LENGTH];
        $data = $decryptedValues[ComputopApiConfig::DATA];

        $computopPaymentTransfer->setData($data);
        $computopPaymentTransfer->setLen($length);
        $computopPaymentTransfer->setUrl($this->getUrlToComputop($computopPaymentTransfer->getMerchantId(), $data, $length));

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
        return $this->getActionUrl() . '?' . http_build_query([
                ComputopApiConfig::MERCHANT_ID => $merchantId,
                ComputopApiConfig::DATA => $data,
                ComputopApiConfig::LENGTH => $length,
            ]);
    }
}
