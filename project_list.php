<?php
require 'inc/functions.php';

$page = "projects";
$pageTitle = "Project List | Time Tracker";

if(isset($_POST["delete"])) {   
    $projectId = filter_input(INPUT_POST, "delete", FILTER_SANITIZE_NUMBER_INT);
    echo delete_project($projectId);
    // if(delete_project($projectId)) {
    //     header("location:project_list.php?msg=Project+Deleted");
    //     exit;
    // }
    // else {
    //     header("location:project_list.php?msg=Unable+to+Delete+Project");
    //     exit;
    // }
}

if(isset($_GET["msg"])) {
    $error_message = filter_input(INPUT_GET, "msg", FILTER_SANITIZE_STRING);
}

include 'inc/header.php';
?>
<div class="section catalog random">

    <div class="col-container page-container">
        <div class="col col-70-md col-60-lg col-center">
            <h1 class="actions-header">Project List</h1>
            <div class="actions-item">
                <a class="actions-link" href="project.php">
                <span class="actions-icon">
                  <svg viewbox="0 0 64 64"><use xlink:href="#project_icon"></use></svg>
                </span>
                Add Project
                </a>
            </div>
            <!-- display error message if project can't be deleted -->
            <?php
                if(isset($error_message)) {
                    echo "<p class=\"message\">$error_message</p>";
                }
            ?>
            <div class="form-container">
                <ul class="items">
                    <!-- iterate over the list of projects returned from get_project_list()
                    and display it in the project list UI-->
                    <?php
                    foreach(get_project_list() as $project) {
                        echo "<li><a href=\"project.php?id=" . $project["project_id"] . "\">"
                        . $project["title"] . "</a>";
                        
                        // Add a delete form to each item
                        // onsubmit executes a small JS confirmation
                        echo "<form action=\"project_list.php\" method=\"post\"
                                onsubmit=\"return confirm('Are you sure?')\">\n";
                        echo "<input type=\"hidden\" value=\"" . $project["project_id"] . "\" name=\"delete\">";
                        echo "<input type=\"submit\" class=\"button--delete\" value=\"delete\">";
                        echo "</form>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div> <!-- end project list container -->
        </div>
    </div>

</div>

<?php include("inc/footer.php"); ?>
