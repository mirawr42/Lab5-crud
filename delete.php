<?php
// delete_user.php
require_once 'config.php';
requireLogin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    // Prevent deleting yourself? (Optional – but a good safety)
    if ($id == $_SESSION['user_id']) {
        // Do not allow self deletion
        header("Location: list_users.php?error=cannot_delete_self");
        exit();
    }
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: list_users.php");
exit();
?>