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
 * Date: 03/03/2019 18:26
 */
namespace BetterPassword\Form;

use BetterPassword\BetterPassword;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class ConfigForm extends BaseForm
{
    protected function buildForm()
    {
        $translator = Translator::getInstance();

        $this->formBuilder
            ->add(
                BetterPassword::VAR_REGULAR_EXPRESSION,
                'text',
                [
                    'constraints' => [
                        new NotBlank(),
                    ],
                    'label'       => $translator->trans('Regular expression to match', [], BetterPassword::DOMAIN_NAME),
                    'label_attr'  => [
                        'help' => $this->translator->trans(
                            'This is the regular expression the passwords must match.',
                            [],
                            BetterPassword::DOMAIN_NAME
                        )
                    ]
                ]
            )->add(
                BetterPassword::VAR_PASSWORD_REQUIREMENTS,
                'collection',
                [
                    'type' => 'text',
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'label' => $translator->trans('Password requirements description', [], BetterPassword::DOMAIN_NAME),
                    'label_attr'  => [
                        'help' => $this->translator->trans(
                            'Please enter the password requirements description that will be displayed to your customers.',
                            [],
                            BetterPassword::DOMAIN_NAME
                        )
                    ],
                ]
            );

    }
}
