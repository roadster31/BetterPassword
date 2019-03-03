<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 03/03/2019 17:38
 */
namespace BetterPassword\EventListeners;

use BetterPassword\BetterPassword;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ExecutionContextInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Translation\Translator;

class FormHandler implements EventSubscriberInterface
{
    /** @var RequestStack */
    protected $requestStack;

    /**
     * FormHandler constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function changeCreatePasswordVerification(TheliaFormEvent $event)
    {
        $this->changePasswordVerification($event, 'password');
    }

    public function changeUpdatePasswordVerification(TheliaFormEvent $event)
    {
        $this->changePasswordVerification($event, 'password');
    }

    protected function getLocale()
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        return $request->getSession()->getLang()->getLocale();
    }

    public function changePasswordVerification(TheliaFormEvent $event, $fieldName)
    {
        $formBuilder = $event->getForm()->getFormBuilder();

        $passwordField = $formBuilder->get($fieldName);

        $options = $passwordField->getOptions();
        $type = $passwordField->getType()->getName();

        $options['constraints'] = [
            new NotBlank(),
            new Callback([ "methods" => [[ $this, "checkPasswordValidity" ]]])
        ];

        $options['label_attr']['help'] = BetterPassword::getConfigValue('password_requirements', '', $this->getLocale());

        $formBuilder->add($fieldName, $type, $options);
    }

    public function checkPasswordValidity($value, ExecutionContextInterface $context)
    {
        $expression = BetterPassword::getConfigValue('password_expression', null);

        if (null !== $expression && ! preg_match("/$expression/", $value)) {
            $context->addViolation(
                Translator::getInstance()->trans(
                    "Your password does not match the requirements : %requirement_text",
                        [
                            '%requirement_text' => BetterPassword::getConfigValue('password_requirements', '', $this->getLocale())
                        ],
                    BetterPassword::DOMAIN_NAME
                )
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::FORM_AFTER_BUILD . ".thelia_customer_create" => ['changeCreatePasswordVerification', 128],
            TheliaEvents::FORM_AFTER_BUILD . ".thelia_customer_password_update" => ['changeUpdatePasswordVerification', 128]
        ];
    }

}
