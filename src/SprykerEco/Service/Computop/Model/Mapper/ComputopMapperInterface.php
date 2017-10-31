<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Service\Computop\Model\Mapper;

use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ComputopMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $cardPaymentTransfer
     *
     * @return string
     */
    public function getMacEncryptedValue(TransferInterface $cardPaymentTransfer);

    /**
     * @param \Generated\Shared\Transfer\ComputopResponseHeaderTransfer $header
     *
     * @return string
     */
    public function getMacResponseEncryptedValue(ComputopResponseHeaderTransfer $header);

    /**
     * @param array $dataSubArray
     *
     * @return string
     */
    public function getDataPlaintext(array $dataSubArray);

    /**
     * Needed for payment methods without test mode on Computop server (like Credit Card)
     *
     * @param array $items
     *
     * @return string
     */
    public function getTestModeDescriptionValue(array $items);

    /**
     * @param array $items
     *
     * @return string
     */
    public function getDescriptionValue(array $items);
}
