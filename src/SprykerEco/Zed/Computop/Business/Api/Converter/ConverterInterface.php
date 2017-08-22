<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Converter;

use GuzzleHttp\Psr7\Stream;

interface ConverterInterface
{

    /**
     * @param \GuzzleHttp\Psr7\Stream $response
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function toTransactionResponseTransfer(Stream $response);

}
