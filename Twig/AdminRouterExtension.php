<?php

/**
 * SfsAdminBundle - Symfony2 project
 *
 * @author Ramine AGOUNE <ramine.agoune@solidlynx.com>
 */

namespace Sfs\AdminBundle\Twig;

use Sfs\AdminBundle\Controller\AdminController;
use Sfs\AdminBundle\Core\CoreAdmin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminRouterExtension extends AbstractExtension {
	/**
	 * @var CoreAdmin
	 */
	protected $core;

	/**
	 * 
	 * @param CoreAdmin $core
	 */
	public function __construct(CoreAdmin $core) {
		$this->core = $core;
	}

	/**
	 * getFunctions
	 * 
	 * @return array
	 */
	public function getFunctions() {
		return [
				new TwigFunction('admin_get_actions', [$this, 'adminGetActions']),
				new TwigFunction('admin_get_entry_actions', [$this, 'adminGetEntryActions']),
                new TwigFunction('admin_get_global_actions', [$this, 'adminGetGlobalActions']),
                new TwigFunction('admin_get_current_action', [$this, 'adminGetCurrentAction']),
                new TwigFunction('admin_has_action', [$this, 'adminHasAction']),
                new TwigFunction('admin_identifier', [$this, 'getAdminIdentifier']),
				new TwigFunction('admin_route', [$this, 'getAdminRoute']),
				new TwigFunction('admin_url', [$this, 'getAdminUrl']),
		];
	}

    /**
     * @param null $object
     * @return array
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function adminGetActions($object = null) {
        if(isset($object)) {
            $slug = $this->core->getAdminSlug($object);
        }
        else {
            $slug = $this->core->getCurrentSlug();
        }

        $adminService = $this->core->getAdminService($slug);

        if(null !== $adminService) {
            return $adminService->getActions();
        }
        else {
            return array();
        }
    }

    /**
     * @param null $object
     * @return array
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function adminGetEntryActions($object = null) {
        if(isset($object)) {
            $slug = $this->core->getAdminSlug($object);
        }
        else {
            $slug = $this->core->getCurrentSlug();
        }

        $adminService = $this->core->getAdminService($slug);

        if(null !== $adminService) {
            return $adminService->getEntryActions();
        }
        else {
            return array();
        }
    }

    /**
     * @param null $object
     * @return array
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function adminGetGlobalActions($object = null) {
        if(isset($object)) {
            $slug = $this->core->getAdminSlug($object);
        }
        else {
            $slug = $this->core->getCurrentSlug();
        }

        $adminService = $this->core->getAdminService($slug);

        if(null !== $adminService) {
            return $adminService->getGlobalActions();
        }
        else {
            return array();
        }
    }

    /**
     * @return string
     */
    public function adminGetCurrentAction() {
        return $this->core->getCurrentAction();
    }

    /**
	 * @param string $action
	 * @param null $object
	 * @return bool
	 */
	public function adminHasAction($action, $object = null) {
		if(isset($object)) {
			$slug = $this->core->getAdminSlug($object);
		}
		else {
			$slug = $this->core->getCurrentSlug();
		}

		$adminService = $this->core->getAdminService($slug);
		if(in_array($action, $adminService->getActions())) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * getAdminIdentifier
	 *
	 * @param null $object
	 * @return string
	 *
	 */
	public function getAdminIdentifier($object = null) {
		if(isset($object)) {
			$slug = $this->core->getAdminSlug($object);
		}
		else {
			$slug = $this->core->getCurrentSlug();
		}
		$property = $this->core->getAdminService($slug)->getIdentifierProperty();
		
		return $property;
	}

    /**
     * @param $action
     * @param null $object
     * @return string
     */
	public function getAdminRoute($action, $object = null) {
	    if($object !== null && is_string($object)) {
	        $object = new $object;
        }

        if($object !== null && is_object($object)) {
			$url = $this->core->getRouteByEntity($object, $action);
			return $url;
		}
	}

	/**
	 * getAdminUrl
	 *
	 * @param string $action
	 * @param array $parameters
	 *
	 * @return string
	 */
	public function getAdminUrl($action, array $parameters = array()) {
		if(isset($parameters['object'])) {
			$slug = $this->core->getAdminSlug($parameters['object']);
			$url = $this->core->getUrl($slug, $action, $parameters);
			return $url;
		}
		else {
			$slug = $this->core->getCurrentSlug();
			if($slug) {
				$url = $this->core->getUrl($slug, $action, $parameters);
				return $url;
			}
		}
	}

	/**
	 * getName
	 * 
	 * @return string
	 */
	public function getName() {
		return 'twig_sfs_admin_router';
	}
}
