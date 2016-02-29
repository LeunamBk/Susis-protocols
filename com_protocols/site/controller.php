<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_protocols
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * Hello World Component Controller
 *
 * @since  0.0.1
 */
class ProtocolsController extends JControllerLegacy
{

    public function display($cachable = false, $urlparams = array())
    {

        // restrict component access to only logged in users
        $user = JFactory::getUser();
        $app  = JFactory::getApplication();

        // $view = $this->input->get('view');
        // if ($view == 'someview' || $view == 'anotherview'){

            if ($user->get('guest') == 1)
            {
                $uri = JUri::getInstance();
                $this->setRedirect(
                    JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($uri->toString())), $app->enqueueMessage(JText::_('COM_PROTOCOLS_LOGIN_REQUIRED'), 'warning')
                );

                return;
            }
        //}

        parent::display($cachable, $urlparams);
    }

}