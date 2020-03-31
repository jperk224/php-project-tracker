<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = 'all';    // default filter view --> all tasks by project

include 'inc/header.php';
?>
<div class="col-container page-container">
    <div class="col col-70-md col-60-lg col-center">
        <div class="col-container">
            <h1 class='actions-header'>Reports</h1>
        </div>
        <div class="section page">
            <div class="wrapper">
                <table>
                <?php
                // TODO: Can this be moved to a separate file?
                $grandTotalTime = 0;
                $projectId = 0;    // logic will be built off project_id to group tasks
                                    // by project; project_id in table projects starts at 1, so
                                    // initialize to 0 to enter the logic fresh
                $projectTotalTime = 0;
                $tasks = get_task_list($filter);    // make an array variable to leverage the next() function
                                                    // to apply the projectTotalTime output to each project in the 
                                                    // array of tasks
                foreach($tasks as $task) {
                    // this loop works for the default sort because we sort by project title
                    // so once the $projectId changes hands, we've moved onto a new project and
                    // will never revist the old project we left 
                    if ($projectId != $task["project_id"]) {
                        $projectId = $task["project_id"];   // set the $projectId placeholder to the current project
                        echo "<thead>\n";                   // to drive output and add project headers
                        echo "<tr>\n";
                        echo "<th>" . $task["project"] . "</th>\n";     // project title
                        echo "<th>Date</th>\n";
                        echo "<th>Time</th>\n";
                        echo "</tr>\n";
                        echo "</thead>\n";
                    }
                    $projectTotalTime += $task["time"];
                    $grandTotalTime += $task["time"];
                    echo "<tr>\n";
                    echo "<td>" . $task["title"] . "</td>\n";   // TODO: add project name
                    echo "<td>" . $task["date"] . "</td>\n";
                    echo "<td>" . $task["time"] . "</td>\n";
                    echo "</tr>\n";
                    // If the projectId for the next element in the task array is not equal to the current projectId
                    // projects are changing hands, so we need to display the
                    // total of the project before moving on
                    if(next($tasks)["project_id"] != $task["project_id"]) {   
                        echo "<tr>\n";
                        echo "<th class=\"project-total-label\" colspan=\"2\">Project Total</th>\n";
                        echo "<th class=\"project-total-number\">$projectTotalTime</th>\n";
                        echo "</tr>\n";
                        // reset project total time back to 0 for the next project
                        $projectTotalTime = 0;
                    }
                }
                ?>
                    <tr>
                        <th class='grand-total-label' colspan='2'>Grand Total</th>
                        <th class='grand-total-number'><?php echo $grandTotalTime; ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>

