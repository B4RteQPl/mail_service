<?php

namespace App\Interfaces;

interface SubscriberInterface
{
    public function __construct(string $email, string $firstName, string $lastName, string $phoneNumber);

    public function getFirstName();
    public function getLastName();
    public function getEmail();
    public function getPhoneNumber();

}
