<?php
try {
    // Connect to SQLite database
    $pdo = new PDO('sqlite:timeline.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert Work data
    $pdo->exec("INSERT INTO work (job_title, company_name, start_date, end_date, details) VALUES
        ('Software Engineer', 'ABC Consultancy Services', '2018-07-02', '2022-05-31', 'Worked on various web applications.')
    ");

    $pdo->exec("INSERT INTO work (job_title, company_name, start_date, end_date, details) VALUES
        ('Intern Software Developer', 'Tech Inc', '2023-04-01', '2023-07-31', 'Worked on various web applications.')
    ");

    // Insert Education List
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

    // Insert Publication List
    $pdo->exec("INSERT INTO publication (title, start_date, end_date, year, citation_details) VALUES
        ('Business Resilience', '2023-11-01', '2024-01-31', 2023, 'Doe, J. (2023). AI for Modern Software Development. Tech Journal, 10(2), 45-56.')
    ");

    $pdo->exec("INSERT INTO publication (title, start_date, end_date, year, citation_details) VALUES
        ('Agile Ethic', '2024-01-01', '2024-07-31', 2023, 'Doe, J. (2023). AI for Modern Software Development. Tech Journal, 10(2), 45-56.')
    ");

    // Insert Awards List
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
        ('Python', 'Programming')
;");

    // Insert tools skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Arduino Uno', 'Tools'),
        ('Microsoft Server', 'Tools'),
        ('Jira', 'Tools')
;");

    // Insert hardware skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('IoT Integration', 'Hardware'),
        ('Printer Installation', 'Hardware'),
        ('PC Installation', 'Hardware')
    ;");

    // Insert Methodologies skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('System Integration Testing (SIT)', 'Methodologies'),
        ('User Acceptance Testing (UAT)', 'Methodologies'),
        ('Circular Economy Practices', 'Methodologies')
    ;");

    // Insert research & analysis skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Stakeholder Analysis', 'Research_Analysis'),
        ('LLM', 'Research_Analysis'),
        ('Ethical AI', 'Research_Analysis')
    ;");

    // Insert Language skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Indonesian (Native)', 'Language'),
        ('English (IELTS C1)', 'Language')
    ;");

    // Insert Managerial skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Teaching & Mentoring', 'Managerial'),
        ('User Support', 'Managerial'),
        ('Technical Documentation', 'Managerial')
    ;");

    // Insert Interests skills
    $pdo->exec("INSERT INTO skills (skill_name, category) VALUES
        ('Digital Transformation', 'Interests'),
        ('Business Information', 'Interests'),
        ('Solutions Architect', 'Interests'),
        ('Ethical AI', 'Interests')
    ;");

    // Insert Certificate List
    $pdo->exec("INSERT INTO Certifications (Name, IssueDate, Issuer) VALUES
        ('Business Analysis Fundamentals', '2024-11-16', 'Microsoft & Coursera'),
        ('International English Language Testing System (IELTS) C1', '2021-11-08', 'IDP')
    ;");

    // Insert Project List
    $pdo->exec("INSERT INTO Projects (
        Name,
        ShortDescription,
        LongDescription,
        Tags,
        GithubLink1,
        GithubLink2,
        GithubLink3,
        GithubLink4,
        YoutubeLink,
        PaperLink,
        PresentationLink
    ) VALUES (
        'Auto-generated template from SQLLite for Tech Career',
        'Tools for helping tech professionals generate their portfolio by just listing it in a database. It can generate resume timelines (work/education/publication), certifications, awards, and projects. Built using PHP, SQLite, HTML, and Bootstrap CSS.',
        NULL,
        'Template, PHP, CSS, HTML, Free template, Open Source',
        'https://github.com/cinapr/TechCareer-AutoGenerate-Portfolio-Template',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL
    );
    ");

    $pdo->exec("INSERT INTO Projects (
        Name,
        ShortDescription,
        LongDescription,
        Tags,
        GithubLink1,
        GithubLink2,
        GithubLink3,
        GithubLink4,
        YoutubeLink,
        PaperLink,
        PresentationLink
    ) VALUES (
        'Close All Docker with Python',
        'Script for terminating all docker running in Windows. This tool can help system administrators who have Docker running and stuck in their server.',
        NULL,
        'Docker, Python Scripting',
        'https://github.com/cinapr/Close-All-Docker-with-Python',
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL
    );
    ");

    echo "Sample data inserted successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
