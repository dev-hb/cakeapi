<?php

/**
 * @author Zakaria HBA
 * @version 1.0
 * @license DevCrawlers's Open Source
 * @since 2019
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
// Parse params to php array
$action->parseParams();

// check if user tokener defined
if((new Helpers())->checkTokener()){
    $token = isset($_GET['token']) ? $action->getParams()['token'] : null;
    (new AccessToken($action->getContext(), $token))->handle();
}

// if the client requested a URL source code
if(isset($_GET['context'])){
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
}
$context = $action->getContext();
/// if the client requested for a text or JSON response
$blueprint = $action->getContext();
// Passing blueprint to Dracula
$action->setDracula(new Dracula($blueprint));
// check if user wants regular models or preferred ones
if(! file_exists('models/'.ucfirst($context).'.php')){
    $action = new PreferredHandler();
    // Parse params to php array
    $action->parseParams();
    // in case of preferred
    $blueprint = $action->getContext();
    // Passing blueprint to Dracula
    $action->setDracula(new Dracula($blueprint));
    $action->setDirectory('preferred');
}else{
    // Check privileges
    $action->setPrivileges(new Privileges($context));
}
// Setting up method (default is GET)

// check if post request required for insert, delete and update operations
if($action->getDirectory() == 'models')
    if(in_array('post', $action->getPrivileges()->getPrivilege()))
        $action->setUsePost(true);

// set the requested method (GET by default)
if($_SERVER['REQUEST_METHOD'] === ActionHandler::POST) $action->setMethod('POST');

// handle habitual actions (findAll, find(by column), delete, update, count)
$action->handle();