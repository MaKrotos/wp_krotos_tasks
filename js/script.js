function showNewTaskForm() {
    document.getElementById('new-task-form').style.display = 'block';
}

function saveTask() {
    var title = document.getElementById('task-title').value;
    var due_date = document.getElementById('task-due-date').value;
    var user_id = document.getElementById('user-id').value;

    fetch('/wp-content/plugins/krotos_tasks/ajaxs.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'create',
            title: title,
            due_date: due_date,
            user_id: user_id 
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            console.log(data);
            var row = document.createElement('tr');
            var taskId = data.task_id;
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${title}</td>
                <td>publish</td>
                <td>${due_date}</td>
                <td>
                    <button data-task-id="${taskId}" class="delete-task-button" onclick="deleteTask(this)">Удалить</button>
                </td>
            `;
            document.querySelector('.wp-list-table tbody').appendChild(row);

            document.querySelector('.wp-list-table tbody').appendChild(row);


            document.getElementById('task-title').value = '';
            document.getElementById('task-due-date').value = '';
        } else {

            alert(data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });

}

function deleteTask(button) {
    var taskId = button.dataset.taskId;

    fetch('/wp-content/plugins/krotos_tasks/ajaxs.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'delete',
            task_id: taskId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {

            console.log(data);
            button.parentElement.parentElement.remove();
        } else {

            alert(data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });

}

function changeTaskStatus(changedElement) {
    var taskId = changedElement.getAttribute('data-task-id');
    var newStatus = changedElement.value;

    var js = JSON.stringify({
        action: "change_status",
        task_id: taskId,
        status: newStatus
    });
    console.log(js);

fetch('/wp-content/plugins/krotos_tasks/ajaxs.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: js
})
.then(response => response.json())
.then(data => {
    console.log(data);
    if (data.status == 'success') {
        console.log('Статус задачи ' + taskId + ' успешно изменен на ' + newStatus + '!');
        changedElement.parentElement.parentElement.style.backgroundColor =  data.color ;
        console.log(changedElement);

    } else {
        console.error('Ошибка при изменении статуса задачи: ' + data.message);
    }
})
.catch((error) => {
    console.error('Ошибка при отправке запроса: ', error);
});

}

