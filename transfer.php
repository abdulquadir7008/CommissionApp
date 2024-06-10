<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/dbcon.php';

if (isset($_GET['event_id'])) {
    $eventID = mysqli_real_escape_string($conn, $_GET['event_id']);

    // Define the number of times you want to insert
    $insertCount = 1; // Change this value based on your requirement

    for ($i = 0; $i < $insertCount; $i++) {
        // Insert selected columns from qr_attendance into qr_attendance_history
        $insertQuery = "INSERT INTO qr_attendance_history 
                        (id, de, past_new_de, agency_count, de_to_call, reminder_call1, reminder_call2, onsite_time, onsite_remarks, notes, status, polling_no, lanyard, qrcode, badge_name, firstname, lastname, job_title, org, did, mobile, email, alt_email, office_address, street, city, postal_code, country, wishlist, diet, parking, grouping, sched, job_category,vaccinated, roe, event_id, create_date, unique_id, created_by, approved_by, approved_time, state) 
                        SELECT id, de, past_new_de, agency_count, de_to_call, reminder_call1, reminder_call2, onsite_time, onsite_remarks, notes, status, polling_no, lanyard, qrcode, badge_name, firstname, lastname, job_title, org, did, mobile, email, alt_email, office_address, street, city, postal_code, country, wishlist, diet, parking, grouping, sched,job_category, vaccinated, roe, event_id, create_date, unique_id, created_by, approved_by, approved_time, state
                        FROM qr_attendance";
        $insertResult = mysqli_query($conn, $insertQuery);

        if (!$insertResult) {
            // If the insertion fails, provide error details
            echo "<script>
                    alert('Error transferring data to qr_attendance_history: " . mysqli_error($conn) . "');
                    window.location.href = 'event_manage.php';
                  </script>";
            break;
        }
    }

    // Update the status in qr_event table to 'Completed'
    $updateQuery = "UPDATE qr_event SET STATUS = 'Completed' WHERE ID = $eventID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        // If successful display an alert and redirect
        echo "<script>
                alert('Data transferred successfully to qr_attendance_history and event marked as complete.');
                window.location.href = 'event_manage.php';
              </script>";
    } else {
        // If the update query is unsuccessful, provide error details
        echo "<script>
                alert('Error updating event status: " . mysqli_error($conn) . "');
                window.location.href = 'event_manage.php';
              </script>";
    }
} else {
    // Display an error message if the event_id parameter is not set
    echo "<script>
            alert('Error: Event ID not specified in the URL.');
            window.location.href = 'event_manage.php';
          </script>";
}

// Close your database connection if needed
mysqli_close($conn);
?>
