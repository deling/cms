<?php
namespace Blocks;

/**
 *
 */
class TemplateController extends BaseController
{
	/**
	 * Required
	 */
	public function actionIndex()
	{
		// Require user to be logged in on every page but /login in the control panel and account/password with an activation code
		if (b()->request->mode == RequestMode::CP)
		{
			$path = b()->request->path;
			if ($path !== 'login')
				if ($path !== b()->users->verifyAccountUrl && b()->request->getParam('code', null) == null)
					if ($path !== b()->users->forgotPasswordUrl)
						$this->requireLogin();
		}

		$this->loadRequestedTemplate();
	}
}
