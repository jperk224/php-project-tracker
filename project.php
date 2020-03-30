<?php
require 'inc/functions.php';

$pageTitle = "Project | Time Tracker";
$page = "projects";

// The from to add projects has a method of POST
// Wrap logic to execute if this page is reached via POST request (i.e. user is adding a project)
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // add trim funciton to remove whitespace from beg/end of input
    $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));  // Filter the project title
    $category = trim(filter_input(INPUT_POST, "category", FILTER_SANITIZE_STRING));  // Filter the project category

    // these fields are required in the UI form, make sure they aren't empty
    if(empty($title) || empty($category)) {
        if(empty($title)) {
            $error_message = "Project must have a title.";   // TODO: Make this required to be unique
        }
        else if(empty($cateogry)) {
            $error_message = "Project must have an associated category.";
        }
    }
    else {
        // Add the project to the DB
        if(add_project($title, $category)) {    // returns true if project is successfully added
            // redirect to the project list page to see newlay added project
            header("location:project_list.php");
        } 
        else {
            $error_message = "Error adding project.";
        }
    }
}

include 'inc/header.php';
?>

<div class="section page">
    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">
            <h1 class="actions-header">Add Project</h1>
            <!-- display error message is user does not populate all the required fields -->
            <?php
                if(isset($error_message)) {
                    echo "<p class=\"message\">$error_message</p>";
                }
            ?>
            <form class="form-container form-add" method="post" action="project.php">
                <table>
                    <tr>
                        <th><label for="title">Title<span class="required">*</span></label></th>
                        <td><input type="text" id="title" name="title" value="" /></td>
                    </tr>
                    <tr>
                        <th><label for="category">Category<span class="required">*</span></label></th>
                        <td><select id="category" name="category">
                                <option value="">Select One</option>
                                <option value="Billable">Billable</option>
                                <option value="Charity">Charity</option>
                                <option value="Personal">Personal</option>
                        </select></td>
                    </tr>
                </table>
                <input class="button button--primary button--topic-php" type="submit" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
