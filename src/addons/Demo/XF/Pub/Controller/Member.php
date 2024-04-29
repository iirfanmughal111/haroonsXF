<?php

namespace Demo\XF\Pub\Controller;

class Member extends XFCP_Member
{
    public function actionHelloWorld()
    {
        return $this->message('Hello Haroon!');
    }

    public function actionHello()
    {
        return $this->message('Hello Cheem@!');
    }

    public function actionExample()
    {
        $hello = 'Hello';
        $world = 'world!';

        $viewParams = [
            'hello' => $hello,
            'world' => $world
        ];
        return $this->view('Demo:Example', 'demo_example', $viewParams);
    }

    public function actionRedirect()
    {
        return $this->redirect($this->buildLink('members/hello'), 'This is a redirect message.', 'permanent');
    }

    public function actionError()
    {
        return $this->error('Unfortunately the thing you are looking for could not be found.', 404);
    }

    public function actionException()
    {
        throw $this->exception($this->error('An unexpected error occurred'));
    }

    public function actionReroute()
    {
        return $this->rerouteController(__CLASS__, 'error');
    }

    public function actionExample1()
    {
        $reply = parent::actionExample();

        return $reply;
    }

    public function actionFetchDatabase()
    {
        $finder = \XF::finder('XF:User');
        $users = $finder->limit(10)->fetch();
        // $users = json_decode($users);
        return $this->message(print_r($users));

    }
}