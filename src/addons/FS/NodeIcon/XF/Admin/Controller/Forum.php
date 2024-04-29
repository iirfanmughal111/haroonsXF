<?php

namespace FS\NodeIcon\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class Forum extends XFCP_Forum
{

    // public function actionIndex(){
    //     // system("mysqldumpPath -h hostname -u username -ppassword database > backupFilePath");
    // }

    public function actionDeleteIcon(ParameterBag $params)
    {
        $node = $this->assertNodeExists($params['node_id']);

        if (!$node->getIcon()) {
            return $this->error(\XF::phrase('no_icon_to_delete'));
        }

        if ($this->isPost()) {
            \XF\Util\File::deleteFromAbstractedPath('data://nodeIcons/' . $node->node_id . '.jpg');

            return $this->redirect($this->buildLink('forums/edit', $node));
        }

        $viewParams = [
            'node' => $node
        ];

        return $this->view('FS\NodeIcon:DeleteIcon', 'FS_NodeIcon_delete_icon', $viewParams);
    }
}
