<?php
include( 'vendor/autoload.php' );
include '../includes/dbcon.php';
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\ApiException;
use HubSpot\Client\Crm\Contacts\Model\Filter;
use HubSpot\Client\Crm\Contacts\Model\FilterGroup;
use HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest;

$client = Factory::createWithAccessToken( $Hub_token_id );

$sub_id = ( $_REQUEST[ "sub_id" ] <> "" ) ? trim( $_REQUEST[ "sub_id" ] ) : "";
if ( $sub_id <> "" ) {
	$subid2= explode(",",$sub_id);
	foreach ($subid2 as $key => $value) {
		if($key==0){
			$propertyOne = $value;
		}
		else{
			$propertyTwo = $value;
		}
   
}

$filter1 = new Filter( [
  //    'high_value' => 'string',
  'property_name' => $propertyOne,
  //    'values' => ['string'],
  'value' => $propertyTwo,
  'operator' => 'EQ'
] );
$filterGroup1 = new FilterGroup( [
  'filters' => [ $filter1 ]
] );
$publicObjectSearchRequest = new PublicObjectSearchRequest( [
  //    'query' => 'string',
  'limit' => 100,
  //    'after' => 'string',
  //    'sorts' => ['string'],
  'properties' => [ 'firstname', 'lastname', 'phone', 'email', 'delegate', 'jobtitle', 'company', 'address', 'city', 'country', 'zip', 'mobilephone', 'confirmed_events_2024' ],
  'filter_groups' => [ $filterGroup1 ],
] );
try {
  $response = $client->crm()->contacts()->searchApi()->doSearch( $publicObjectSearchRequest );
  //    var_dump($response);
  //	echo $response;
  $data = json_decode( $response, true );

  // Access total
  $total = $data[ 'total' ];
  //echo "Total: $total\n";

  // Access results
  $results = $data[ 'results' ];
  echo "<h3 style='width: 100%; margin-top: 30px;margin-left: 17px; color:#0B4596'>HubSpot Contacts</h3>
  <p style='margin-left: 17px; color:black'>".$propertyOne." / ".$propertyTwo."</p>
  <div style='height:500px; overflow:auto; width:80%; margin-top:20px;'>
  <table style='border:1px solid #ccc;'  border=1>
	<thead>
	<th>No.</th>
		<th>firstname</th>
		<th>lastname</th>
		<th>email</th>
		<th>phone</th>
		<th>email</th>
		<th>delegate</th>
		<th>jobtitle</th>
		<th>company</th>
		<th>address</th>
		<th>city</th>
		<th>country</th>
		<th>zip</th>
		<th>mobilephone</th>
		<th>".$propertyOne."</th>
		</thead><tbody>";
  foreach ( $results as $index => $result ) {
    // Access individual result properties
    $id = $result[ 'id' ];
    $firstname = $result[ 'properties' ][ 'firstname' ];
    $lastname = $result[ 'properties' ][ 'lastname' ];
    $email = $result[ 'properties' ][ 'email' ];
    $phone = $result[ 'properties' ][ 'phone' ];
    $email = $result[ 'properties' ][ 'email' ];
    $delegate = $result[ 'properties' ][ 'delegate' ];
    $jobtitle = $result[ 'properties' ][ 'jobtitle' ];
    $company = $result[ 'properties' ][ 'company' ];
    $address = $result[ 'properties' ][ 'address' ];
    $city = $result[ 'properties' ][ 'city' ];
    $country = $result[ 'properties' ][ 'country' ];
    $zip = $result[ 'properties' ][ 'zip' ];
    $mobilephone = $result[ 'properties' ][ 'mobilephone' ];
    $confirmed_events_2024 = $result[ 'properties' ][ 'confirmed_events_2024' ];


    echo "<tr>
			<td> </td>
			<td>" . $firstname . "</td>
			<td>" . $lastname . "</td>
			<td>" . $email . "</td>
			<td>" . $phone . "</td>
			<td>" . $email . "</td>
			<td>" . $delegate . "</td>
			<td>" . $jobtitle . "</td>
			<td>" . $company . "</td>
			<td>" . $address . "</td>
			<td>" . $city . "</td>
			<td>" . $country . "</td>
			<td>" . $zip . "</td>
			<td>" . $mobilephone . "</td>
			<td>" . $confirmed_events_2024 . "</td>		
		</tr>";
  }
  echo "</tbody></table></div>";
} catch ( ApiException $e ) {
  echo "Exception when calling search_api->do_search: ", $e->getMessage();
}

}