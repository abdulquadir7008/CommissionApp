<?php
$subProp = ( $_REQUEST[ "city_id" ] <> "" ) ? trim( $_REQUEST[ "city_id" ] ) : "";
if ( $subProp <> "" ) {

  $access_token = "";
  $headers = array(
    "Authorization: Bearer $access_token",
    "Content-Type: application/json"
  );
  $url = "https://api.hubapi.com/properties/v1/contacts/properties/named/$subProp";
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
  $response = curl_exec( $ch );
  $status_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
  curl_close( $ch );
  if ( $status_code == 200 ) {
    $data = json_decode( $response, true );
    $options = $data[ 'options' ];
    echo "<label>Sub Property</label><select class='form-control' name='subProperty' required onChange='showSub(this);'><option value=''>Select Sub Property</option>";
    foreach ( $options as $index => $result ) {
      echo "<option value='" .$subProp.",". $result[ 'value' ] . "'>" . $result[ 'label' ] . "</option>";
    }
	  echo "</select>";
  }
}
?>
