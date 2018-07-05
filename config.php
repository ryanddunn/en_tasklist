<?php

$dev_mode = false;

if($dev_mode){
    // TEST Account for <me@ryandunn.co>
    $authToken = "S=s1:U=9364b:E=16bb96be492:C=16461bab780:P=1cd:A=en-devtoken:V=2:H=a560600b92105c82303b4a6cb05a90d4";

    // "Action Item" tag GUID, this changes per account you are using
    $actionitem_tag_guid = array("12f1bf0a-4179-4c78-979d-f1c89034a115");
}else{
    // PRODUCTION Acccont
    $authToken = "S=s4:U=45d6e:E=16bb37d04a0:C=1645bcbd778:P=1cd:A=en-devtoken:V=2:H=0aa54dd63e4cb5169b0c05b2e92bb61c";
    //$client = new Client(array('token' => $authToken, 'sandbox' => false));

    // "Action Item" tag GUID, this changes per account you are using
    $actionitem_tag_guid = array("42cdbc60-d081-45b5-960e-1c7d0f78b066");
}
?>
