<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Module;

use Codeception\Lib\ModuleContainer;
use SprykerTest\Shared\Testify\Helper\Environment;

class EnvironmentModule extends Environment
{
    /**
     * {@inheritDoc}
     *
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);
        $this->_initialize();
    }
}
