#!/usr/bin/php
<?php

require_once __DIR__."/config.php";

exec("curl -G https://api.abuseipdb.com/api/v2/blacklist \
  -d countMinimum=15 \
  -d maxAgeInDays=60 \
  -d confidenceMinimum=90 \
  -H \"Key: ".ABUSE_IP_DB_KEY."\" \
  -H \"Accept: application/json\" > abuseipdb-data.json");

$fileContents = file_get_contents(__DIR__."/abuseipdb-data.json");

$object = json_decode($fileContents);

if (isset($object -> errors) || !$object || empty($object)) {
    print PHP_EOL.$object -> errors[0] -> detail.PHP_EOL.PHP_EOL;
    unlink(__DIR__."/abuseipdb-data.json");
    exit;
}

$response = null;

if (file_exists(__DIR__."/local-blacklist.conf") && is_file(__DIR__."/local-blacklist.conf")) {
    $response = file_get_contents(__DIR__."/local-blacklist.conf").PHP_EOL;
}

$count = 0;
foreach ($object -> data as $key => $values) {
    if ($values -> abuseConfidenceScore >= ABUSE_CONFIDENCE_SCORE) {
        $response .= "deny ".$values -> ipAddress.";".PHP_EOL;
        $count++;
    }
}

file_put_contents(__DIR__."/nginx-abuseipdb-blacklist.conf", $response);

unlink(__DIR__."/abuseipdb-data.json");
print PHP_EOL;
print PHP_EOL;
print "Added ".$count." ip addresses to your blacklist.".PHP_EOL;
print "You can now test the configuration: nginx -t".PHP_EOL;
print "You will also want to reload nginx. For example, sudo service nginx reload on Ubuntu.".PHP_EOL;
