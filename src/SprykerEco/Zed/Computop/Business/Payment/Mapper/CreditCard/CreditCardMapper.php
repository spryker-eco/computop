<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Payment\Mapper\CreditCard;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;
use SprykerEco\Zed\Computop\Business\Payment\Mapper\AbstractMapper;
use SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface;

class CreditCardMapper extends AbstractMapper implements CreditCardMapperInterface
{

    /**
     * @var \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface
     */
    protected $computopService;

    /**
     * CreditCardMapper constructor.
     *
     * @param \SprykerEco\Zed\Computop\Dependency\Facade\ComputopToComputopServiceInterface $computopService
     */
    public function __construct(ComputopToComputopServiceInterface $computopService)
    {
        $this->computopService = $computopService;
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
            $this->computopService->computopMacHashHmacValue($computopCreditCardPaymentTransfer)
        );

        $data = $this->getDataAttribute($computopCreditCardPaymentTransfer);
        $len = $this->getLen($computopCreditCardPaymentTransfer);

        $requestData = [
                'MerchantID' => $orderTransfer->getComputopCreditCard()->getMerchantId(),
                'Data' => $data,
                'Len' => $len,
            ];

        return $requestData;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return bool|string
     */
    protected function getDataAttribute(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer)
    {
        $plaintext = $this->computopService->computopAuthorizationDataEncryptedValue($computopCreditCardPaymentTransfer);
        $len = $this->getLen($computopCreditCardPaymentTransfer);
        $password = Config::get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD);

        return $this->computopService->blowfishEncryptedValue($plaintext, $len, $password);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer
     *
     * @return int
     */
    protected function getLen(ComputopCreditCardPaymentTransfer $computopCreditCardPaymentTransfer)
    {
        return strlen($this->computopService->computopAuthorizationDataEncryptedValue($computopCreditCardPaymentTransfer));
    }

}
