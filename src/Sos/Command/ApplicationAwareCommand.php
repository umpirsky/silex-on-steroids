<?php

/**
 * This file is part of Silex on Steroids.
 *
 *  (c) Саша Стаменковић <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sos\Command;

use Symfony\Component\Console\Command\Command;
use Silex\Application;

/**
 * Base command.
 *
 * @author Саша Стаменковић <umpirsky@gmail.com>
 */
abstract class ApplicationAwareCommand extends Command
{
    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        parent::__construct();
    }
}
