<?php
// Connect to DB (change your credentials here)
$conn = new mysqli("localhost", "root", "", "lms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to show SweetAlert2 popup and redirect back or elsewhere
function alertAndRedirect($icon, $title, $text, $redirect = null) {
    $redirectJS = $redirect ? "window.location.href = '$redirect';" : "window.history.back();";
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$text',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            $redirectJS
        });
    </script>
    </body>
    </html>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (empty($_POST['subject']) || empty($_POST['year']) || !isset($_FILES['file'])) {
        alertAndRedirect('warning', 'Missing Fields', 'Please fill all required fields.');
    }

    $subject = trim($_POST['subject']);
    $year = trim($_POST['year']);
    $file = $_FILES['file'];

    // Validate year format (optional: e.g., 4 digit year)
    if (!preg_match('/^\d{4}$/', $year)) {
        alertAndRedirect('error', 'Invalid Year', 'Please enter a valid 4-digit year.');
    }

    // Upload directory
    $upload_dir = "uploads/";
    if(!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create folder if not exists
    }

    // Check for file upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        alertAndRedirect('error', 'Upload Failed', 'There was an error uploading your file.');
    }

    // Validate file extension
    $allowed_extensions = ['pdf', 'doc', 'docx'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_extensions)) {
        alertAndRedirect('error', 'Invalid File Type', 'Only PDF, DOC, and DOCX files are allowed.');
    }

    // Generate unique file name to avoid overwrite
    $unique_name = time() . "_" . basename($file['name']);
    $upload_path = $upload_dir . $unique_name;

    // Try to move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Insert into DB with status 'pending'
        $status = "pending";
        $submitted_at = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("INSERT INTO pyq_requests (subject, year, file_path, status, submitted_at) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            alertAndRedirect('error', 'Database Error', 'Prepare statement failed: ' . $conn->error);
        }
        $stmt->bind_param("sssss", $subject, $year, $upload_path, $status, $submitted_at);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            alertAndRedirect('success', 'PYQ Submitted', 'Your PYQ has been submitted successfully and is pending approval.', 'upload_pyq.html'); // Redirect back or to form page
        } else {
            $stmt->close();
            $conn->close();
            alertAndRedirect('error', 'Database Error', 'Failed to save PYQ submission: ' . $conn->error);
        }
    } else {
        alertAndRedirect('error', 'Upload Error', "Error uploading file. Please check the 'uploads' folder permissions.");
    }
}

$conn->close();
?>
