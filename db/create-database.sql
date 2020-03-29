-- Database: project_tracker_app
-- --------------------------------------------------------

-- Initial table structure for table projects
-- --------------------------------------------------------

USE DATABASE project_tracker_app;

DROP TABLE IF EXISTS projects;
CREATE TABLE projects (
  project_id int(11) NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  category VARCHAR(255) NOT NULL
)

-- Add initial test data to projects
-----------------------------------------------------------

INSERT INTO projects (project_id, title, category) VALUES
('Project 1 Test', 'Billable'),
('Project 2 Test', 'Personal'),
('Project 3 Test', 'Charity');

-- Initial table structure for table tasks
-- --------------------------------------------------------
DROP TABLE IF EXISTS tasks;
CREATE TABLE tasks (
  task_id int(11) NOT NULL AUTO_INCREMENT,
  project_id int(11) NOT NULL,
  title VARCHAR(255) NOT NULL,
  date text NOT NULL,   --This will need to change to DATE type eventually
  time INT NOT NULL,    --This will need to change to TIME type eventually
) 

-- Add initial test data to tasks
-- --------------------------------------------------------

INSERT INTO tasks (task_id, project_id, title, `date`, `time`) VALUES
(1, 1, 'Task 1 Test', '7', 90),
(2, 1, 'Task 2 Test', '7', 60),
(3, 2, 'Task 3 Test', '8', 120),
(4, 2, 'Task 4 Test', '8', 30);

-- Add primary keys
-- --------------------------------------------------------
ALTER TABLE projects
  ADD PRIMARY KEY (`project_id`);

ALTER TABLE tasks
  ADD PRIMARY KEY (`task_id`);

-- Add AUTO_INCREMENT 
-- --------------------------------------------------------
ALTER TABLE projects
  MODIFY project_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE tasks
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
