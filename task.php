<?php
require 'inc/functions.php';

$pageTitle = "Task | Time Tracker";
$page = "tasks";

// Declare and initialize form field variables outside the POST logic so they are available
// ouside the POST logic to set as the default form values (i.e. <input value="$variable">)
// POSTing to the page will remember the values in the form if there are issues POSTing (e.g. user
// neglects to populate a required field)
// when the user is directed to this page in some way other than POST, setting these varianbles
// to empty strings will clear the form fields

$project_id = '';
$title = '';
$date = '';
$time = '';

// Filter user input and use it to add a task to the DB
// The form to add tasks has a method of POST
// Wrap logic to execute if this page is reached via POST request (i.e. user is adding a task)
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // add trim function to remove whitespace from beg/end of input
    $project_id = trim(filter_input(INPUT_POST, "project_id", FILTER_SANITIZE_NUMBER_INT));     // Filter the project ID
    $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));                   // Filter the task title
    $date = trim(filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING));                     // Filter the task date
    $time = trim(filter_input(INPUT_POST, "time", FILTER_SANITIZE_NUMBER_INT));                 // Filter the task time

    // ensure the $date POSTed is in the specified format (mm/dd/yyyy)
    $dateMatch = explode("/", $date);   // convert the value passed into an array deliminted by '/'
                                        // result SHOULD BE a 3 element array of mm dd yyyy

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
        else {  // If you've reached this by default, $time is empty  
            $error_message = "Please assign a valid time.";
        }
    }
    // Validate date entry
    // TODO: If date type in DB were DATE could not just date checker be used?
    else if((count($dateMatch) != 3)        // a valid date input should yield a 3 element array
                || (strlen($dateMatch[0]) != 2) // check for 2-digit month
                || (strlen($dateMatch[1]) != 2) // check for 2-digit day
                || (strlen($dateMatch[2]) != 4) // check for 4-digit year
                || (!checkdate($dateMatch[0], $dateMatch[1], $dateMatch[2]))) {   // check date is valid
            $error_message = "Date entered is invalid.  Please use format MM/DD/YYYY.";
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
                                foreach(get_project_list() as $project) {
                                    echo "<option value=\"" . $project["project_id"] . "\"";
                                    // The HTML attribute 'selected' defaults the option to selected
                                    // in the drop down; if the $project_id is not an empty
                                    // string, we know a project was selected via POST
                                    // when looping through, once you reach the project that matches
                                    // the project_id variable, set its HTML attribute 'selected' so
                                    // that project is rendered as selected in the form
                                    if($project_id == $project["project_id"]) {
                                        echo " selected";   // tacks it to the end of the attributes
                                                            // inside the opening option tag
                                    }
                                    echo ">" . $project["title"] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <!-- Set the field values equal to the PHP variables declared at the beginning
                        of the script to hold form values when a POST fails; escape
                        the output to prevent XSS -->
                        <th><label for="title">Title<span class="required">*</span></label></th>
                        <td><input type="text" id="title" name="title" 
                            value="<?php echo htmlspecialchars($title /*default escape , default encoding (UTF-8)*/); ?>" /></td>
                    </tr>
                    <tr>
                        <th><label for="date">Date<span class="required">*</span></label></th>
                        <td><input type="text" id="date" name="date" 
                            value="<?php echo htmlspecialchars($date /*default escape , default encoding (UTF-8)*/); ?>" placeholder="mm/dd/yyyy" /></td>
                    </tr>
                    <tr>
                        <th><label for="time">Time<span class="required">*</span></label></th>
                        <td><input type="text" id="time" name="time" 
                            value="<?php echo htmlspecialchars($time /*default escape , default encoding (UTF-8)*/); ?>" /> minutes</td>
                    </tr>
                </table>
                <input class="button button--primary button--topic-php" type="submit" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
