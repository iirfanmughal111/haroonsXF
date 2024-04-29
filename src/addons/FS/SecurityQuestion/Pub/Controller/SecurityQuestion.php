<?php

namespace FS\SecurityQuestion\Pub\Controller;

use XF\Pub\Controller\AbstractController;

use XF\Mvc\ParameterBag;
class SecurityQuestion extends AbstractController {

    public function actionIndex() {
  
        $finder = $this->finder('FS\SecurityQuestion:SecurityQuestion');

        $visitor = \XF::visitor();

        $userQuestions = $this->finder('FS\SecurityQuestion:SecurityQuestionAnswer')->where('user_id', $visitor->user_id)->fetch();

        $answersArray = array();
        $questionExist = array();

        if (count($userQuestions)) {


            $count = 1;
            foreach ($userQuestions as $question) {


                $questionExist[$count] = $question->question_id;
                $answersArray[$count] = $question->answer;

                $count++;
            }
        }



        $viewParams = [
            'questions' => $finder->fetch(),
            'answersArray' => $answersArray,
            'questionExist' => $questionExist,
        ];

        return $this->view('FS\SecurityQuestion:SecurityQuestion\Index', 'security_question_index', $viewParams);
    }

    public function actionSave() {

        $input = $this->filter([
            'questions' => 'array',
            'answers' => 'array',
        ]);

        $this->checkQuestionAnswer($input);

        $visitor = \XF::visitor();

          $userQuesiton = $this->finder('FS\SecurityQuestion:SecurityQuestionAnswer')->where('user_id', $visitor->user_id)->fetch();
       
          foreach($userQuesiton as $question){
                
                $question->delete();
                
          }
          
        foreach ($input['questions'] as $key => $value) {

            $addEditQuestion = $this->em()->create('FS\SecurityQuestion:SecurityQuestionAnswer');
            $addEditQuestion->user_id = $visitor->user_id;
            $addEditQuestion->question_id = $value;
            $addEditQuestion->answer = strtolower($input['answers'][$key]);

            $addEditQuestion->save();
        }

        return $this->redirect($this->buildLink('sec-qu'));
    }

    public function actionSelect() {
        $input = $this->filter([
            'select_option' => 'int',
        ]);

        if ($input['select_option'] == 1) {
            return $this->view('XF:LostPassword\Index', 'lost_password');
        }

        return $this->view('FS\SecurityQuestion:SecurityQuestion\select', 'security_question_user');
    }

    public function actionVerifyuser() {

        $input = $this->filter([
            'user_name' => 'str',
        ]);

        $user = $this->finder('XF:User')->where('username', $input['user_name'])->fetchOne();

        if (!$user) {
            throw $this->exception($this->error(\XF::phrase('fs_invalid')));
        }

        $finder = $this->finder('FS\SecurityQuestion:SecurityQuestionAnswer')->where('user_id', $user->user_id);

        if (!count($finder->fetch())) {

            throw $this->exception($this->error(\XF::phrase('fs_invalid')));
        }

        $viewParams = [
            'questions' => $finder->order('id', 'ASC')->fetch(),
            'user' => $user->user_id,
        ];

        return $this->view('FS\SecurityQuestion:SecurityQuestion\verifyuser', 'security_question_verify', $viewParams);
    }

    public function actionCompare() {
        $input = $this->filter([
            'user_id' => 'int',
            'questions' => 'array',
            'answers' => 'array',
        ]);

        $this->checkQuestionAnswer($input);

        foreach ($input['questions'] as $key => $question) {

            $exist = $this->finder('FS\SecurityQuestion:SecurityQuestionAnswer')->where('user_id', $input['user_id'])->where('question_id', (int) $question)->fetchOne();

            if (!$exist) {

                throw $this->exception($this->error(\XF::phrase('fs_invalid')));
            }

            if (strcmp($exist->answer, strtolower($input['answers'][$key])) > 0) {

                throw $this->exception($this->error(\XF::phrase('fs_invalid')));
            }
        }

        $user = $this->finder('XF:User')->where('user_id', $input['user_id'])->fetchOne();

        /** @var \XF\Service\User\PasswordReset $passwordConfirmation */
        $passwordConfirmation = $this->service('XF:User\PasswordReset', $user);

        if (!$passwordConfirmation->canTriggerConfirmation($error)) {
            return $this->error($error);
        }

        $passwordConfirmation->triggerConfirmation();

        $viewParams = [
            'user' => $user,
        ];
        return $this->view('XF:LostPassword\Confirm', 'lost_password_confirm', $viewParams);
    }

    public function checkQuestionAnswer($input) {

        if ($input['questions'][0] == 0 || $input['questions'][1] == 0 || $input['answers'][0] == '' || $input['answers'][1] == '') {
            throw $this->exception($this->error(\XF::phrase('fs_all_fields_required')));
        }

        if ($input['questions'][0] == $input['questions'][1]) {
            throw $this->exception($this->error(\XF::phrase('fs_choose_different_question')));
        }
    }

}
