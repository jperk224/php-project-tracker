<?php
//application functions

// Pull the projects from the database
// returns the full result set
function pull_project_list() {
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
    }                       // call this to iterate remain valid
    
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
