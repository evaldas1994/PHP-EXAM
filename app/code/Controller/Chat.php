<?php

namespace Controller;

use Carbon\Carbon;
use Core\Controller;
use Core\Request;
use Helper\FormBuilder;
use Helper\Url;
use Helper\Validation\InputValidation as Validation;
use Model\User;
use Session\User as UserSession;
use Model\Chat as ChatModel;

class Chat extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->isLogedIn()) {
            Url::redirect(Url::make('/user/login'));
        }
    }

    public function index(): void
    {
        $userSession = new UserSession();

        $lastChatsMessages = $this->getLastMessageOfAllChatsRooms();

        $this->data['messages'] = $lastChatsMessages;
        $this->data['authId'] = $userSession->getAuthUserId();
        $this->data['authName'] = $userSession->getAuthUserName();

        $this->render('chat/index', $this->data);
    }

    private function getLastMessageOfAllChatsRooms(): array
    {
        $userSession = new UserSession();
        $chatModel = new ChatModel();

        //create players chat rooms by foreign users id
        $chatRooms = $chatModel->getAllRooms($userSession->getAuthUserId());

        // get all foreign users ids in chat
        $foreignPlayers = [];
        for ($i = 0; $i<count($chatRooms); $i++) {
            if ($chatRooms[$i]['to_id'] !== $userSession->getAuthUserId()) {
                if (!in_array($chatRooms[$i]['to_id'], $foreignPlayers)) {
                    $foreignPlayers[] = $chatRooms[$i]['to_id'];
                }
            } else {
                if (!in_array($chatRooms[$i]['from_id'], $foreignPlayers)) {
                    $foreignPlayers[] = $chatRooms[$i]['from_id'];
                }
            }
        }

        // get last message for each foreign users
        $lastChatsMessages = [];
        for ($i = 0; $i<count($foreignPlayers); $i++) {
            $lastChatsMessages[] = $chatModel->getLastMessageByUsers($userSession->getAuthUserId(), $foreignPlayers[$i]);
        }

        return $lastChatsMessages;
    }

    public function send($id): void
    {
        $userSession = new UserSession();
        $request = new Request();
        $chatModel = new ChatModel();
        $userModel = new User();

        if (!Validation::isNotEmpty($request->getPost('text'))) {
            $this->message->setErrorMessage('Message field is required');
        } else {
            if (!Validation::isLengthUpTo(40, $request->getPost('subject'))) {
                $this->message->setErrorMessage('Subject field is too long');
            } else {
                $chatModel->setFrom($userSession->getAuthUserId());
                $chatModel->setTo($id);
                $chatModel->setSubject($request->getPost('subject'));
                $chatModel->setText($request->getPost('text'));
                $chatModel->setSeen(0);
                $chatModel->setDate(Carbon::now());
                $chatModel->setFromName($userSession->getAuthUserName());
                $chatModel->setToName($userModel->load($id)->getUserName());
                $chatModel->sendMessage();
            }
        }

        Url::redirect('/chat/show/' . $id);
    }

    public function show($id): void
    {
        $userSession = new UserSession();
        $chatModel = new ChatModel();

        $messagesOfRoom = $chatModel->getAllMessagesOfRoom($userSession->getAuthUserId(), $id);
        $unseenMessagesOfRoom = $chatModel->getAllUnseenMessagesOfRoom($userSession->getAuthUserId(), $id);

        // check as seen
        if (!empty($unseenMessagesOfRoom)) {
            for ($i=0; $i<count($unseenMessagesOfRoom); $i++) {
                $chatModel->seen($unseenMessagesOfRoom[$i]['id']);
            }
        }

        // make button value
        if (empty($messagesOfRoom)) {
            $buttonValue = 'Send';
        } else {
            $buttonValue = 'Answer';
        }

        $form = new FormBuilder('post', Url::make('/chat/send/' . $id));
        $form->input('subject', 'text', '', 'Subject');
        $form->texarea('text');
        $form->input('submit', 'submit', $buttonValue);

        $this->data['authId'] = $userSession->getAuthUserId();
        $this->data['form'] = $form->get();
        $this->data['messagesOfRoom'] = $messagesOfRoom;

        $this->render('chat/show', $this->data);
    }
}