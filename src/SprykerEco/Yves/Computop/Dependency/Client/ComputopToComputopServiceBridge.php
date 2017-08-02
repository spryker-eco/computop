<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Dependency\Client;

use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use SprykerEco\Service\Computop\ComputopServiceInterface;

class ComputopToComputopServiceBridge implements ComputopToComputopServiceInterface
{

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     */
    public function __construct(ComputopServiceInterface $computopService)
    {
        $this->computopService = $computopService;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getComputopMacHashHmacValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->computopService->getComputopMacHashHmacValue($cardPaymentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer $cardPaymentTransfer
     *
     * @return string
     */
    public function getComputopOrderDataEncryptedValue(ComputopCreditCardPaymentTransfer $cardPaymentTransfer)
    {
        return $this->computopService->getComputopOrderDataEncryptedValue($cardPaymentTransfer);
    }

    /**
     * @param array $computopResponseArray
     *
     * @return \Generated\Shared\Transfer\ComputopCreditCardResponseTransfer
     */
    public function getComputopResponseTransfer($computopResponseArray)
    {
        return $this->computopService->getComputopResponseTransfer($computopResponseArray);
    }

    /**
     * @param string $plaintext
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishEncryptedValue($plaintext, $len, $password)
    {
        return $this->computopService->getBlowfishEncryptedValue($plaintext, $len, $password);
    }

    /**
     * @param string $cipher
     * @param int $len
     * @param string $password
     *
     * @return string
     */
    public function getBlowfishDecryptedValue($cipher, $len, $password)
    {
        return $this->computopService->getBlowfishDecryptedValue($cipher, $len, $password);
    }

}
