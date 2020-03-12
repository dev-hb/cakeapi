<?php

/**
 * @author Zakaria HBA
 * @version BETA
 * @license DevCrawlers's Open Source
 * @since 2020
 * @copyright All Rights Reserved To DevCrawlers
 * @website https://devcrawlers.com
 */

require_once 'autoload.php';

// accord permission to users
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Expose-Headers: *");

$errors = new Errors();
$errors->displayErros();

$action = new ActionHandler();
// if the client requested a URL source code
if($_GET['context'] == "webget"){
    $wget = new WebGet();
    if(! isset($_GET['url'])){
        (new Logger())->shout("please specify a url to be fetched");
    }else{
        $wget->setUrl($action->getParams()['url']);
        (new Logger())->shout($wget->read()->replace()->getResponse());
    }
    die;
}
$context = $action->getContext();
/// if the client requested for a text or JSON response
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