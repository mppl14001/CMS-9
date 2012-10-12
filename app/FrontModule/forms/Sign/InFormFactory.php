<?php
/**
 * InFormFactory.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @package DoctrineSandbox
 *
 * @date    01.09.12
 */

namespace DoctrineSandbox\Forms\Sign;

use Nette\Security as NS;

class InFormFactory extends \Flame\Application\FormFactory
{

	/**
	 * @var string|null
	 */
	private $backlink;

	/**
	 * @var \Nette\Security\User $user
	 */
	private $user;

	/**
	 * @var \FrontModule\SignPresenter
	 */
	private $presenter;

	/**
	 * @param \FrontModule\SignPresenter $presenter
	 */
	public function setPresenter(\FrontModule\SignPresenter $presenter)
	{
		$this->presenter = $presenter;
	}

	/**
	 * @param $backLink
	 */
	public function setBacklink($backLink)
	{
		$this->backlink = $backLink;
	}

	/**
	 * @param \Nette\Security\User $user
	 */
	public function injectUser(\Nette\Security\User $user)
	{
		$this->user = $user;
	}

	/**
	 * @return InForm|\Nette\Application\UI\Form
	 */
	public function createForm()
	{
		$form = new InForm();
		$form->configure();
		$form->onSuccess[] = $this->formSubmitted;
		return $form;
	}

	public function formSubmitted(InForm $form)
	{
		$values = $form->getValues();

		try {

			if ($values->remember) {
				$this->user->setExpiration('+ 14 days', false);
			} else {
				$this->user->setExpiration('+ 2 hours', true);
			}

			$this->user->login($values->email, $values->password);

			if($this->backlink and $this->presenter) $this->presenter->restoreRequest($this->backlink);

		} catch (NS\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

}
