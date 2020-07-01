<?php

namespace App\Entity;

use App\Repository\MeetingParticipantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetingParticipantRepository::class)
 */
class MeetingParticipant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="binary")
     */
    private $user_uuid;

    /**
     * @ORM\Column(type="binary")
     */
    private $meeting_uuid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserUuid()
    {
        return $this->user_uuid;
    }

    public function setUserUuid($user_uuid): self
    {
        $this->user_uuid = $user_uuid;

        return $this;
    }

    public function getMeetingUuid()
    {
        return $this->meeting_uuid;
    }

    public function setMeetingUuid($meeting_uuid): self
    {
        $this->meeting_uuid = $meeting_uuid;

        return $this;
    }
}
