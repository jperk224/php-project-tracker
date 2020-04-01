<?php
require 'inc/functions.php';

$page = "tasks";
$pageTitle = "Task List | Time Tracker";

include 'inc/header.php';
?>
<div class="section catalog random">

    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">

            <h1 class="actions-header">Task List</h1>
            <div class="actions-item">
                <a class="actions-link" href="task.php">
                    <span class="actions-icon">
                        <svg viewbox="0 0 64 64"><use xlink:href="#task_icon"></use></svg>
                    </span>
                Add Task</a>
            </div>

            <div class="form-container">
                <ul class="items">
                    <!-- iterate over the list of tasks returned from get_task_list()
                    and display it in the task list UI-->
                    <?php
                    foreach(get_task_list() as $task) {
                        echo "<li><a href=\"task.php?id=" . $task["task_id"] . "\">" . 
                        $task["title"] . "</a></li>";
                    }
                    ?>
                </ul>
            </div> <!-- end task list container -->

        </div>
    </div>
</div>

<?php include("inc/footer.php"); ?>
