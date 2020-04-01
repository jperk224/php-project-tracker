<?php
require 'inc/functions.php';

$pageTitle = "Project | Time Tracker";
$page = "projects";

// Declare and initialize form field variables outside the POST logic so they are available
// ouside the POST logic to set as the default form values (i.e. <input value="$variable">)
// POSTing to the page will remember the values in the form if there are issues POSTing
// See task.php for more discussion around this
$title = '';
$category = '';

// If we reach this page via GET request, user has either opted to add a project
// or clicked a link of an existing project; if the latter 'id' will be set in the query string
if(isset($_GET["id"])) {
    $projectId = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT); // set to project id received in GET
    // get_project() returns an array of all the project details by the id passed in
    $projectId = get_project($projectId)["project_id"];
    $title = get_project($projectId)["title"];
    $category = get_project($projectId)["category"];
}

// Filter user input and use it to add a project to the DB
// The form to add projects has a method of POST
// Wrap logic to execute if this page is reached via POST request (i.e. user is adding a project)
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // add trim function to remove whitespace from beg/end of input
    $title = trim(filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING));  // Filter the project title
    $category = trim(filter_input(INPUT_POST, "category", FILTER_SANITIZE_STRING));  // Filter the project category
    // capturing the project ID from the POST if user is editing an existing project
    // filter_input returns a null if there's not value to filter, so this can be an optional parameter
    // for the SQL function add_project()
    $projectId = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);  // Filter the project Id

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
        if(add_project($title, $category, $projectId)) {    // returns true if project is successfully added
            // redirect to the project list page to see newly added project
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
        <!-- dynamically update header to show whether we're adding a new or updating and existing project -->
            <h1 class="actions-header">
            <?php
            // if projectId is not empty, we know we're updating as project, not adding new
            if(!empty($projectId)) {
                echo "Update Project : " . $title;
            }
            else {
                echo "Add Project";
            }
            echo "</h1>";
            ?>
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
                        <td><input type="text" id="title" name="title" 
                            value="<?php echo htmlspecialchars($title /*default escape , default encoding (UTF-8)*/); ?>" /></td>
                    </tr>
                    <tr>
                        <!-- Get category list from DB, loop, and form field retention -->
                        <!-- TODO: How to add a new category?? -->
                        <!-- TODO: Save selection on POST failure -->
                        <th><label for="category">Category<span class="required">*</span></label></th>
                        <td><select id="category" name="category">
                                <option value="">Select One</option>
                                <?php
                                $categories = get_project_categories();
                                foreach($categories as $instance) {
                                    echo "<option value=\"" . $instance["category"] . "\""; 
                                    if($category == $instance["category"]) {
                                        echo " selected ";
                                    }
                                    echo ">" . $instance["category"];
                                    echo"</option>";
                                }
                                ?>
                        </select></td>
                    </tr>
                </table>
                <?php
                // If the projectId is not empty, we've gotten to this form from a GET and we're editing
                // an existing project.  We need to capture the projectId to pass into out update SQL
                if(!empty($projectId)) {
                    echo "<input type=\"hidden\" name=\"id\" value=\"" . $projectId . "\">";
                }
                ?>
                <input class="button button--primary button--topic-php" type="submit" value="Submit" />
            </form>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>
