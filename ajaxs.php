<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
require_once(plugin_dir_path(__FILE__) .'methods.php');
$taskManager = new TaskManager();

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action']; 

switch ($action) {
    case 'create':
        $taskManager->create_task($data);
        break;

    case 'delete':
        $taskManager->delete_task($data);
        break;

    case 'change_status':
        $taskManager->change_task_status($data);
        break;

    default:
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'error', 'message' => 'Неизвестное действие!'));
        break;

}
?>
