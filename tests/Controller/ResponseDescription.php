<?php


namespace App\Tests\Controller;


class ResponseDescription
{
    public const USER_CREATED = 'User created';
    public const USER_DELETED = 'User deleted';
    public const USER_IS_NOT_EXIST = 'User is not exist';
    public const USER_ALREADY_EXIST = 'User already exist';

    public const MEETING_CREATED = 'Meeting created';
    public const MEETING_DELETED = 'Meeting deleted';
    public const MEETING_ORGANISER_IS_NOT_EXIST = 'Meeting organizer is not exist';

    public const INVITATION_CREATED = 'Invitation created';
    public const MEETING_PARTICIPANT_AMOUNT_EXCEEDS_LIMIT = 'Meeting participant amount exceeds limit';
    public const USER_IS_ALREADY_MEETING_PARTICIPANT = 'User ia already meeting participant';
    public const USER_IS_NOT_MEETING_ORGANIZER = 'User is not meeting organizer';

    public const USER_DELETED_FROM_MEETING = 'User deleted from meeting';

    public const EMPTY_REQUEST_PARAMETERS = 'Empty request parameters';
}