<?php
/**
 * i-MSCP - internet Multi Server Control Panel
 * Copyright (C) 2010-2018 by Laurent Declercq <l.declercq@nuxwin.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace iMSCP\Frontend\Layout;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package iMSCP\Frontend\Layout
 */
class Module implements ConfigProviderInterface, BootstrapListenerInterface
{
    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @inheritdoc
     */
    public function onBootstrap(EventInterface $event)
    {
        /** @var ApplicationInterface $application */
        $application = $event->getTarget();
        $events = $application->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, [$this, 'onDispatchError'], 100);
    }

    /**
     * Listener for overriding of default layout on error
     *
     * @see https://github.com/zendframework/zendframework/issues/2604
     * @param MvcEvent $event
     */
    public function onDispatchError(MvcEvent $event)
    {
        $event->getViewModel()->setTemplate('layout/error.phtml');
    }
}
