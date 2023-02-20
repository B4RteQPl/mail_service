<?php

$commands = array();

$commands['InviteUsers'] = require_once('Users/InviteUsersCommand.php');
$commands['KickUser'] = require_once('Users/KickUserCommand.php');
$commands['GetUser'] = require_once('Users/GetUserCommand.php');
$commands['ListUsers'] = require_once('Users/ListUsersCommands.php');
$commands['SearchUser'] = require_once('Users/SearchUserCommands.php');


return $commands;
