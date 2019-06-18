<?php

// Import the classes that we're going to be using
use EDAM\Types\Data, EDAM\Types\NoteSortOrder, EDAM\Types\Note, EDAM\Types\Resource, EDAM\Types\ResourceAttributes;
use EDAM\Error\EDAMUserException, EDAM\Error\EDAMErrorCode;
use Evernote\Client;

//ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . "../../lib" . PATH_SEPARATOR);
ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . "lib" . PATH_SEPARATOR);
require_once 'autoload.php';
require_once 'Evernote/Client.php';
require_once 'packages/Errors/Errors_types.php';
require_once 'packages/Types/Types_types.php';
require_once 'packages/Limits/Limits_constants.php';
require_once 'cliColors/cli_Colors.php';
include 'config.php';

// A global exception handler for our program so that error messages all go to the console
function en_exception_handler($exception){
    echo "Uncaught " . get_class($exception) . ":\n";
    if ($exception instanceof EDAMUserException) {
        echo "Error code: " . EDAMErrorCode::$__names[$exception->errorCode] . "\n";
        echo "Parameter: " . $exception->parameter . "\n";
    } elseif ($exception instanceof EDAMSystemException) {
        echo "Error code: " . EDAMErrorCode::$__names[$exception->errorCode] . "\n";
        echo "Message: " . $exception->message . "\n";
    } else {
        echo $exception;
    }
}
set_exception_handler('en_exception_handler');

// ======================================================================================
// ======================================================================================

if($dev_mode){
    $client = new Client(array('token' => $authToken, 'sandbox' => $dev_mode));
}else{
    $client = new Client(array('token' => $authToken, 'sandbox' => $dev_mode));
}
// ======================================================================================
// ======================================================================================

$userStore = $client->getUserStore();

// Connect to the service and check the protocol version
$versionOK =
    $userStore->checkVersion("Evernote EDAMTest (PHP)",
         $GLOBALS['EDAM_UserStore_UserStore_CONSTANTS']['EDAM_VERSION_MAJOR'],
         $GLOBALS['EDAM_UserStore_UserStore_CONSTANTS']['EDAM_VERSION_MINOR']);

//print "Is my Evernote API version up to date?  " . $versionOK . "\n\n";
if ($versionOK == 0) {
    exit(1);
}
$noteStore = $client->getNoteStore();
// ===============================================
if($dev_mode){ echo "*** DEV MODE *** \n"; }
$cli_banner =
"  _____         _      _     _     _
 |_   _|_ _ ___| | __ | |   (_)___| |_
   | |/ _` / __| |/ / | |   | / __| __|
   | | (_| \__ \   <  | |___| \__ \ |_
   |_|\__,_|___/_|\_\ |_____|_|___/\__|
";

    $colors = new Colors();
	echo $colors->getColoredString($cli_banner, "white", "") . "\n";

    use EDAM\NoteStore\NoteFilter;
    $client = new Client(array(
     'token' => $authToken,
     'sandbox' => $dev_mode
    ));

    // ------- HIGH TASKS ----------
    $count = 0;
    $filter = new NoteFilter();
    $filter->order = NoteSortOrder::TITLE;
    $filter->ascending = TRUE;
// SORT_ORDER_TITLE
    $filter->tagGuids = $tag_guid_high;
    $notes_result = $client->getNoteStore()->findNotes($filter, 0, 10);
    $notes = $notes_result->notes;
	if($notes){
		echo $colors->getColoredString("========= HIGH ================",  "white", "") . "\n";
	}
    foreach ($notes as $note) {
        $count ++;
        echo $colors->getColoredString(" * (".$count.") ".$note->title, "white", "") . "\n";
        //echo "\n ... : "; //print_r($note->tagGuids);
    }
    // ------- MEDIUM TASKS ----------
    $count = 0;
    $filter = new NoteFilter();
    $filter->tagGuids = $tag_guid_medium;
    $notes_result = $client->getNoteStore()->findNotes($filter, 0, 10);
    $notes = $notes_result->notes;
    if($notes){
        echo $colors->getColoredString("\n======== MEDIUM ===============",  "yellow", "") . "\n";
	}
    foreach ($notes as $note) {
        $count ++;
        echo $colors->getColoredString(" * (".$count.") ".$note->title, "yellow", "") . "\n";
    }
    // ------- LOW TASKS -------------
    $count = 0;
    $filter = new NoteFilter();
    $filter->tagGuids = $tag_guid_low;
    $notes_result = $client->getNoteStore()->findNotes($filter, 0, 10);
    $notes = $notes_result->notes;
    if($notes){
        echo $colors->getColoredString("\n========= LOW =================",  "light_green", "") . "\n";
	}
    foreach ($notes as $note) {
        $count ++;
        echo $colors->getColoredString(" * (".$count.") ".$note->title, "light_green", "") . "\n";
    }

    // ------- Count of QUICK TASKS -------------
    $count = 0;
    $filter = new NoteFilter();
    $filter->tagGuids = $tag_guid_quick;
    $notes_result = $client->getNoteStore()->findNotes($filter, 0, 10);
    $notes = $notes_result->notes;
    foreach ($notes as $note) {
        $count ++;
    }
    echo $colors->getColoredString("\n QUICK Notes: ".$count,  "blue", "") . "\n";

    echo "\n\n\n"; // makes it more readable on the CLI
