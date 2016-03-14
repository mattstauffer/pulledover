<?php

namespace App\Jobs;

use App\Friend;
use App\Jobs\Job;
use App\Phone\TwilioClient;
use App\PhoneNumber;
use App\Recording;
use App\User;
use Illuminate\Log\Writer as Logger;

class SendNewRecordingNotifications extends Job
{

    /**
     * @var Recording
     */
    private $recording;

    public function __construct(Recording $recording)
    {
        $this->recording = $recording;
    }

    public function handle(TwilioClient $twilio, Logger $logger)
    {
        $user = $this->recording->user;
        $this->notifyOwner($twilio, $logger);
        $this->notifyFriends($twilio, $logger, $user);
    }

    /**
     * @param TwilioClient $twilio
     * @param Logger $logger
     *
     * @throws \App\Phone\Exceptions\BlacklistedPhoneNumberException
     * @throws \App\Phone\Exceptions\InternationalPhoneNumberException
     * @throws \App\Phone\Exceptions\InvalidPhoneNumberException
     * @throws \App\Phone\Exceptions\NonMobilePhoneNumberException
     * @throws \App\Phone\Exceptions\TwilioException
     */
    protected function notifyOwner(TwilioClient $twilio, Logger $logger)
    {
        $text = $this->getOwnerBody();
        $logger->info($this->getOwnerNumber());
        $twilio->text($this->getOwnerNumber(), $text);
        $logger->info('Owner SMS sent: ' . $text);
    }

    /**
     * @return mixed
     */
    protected function getOwnerNumber()
    {
        if($number = $this->recording->phone_number) {
            return $number->number;
        }

        return str_replace('+1', '', $this->recording->from);
    }

    /**
     * @return string
     */
    protected function getOwnerBody()
    {
        $text = sprintf(
            "New Pulledover.us recording. Number: %s\nFrom: %s %s\nURL: %s \n .",
            $this->recording->from,
            $this->recording->city,
            $this->recording->state,
            $this->recording->url
        );

        return $text;
    }

    /**
     * @param TwilioClient $twilio
     * @param Logger $logger
     * @param $user
     */
    protected function notifyFriends(TwilioClient $twilio, Logger $logger,User $user)
    {
        $text = $this->getFriendBody($user);

        foreach ($user->friends()->verified()->get() as $friend) {
            $logger->info($friend->number);
            $this->notifyFriend($friend, $twilio, $text);
        }

        $logger->info('Friends SMS sent: ' . $text);
    }

    /**
     * @param Friend $friend
     * @param TwilioClient $twilio
     * @param $text
     *
     * @throws \App\Phone\Exceptions\BlacklistedPhoneNumberException
     * @throws \App\Phone\Exceptions\InternationalPhoneNumberException
     * @throws \App\Phone\Exceptions\InvalidPhoneNumberException
     * @throws \App\Phone\Exceptions\NonMobilePhoneNumberException
     * @throws \App\Phone\Exceptions\TwilioException
     */
    function notifyFriend(Friend $friend,TwilioClient $twilio, $text)
    {
        $twilio->text($friend->number, $text);
    }

    /**
     * @param $user
     *
     * @return string
     */
    protected function getFriendBody($user)
    {
        $text = sprintf(
            "Your friend {$user->name} has completed a PulledOver recording. From %s, City %s, State %s, Recording %s",
            $this->recording->from,
            $this->recording->city,
            $this->recording->state,
            $this->recording->url
        );

        return $text;
    }
}
