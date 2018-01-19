<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Hook\Mapper\Init;

use SprykerEco\Service\Computop\ComputopServiceInterface;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Zed\Computop\ComputopConfig;

abstract class AbstractMapper implements InitMapperInterface
{
    /**
     * @var \SprykerEco\Zed\Computop\ComputopConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Service\Computop\ComputopServiceInterface
     */
    protected $computopService;

    /**
     * @param \SprykerEco\Zed\Computop\ComputopConfig $config
     * @param \SprykerEco\Service\Computop\ComputopServiceInterface $computopService
     */
    public function __construct(ComputopConfig $config, ComputopServiceInterface $computopService)
    {
        $this->config = $config;
        $this->computopService = $computopService;
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