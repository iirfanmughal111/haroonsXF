<?php

namespace FS\SecurityQuestion\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class SecurityQuestion extends AbstractController
{


    public function actionIndex(ParameterBag $params)
    {

        $finder = $this->finder('FS\SecurityQuestion:SecurityQuestion')->order('question_id', 'DESC');


        $page = $params->page;
        $perPage = 10;

        $finder->limitByPage($page, $perPage);

        $viewParams = [
            'question' => $finder->fetch(),

            'page' => $page,
            'perPage' => $perPage,
            'total' => $finder->total(),
        ];

        return $this->view('FS\SecurityQuestion:SecurityQuestion\Index', 'security_question_index', $viewParams);
    }

    public function actionAdd()
    {
        $newQuestion = $this->em()->create('FS\SecurityQuestion:SecurityQuestion');
        return $this->questionAddEdit($newQuestion);
    }

    public function actionEdit(ParameterBag $params)
    {
        /**
     
         * @var \FS\SecurityQuestion\Entity\SecurityQuestion $securityQuestion
         */
        $securityQuestion = $this->assertDataExists($params->question_id);
        return $this->questionAddEdit($securityQuestion);
    }

    protected function questionAddEdit(\FS\SecurityQuestion\Entity\SecurityQuestion $SecurityQuestion)
    {
        $viewParams = [
            'question' => $SecurityQuestion
        ];

        return $this->view('FS\SecurityQuestion:SecurityQuestion\Add', 'security_question_add', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->question_id) {
            $questionEditAdd = $this->assertDataExists($params->question_id);
        } else {
            $questionEditAdd = $this->em()->create('FS\SecurityQuestion:SecurityQuestion');
        }

        $this->questionSaveProcess($questionEditAdd)->run();

        return $this->redirect($this->buildLink('securityQuestion'));
    }

    protected function questionSaveProcess(\FS\SecurityQuestion\Entity\SecurityQuestion $SecurityQuestion)
    {
        $input = $this->filter([
            'security_question' => 'str',
        ]);

        $form = $this->formAction();
        $form->basicEntitySave($SecurityQuestion, $input);

        return $form;
    }

    public function actionDeleteRecord(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->question_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');
        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('securityQuestion/delete-record', $replyExists),
            null,
            $this->buildLink('securityQuestion'),
            "{$replyExists->security_question}"
        );
    }

    // plugin for check id exists or not 

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\SecurityQuestion\Entity\SecurityQuestion
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\SecurityQuestion:SecurityQuestion', $id, $extraWith, $phraseKey);
    }
}
