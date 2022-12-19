<?php declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */

use Xmf\Request;
use XoopsModules\Gamers\{
    Common\Cloner,
    Helper,
    Utility
};

require_once __DIR__ . '/admin_header.php';


//constant('CO_' . $moduleDirNameUpper . '_' . 'MIGRATE_OK');

$helper = Helper::getInstance();

//Utility::cpHeader();
xoops_cp_header();

//Utility::openCollapsableBar('clone', 'cloneicon', constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE'), constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_DSC'));

if ('submit' === Request::getString('op', '', 'POST')) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('clone.php', 3, implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
    }

    //    $clone = $_POST['clone'];
    $clone = Request::getString('clone', '', 'POST');

    //check if name is valid
    if (empty($clone) || preg_match('/[^a-zA-Z0-9\_\-]/', (string) $clone)) {
        redirect_header('clone.php', 3, sprintf(constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_INVALIDNAME'), $clone));
    }

    // Check wether the cloned module exists or not
    if ($clone && is_dir($GLOBALS['xoops']->path('modules/' . $clone))) {
        redirect_header('clone.php', 3, sprintf(constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_EXISTS'), $clone));
    }

    $patterns = [
        \mb_strtolower((string) $helper->dirname())          => \mb_strtolower((string) $clone),
        \mb_strtoupper((string) $helper->dirname())          => \mb_strtoupper((string) $clone),
        ucfirst(mb_strtolower((string) $helper->dirname())) => ucfirst(mb_strtolower((string) $clone)),
    ];

    $patKeys   = array_keys($patterns);
    $patValues = array_values($patterns);
    Cloner::cloneFileFolder($helper->path());
    $logocreated = Cloner::createLogo(mb_strtolower((string) $clone));

    $msg = '';
    if (is_dir($GLOBALS['xoops']->path('modules/' . \mb_strtolower((string) $clone)))) {
        $msg .= sprintf(constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_CONGRAT'), "<a href='" . XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin'>" . ucfirst(mb_strtolower((string) $clone)) . '</a>') . "<br>\n";
        if (!$logocreated) {
            $msg .= constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_IMAGEFAIL');
        }
    } else {
        $msg .= constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_FAIL');
    }
    echo $msg;
} else {
    require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
    $form  = new \XoopsThemeForm(sprintf(constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_TITLE'), (string)$helper->getModule()->getVar('name', 'E')), 'clone', 'clone.php', 'post', true);
    $clone = new \XoopsFormText(constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_NAME'), 'clone', 20, 20, '');
    $clone->setDescription(constant('CO_' . $moduleDirNameUpper . '_' . 'CLONE_NAME_DSC'));
    $form->addElement($clone, true);
    $form->addElement(new \XoopsFormHidden('op', 'submit'));
    $form->addElement(new \XoopsFormButton('', '', _SUBMIT, 'submit'));
    $form->display();
}

// End of collapsable bar
//Utility::closeCollapsableBar('clone', 'cloneicon');

require_once __DIR__ . '/admin_footer.php';
