<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Order\PostPlace;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Mapper\Order\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Provider\ComputopControllerProvider;

abstract class AbstractPostPlaceMapper extends AbstractMapper
{

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function createComputopPaymentTransfer(TransferInterface $quoteTransfer)
    {
        $computopPaymentTransfer = $this->createTransferWithUnencryptedValues($quoteTransfer);

        $computopPaymentTransfer->setMerchantId(Config::get(ComputopConstants::COMPUTOP_MERCHANT_ID));
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $computopPaymentTransfer->setCapture(ComputopSharedConfig::CAPTURE_MANUAL_TYPE);
        $computopPaymentTransfer->setResponse(ComputopConfig::RESPONSE_ENCRYPT_TYPE);
        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );

        return $computopPaymentTransfer;
    }

}
