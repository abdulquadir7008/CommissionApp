<?php		
session_start();
include 'includes/dbcon.php';
$keyword = strval($_POST['query']);
$search_param = "{$keyword}%";
$sql = $conn->prepare("SELECT * FROM cm_de_commission INNER JOIN qr_attendance_history ON cm_de_commission.attend_id = qr_attendance_history.id WHERE de LIKE ?");
$sql->bind_param("s", $search_param);
$sql->execute();
$result = $sql->get_result();
$countryResult = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $countryResult[] = $row["de"];
    }
    $uniqueCountryResult = array_unique($countryResult);
    $uniqueCountryResult = array_values($uniqueCountryResult);
    echo json_encode($uniqueCountryResult);
}
$conn->close();
?>

