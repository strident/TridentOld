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

/**
 * Memory Usage Debug Toolbar Extension
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class TridentControllerExtension extends AbstractExtension
{
    private $action;
    private $controller;
    private $statusCode;

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
    private function decorateSegment()
    {
        $this->segment->setBaseName('Controller');
        $this->segment->setBaseValue("{$this->controller}::{$this->action}");
        $this->segment->setBaseIndicator($this->statusCode);
        $this->segment->setBaseIndicatorColor($this->getIndicatorColorForStatusCode($this->statusCode));

        return $this->segment;
    }

    private function getIndicatorColorForStatusCode($statusCode)
    {
        if ( ! is_int($statusCode)) {
            throw new \RuntimeException(sprintf('Status code "%s" is not an integer.', $statusCode));
        }

        switch (true) {
            case $statusCode >= 500:
                return Segment::RED;
            case $statusCode >= 400:
                return Segment::YELLOW;
            default:
                return Segment::GREEN;
        }
    }

    /**
     * Set action.
     *
     * @param string $action
     *
     * @return TridentControllerExtension
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set controller.
     *
     * @param string $controller
     *
     * @return TridentControllerExtension
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * Get controller.
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set status code.
     *
     * @param integer $statusCode
     *
     * @return TridentControllerExtension
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get status code.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}