<?php

namespace Model;

use Core\Db;
use Session\User as UserSession;

class Chat extends ModelAbstract
{
    public const TABLE_NAME = 'chat';

    private $iid;

    /**
     * @return mixed
     */
    public function getIid()
    {
        return $this->iid;
    }

    /**
     * @param mixed $iid
     */
    public function setIid($iid): void
    {
        $this->iid = $iid;
    }
    private $from;
    private $to;
    private $subject;
    private $text;
    private $seen;
    private $date;
    private $fromName;
    private $toName;

    /**
     * @return mixed
     */
    public function getToName()
    {
        return $this->toName;
    }

    /**
     * @param mixed $toName
     */
    public function setToName($toName): void
    {
        $this->toName = $toName;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName): void
    {
        $this->fromName = $fromName;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from): void
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to): void
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * @param mixed $seen
     */
    public function setSeen($seen): void
    {
        $this->seen = $seen;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function sendMessage()
    {
        $db = new Db();
        $userSession = new UserSession();

        $db->insert('chat')->values([
            'from_id' => $userSession->getAuthUserId(),
            'to_id' => $this->to,
            'subject' => $this->subject,
            'text' => $this->text,
            'is_seen' => $this->seen,
            'date' => $this->date,
            'from_name' => $this->fromName,
            'to_name' => $this->toName
        ])->exec();
    }

    public function getUnseenMessages(): array
    {
        $db = new Db();
        $userSession = new UserSession();

        return $db->select()->from('chat')->where('to_id', $userSession->getAuthUserId())->whereAnd('is_seen', 0)->get();
    }

    public function getAllMessagesOfRoom($user_id, $foreign_id): array
    {
        $db = new Db();

        return $db->getAllMessagesOfRoom($user_id, $foreign_id)->get();
    }

    public function getAllUnseenMessagesOfRoom($user_id, $foreign_id): array
    {
        $db = new Db();

        return $db->getAllUnseenMessagesOfRoom($user_id, $foreign_id)->get();
    }

    public function getLastMessageByUsers($user_id, $foreign_id): array
    {
        $db = new Db();

        return $db->getLastMessageByUsers($user_id, $foreign_id)->getOne();
    }

    public function getAllRooms($user_id): array
    {
        $db = new Db();

        return $db->getAllRooms($user_id)->get();
    }

    public function seen($id): void
    {
        $db = new Db();
        $db->update('chat')->set(['is_seen' => 1])->where('id', $id)->exec();
    }

}