<?php

/**
 * Ushahidi User Authorizer
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Tool\Authorizer;

use Ushahidi\Core\Entity;
use Ushahidi\Core\Entity\User;
use Ushahidi\Core\Tool\Authorizer;
use Ushahidi\Core\Traits\AdminAccess;
use Ushahidi\Core\Traits\UserContext;
use Ushahidi\Core\Traits\PrivAccess;

// The `UserAuthorizer` class is responsible for access checks on `Users`
class UserAuthorizer implements Authorizer
{
	// The access checks are run under the context of a specific user
	use UserContext;

	// - `AdminAccess` to check if the user has admin access
	use AdminAccess;

	// It uses `PrivAccess` to provide the `getAllowedPrivs` method.
	use PrivAccess;

	/**
	 * Get a list of all possible privilges.
	 * By default, returns standard HTTP REST methods.
	 * @return Array
	 */
	protected function getAllPrivs()
	{
		return ['read', 'create', 'update', 'delete', 'read_full'];
	}

	/* Authorizer */
	public function isAllowed(Entity $entity, $privilege)
	{
		// These checks are run within the user context.
		$user = $this->getUser();

		// User should not be able to delete self
		if ($privilege === 'delete' && $this->isUserSelf($entity)) {
			return false;
		}

		// Admin user should be able to do anything - short of deleting self
		if ($this->isUserAdmin($user)) {
			return true;
		}

		// User cannot change their own role
		if ('update' === $privilege && $this->isUserSelf($entity) && $entity->hasChanged('role')) {
			return false;
		}

		// Regular user should be able to update and read_full only self
		if ($this->isUserSelf($entity) && in_array($privilege, ['update', 'read_full'])) {
			return true;
		}

		// Regular user can always read
		if ($privilege === 'read') {
			return true;
		}

		// If no other access checks succeed, we default to denying access
		return false;
	}
}
