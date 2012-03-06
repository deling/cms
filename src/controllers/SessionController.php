<?php
namespace Blocks;

/**
 * Handles session related tasks including logging in and out.
 */
class SessionController extends BaseController
{
	/**
	 * Displays the login template. If valid login information, redirects to previous template.
	 */
	public function actionLogin()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$loginName = b()->request->getPost('loginName');
		$password = b()->request->getPost('password');
		$rememberMe = (b()->request->getPost('rememberMe') === 'y');

		// Attempt to log in
		$loginInfo = b()->user->startLogin($loginName, $password, $rememberMe);

		// Did it work?
		if (b()->user->isLoggedIn)
		{
			$r = array(
				'success' => true,
				'redirectUrl' => b()->user->returnUrl
			);
		}
		else
		{
			// they are not logged in, but they need to reset their password.
			if ($loginInfo->identity->errorCode === UserIdentity::ERROR_PASSWORD_RESET_REQUIRED)
			{
				$r = array(
					'success' => true,
					'redirectUrl' => b()->users->forgotPasswordUrl.'?success=1'
				);
			}
			else
			{
				// error logging in.
				$errorMessage = '';

				if ($loginInfo->identity->errorCode === UserIdentity::ERROR_ACCOUNT_LOCKED)
					$errorMessage = 'Account locked.';
				else if ($loginInfo->identity->errorCode === UserIdentity::ERROR_ACCOUNT_COOLDOWN)
				{
					$user = b()->users->getByLoginName($loginName);
					$errorMessage = 'Account locked. Try again in '.DateTimeHelper::secondsToHumanTimeDuration(b()->users->getRemainingCooldownTime($user), false).'.';
				}
				else if ($loginInfo->identity->errorCode === UserIdentity::ERROR_USERNAME_INVALID || $loginInfo->identity->errorCode === UserIdentity::ERROR_ACCOUNT_SUSPENDED)
					$errorMessage = 'Invalid login name or password.';
				else if ($loginInfo->identity->errorCode !== UserIdentity::ERROR_NONE)
					$errorMessage = $loginInfo->identity->failedPasswordAttemptCount.' of '.b()->config->getItem('maxInvalidPasswordAttempts').' failed password attempts.';

				$r = array(
					'error' => $errorMessage,
				);
			}
		}

		$this->returnJson($r);
	}

	public function actionLogout()
	{
		b()->user->logout();
		$this->redirect('');
	}
}
