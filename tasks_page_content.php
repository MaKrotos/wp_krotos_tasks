<?php
require_once(plugin_dir_path(__FILE__) .'methods.php');
$taskManager = new TaskManager();

function tasks_page_content() {

    $current_user_id = get_current_user_id();
    $tasks = get_posts(array(
        'post_type' => 'makrotos_tasks',
        'posts_per_page' => -1,
        'author' => $current_user_id,
    ));
    $current_user_id = get_current_user_id();
    global $taskManager;
    ?>

<script src="<?php echo plugin_dir_url(__FILE__) . 'js/script.js'; ?>"></script>
    <div class="wrap">
        <h1 class="wp-heading-inline">Таски от МаКротоса</h1>
        <button id="new-task-button">Создать задачу</button>
        <div id="new-task-form" style="display: none;">
            <input type="text" id="task-title" placeholder="Task Title">
            <input type="date" id="task-due-date">
            <input type="hidden" id="user-id" value="<?php echo $current_user_id; ?>">
            <button id="save-task-button">Сохранить таску</button>
        </div>
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th>Таска</th>
                    <th>Статус</th>
                    <th>Дедлайн</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($tasks as $task) : 
    $status = get_post_meta($task->ID, 'task_statuses', true);
    $color = $taskManager->task_statuses[$status]['color'];
?>
    <tr style="background-color: <?php echo $color; ?>;">
        <td><?php echo $task->post_title; ?></td>
        <td>
            <select onchange="changeTaskStatus(this)" class="task-status" data-task-id="<?php echo $task->ID; ?>">
            <?php foreach ($taskManager->task_statuses as $status => $details) : ?>
                <option value="<?php echo $status; ?>" <?php selected(get_post_meta($task->ID, 'task_statuses', true), $status); ?> style="background: <?php echo $details['color']; ?>;"><?php echo $details['label']; ?></option>
            <?php endforeach; ?>
            </select>
        </td>
        <td><?php echo get_post_meta($task->ID, '_due_date', true); ?></td>
        <td><button onclick="deleteTask(this)" class="delete-task-button" data-task-id="<?php echo $task->ID; ?>">Удалить</button></td>
    </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>
