<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api;

interface ComputopBusinessApiFactoryInterface
{
    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    public function createAuthorizeAdapter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    public function createReverseAdapter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    public function createInquireAdapter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    public function createCaptureAdapter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Adapter\AdapterInterface
     */
    public function createRefundAdapter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createAuthorizeConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createReverseConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createInquireConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createCaptureConverter();

    /**
     * @return \SprykerEco\Zed\Computop\Business\Api\Converter\ConverterInterface
     */
    public function createRefundConverter();
}
