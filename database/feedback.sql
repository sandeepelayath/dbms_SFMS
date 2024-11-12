
-- Create user table with enhanced security
CREATE TABLE IF NOT EXISTS user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'faculty', 'viewer') NOT NULL DEFAULT 'viewer',
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    failed_login_attempts INT DEFAULT 0,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- Create main feedback table with improved structure
CREATE TABLE IF NOT EXISTS feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    year VARCHAR(10) NOT NULL,
    sem VARCHAR(10) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    branch VARCHAR(100) NOT NULL,
    section VARCHAR(10) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    faculty_id INT,
    ques1 DECIMAL(3,1) CHECK (ques1 >= 0 AND ques1 <= 10),
    ques2i DECIMAL(3,1) CHECK (ques2i >= 0 AND ques2i <= 10),
    ques2ii DECIMAL(3,1) CHECK (ques2ii >= 0 AND ques2ii <= 10),
    ques2iii DECIMAL(3,1) CHECK (ques2iii >= 0 AND ques2iii <= 10),
    ques2iv DECIMAL(3,1) CHECK (ques2iv >= 0 AND ques2iv <= 10),
    ques2v DECIMAL(3,1) CHECK (ques2v >= 0 AND ques2v <= 10),
    ques3 DECIMAL(3,1) CHECK (ques3 >= 0 AND ques3 <= 10),
    ques4 DECIMAL(3,1) CHECK (ques4 >= 0 AND ques4 <= 10),
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_faculty (faculty_id),
    INDEX idx_subject_branch (subject, branch),
    INDEX idx_date (date),
    FOREIGN KEY (faculty_id) REFERENCES user(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Create analytics summary table
CREATE TABLE IF NOT EXISTS feedback_analytics_summary (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject VARCHAR(100) NOT NULL,
    branch VARCHAR(100) NOT NULL,
    total_feedback INT DEFAULT 0,
    avg_score DECIMAL(4,2),
    min_score DECIMAL(4,2),
    max_score DECIMAL(4,2),
    std_deviation DECIMAL(4,2),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY subject_branch_idx (subject, branch)
) ENGINE=InnoDB;

-- Create feedback history table for tracking changes
CREATE TABLE IF NOT EXISTS feedback_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    feedback_id INT NOT NULL,
    field_name VARCHAR(50) NOT NULL,
    old_value TEXT,
    new_value TEXT,
    changed_by INT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (feedback_id) REFERENCES feedback(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES user(id) ON DELETE SET NULL,
    INDEX idx_feedback (feedback_id),
    INDEX idx_change_date (changed_at)
) ENGINE=InnoDB;

-- Create department metrics table
CREATE TABLE IF NOT EXISTS department_metrics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    branch VARCHAR(100) NOT NULL,
    year VARCHAR(10) NOT NULL,
    sem VARCHAR(10) NOT NULL,
    avg_department_score DECIMAL(4,2),
    total_feedbacks INT,
    performance_percentile DECIMAL(5,2),
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY branch_year_sem_idx (branch, year, sem)
) ENGINE=InnoDB;

ALTER TABLE feedback ADD COLUMN changed_by INT;

-- Create views for common queries
CREATE OR REPLACE VIEW subject_performance_view AS
SELECT 
    subject,
    branch,
    year,
    sem,
    COUNT(*) as feedback_count,
    ROUND(AVG((ques1 + ques2i + ques2ii + ques2iii + ques2iv + ques2v + ques3 + ques4)/8), 2) as avg_score,
    ROUND(STD((ques1 + ques2i + ques2ii + ques2iii + ques2iv + ques2v + ques3 + ques4)/8), 2) as score_std_dev
FROM feedback
GROUP BY subject, branch, year, sem;

-- Create stored procedures
DROP PROCEDURE IF EXISTS GetFilteredFeedback;

DELIMITER //
CREATE PROCEDURE GetFilteredFeedback(
    IN p_year VARCHAR(10),
    IN p_branch VARCHAR(100),
    IN p_sem VARCHAR(10),
    IN p_subject VARCHAR(100)
)
BEGIN
    SELECT 
        f.*,
        ROUND(AVG(ques1) OVER (PARTITION BY subject), 2) as subject_avg_clarity,
        ROUND(AVG(ques2i) OVER (PARTITION BY branch), 2) as branch_avg_knowledge,
        DENSE_RANK() OVER (
            ORDER BY (ques1 + ques2i + ques2ii + ques2iii + ques2iv + ques2v + ques3 + ques4)/8 DESC
        ) as overall_rank
    FROM feedback f
    WHERE (p_year IS NULL OR year = p_year)
        AND (p_branch IS NULL OR branch = p_branch)
        AND (p_sem IS NULL OR sem = p_sem)
        AND (p_subject IS NULL OR subject = p_subject);
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS after_feedback_update;

-- Trigger to maintain feedback history
DELIMITER //
CREATE TRIGGER after_feedback_update
AFTER UPDATE ON feedback
FOR EACH ROW
BEGIN
    IF OLD.ques1 != NEW.ques1 THEN
        INSERT INTO feedback_history (feedback_id, field_name, old_value, new_value, changed_by)
        VALUES (NEW.id, 'ques1', OLD.ques1, NEW.ques1, NEW.changed_by);
    END IF;
    IF OLD.remarks != NEW.remarks THEN
        INSERT INTO feedback_history (feedback_id, field_name, old_value, new_value, changed_by)
        VALUES (NEW.id, 'remarks', OLD.remarks, NEW.remarks, NEW.changed_by);
    END IF;
END //
DELIMITER ;



CREATE TABLE IF NOT EXISTS courses (
    course_code VARCHAR(20) PRIMARY KEY,    -- The course code, like 'UE24CS151A'
    course_title VARCHAR(255) NOT NULL,      -- The title of the course
    credits INT NOT NULL,                    -- The number of credits for the course
    semester VARCHAR(10) NOT NULL            -- The semester (e.g., 'I', 'II', 'III')
) ENGINE=InnoDB;


-- Insert sample data into courses table
INSERT INTO courses (course_code, course_title, credits, semester) VALUES
    ('UE24CS151A', 'Python for Computational Problem Solving', 5, 'I'),
    ('UE24CS151B', 'Problem Solving with C', 5, 'II'),
    ('UE23CS251A', 'Digital Design and Computer Organization', 5, 'III'),
    ('UE23CS252A', 'Data Structures and its Applications', 5, 'III'),
    ('UE23CS241A', 'Statistics for Data Science', 4, 'III'),
    ('UE23CS242A', 'Web Technologies', 4, 'III'),
    ('UE23CS243A', 'Automata Formal Languages and Logic', 4, 'III'),
    ('UE23CS251B', 'Microprocessor and Computer Architecture', 5, 'IV'),
    ('UE23CS252B', 'Computer Networks', 5, 'IV'),
    ('UE23CS241B', 'Design and Analysis of Algorithms', 4, 'IV'),
    ('UE23CS242B', 'Operating Systems', 4, 'IV'),
    ('UE23CS243B', 'Vector Spaces and Linear Algebra', 4, 'IV'),
    ('UE22CS351A', 'Database Management System', 5, 'V'),
    ('UE22CS352A', 'Machine Learning', 5, 'V'),
    ('UE22CS341A', 'Software Engineering', 4, 'V'),
    ('UE22CS342AAX', 'Elective I', 4, 'V'),
    ('UE22CS343ABX', 'Elective II', 4, 'V'),
    ('UE22CS351B', 'Cloud Computing', 5, 'VI'),
    ('UE22CS352B', 'Object Oriented Analysis and Design', 5, 'VI'),
    ('UE22CS341B', 'Compiler Design', 4, 'VI'),
    ('UE22CS342BAX', 'Elective III', 4, 'VI'),
    ('UE22CS343BBX', 'Elective IV', 4, 'VI'),
    ('UE22CS320A', 'Capstone Project Phase-1', 2, 'VI'),
    ('UE21CS461A', 'Capstone Phase-2', 6, 'VII'),
    ('U20CS461AX', 'Special Topic/Directed Independent Study', 4, 'VII'),
    ('UE21CS421B', 'Capstone Phase-3', 4, 'VIII'),
    ('UE21CS461XB/UE21CS462XB', 'Internship/Special Topic/Directed Independent Study', 6, 'VIII');

-- Insert sample data for testing
INSERT INTO user (email, password, role) VALUES
('admin@admin.com', SHA2('admin', 256), 'admin'),
('admin2@admin.com', SHA2('admin2', 256), 'admin'),
('admin3@admin.com', SHA2('admin3', 256), 'admin'),
('faculty@example.com', SHA2('faculty123', 256), 'faculty');

-- Drop procedure if it already exists
DROP PROCEDURE IF EXISTS InsertRandomFeedbacks;

DELIMITER //
CREATE PROCEDURE InsertRandomFeedbacks()
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE random_year VARCHAR(10);
    DECLARE random_sem VARCHAR(10);
    DECLARE random_branch VARCHAR(100);
    DECLARE random_section VARCHAR(10);
    DECLARE random_subject VARCHAR(100);
    DECLARE random_faculty_id INT;
    DECLARE random_ques1 DECIMAL(3,1);
    DECLARE random_ques2i DECIMAL(3,1);
    DECLARE random_ques2ii DECIMAL(3,1);
    DECLARE random_ques2iii DECIMAL(3,1);
    DECLARE random_ques2iv DECIMAL(3,1);
    DECLARE random_ques2v DECIMAL(3,1);
    DECLARE random_ques3 DECIMAL(3,1);
    DECLARE random_ques4 DECIMAL(3,1);
    DECLARE random_remarks TEXT;

    WHILE i < 100 DO
        -- Generate random values for feedback
        SET random_year = ELT(FLOOR(1 + (RAND() * 2)), '2024', '2023');
        SET random_sem = ELT(FLOOR(1 + (RAND() * 8)), 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII');
        SET random_branch = ELT(FLOOR(1 + (RAND() * 2)), 'CSE', 'AIML');
        SET random_section = ELT(FLOOR(1 + (RAND() * 12)), 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L');
        
        -- Ensure course and semester match by selecting a random course for the semester
        SET random_subject = (SELECT course_code FROM courses WHERE semester = random_sem ORDER BY RAND() LIMIT 1);

        SET random_faculty_id = (SELECT id FROM user WHERE role = 'faculty' ORDER BY RAND() LIMIT 1);
        SET random_ques1 = ROUND(RAND() * 10, 1);
        SET random_ques2i = ROUND(RAND() * 10, 1);
        SET random_ques2ii = ROUND(RAND() * 10, 1);
        SET random_ques2iii = ROUND(RAND() * 10, 1);
        SET random_ques2iv = ROUND(RAND() * 10, 1);
        SET random_ques2v = ROUND(RAND() * 10, 1);
        SET random_ques3 = ROUND(RAND() * 10, 1);
        SET random_ques4 = ROUND(RAND() * 10, 1);
        SET random_remarks = 'Sample feedback text.';

        -- Insert the random feedback into the table
        INSERT INTO feedback (year, sem, branch, section, subject, faculty_id, ques1, ques2i, ques2ii, ques2iii, ques2iv, ques2v, ques3, ques4, remarks)
        VALUES (random_year, random_sem, random_branch, random_section, random_subject, random_faculty_id, random_ques1, random_ques2i, random_ques2ii, random_ques2iii, random_ques2iv, random_ques2v, random_ques3, random_ques4, random_remarks);
        
        SET i = i + 1;
    END WHILE;
END //
DELIMITER ;

-- Call the procedure to insert random feedbacks
CALL InsertRandomFeedbacks();

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;


