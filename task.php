<?php
require 'inc/functions.php';

$pageTitle = "Task | Time Tracker";
$page = "tasks";

// Filter user input and use it to add a task to the DB
// The form to add tasks has a method of POST
// Wrap logic to execute if this page is reached via POST request (i.e. user is adding a task)
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // add trim function to remove whitespace from beg/end of input
    $project_id = trim(filter_input(INPUT_POST, "project_id", FILTER_SANITIZE_NUMBER_INT));     // Filter the project ID
    $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));                   // Filter the task title
    $date = trim(filter_input(INPUT_POST, "category", FILTER_SANITIZE_STRING));                 // Filter the task date
    $time = trim(filter_input(INPUT_POST, "project_id", FILTER_SANITIZE_NUMBER_INT));           // Filter the task time

    // these fields are required in the UI form, make sure they aren't empty
    if(empty($project_id) || empty($title) || empty($date) || empty($time)) {
        if(empty($project_id)) {
            $error_message = "Task must be assigned to an existing project.";
        }
        else if(empty($title)) {
            $error_message = "Task must have a title.";
        }
        else if(empty($date)) {
            $error_message = "Please assign a valid date.";
        }
        else {
            $error_message = "Please assign a valid time.";
        }
    }
    else {
        // Add the taskto the DB
        if(add_task($project_id, $title, $date, $time)) {    // returns true if task is successfully added
            // redirect to the task list page to see newly added task
            header("location:task_list.php");
        } 
        else {
            $error_message = "Error adding task.";
        }
    }
}

include 'inc/header.php';
?>

<div class="section page">
    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">
            <h1 class="actions-header">Add Task</h1>
            <!-- display error message is user does not populate all the required fields -->
            <?php
                if(isset($error_message)) {
                    echo "<p class=\"message\">$error_message</p>";
                }
            ?>
            <form class="form-container form-add" method="post" action="task.php">
                <table>
                    <tr>
                        <th>
                            <label for="project_id">Project</label>
                        </th>
                        <td>
                            <select name="project_id" id="project_id">
                                <option value="">Select One</option>
                                <!-- iterate over the list of projects in the DB and
                                display them in the dropdown for selection-->
                                <?php
                                foreach(pull_project_list() as $project) {
                                    echo "<option value=\"" . $project["project_id"] . "\">" 
                                    . $project["title"] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="title">Title<span class="required">*</span></label></th>
                        <td><input type="text" id="title" name="title" value="" /></td>
                    </tr>
                    <tr>
                        <th><label for="date">Date<span class="required">*</span></label></th>
                        <td><input type="text" id="date" name="date" value="" placeholder="mm/dd/yyyy" /></td>
                    </tr>
                    <tr>
                        <th><label for="time">Time<span class="required">*</span></label></th>
                        <td><input type="text" id="time" name="time" value="" /> minutes</td>
                    </tr>
                </table>
                <input class="button button--primary button--topic-php" type="submit" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
