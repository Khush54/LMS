<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_admin() {
    if (!isset($_SESSION['admin_name'])) {
        header("Location: login.html");
        exit();
    }
}

function require_student() {
    if (!isset($_SESSION['student_id'])) {
        header("Location: login.html");
        exit();
    }
}

function require_portal_user() {
    if (!isset($_SESSION['admin_name']) && !isset($_SESSION['student_id'])) {
        header("Location: login.html");
        exit();
    }
}

function is_demo_admin() {
    return !empty($_SESSION['is_demo_admin']);
}

function demo_block_message($redirect = null) {
    $target = $redirect ? "window.location.href = '$redirect';" : "window.history.back();";
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Demo Mode',
            text: 'This public demo admin is read-only to protect the live database.',
            confirmButtonText: 'OK',
            backdrop: false
        }).then(() => {
            $target
        });
    </script>
    </body>
    </html>";
    exit();
}

function block_demo_admin($redirect = null) {
    require_admin();
    if (is_demo_admin()) {
        demo_block_message($redirect);
    }
}
?>
