<?php
/**
 * Member
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
 * @package     Com
 * @subpackage  Member
 * @author      Twin Huang <twinh@yahoo.cn>
 * @copyright   Twin Huang
 * @license     http://www.opensource.org/licenses/apache2.0.php Apache License
 * @version     $Id$
 * @since       2010-05-13 10:17:58
 */

class Com_Member_Controller extends Com_ActionController
{
    /**
     * 编辑密码
     * @return object 实例化编辑操作
     * @todo 重新登陆
     */
    public function actionEditPassword()
    {
        $request = $this->_request;
        if('guest' == $request->get('id') || 'guest' == $request->post('id')) {
            $lang = Qwin::call('-lang');
            return $this->getView()->alert($lang->t('MSG_GUEST_NOT_ALLOW_EDIT_PASSWORD'));
        }
        $meta = Qwin_Metadata::getInstance()->get('Com_Member_PasswordMetadata');

        if (!$request->isPost()) {
            return Qwin::call('-widget')->get('View')->execute(array(
                'module'    => $this->_module,
                'meta'      => $meta,
                'id'        => $request->get('id'),
                'asAction'  => 'edit',
                'isView'    => false,
            ));
        } else {
            return Com_Widget::getByModule('com/member', 'editPassword')->execute(array(
                'data'      => $_POST,
            ));
        }
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        $id = $this->_request->get('id');
        $idList = explode(',', $id);

        /**
         * @todo 是否在数据库增加一个字段,作为不允许删除的标志
         */
        $banIdList = array(
            'guest', 'admin'
        );
        $result = array_intersect($idList, $banIdList);
        if (!empty($result)) {
            $lang = Qwin::call('-lang');
            return $this->getView()->alert($lang->t('MSG_NOT_ALLOW_DELETE'));
        }
        parent::actionDelete();
    }

    /**
     * 用户名是否已使用
     */
    public function actionIsUsernameExists()
    {
        $username = $this->_request->get('usesrname');
        if(true == $this->isUsernameExists($username))
        {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }

    public function isUsernameExists($username)
    {
        $query = $this->_meta->getQueryByAsc($this->_asc);
        $result = $query->where('username = ?', $username)
            ->fetchOne();
        if(false != $result)
        {
            $result = true;
        }
        return $result;
    }

    /*public function onAfterDb($action, $data)
    {
        if('EditPassword' == $action)
        {
            $url = Qwin::call('-url')->url(array('module' => 'Member', 'controller' => 'Log', 'action' => 'Logout'));
            $this->view->redirect('LOGIN', $url)
                    ->loadView()
                    ->display();
            exit();
        }
    }*/
}
