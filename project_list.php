<?php
require 'inc/functions.php';

$page = "projects";
$pageTitle = "Project List | Time Tracker";

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

            <div class="form-container">
                <ul class="items">
                    <!-- iterate over the list of projects returned from get_project_list()
                    and display it in the project list UI-->
                    <?php
                    foreach(get_project_list() as $project) {
                        echo "<li>" . $project["title"] . "</li>";
                    }
                    ?>
                </ul>
            </div> <!-- end project list container -->
        </div>
    </div>

</div>

<?php include("inc/footer.php"); ?>
