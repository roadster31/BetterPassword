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
 * Date: 03/03/2019 18:14
 */
namespace BetterPassword\Hook;

use BetterPassword\BetterPassword;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\Lang;
use Thelia\Model\LangQuery;

class HookManager extends BaseHook
{
    public function onModuleConfigure(HookRenderEvent $event)
    {
        $langs = LangQuery::create()->findByActive(true);

        $requirements = [];

        /** @var Lang $lang */
        foreach ($langs as $lang) {
            $requirements[$lang->getLocale()] =
                BetterPassword::getConfigValue(
                    BetterPassword::VAR_PASSWORD_REQUIREMENTS, '', $lang->getLocale()
                );
        }

        $event->add(
            $this->render(
                'betterpassword/module-configuration.html',
                [
                    BetterPassword::VAR_REGULAR_EXPRESSION => BetterPassword::getConfigValue(BetterPassword::VAR_REGULAR_EXPRESSION) ,
                    BetterPassword::VAR_PASSWORD_REQUIREMENTS => $requirements
                ]

            )
        );
    }
}
