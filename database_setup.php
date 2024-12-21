<?php
try {
    // Connect to SQLite database
    $pdo = new PDO('sqlite:timeline.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Drop tables if they exist, to ensure we start fresh
    $pdo->exec('
        DROP TABLE IF EXISTS work;
        DROP TABLE IF EXISTS education;
        DROP TABLE IF EXISTS publication;
        DROP TABLE IF EXISTS Awards;
        DROP TABLE IF EXISTS Certifications;
        DROP TABLE IF EXISTS Projects;
    ');

    // Create tables
    $pdo->exec('
        CREATE TABLE work (
            id INTEGER PRIMARY KEY,
            job_title TEXT NOT NULL,
            company_name TEXT NOT NULL,
            start_date TEXT NOT NULL,
            end_date TEXT NOT NULL,
            details TEXT
        );

        CREATE TABLE education (
            id INTEGER PRIMARY KEY,
            degree_program TEXT NOT NULL,
            university_name TEXT NOT NULL,
            start_date TEXT NOT NULL,
            end_date TEXT NOT NULL,
            thesis_title TEXT,
            syllabus TEXT
        );

        CREATE TABLE publication (
            id INTEGER PRIMARY KEY,
            start_date DATE NOT NULL, 
            end_date DATE NOT NULL,
            title TEXT NOT NULL,
            year INTEGER NOT NULL,
            citation_details TEXT
        );

        CREATE TABLE Awards (
            AwardID INTEGER PRIMARY KEY,
            Name TEXT NOT NULL,
            Year INTEGER NOT NULL,
            Description TEXT NOT NULL
        );

        CREATE TABLE Certifications (
            CertificationID INTEGER PRIMARY KEY,
            Name TEXT NOT NULL,
            IssueDate DATE NOT NULL,
            Issuer TEXT NOT NULL
        );

        CREATE TABLE Projects (
            ProjectID INTEGER PRIMARY KEY,
            Name TEXT NOT NULL,
            ShortDescription TEXT NOT NULL,
            LongDescription TEXT NOT NULL,
            Tags TEXT NOT NULL,
            GithubLink TEXT,
            YoutubeLink TEXT,
            PaperLink TEXT,
            PresentationLink TEXT
        );
    ');

    echo "Database and tables created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
