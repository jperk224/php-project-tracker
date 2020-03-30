<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = 'all';

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
                // Sum up the total time from all tasks across all projects
                // To include in the Grand Total
                // Default view is to group tasks by project
                $grandTotalTime = 0; 
                foreach(get_task_list($filter) as $task) {
                    $grandTotalTime += $task["time"];
                    echo "<tr>\n";
                    echo "<td>" . $task["title"] . "</td>\n";   // TODO: add project name
                    echo "<td>" . $task["date"] . "</td>\n";
                    echo "<td>" . $task["time"] . "</td>\n";
                    echo "</tr>\n";
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

