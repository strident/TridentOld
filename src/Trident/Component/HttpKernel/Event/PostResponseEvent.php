<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Component\HttpKernel\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Trident\Component\HttpKernel\HttpKernelInterface;

/**
 * Post-response Event
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class PostResponseEvent extends KernelEvent
{
}
