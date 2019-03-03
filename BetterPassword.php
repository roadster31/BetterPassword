<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace BetterPassword;

use Thelia\Module\BaseModule;

class BetterPassword extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'betterpassword';

    const VAR_REGULAR_EXPRESSION = "password_expression";

    const VAR_PASSWORD_REQUIREMENTS = "password_requirements";
}
