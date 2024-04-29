<?php

namespace FS\DeleteEmail\XF\Pub\Controller;

class Account extends XFCP_Account
{
	public function actionDeleteEmail()
	{
		$visitor = \XF::visitor();
		$auth = $visitor->Auth->getAuthenticationHandler();
		if (!$auth) {
			return $this->noPermission();
		}

		if ($this->isPost()) {
			$visitor->fastUpdate('email', '');

			return $this->redirect($this->buildLink('account/account-details'));
		}

		$viewpParams = [
			'confirmUrl' => $this->buildLink('account/delete-email', $visitor),
			'contentTitle' => $visitor->email,
		];

		return $this->view('XF\Account', 'fs_email_delete_confirm', $viewpParams);
	}

	public function actionEmail()
	{
		$visitor = \XF::visitor();
		$auth = $visitor->Auth->getAuthenticationHandler();
		if (!$auth) {
			return $this->noPermission();
		}

		if ($visitor['deleted_by'] == 1) {
			throw $this->exception(
				$this->error(\XF::phrase('your_email_may_not_be_changed_at_this_time'))
			);
		}
		return parent::actionEmail();
	}
}
