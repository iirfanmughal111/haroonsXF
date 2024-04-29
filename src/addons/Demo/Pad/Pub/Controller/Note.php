<?php

namespace Demo\Pad\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;

class Note extends AbstractController
{

    // http://localhost/xenforo/index.php?notes/

    public function actionIndex()
    {
        return $this->view('Demo\Pad:Note\Index', 'demo_pad_edit');
    }

    // public function actionIndex()
    // {
    //     return $this->view('Demo\Pad:Note\Index','demo_pad_index');
    // }



    // http://localhost/xenforo/index.php?notes/

    public function actionCreateInsert()
    {

        /** @var \Demo\Pad\Entity\Note $note */
        $note = $this->em()->create('Demo\Pad:Note');

        $input = $this->filter([
            'title' => 'str',
            'content' => 'str',
        ]);

        $note->title = $input['title'];
        $note->content = $input['content'];


        $note->save();
        // var_dump($note);exit;
        $viewParams = [
            'note' => $note
        ];

        return $this->view('Demo\Pad:Note\CreateInsert', 'demo_pad_index', $viewParams);
    }



    // http://localhost/xenforo/index.php?notes/

    public function actionUpdateNote()
    {

        $notes = $this->finder('Demo\Pad:Note')->whereId(3);

        /** @var \Demo\Pad\Entity\Note $note */
        $note = $notes->fetchOne();

        $note->title = 'This is my update note title';
        $note->content = 'Here is the ontent of my first note...';


        $note->save();
        var_dump($note);
        exit;
        $viewParams = [
            'note' => $note
        ];

        return $this->view('Demo\Pad:Note\Index', 'demo_pad_index', $viewParams);
    }


    // http://localhost/xenforo/index.php?notes/

    public function actionDeleteNote()
    {

        $notes = $this->finder('Demo\Pad:Note')->whereId(2);

        /** @var \Demo\Pad\Entity\Note $note */
        $note = $notes->fetchOne();

        // $note->title = 'This is my update note title';
        // $note->content = 'Here is the ontent of my first note...';


        $note->delete();
        var_dump($note);
        exit;
        $viewParams = [
            'note' => $note
        ];

        return $this->view('Demo\Pad:Note\Index', 'demo_pad_index', $viewParams);
    }


    // http://localhost/xenforo/index.php?notes/test/

    public function actionTest()
    {
        $postFinder = $this->finder('XF:Post')
            // JOIN lgane k liye use hota hai ->with is se user waley table ka data b postFinder me a jaye ga
            ->with('User')
            ->with('User.Profile')
            ->with('Thread')
            ->where('user_id', '<>', 0);

        $viewParams = [
            'posts' => $postFinder
        ];

        return $this->view('Demo\Pad:Note\Test', 'demo_pad_test', $viewParams);
    }

    public function actionFetchtestdata()
    {
        // $postFinder = $this->finder('Demo\Pad:Note');

        // $finder = \XF::finder('Demo\Pad:Note');
        // $user = $finder->where('user_id', 0);

        $db = \XF::db();
        $user = $db->fetchAll('SELECT * FROM demo_pad_note WHERE user_id = ?', 0);

        $viewParams = [
            'posts' => $user
        ];
        echo "<pre>";
        print_r($user);
        echo "</pre>";

        return $this->view('Demo\Pad:Note\Fetchtestdata', 'demo_pad_fetch', $viewParams);
    }


    public function actionQuerydatabase()
    {

        // for fetch by id query
        // $userFinder = $this->finder('XF:User')->wherId(2);

        $userFinder = $this->finder('XF:User')
            // ->where('user_id','<',4)
            // ->where('username','LIKE','h%')
            ->whereOr(['user_id', '<', 4], ['username', 'LIKE', 'h%'])
            ->order('user_id', 'desc');

        $total = $userFinder->total();

        // $users = $userFinder->fetchOne();
        $users = $userFinder->fetch();

        $viewParams = [];
        return $this->view('Demo\Pad:Note\Index', 'demo_pad_index', $viewParams);
    }


    public function actionQueryupdatebyid()
    {

        $userFinder = $this->finder('XF:User')->whereId(5);

        /** @var \XF\Entity\User $user */
        $users = $userFinder->fetchOne();

        $users->email = 'helo@example.com';
        $users->username = 'mister';

        //                 Or

        // $user->bulkset([
        //     'email' => 'hello@example.com',
        //     'username' => 'mister',
        // ]);

        $users->save();

        return $this->view('Demo\Pad:Note\Index', 'demo_pad_index');
    }


    public function actionQueryinsertdata()
    {
        $users = $this->em()->create('XF:User');

        $users->email = 'helqwert@example.com';
        $users->username = 'qwerty';

        //                 Or

        // $users->bulkset([
        //     'email' => 'hello@example.com',
        //     'username' => 'mister',
        // ]);

        $users->save();

        return $this->view('Demo\Pad:Note\Index', 'demo_pad_index');
    }

    //          ider se hm koch parameters ko pass krein gey template me phir uder print kerwaien gey

    //    Route is : http://localhost/xenforo/index.php?notes/pass-params

    public function actionPassParams()
    {
        $string = 'Hello';
        $number = 11234;
        $money = 10.1;
        $array = ['one', 'two', 'there'];
        $array1 = ['name' => 'Mr Haroon', 'email' => 'example@example.com'];

        foreach ($array as $key => $value) {
            # code...
        }

        $viewParams = [
            'string' => $string,
            'number' => $number,
            'money' => $money,
            'array' => $array,
            'array1' => $array1
        ];

        return $this->view('Demo\Pad:Note\PassParam', 'demo_pad_pass_param', $viewParams);
    }
















    //                      Note Add Edit code

    public function actionAdd()
    {
        $note = $this->em()->create('Demo\Pad:Note');
        return $this->noteAddEdit($note);
    }


    public function actionEdit(ParameterBag $params)
    {
        $note = $this->assertNoteExists($params->note_id);
        return $this->noteAddEdit($note);
    }

    protected function noteAddEdit(\Demo\Pad\Entity\Note $note)
    {
        $viewParams = [
            'note' => $note
        ];

        return $this->view('Demo\Pad:Note\Edit', 'demo_pad_edit', $viewParams);
    }

    public function actionSave(ParameterBag $params)
    {
        if ($params->note_id) {
            $note = $this->assertNoteExists($params->note_id);
            // var_dump($params);
        } else {
            $note = $this->em()->create('Demo\Pad:Note');
            // $input = $this->filter([
            //     'title' => 'str',
            //     'content' => 'str',
            // ]);

            // echo $input['content'];
        }

        $this->noteSaveProcess($note)->run();

        return $this->redirect($this->buildLink('notes'));
    }

    protected function noteSaveProcess(\Demo\Pad\Entity\Note $note)
    {
        $input = $this->filter([
            'title' => 'str',
            'content' => 'str',
        ]);

        $form = $this->formAction();
        $form->basicEntitySave($note, $input);

        return $form;
    }

    protected function assertNoteExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Demo\Pad:Note', $id, $with, $phraseKey);
    }
}
