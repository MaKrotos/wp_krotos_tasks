<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');


class TaskManager {
    public function create_task($data) {
        $title = $data['title'];
        $due_date = $data['due_date'];
        $user_id = $data['user_id']; 

     
        if (empty($title) || empty($due_date) || empty($user_id)) {
  
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'error', 'message' => 'Необходимо указать название задачи, время выполнения задачи!.'));
            exit;
        }

        $task_id = wp_insert_post(array(
            'post_title' => $title,
            'post_type' => 'makrotos_tasks',
            'post_status' => 'publish',
            'post_author' => $user_id, 
        ));

 
        update_post_meta($task_id, '_due_date', $due_date);

        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'task_id' => $task_id));
    }

    public function delete_task($data) {
        $task_id = $data['task_id']; 
        
        if (empty($task_id)) {
      
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'error', 'message' => 'Необходимо указать ID задачи!.'));
            exit;
        }


        wp_delete_post($task_id, true);


        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success'));
    }

    public $task_statuses = array(
        'new' => array(
            'label' => 'Новая',
            'color' => '#0071A17A'
        ),
        'in_progress' => array(
            'label' => 'В процессе',
            'color' => '#F1C40F71'
        ),
        'completed' => array(
            'label' => 'Завершена',
            'color' => '#2ECC7071'
        ),
        'cancelled' => array(
            'label' => 'Отменена',
            'color' => '#E74D3C70'
        )
    );

    public function change_task_status($data) {

        $task_id = $data['task_id'];
        $status = $data['status'];




        if (!array_key_exists($status, $this->task_statuses)) {
       
            header('Content-Type: application/json');
            echo json_encode(array('status' => 'error', 'message' => 'Недопустимый статус задачи! ' . $status));
            exit;
        }

     
        update_post_meta($task_id, 'task_statuses', $status);

        header('Content-Type: application/json');
        echo json_encode(array('status' => 'success', 'color' => $this->task_statuses[$status]["color"]));
    }
}
?>
