<?php

namespace App\Jobs;

use App\Friend;
use App\Jobs\Job;
use App\Phone\Exceptions\BlacklistedPhoneNumberException;
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

    protected function notifyOwner(TwilioClient $twilio, Logger $logger)
    {
        $text = $this->getOwnerBody();
        $logger->info($this->getOwnerNumber());

        try {
            $twilio->text($this->getOwnerNumber(), $text);
        } catch (BlacklistedPhoneNumberException $e) {
            $this->recording->phoneNumber->markBlacklisted();
        }

        $logger->info('Owner SMS sent: ' . $text);
    }

    protected function getOwnerNumber()
    {
        if ($number = $this->recording->phoneNumber) {
            return $number->number;
        }

        return str_replace('+1', '', $this->recording->from);
    }

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

    protected function notifyFriends(TwilioClient $twilio, Logger $logger, User $user)
    {
        $text = $this->getFriendBody($user);

        foreach ($user->friends()->verified()->get() as $friend) {
            $logger->info($friend->number);
            $this->notifyFriend($friend, $twilio, $text);
        }

        $logger->info('Friends SMS sent: ' . $text);
    }

    public function notifyFriend(Friend $friend, TwilioClient $twilio, $text)
    {
        try {
            $twilio->text($friend->number, $text);
        } catch (BlacklistedPhoneNumberException $e) {
            $friend->markBlacklisted();
        }
    }

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
