<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	<?php
	
	$firstname = 'kadir';
	$lastname = 'Abdul';
	$rw_job_title = 'web design';
	$cleanedNumber = '+91 9938833873';
	$rw_city	= 'cuttack';
	$rw_postal	= '754200';
	$rw_country	= 'India';

	$db_firstname = 'kadir';
	$db_lastname = 'Abdul';
	$db_job_title = 'web design';
	$db_cleanedNumber = '+91 9938833873';
	$db_city	= 'cuttack';
	$db_postal	= '754200';
	$db_country	= 'India';

	
	$conditions = [
                  'Firstname' => $firstname != $db_firstname,
                  'Lastname' => $lastname != $db_lastname,
                  'Designation' => $rw_job_title != $db_job_title,
                  'Mobile' => $cleanedNumber != $db_cleanedNumber,
                  'City' => $rw_city != $db_city,
                  'Postal Code' => $rw_postal != $db_postal,
                  'Country' => $rw_country != $db_country,
                ];

                foreach ( $conditions as $key => $condition ) {
                  if ( $condition ) {
                    echo $invalid = $key;
                    break;
                  }
                }
	?>
</body>
</html>