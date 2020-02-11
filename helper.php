<?php

function checkPhoneNumber($number){
    if(!ctype_digit($number)) return false;
    if(strlen($number) != 10) return false;
    $firstNumbers = substr($number, 0, 2);
    if(($firstNumbers != "06") && ($firstNumbers != "07")) return false;
    return true;
}

function parseNumber($number){
    $lastNumbers = substr($number, 1, 9);
    $numberParsed = '+33'.$lastNumbers;
    return $numberParsed;
}

function storeSms($smsData){
    try {
        $db = new PDO('mysql:host='.$localhost.';dbname='.$test.'', $dbHost, $dbPassword);
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
    $test = $smsData['Cost']['Currency'];
    $req = $db->prepare('INSERT INTO sms(from_sms, to_sms, text_sms, message_id, sms_count, creation_ts, cost_value, cost_currency, status_code, status_name, status_description) 
    VALUES(:from_sms, :to_sms, :text_sms, :message_id, :sms_count, :creation_ts, :cost_value, :cost_currency, :status_code, :status_name, :status_description)');
    $req->execute(array(
        'from_sms' => $smsData['From'],
        'to_sms' => $smsData['To'],
        'text_sms' => $smsData['Text'],
        'message_id' => $smsData['ID'],
        'sms_count' => $smsData['SmsCount'],
        'creation_ts' => $smsData['CreationTS'],
        'cost_value' => $smsData['Cost']['Value'],
        'cost_currency' => $smsData['Cost']['Currency'],
        'status_code' => $smsData['Status']['Code'],
        'status_name' => $smsData['Status']['Name'],
        'status_description' => $smsData['Status']['Description']
        ));
}