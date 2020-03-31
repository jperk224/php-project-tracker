<?php
//application functions

// Pull the projects from the database
// returns the full result set
function get_project_list() {
    include("connection.php");
    // Function has no parameters, just pulling projects from the DB
    // Can use PDO query method -> returns a PDOStatement object 
    // (i.e. a result set that can be iterated over; keys are result columns) 
    // returns false on failure
    try {
        $sql = "SELECT project_id, title, category FROM projects";
        return $db->query($sql);
    }
    catch(Exception $e) {   // output the error if failure
        echo "Error!: " . $e->getMessage() . "<br>";
        return array();     // return an array in lieu of false so foreach loops that
    }                       // call this to iterate over remain valid
    
}

// Pull the distinct categories from the database
function get_project_categories() {
    include("connection.php");
    // funciton has no parameters, use query method to return a PDOStatement
    // object that can be iterated over
    try {
        $sql = "SELECT DISTINCT(category) from projects";
        return $db->query($sql);
    }
    catch(Exception $e) {
        echo "Error!: " . $e->getMessage(). "<br";
        return array();     // return an empty array for foreach loops in the UI remain valid
    }
}

// Pull the tasks from the database
// returns the full result set
function get_task_list($filter = null) {   // optional filter value to build various reports
    include("connection.php");
        try {
        $sql = "SELECT t.*, p.title AS project 
                FROM tasks t
                JOIN projects p
                ON t.project_id = p.project_id";

        $where = '';    // if the filter passed in is an array from the form on reports.php,
                        // we need a WHERE clause in the sql to filter the result set based on the 
                        // value(s) passed in from the report filter form
        if(is_array($filter)) { // if filter is an array, we've gotten filter value from reports.php
            switch($filter[0]) {    // case used here to add future filter items
                case "project": 
                    $where = " WHERE p.project_id = ?";
                    break;
                case "category":
                    $where = " WHERE p.category = ?";
                    break;
                case "date":
                    $where = " WHERE date BETWEEN '?' AND '?'";
                    break;
                default:
                    $where = '';    // no filter.  Default should never be reached
                    break;
            }
        }

        $orderBy = " ORDER BY date DESC"; // append to $sql for sorting; default is to order by most recent tasks
        
        if($filter) {
            // filter exists, default order by project, then date
            // TODO: Add more features here?
            $orderBy = " ORDER BY p.title ASC, date DESC";
        }

        $results = $db->prepare($sql . $where . $orderBy);  // prepare prevents injection, filters user data
        if(is_array($filter)) { // we've received a filter value so need to include the WHERE clause to filter the result set
            $results->bindValue(1, $filter[1]);     // left off optional third argument due 
                                                    // to differing types in switch (e.g. int vs. string)
            // bind second value if date filter
            if($filter[0] == "date") {
                $results->bindValue(2, $filter[2]);
            }
        }                                           
        $results->execute();
    }
    catch(Exception $e) {   // output the error if failure
        echo "Error!: " . $e->getMessage() . "<br>";
        return array();     // return an array in lieu of false so foreach loops that
    }                       // call this to iterate over remain valid

    // FIXME: This returns nothing if $filter[0] = "date"
    return $results->fetchAll(PDO::FETCH_ASSOC);    // return an array of the result set to iterate over in the HTML  
}

// Add projects to the DB via form post from the UI (project.php)
// Returns false if unsuccessful adding
function add_project($title, $category) {
    include("connection.php");
    try {
        $sql = "INSERT INTO projects
            (title, category)
            VALUES (?, ?)";
        // Use a prepared statement to use customized parameters (i.e. function params)
        // Prepared statements essentially get parsed (analyze/compile/optimize)
        // only once and then 'cached' to be 
        // executed quickly multiple times with the same or different parameters
        // Also Prevents SQL injection thorugh use of SQL template
        $results = $db->prepare($sql); // returns PDOStatement object to bind the parameters to
        $results->bindParam(1, $title, PDO::PARAM_STR);
        $results->bindParam(2, $category, PDO::PARAM_STR);
        $results->execute();
    }
    catch(Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br>";
        return false;
    }
    return true;   
}

// Add tasks to the DB via form post from the UI (task.php)
// Returns false if unsuccessful adding
function add_task($project_id, $title, $date, $time) {
    include("connection.php");
    try {
        $sql = "INSERT INTO tasks
            (project_id, title, `date`, `time`)
            VALUES (?, ?, ?, ?)";
        // Use a prepared statement to use customized parameters (i.e. function params)
        // Prepared statements essentially get parsed (analyze/compile/optimize)
        // only once and then 'cached' to be 
        // executed quickly multiple times with the same or different parameters
        // Also Prevents SQL injection thorugh use of SQL template
        $results = $db->prepare($sql); // returns PDOStatement object to bind the parameters to
        $results->bindParam(1, $project_id, PDO::PARAM_INT);
        $results->bindParam(2, $title, PDO::PARAM_STR);
        $results->bindParam(3, $date, PDO::PARAM_STR);
        $results->bindParam(4, $time, PDO::PARAM_INT);
        $results->execute();
    }
    catch(Exception $e) {
        echo "Error!: " . $e->getMessage() . "<br>";
        return false;
    }
    return true;   
}
