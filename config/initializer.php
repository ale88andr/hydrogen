<?php
function setErrorReporting()
{
    error_reporting(E_ALL);
    if (DEVELOPMENT_ENV === true) {
        ini_set('display_errors', 'On');
    }
    else {
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
    }
}
function stripSlashesDeep($value)
{
    $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
    return ($value);
}
function removeMagicQuotes()
{
    if (get_magic_quotes_gpc()) {
        $_GET = stripSlashesDeep($_GET);
        $_POST = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}
function before_load()
{
    setErrorReporting();
    removeMagicQuotes();
}