<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DebugModule\Toolbar\Extension;

use Trident\Component\Debug\Toolbar\Extension\AbstractExtension;
use Trident\Component\Debug\Toolbar\Segment;
use Trident\Component\HttpKernel\AbstractKernel;

/**
 * Application Runtime Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentRuntimeExtension extends AbstractExtension
{
    protected $kernel;

    /**
     * Constructor.
     *
     * @param AbstractKernel $kernel
     */
    public function __construct(AbstractKernel $kernel)
    {
        parent::__construct();

        $this->kernel  = $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function getSegment()
    {
        $this->decorateSegment();

        return parent::getSegment();
    }

    /**
     * Decorate this extensions Segment.
     *
     * @return Segment
     */
    protected function decorateSegment()
    {
        $runtime = (microtime(true) - $this->kernel->getStartTime()) * 1000;

        $this->segment->setBaseName('Runtime');
        $this->segment->setBaseValue($runtime);
        $this->segment->setBaseUnit('ms');

        return $this->segment;
    }
}