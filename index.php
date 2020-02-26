<?php

/**
 * @author Zakaria HBA
 * @version BETA
 * @license DevCrawlers's Open Source
 * @since 2020
 * @copyright All Rights Reserved To DevCrawlers
 * @website https://devcrawlers.com
 */

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once 'autoload.php';

$action = new ActionHandler();
$context = $action->getContext();
$blueprint = $action->getContext();
// Passing blueprint to Dracula
$action->setDracula(new Dracula($blueprint));
// check if user wants regular models or preferred ones
if(! file_exists('models/'.ucfirst($context).'.php')){
    $action = new PreferredHandler();
    // in case of preferred
    $blueprint = $action->getContext();
    // Passing blueprint to Dracula
    $action->setDracula(new Dracula($blueprint));
    $action->setDirectory('preferred');
}else{
    // Check priviliges
    $action->setPrivileges(new Privileges($context));
}
// Setting up method (default is GET)
if(count($_POST) > 0) $action->setMethod('POST');
// Parse params to php array
$action->parseParams();
// handle habitual actions (findAll, find(by column), delete, update, count)
$action->handle();