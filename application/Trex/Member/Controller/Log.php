<?php
/**
 * Log
 *
 * Copyright (c) 2008-2010 Twin Huang. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package     Trex
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-23 00:22:37
 */

class Trex_Member_Controller_Log extends Trex_Controller
{
    public function actionLogin()
    {
        $ini = Qwin::run('-ini');
        $js = Qwin::run('Qwin_Helper_Js');
        $meta = $this->_meta;

        /**
         * 提示已经登陆的信息
         */
        $member = $this->session->get('member');
        /*if('guest' != $member['username'])
        {
            return $this->setRedirectView($this->_lang->t('MSG_LOGINED'));
        }*/

        if(empty($_POST))
        {
            /**
             * 设置视图,加载登陆界面
             */
            $this->_view = array(
                'class' => 'Trex_Member_View_Login',
                'data' => get_defined_vars(),
            );
        } else {
            return Qwin::run('Trex_Member_Service_Login')->process(array(
                'set' => $this->_set,
                'data' => array(
                    'db' => $_POST,
                ),
                'this' => $this,
            ));
        }
    }

    public function actionLogout()
    {
        return Qwin::run('Trex_Member_Service_Logout')->process(array(
            'set' => $this->_set,
        ));
    }

    public function validateCaptcha($value, $name, $data)
    {
        if($value == Qwin::run('Qwin_Session')->get('captcha'))
        {
            return true;
        }
        return new Qwin_Validator_Result(false, $name, 'MSG_ERROR_CAPTCHA');
    }

    public function validatePassword($value, $name, $data)
    {
        $value = md5($value);
        $result = Qwin::run('Qwin_Trex_Metadata')
            ->getDoctrineQuery(array(
                'namespace' => 'Trex',
                'module' => 'Member',
                'controller' => 'Member',
            ))
            ->where('username = ? AND password = ?', array($data['username'], $value))
            ->fetchOne();
        if(false != $result)
        {
            $this->member = $result->toArray();
            unset($this->member['password']);
            return true;
        }
        Qwin::run('Qwin_Session')->set('member', null);
        return new Qwin_Validator_Result(false, 'password', 'MSG_ERROR_USERNAME_PASSWORD');
    }
}