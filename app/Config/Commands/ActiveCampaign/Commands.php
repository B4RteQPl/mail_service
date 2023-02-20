<?php

namespace App\Config\Commands\ActiveCampaign;

use App\Config\BaseConfig;

class Commands extends BaseConfig
{
    protected function setConfig()
    {
        $this->config['createNewContact'] = require_once('Contacts/CreateNewContactCommand.php');
        $this->config['listAllContacts'] = require_once('Contacts/ListAllContactsCommand.php');
        $this->config['retrieveAllLists'] = require_once('Lists/RetrieveAllListsCommand.php');
        $this->config['updateListStatusForContact'] = require_once('Lists/UpdateListStatusForContactCommand.php');
    }

    public function getClient()
    {

    }

    public function setClient()
    {

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
