<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Toggle;

use Psr\Log\LoggerInterface;

/**
 * Manages the toggles of an application.
 */
class ToggleManager
{
    /**
     * @var ToggleCollection
     */
    private $collection;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ToggleCollection $collection
     * @param LoggerInterface  $logger
     */
    public function __construct(ToggleCollection $collection, LoggerInterface $logger)
    {
        $this->collection = $collection;
        $this->logger = $logger;
    }

    /**
     * @param string  $name
     * @param Context $context
     *
     * @return True, if the toggle exists and is active
     */
    public function active($name, Context $context)
    {
        if (null === $toggle = $this->collection->get($name)) {
            $this->logger->notice(sprintf(
                "Toggle %s does not exist in the toggle collection",
                $name
            ));

            return false;
        }

        return $toggle->activeFor($context);
    }

    /**
     * Removes the toggle from the manager.
     *
     * @param string $name
     *
     * @return boolean True, if element was removed
     */
    public function remove($name)
    {
        if ($this->collection->remove($name)) {
            $this->logger->info(sprintf(
                "Toggle %s was removed from the toggle collection",
                $name
            ));

            return true;
        }

        return false;
    }

    /**
     * Add the toggle to the manager.
     *
     * @param Toggle $toggle
     */
    public function add(Toggle $toggle)
    {
        $this->collection->set($toggle->getName(), $toggle);
        $this->logger->info(
            sprintf(
                "Toggle %s was added to the toggle collection",
                $toggle->getName()
            ),
            [
                'toggle' => [
                    'name'       => $toggle->getName(),
                    'status'     => $toggle->getStatus(),
                    'conditions' => $toggle->getConditions()
                ]
            ]
        );
    }

    /**
     * Update the toggle.
     *
     * @param Toggle $toggle
     */
    public function update(Toggle $toggle)
    {
        $this->collection->set($toggle->getName(), $toggle);
        $this->logger->info(
            sprintf(
                "Toggle %s was updated in the toggle collection",
                $toggle->getName()
            ),
            [
                'toggle' => [
                    'name'       => $toggle->getName(),
                    'status'     => $toggle->getStatus(),
                    'conditions' => $toggle->getConditions()
                ]
            ]
        );
    }

    /**
     * @return array All toggles from the manager.
     */
    public function all()
    {
        return $this->collection->all();
    }
}
