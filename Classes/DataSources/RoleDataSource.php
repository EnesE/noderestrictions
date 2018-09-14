<?php

namespace PunktDe\NodeRestrictions\DataSources;

/*
 * This file is part of the PunktDe.NodeRestrictions package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Security\Policy\PolicyService;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Annotations as Flow;

class RoleDataSource extends AbstractDataSource
{

    /**
     * @var string
     */
    static protected $identifier = 'punktde-noderestrictions-roles';

    /**
     * @Flow\InjectConfiguration(path="excludeRolesFromPackages")
     * @var array
     */
    protected $excludedPackages;

    /**
     * @Flow\InjectConfiguration(path="excludeSpecificRoles")
     * @var array
     */
    protected $excludedRoles;

    /**
     * @Flow\Inject
     * @var PolicyService
     */
    protected $policyService;

    /**
     * @param NodeInterface|null $node
     * @param array $arguments
     * @return array
     */
    public function getData(NodeInterface $node = null, array $arguments)
    {
        $roles = ['' => ['label' => 'Not restricted']];

        foreach ($this->policyService->getRoles() as $role) {
            if (!in_array($role->getPackageKey(), $this->excludedPackages) && !in_array($role->getIdentifier(), $this->excludedRoles)) {
                $roles[$role->getIdentifier()] = [
                    'label' => $role->getName(),
                    'icon' =>  'icon-users'
                ];
            }
        }

        return $roles;
    }
}
