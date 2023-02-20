<?php

namespace App\Config\Commands\ActiveCampaign\Contacts;

use App\Config\BaseConfig;

class ListAllContactsCommand // extends BaseConfig
{
    //    protected function setConfig()
    //    {
    //        $this->config['createNewContact'] = require_once('Contacts/CreateNewContactCommand.php');
    //        $this->config['listAllContacts'] = require_once('Contacts/ListAllContactsCommand.php');
    //        $this->config['retrieveAllLists'] = require_once('Lists/RetrieveAllListsCommand.php');
    //        $this->config['updateListStatusForContact'] = require_once('Lists/UpdateListStatusForContactCommand.php');
    //    }

    public function handler()
    {

    }

    public function getConfig($name)
    {
        if ($name === 'call') {
            return
        }
        return $this->config[$name];
    }


}


//
//$commands = array();
//
//$commands['CreateNewContact'] = require_once('Contacts/CreateNewContactCommand.php');
//$commands['ListAllContacts'] = require_once('Contacts/ListAllContactsCommand.php');
//$commands['RetrieveAllLists'] = require_once('Lists/RetrieveAllListsCommand.php');
//$commands['UpdateListStatusForContact'] = require_once('Lists/UpdateListStatusForContactCommand.php');
//
//return $commands;



//<?php
//
//
//use App\Commands\ActiveCampaign\Contacts\ListAllContactsCommand;
//
//return array(
//    'class' => ListAllContactsCommand::class,
//    'title' => [
//        'pl' => 'Pobierz użytkownika',
//        'en' => 'Get a User',
//    ],
//    'description' => [
//        'pl' => 'Zwróć szczegóły o użytkowniku',
//        'en' => 'Returns details about a member of a workspace',
//    ],
//    'parameters' => [],
//);
