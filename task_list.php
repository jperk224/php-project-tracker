<?php
require 'inc/functions.php';

$page = "tasks";
$pageTitle = "Task List | Time Tracker";

if(isset($_POST["delete"])) {   
    $taskId = filter_input(INPUT_POST, "delete", FILTER_SANITIZE_NUMBER_INT);
    if(delete_task($taskId)) {
        header("location:task_list.php?msg=Task+Deleted");
        exit;
    }
    else {
        header("location:task_list.php?msg=Unable+to+Delete+Task");
        exit;
    }
}

if(isset($_GET["msg"])) {
    $error_message = filter_input(INPUT_GET, "msg", FILTER_SANITIZE_STRING);
}

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
            <!-- display error message if task can't be deleted -->
            <?php
                if(isset($error_message)) {
                    echo "<p class=\"message\">$error_message</p>";
                }
            ?>
            <div class="form-container">
                <ul class="items">
                    <!-- iterate over the list of tasks returned from get_task_list()
                    and display it in the task list UI-->
                    <?php
                    foreach(get_task_list() as $task) {
                        echo "<li><a href=\"task.php?id=" . $task["task_id"] . "\">" . 
                        $task["title"] . "</a>";
                        // Add a delete form to each item
                        // onsubmit executes a small JS confirmation
                        echo "<form action=\"task_list.php\" method=\"post\"
                                onsubmit=\"return confirm('Are you sure?')\">\n";
                        echo "<input type=\"hidden\" value=\"" . $task["task_id"] . "\" name=\"delete\">";
                        echo "<input type=\"submit\" class=\"button--delete\" value=\"delete\">";
                        echo "</form>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div> <!-- end task list container -->

        </div>
    </div>
</div>

<?php include("inc/footer.php"); ?>
