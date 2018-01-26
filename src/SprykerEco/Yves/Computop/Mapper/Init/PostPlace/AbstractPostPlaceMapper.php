<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use SprykerEco\Yves\Computop\ComputopConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
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

        $computopPaymentTransfer->setMerchantId($this->config->getMerchantId());
        $computopPaymentTransfer->setAmount($quoteTransfer->getTotals()->getGrandTotal());
        $computopPaymentTransfer->setCurrency($this->store->getCurrencyIsoCode());
        $computopPaymentTransfer->setResponse(ComputopConfig::RESPONSE_ENCRYPT_TYPE);
        $computopPaymentTransfer->setClientIp($this->getClientIp());
        $computopPaymentTransfer->setReqId($this->generateReqId($quoteTransfer));
        $computopPaymentTransfer->setUrlFailure(
            $this->getAbsoluteUrl($this->application->path(ComputopControllerProvider::FAILURE_PATH_NAME))
        );

        return $computopPaymentTransfer;
    }
}
