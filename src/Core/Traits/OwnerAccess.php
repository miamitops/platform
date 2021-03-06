<?php

/**
 * Ushahidi Owner Access Trait
 *
 * Gives objects one new method:
 * `isUserOwner(Entity $entity, User $user)`
 *
 * This checks if `$user` is the owner of `$entity`
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\Core\Traits;

use Ushahidi\Core\Entity;
use Ushahidi\Core\Entity\User;

trait OwnerAccess
{

	/**
	 * Check if $user is the owner of $entity
	 * @param  Entity  $entity
	 * @param  User    $user
	 * @return boolean
	 */
	protected function isUserOwner(Entity $entity, User $user)
	{
		return ($entity->user_id === $user->id);
	}
}
