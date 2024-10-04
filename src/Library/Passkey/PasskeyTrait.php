<?php
/**
 * @package   panopticon
 * @copyright Copyright (c)2023-2024 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   https://www.gnu.org/licenses/agpl-3.0.txt GNU Affero General Public License, version 3 or later
 */

namespace Akeeba\Panopticon\Library\Passkey;

defined('AKEEBA') || die;

use Akeeba\Panopticon\Factory;

trait PasskeyTrait
{
	private function needsPasskeyForcedSetup(): bool
	{
		// If the user is not logged in they cannot set up passkeys.
		$user = Factory::getContainer()->userManager->getUser();

		if (empty($user) || empty($user->getId()))
		{
			return false;
		}

		// Is the user a Superuser and passkey_login_force_superuser enabled?
		$appConfig  = Factory::getContainer()->appConfig;
		$criterion1 = $appConfig->get('passkey_login_force_superuser', false)
		              && $user->getPrivilege('panopticon.super');

		// Is the user an Administrator and passkey_login_force_admin enabled?
		$criterion2 = $appConfig->get('passkey_login_force_admin', false)
		              && $user->getPrivilege('panopticon.admin');

		// Is the user in one of the forced MFA groups?
		$forcedGroups = $appConfig->get('passkey_login_force_groups', []);
		$forcedGroups = is_array($forcedGroups) ? array_values($forcedGroups) : [];
		$userGroups   = $user->getParameters()->get('usergroups', []);
		$userGroups   = is_array($userGroups) ? array_values($userGroups) : [];
		$intersection = array_intersect($forcedGroups, $userGroups);
		$criterion3   = !empty($forcedGroups) && !empty($userGroups) && !empty($intersection);

		// If none of the above criteria is met we don't need to redirect the user
		if (!$criterion1 && !$criterion2 && !$criterion3)
		{
			return false;
		}

		// If the user has any passkeys I don't need to continue
		$hasPasskeys = Factory::getContainer()->segment->get('passkey.hasPasskeys', null);
		$hasPasskeys ??= count((new Authentication())->getCredentialsRepository()->getAll($user->getId())) > 0;

		if ($hasPasskeys)
		{
			return false;
		}

		// If we are in a passkey-transparent view we won't redirect. These are the same as MFA transparent views.
		$view = strtolower(Factory::getContainer()->input->get('view', 'main'));

		if (in_array($view, $this->mfaAllowedViews))
		{
			return false;
		}

		// Allow the view where the user sets up new passkeys
		if (str_starts_with($view, 'passkey'))
		{
			return false;
		}

		// If we are already in the user edit, save, or apply task we return false to prevent a redirect loop.
		$task = strtolower(Factory::getContainer()->input->get('task', 'main'));

		if (($view === 'users' || $view === 'user') && in_array($task, ['edit', 'apply', 'save']))
		{
			return false;
		}

		return true;
	}

}