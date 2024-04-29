<?php

namespace FS\NodeIcon\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class LinkForum extends XFCP_LinkForum
{
    protected function nodeSaveProcess(\XF\Entity\Node $node)
    {
        if ($upload = $this->request->getFile('upload', false, false)) {
            $this->repository('FS\NodeIcon:Node')->setIconFromUpload($node, $upload);
        }

        return parent::nodeSaveProcess($node);
    }

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
