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
        echo "Error!" . $e->getMessage() . "<br>";
        return array();     // return an array in lieu of false so foreach loops that
    }                       // call this to iterate remain valid
    
}