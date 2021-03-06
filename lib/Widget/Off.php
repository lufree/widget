<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget;

/**
 * Remove event handlers by specified type
 *
 * This widget is the alias of `$widget->eventManager->remove()`
 *
 * @author      Twin Huang <twinhuang@qq.com>
 * @property    EventManager $eventManager The event manager widget
 */
class Off extends AbstractWidget
{
    /**
     * Remove event handlers by specified type
     *
     * param string $type The type of event
     * @return EventManager
     */
    public function __invoke($type)
    {
        return $this->eventManager->remove($type);
    }
}
