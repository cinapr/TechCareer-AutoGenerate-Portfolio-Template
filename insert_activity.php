<?php
try {
    // Connect to SQLite database
    $pdo = new PDO('sqlite:timeline.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert sample data
    $pdo->exec("INSERT INTO work (job_title, company_name, start_date, end_date, details) VALUES
        ('Software Engineer', 'ABC Consultancy Services', '2018-07-02', '2022-05-31', 'Worked on various web applications.')
    ");

    $pdo->exec("INSERT INTO work (job_title, company_name, start_date, end_date, details) VALUES
        ('Intern Software Developer', 'Tech Inc', '2023-04-01', '2023-07-31', 'Worked on various web applications.')
    ");

    $pdo->exec("INSERT INTO education (degree_program, university_name, start_date, end_date, thesis_title, syllabus) VALUES
        ('Computer Science', 'University UNII', '2013-07-15', '2018-01-25', 'AI in Software Development', 'Data Structures, Algorithms, Machine Learning')
    ");

    $pdo->exec("INSERT INTO education (degree_program, university_name, start_date, end_date, thesis_title, syllabus) VALUES
        ('Computer Science', 'University UNII2', '2022-09-01', '2024-08-22', 'AI in Software Development', 'Data Structures, Algorithms, Machine Learning')
    ");

    $pdo->exec("INSERT INTO education (degree_program, university_name, start_date, end_date, thesis_title, syllabus) VALUES
        ('Computer Science', 'University UNII3', '2022-09-01', '2024-08-31', 'AI in Software Development', 'Data Structures, Algorithms, Machine Learning')
    ");

    $pdo->exec("INSERT INTO education (degree_program, university_name, start_date, end_date, thesis_title, syllabus) VALUES
        ('Computer Science', 'University UNII4', '2022-09-01', '2024-08-15', 'AI in Software Development', 'Data Structures, Algorithms, Machine Learning')
    ");

    $pdo->exec("INSERT INTO publication (title, start_date, end_date, year, citation_details) VALUES
        ('Business Resilience', '2023-11-01', '2024-01-31', 2023, 'Doe, J. (2023). AI for Modern Software Development. Tech Journal, 10(2), 45-56.')
    ");

    $pdo->exec("INSERT INTO publication (title, start_date, end_date, year, citation_details) VALUES
        ('Agile Ethic', '2024-01-01', '2024-07-31', 2023, 'Doe, J. (2023). AI for Modern Software Development. Tech Journal, 10(2), 45-56.')
    ");

    $pdo->exec("INSERT INTO Awards (Name, Year, Description) VALUES
        ('Highest Achievement Student of the Class of 2013', 2018, 'Awarded by the IT Department, Universitas UNII, for achieving the highest academic standing in the cohort.'),
        ('Thesis Grant', 2018, 'Received from Universitas UNII in recognition of an exceptional thesis.'),
        ('Academic Achievement Improvement-Merit Scholarships', 2014, 'Awarded by The Ministry of Research and Higher Education for significant academic excellence.')
    ");

    

    // Insert skills data - Take notice on the category should be Programming, Tools, Hardware, Methodologies, Language, Research_Analysis, Managerial, Interests - Should have at least one of each category
    // Insert programming skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('C#', 'Programming'),
        ('C#.NET', 'Programming'),
        ('ASP.NET', 'Programming'),
        ('C', 'Programming'),
        ('Python', 'Programming');
    ");

    // Insert tools skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Visual Studio', 'Tools'),
        ('SQL Server', 'Tools'),
        ('DBMS', 'Tools'),
        ('Arduino Uno', 'Tools'),
        ('Microsoft Server', 'Tools'),
        ('Jira', 'Tools'),
        ('Internal ERP systems', 'Tools');
    ");

    // Insert hardware skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('IoT Integration', 'Hardware');
    ");

    // Insert Methodologies skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Agile', 'Methodologies'),
        ('Scrum', 'Methodologies'),
        ('System Integration Testing (SIT)', 'Methodologies'),
        ('User Acceptance Testing (UAT)', 'Methodologies'),
        ('Circular Economy Practices', 'Methodologies');
    ");

    // Insert research & analysis skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Technology Adoption', 'Research_Analysis'),
        ('Economic Sustainability', 'Research_Analysis'),
        ('Stakeholder Analysis', 'Research_Analysis'),
        ('LLM', 'Research_Analysis'),
        ('Ethical AI', 'Research_Analysis'),
        ('Ethical IT', 'Research_Analysis');
    ");

    // Insert Language skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Indonesian (Native)', 'Language'),
        ('English (IELTS C1)', 'Language'),
        ('Chinese (TOCFL A2)', 'Language'),
        ('Chinese Fujian Dialect (Bilingual Fluency)', 'Language');
    ");

    // Insert Managerial skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Teaching & Mentoring', 'Managerial'),
        ('User Support', 'Managerial'),
        ('Technical Documentation', 'Managerial'),
        ('Syllabus Development', 'Managerial'),
        ('Student Guidance', 'Managerial'),
        ('Timesheet Management', 'Managerial'),
        ('Staff Recruitment and Training', 'Managerial');
    ");

    // Insert Interests skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Sustainability', 'Interests'),
        ('IT Business Analyst', 'Interests'),
        ('System Analyst', 'Interests'),
        ('Digital Business Analysts', 'Interests'),
        ('Functional Consultant', 'Interests'),
        ('Digital Transformation', 'Interests'),
        ('Business Information', 'Interests'),
        ('Solutions Architect', 'Interests'),
        ('Technical Writer', 'Interests'),
        ('IT Support', 'Interests'),
        ('Delivering Specialist', 'Interests'),
        ('Inter-disciplinary IT', 'Interests'),
        ('Decision Making', 'Interests'),
        ('Ethics', 'Interests'),
        ('ICT', 'Interests'),
        ('Ethical AI', 'Interests'),
        ('ERP', 'Interests');
    ");

    $pdo->exec("INSERT INTO Certifications (Name, IssueDate, Issuer) VALUES
        ('Business Analysis Fundamentals', '2024-11-16', 'Microsoft & Coursera'),
        ('International English Language Testing System (IELTS) C1', '2021-11-08', 'IDP'),
        ('Test of Chinese as a Foreign Language (TOCFL) A2', '2021-05-02', 'Steering Committee for the Test Of Proficiency-Huayu'),
        ('Microsoft Office Specialist (Office Powerpoint 2013)', '2017-10-17', 'Microsoft & Certiport'),
        ('Microsoft Office Specialist (Office Powerpoint 2010)', '2016-11-18', 'Microsoft & Certiport')
    ");

    $pdo->exec("INSERT INTO Projects (Name, ShortDescription, LongDescription, Tags, GithubLink, YoutubeLink, PaperLink, PresentationLink) VALUES
        ('Smart City Traffic Management System', 
        'AI-powered solution for traffic congestion in urban areas.', 
        'A comprehensive AI-powered solution designed to monitor and reduce traffic congestion in urban areas by utilizing IoT and machine learning algorithms.', 
        'software architecture, full system',
        'https://github.com/example/smartcity-traffic', 
        'https://youtube.com/example-traffic', 
        NULL, 
        'https://example.com/traffic-presentation'),

        ('E-Commerce Recommendation Engine', 
        'Recommendation engine for e-commerce platforms.', 
        'Designed a machine learning-based recommendation engine for e-commerce platforms to suggest personalized products to customers.', 
        'programming, scripting', 
        'https://github.com/example/recommendation-engine', 
        NULL, 
        'https://example.com/recommendation-paper', 
        'https://example.com/recommendation-presentation')
    ");

    echo "Sample data inserted successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
