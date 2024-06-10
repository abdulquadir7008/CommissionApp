<?php		
session_start();
include 'includes/dbcon.php';
$keyword = strval($_POST['query']);
$search_param = "{$keyword}%";
$sql = $conn->prepare("SELECT * FROM qr_hubspot_check WHERE fullname LIKE ?");
$sql->bind_param("s", $search_param);
$sql->execute();
$result = $sql->get_result();
$countryResult = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $countryResult[] = $row["fullname"];
    }
    $uniqueCountryResult = array_unique($countryResult);
    $uniqueCountryResult = array_values($uniqueCountryResult);
    echo json_encode($uniqueCountryResult);
}
$conn->close();
?>

