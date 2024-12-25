<?php
// index.php - Display timeline with events
include 'db.php';

// Fetch and sort work experiences
$query_work = $pdo->query("SELECT * FROM work");
$work_data = $query_work->fetchAll(PDO::FETCH_ASSOC);
usort($work_data, fn($a, $b) => strtotime($a['start_date']) - strtotime($b['start_date']));

// Fetch and sort education experiences
$query_education = $pdo->query("SELECT * FROM education");
$education_data = $query_education->fetchAll(PDO::FETCH_ASSOC);
usort($education_data, fn($a, $b) => strtotime($a['start_date']) - strtotime($b['start_date']));

// Fetch and sort publications
$query_publications = $pdo->query("SELECT * FROM publication");
$publication_data = $query_publications->fetchAll(PDO::FETCH_ASSOC);
usort($publication_data, fn($a, $b) => $a['year'] - $b['year']);

// Fetch certifications
$query_certifications = $pdo->query("SELECT * FROM certifications");
$certifications_data = $query_certifications->fetchAll(PDO::FETCH_ASSOC);

// Fetch awards
$query_awards = $pdo->query("SELECT * FROM awards");
$awards_data = $query_awards->fetchAll(PDO::FETCH_ASSOC);

// Fetch portfolio projects
$query_projects = $pdo->query("SELECT * FROM projects");
$projects_data = $query_projects->fetchAll(PDO::FETCH_ASSOC);

// Fetch skills for each skill category from the database
$query_technical_skills = $pdo->query("SELECT skill_name, category 
                                       FROM skills 
                                       WHERE category IN ('Programming', 'Tools', 'Hardware') 
                                       ORDER BY 
                                           CASE category 
                                               WHEN 'Programming' THEN 1 
                                               WHEN 'Tools' THEN 2 
                                               WHEN 'Hardware' THEN 3 
                                           END");
$technical_skills = $query_technical_skills->fetchAll(PDO::FETCH_ASSOC);

$query_soft_skills = $pdo->query("SELECT skill_name, category 
                                  FROM skills 
                                  WHERE category IN ('Methodologies', 'Research_Analysis', 'Language', 'Managerial') 
                                  ORDER BY 
                                      CASE category 
                                          WHEN 'Methodologies' THEN 1 
                                          WHEN 'Research_Analysis' THEN 2 
                                          WHEN 'Language' THEN 3 
                                          WHEN 'Managerial' THEN 4 
                                      END");
$soft_skills = $query_soft_skills->fetchAll(PDO::FETCH_ASSOC);

$query_interests = $pdo->query("SELECT skill_name, category 
                               FROM skills 
                               WHERE category = 'Interests'");
$interests = $query_interests->fetchAll(PDO::FETCH_ASSOC);


// Function to allocate lines for overlapping events
function allocateLines($events) {
    $lines = [];
    foreach ($events as $event) {
        $placed = false;
        foreach ($lines as &$line) {
            $overlap = false;
            foreach ($line as $e) {
                if (!(strtotime($event['end_date']) < strtotime($e['start_date']) ||
                      strtotime($event['start_date']) > strtotime($e['end_date']))) {
                    $overlap = true;
                    break;
                }
            }
            if (!$overlap) {
                $line[] = $event;
                $placed = true;
                break;
            }
        }
        if (!$placed) {
            $lines[] = [$event];
        }
    }
    return $lines;
}

// Adjust grid placement based on start_date and end_date
function calculateGridPlacement($start_date, $end_date) {
    $start_date_obj = new DateTime($start_date);
    $end_date_obj = new DateTime($end_date);

    // Define the start of the timeline
    $timeline_start = new DateTime('2013-01-01');

    // Calculate the grid start based on months from the timeline start
    $grid_start = max(1, (($start_date_obj->format('Y') - 2013) * 12 + $start_date_obj->format('n')));

    // Calculate the grid span (in months) between start_date and end_date
    $interval = $start_date_obj->diff($end_date_obj);
    $span_months = $interval->y * 12 + $interval->m + 1; // Add 1 to include the last month

    return [$grid_start, $span_months];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cindy Aprilia</title>

    <link href="styles/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>

    <!--MY CSS -->
    <!--<link href="styles/header.css" rel="stylesheet">-->
    <link href="styles/timeline.css" rel="stylesheet">
    <link href="styles/modal.css" rel="stylesheet">

    <!--CHANGE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet"> 
    
    <!--CSS FROM BOOTSTRAP EXAMPLES -->
    <link href="styles/exampleBootstrap.css" rel="stylesheet">
    <link href="styles/skills.css" rel="stylesheet">
    <!--<link href="styles/blog.css" rel="stylesheet">-->
    <!--<link href="styles/product.css" rel="stylesheet">-->
</head>

<body>
    <header class="border-bottom lh-1 py-3">
        <div id="MyContact" class="row flex-nowrap justify-content-center align-items-center">
            <div class="col-4 text-center">
                <a class="blog-header-logo text-body-emphasis text-decoration-none" href="#MyContact"><h1>Cindy Aprilia</h1></a>
            </div>
        </div>
        <div class="row flex-nowrap justify-content-center align-items-center">
            <div class="col-8 text-center">
                <p class="lead my-3">Address • City, Province, Country Postal_Code</p>
                    <p>
                        <a class="link-secondary lead my-3" href="mailto:email@gmail.com">Email:email@gmail.com</a> •
                        <a class="link-secondary lead my-3" href="https://api.whatsapp.com/send/?phone=628PHONE&text=&app_absent=0">Call & Whatsapp:+628PHONE</a>
                    </p>
                    <p>
                        <a class="link-secondary lead my-3" href="https://www.linkedin.com/in/LINKEDINID/" target="_blank">LinkedIn</a> •
                        <a class="link-secondary lead my-3" href="https://github.com/GITHUBID" target="_blank">Github</a> •
                        <a class="link-secondary lead my-3" href="https://www.youtube.com/@YOUTUBEID" target="_blank">YouTube</a>
                    </p>
                    <p>
                        <a class="link-secondary lead my-3" href="#MyContact" target="_blank">Contact Details</a> •
                        <a class="link-secondary lead my-3" href="#Timeline" target="_blank">Resume Timeline</a> •
                        <a class="link-secondary lead my-3" href="#Portfolio" target="_blank">Portofolio</a> •
                        <a class="link-secondary lead my-3" href="#Awards" target="_blank">Awards</a> •
                        <a class="link-secondary lead my-3" href="#Certifications" target="_blank">Certifications</a>
                        <a class="link-secondary lead my-3" href="#SkillTags" target="_blank">Skills</a>
                    </p>
            </div>
        </div>
    </header>

    <main>
        <!-- TIMELINE -->
        <div id="Timeline">
        <h1 id="Timeline" class="position-relative overflow-hidden pt-3 px-3 m-md-3 text-center bg-body-tertiary">Resume - Timeline</h1>
            <div class="text-center m-0 p-0">
                <div class="p-lg-5 px-3 m-0 mx-3">
                    <!--<h1 class="display-3 fw-bold">Designed for engineers</h1>-->
                    <!--<h3 class="fw-normal text-muted mb-3">Build anything you want with Aperture</h3>-->
                    
                    <!-- RESUME TIMELINE -->
                    <div class="chart-container ">
                        <div class="chart">
                            <!-- Year Headers -->
                            <div class="chart-row">
                                <?php for ($year = 2013; $year <= 2025; $year++): ?>
                                    <!-- UBAH DISINI MENJADI 13 <div style="grid-column: span 13; text-align: center; font-weight: bold;"><?php echo $year; ?></div> -->
                                    <div style="grid-column: span 13; text-align: center; font-weight: bold;"><?php echo $year; ?></div>
                                <?php endfor; ?>
                            </div>

                            <!-- Work Experience -->
                            <?php foreach (allocateLines($work_data) as $line): ?>
                                <div class="chart-row">
                                    <ul class="chart-row-bars">
                                        <?php foreach ($line as $work): ?>
                                            <?php list($grid_start, $span_months) = calculateGridPlacement($work['start_date'], $work['end_date']); ?>
                                            <li class="work" style="cursor:pointer; grid-column: <?php echo $grid_start; ?> / span <?php echo $span_months; ?>"
                                                onclick="openWorkEducationModal('<?php echo htmlspecialchars($work['job_title']); ?>', '<?php echo htmlspecialchars($work['company_name']); ?>', '<?php echo htmlspecialchars($work['start_date']); ?> - <?php echo htmlspecialchars($work['end_date']); ?>', '<?php echo htmlspecialchars($work['details']); ?>')">
                                                <div class="title"><?php echo htmlspecialchars($work['job_title']); ?></div>
                                                <div class="subtitle"><?php echo htmlspecialchars($work['company_name']); ?></div>
                                                <!--<div class="dates"><?php echo htmlspecialchars($work['start_date']); ?> - <?php echo htmlspecialchars($work['end_date']); ?></div>-->
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>

                            <!-- Education -->
                            <?php foreach (allocateLines($education_data) as $line): ?>
                                <div class="chart-row">
                                    <ul class="chart-row-bars">
                                        <?php foreach ($line as $education): ?>
                                            <?php list($grid_start, $span_months) = calculateGridPlacement($education['start_date'], $education['end_date']); ?>
                                            <li class="education" style="cursor:pointer; grid-column: <?php echo $grid_start; ?> / span <?php echo $span_months; ?>"
                                                onclick="openWorkEducationModal('<?php echo htmlspecialchars($education['degree_program']); ?>', '<?php echo htmlspecialchars($education['university_name']); ?>', '<?php echo htmlspecialchars($education['start_date']); ?> - <?php echo htmlspecialchars($education['end_date']); ?>', '<?php echo htmlspecialchars($education['thesis_title'] ?? ''); ?>')">
                                                <div class="title"><?php echo htmlspecialchars($education['degree_program']); ?></div>
                                                <div class="subtitle"><?php echo htmlspecialchars($education['university_name']); ?></div>
                                                <!--<div class="dates"><?php echo htmlspecialchars($education['start_date']); ?> - <?php echo htmlspecialchars($education['end_date']); ?></div>-->
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>


                            <!-- Render publication experiences -->
                            <?php foreach (allocateLines($publication_data) as $line): ?>
                                <div class="chart-row">
                                    <ul class="chart-row-bars">
                                        <?php foreach ($line as $publication): ?>
                                            <?php list($grid_start, $span_months) = calculateGridPlacement($publication['start_date'], $publication['end_date']); ?>
                                            <?php
                                                //$pub_year = isset($publication['year']) ? (int)$publication['year'] : 2013;
                                                //$grid_start = max(1, $pub_year - 2013 + 1);
                                            ?>
                                            <li class="publication" style="cursor:pointer; grid-column: <?php echo $grid_start; ?> / span <?php echo $span_months; ?>"
                                            onclick="openWorkEducationModal('<?php echo htmlspecialchars($publication['title']); ?>', '<?php echo htmlspecialchars($publication['citation_details'] ?? ''); ?>', '<?php echo htmlspecialchars($publication['year'] ?? 'Unknown'); ?>', '')">
                                                <div class="title"><?php echo htmlspecialchars($publication['title']); ?></div>
                                                <!-- <div class="subtitle"><?php echo htmlspecialchars($publication['citation_details'] ?? ''); ?></div> -->
                                                <!-- <div class="dates"><?php echo htmlspecialchars($publication['year'] ?? 'Unknown'); ?></div> -->
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Automatically scroll to left the timeline when opened -->
                    <script>
                        window.onload = function() {
                            const chartContainer = document.querySelector('.chart-container');
                            if (chartContainer) {
                                setTimeout(function() {
                                    chartContainer.scrollLeft = chartContainer.scrollWidth;
                                }, 500); // Delay of 500 milliseconds (adjust as needed)
                            }
                        };
                    </script>

                    <!-- Resume Timeline Modal -->
                    <div class="overlay" id="work-education-overlay"></div>
                    <div class="modal" id="work-education-modal">
                        <div class="modal-header">
                            <h5 id="work-education-modal-title" class="modal-title"></h5>
                            <button class="btn-close" onclick="closeWorkEducationModal()" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body" id="work-education-modal-content"></div>
                    </div>

                    <script>
                        // Open modal for Education or Work (Generic Function)
                        function openWorkEducationModal(title, subtitle, dates, details) {
                            document.getElementById('work-education-modal-title').textContent = title;
                            document.getElementById('work-education-modal-content').innerHTML = `
                                <strong>Subtitle:</strong> ${subtitle}<br>
                                <strong>Dates:</strong> ${dates}<br>
                                <strong>Details:</strong> ${details || 'N/A'}
                            `;
                            document.getElementById('work-education-overlay').classList.add('active');
                            document.getElementById('work-education-modal').classList.add('active');
                        }

                        // Close modal for Education or Work
                        function closeWorkEducationModal() {
                            document.getElementById('work-education-overlay').classList.remove('active');
                            document.getElementById('work-education-modal').classList.remove('active');
                        }
                    </script>
                    
                </div>
            </div>
        </div>


        <?php
        // Certifications
        ?>
        <h1 id="Certifications" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">Certification</h1>
        <div class="b-example-divider"></div>    
        <div class="container container-fluid">
            <div class="row rtl text-center">
                <?php foreach ($certifications_data as $cert): ?>
                    <div class="col box">
                        <i class="fas fa-certificate"></i>
                        <h5><?= htmlspecialchars($cert['Name']); ?></h5>
                        <span>Issuer: <?= htmlspecialchars($cert['Issuer']); ?></span>
                        <span>Issue Date: <?= htmlspecialchars($cert['IssueDate']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="b-example-divider"></div>
        <br>

        <?php
        //Awards
        ?>
        <h1 id="Awards" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">Awards</h1>
        <div class="b-example-divider"></div>    
        <div class="container container-fluid">
            <div class="row rtl text-center">
                <?php foreach ($awards_data as $award): ?>
                    <div class="col box">
                        <i class="fas fa-trophy"></i>
                        <h5><?= htmlspecialchars($award['Name']); ?></h5>
                        <span>Year: 
                            <?php
                                $awardyear = $award['Year'] ?? 'Year not specified';
                                echo $awardyear;
                            ?>
                        </span>
                        <span>Description: <?= htmlspecialchars($award['Description']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="b-example-divider"></div>
        <br>

        <?php
        // Portfolio
        ?>
        <!-- PORTFOLIO -->
        <h1 id="Projects" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">Projects</h1>
        <div class="b-example-divider"></div>

        <div id="Portfolio" class="container">
            <div class="row">
            <?php foreach ($projects_data as $project): ?>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?= htmlspecialchars($project['Name']); ?></h4>
                            <p class="card-text"><?= htmlspecialchars($project['ShortDescription']); ?></p>
                            <p class="card-tags">
                                <?php
                                $tags = explode(',', $project['Tags']);
                                foreach ($tags as $tag):
                                ?>
                                    <span class="badge bg-primary"><?= htmlspecialchars(trim($tag)); ?></span>
                                <?php endforeach; ?>
                            </p>
                            <p>
                                <?php if (!empty($project['YoutubeLink'])): ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($project['YoutubeLink']); ?>" target="_blank" class="card-link">YouTube/Demo</a>
                                <?php endif; ?>
                                <?php if (!empty($project['PaperLink'])): ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($project['PaperLink']); ?>" target="_blank" class="card-link">Documentation</a>
                                <?php endif; ?>
                                <?php if (!empty($project['PresentationLink'])): ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($project['PresentationLink']); ?>" target="_blank" class="card-link">Presentation</a>
                                <?php endif; ?>
                                <?php if (!empty($project['GithubLink1'])): ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($project['GithubLink1']); ?>" target="_blank" class="card-link">Github</a>
                                <?php endif; ?>
                                <?php if (!empty($project['GithubLink2'])): ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($project['GithubLink2']); ?>" target="_blank" class="card-link">Github</a>
                                <?php endif; ?>
                                <?php if (!empty($project['GithubLink3'])): ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($project['GithubLink3']); ?>" target="_blank" class="card-link">Github</a>
                                <?php endif; ?>
                                <?php if (!empty($project['GithubLink4'])): ?>
                                    <a class="btn btn-primary" href="<?= htmlspecialchars($project['GithubLink4']); ?>" target="_blank" class="card-link">Github</a>
                                <?php endif; ?>
                                <!-- Other links handled similarly -->
                            </p>
                            <!-- <button  -->
                            <!--     class="btn btn-primary"  -->
                            <!--     data-project-name="<?= htmlspecialchars($project['Name'], ENT_QUOTES); ?>" -->
                            <!--     data-project-description="<?= htmlspecialchars($project['LongDescription'] ?? $project['ShortDescription'], ENT_QUOTES); ?>" -->
                            <!--     data-github-link="<?= htmlspecialchars($project['GithubLink1'] ?? '', ENT_QUOTES); ?>" -->
                            <!--     data-youtube-link="<?= htmlspecialchars($project['YoutubeLink'] ?? '', ENT_QUOTES); ?>" -->
                            <!--     data-paper-link="<?= htmlspecialchars($project['PaperLink'] ?? '', ENT_QUOTES); ?>" -->
                            <!--     data-presentation-link="<?= htmlspecialchars($project['PresentationLink'] ?? '', ENT_QUOTES); ?>" -->
                            <!--     onclick="openProjectModalFromButton(this)" -->
                            <!-- > -->
                            <!--     More Details -->
                            <!-- </button> -->
                            <button 
                                class="btn btn-primary" 
                                data-project='<?= json_encode([
                                    'name' => $project['Name'],
                                    'github1' => $project['GithubLink1'],
                                    'github2' => $project['GithubLink2'],
                                    'github3' => $project['GithubLink3'],
                                    'github4' => $project['GithubLink4'],
                                    'youtube' => $project['YoutubeLink'],
                                    'paper' => $project['PaperLink'],
                                    'presentation' => $project['PresentationLink'],
                                    'description' => $project['LongDescription'] ?? $project['ShortDescription']
                                ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>'
                                onclick="openProjectModalFromData(this)"
                            >
                                More Details
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            </div>
        </div>

        <!-- Modal for Project Details -->
        <div class="overlay" id="project-overlay"></div>
        <div class="modal" id="project-modal">
            <div class="modal-header">
                <h5 id="project-modal-title" class="modal-title"></h5>
                <button class="btn-close" onclick="closeProjectModal()" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="project-modal-content"></div>
        </div>

        <script>
            // Open modal for Project Details
            function openProjectModal(name, description, github, youtube, paper, presentation) {
                console.log(description);
                
                document.getElementById('project-modal-title').textContent = name;
                description = sanitizeHTML(description);  // Sanitize to prevent XSS
                description = description.replace(/\n/g, '<br>'); // Handle newlines

                console.log(description);

                const links = `
                    ${github ? `<a href="${github}" target="_blank">Github</a><br>` : ''}
                    ${youtube ? `<a href="${youtube}" target="_blank">YouTube</a><br>` : ''}
                    ${paper ? `<a href="${paper}" target="_blank">Paper</a><br>` : ''}
                    ${presentation ? `<a href="${presentation}" target="_blank">Presentation</a><br>` : ''}
                `;

                document.getElementById('project-modal-content').innerHTML = `
                    <strong>Description:</strong> ${description}<br>
                    ${links}
                `;
                document.getElementById('project-overlay').classList.add('active');
                document.getElementById('project-modal').classList.add('active');
            }

            // Close modal for Project Details
            function closeProjectModal() {
                document.getElementById('project-overlay').classList.remove('active');
                document.getElementById('project-modal').classList.remove('active');
            }

            // Sanitize HTML input to prevent XSS
            function sanitizeHTML(input) {
                var element = document.createElement('div');
                if (input) {
                    element.innerText = input;  // Escape HTML
                    element.textContent = input;
                }
                return element.innerHTML;  // Return sanitized string
            }

            // Ensure to sanitize HTML to prevent XSS
            function sanitizeHTMLFromButton(html) {
                const temp = document.createElement('div');
                temp.textContent = html;
                return temp.innerHTML;
            }

            function openProjectModalFromButton(button) {
                const name = button.getAttribute('data-project-name');
                let description = button.getAttribute('data-project-description');
                const github = button.getAttribute('data-github-link');
                const youtube = button.getAttribute('data-youtube-link');
                const paper = button.getAttribute('data-paper-link');
                const presentation = button.getAttribute('data-presentation-link');

                console.log(description);

                // Sanitize and process description
                description = sanitizeHTMLFromButton(description).replace(/\n/g, '<br>');

                document.getElementById('project-modal-title').textContent = name;
                document.getElementById('project-modal-content').innerHTML = `
                    ${github ? `<a href="${github}" target="_blank">GitHub</a><br>` : ''}
                    ${youtube ? `<a href="${youtube}" target="_blank">YouTube</a><br>` : ''}
                    ${paper ? `<a href="${paper}" target="_blank">Paper</a><br>` : ''}
                    ${presentation ? `<a href="${presentation}" target="_blank">Presentation</a><br>` : ''}
                    <strong>Description:</strong> ${description}<br>
                `;
                document.getElementById('project-overlay').classList.add('active');
                document.getElementById('project-modal').classList.add('active');
            }

            function openProjectModalFromData(button) {
                const project = JSON.parse(button.getAttribute('data-project'));

                document.getElementById('project-modal-title').textContent = project.name;
                let description = sanitizeHTMLFromButton(project.description).replace(/\n/g, '<br>');

                document.getElementById('project-modal-content').innerHTML = `
                    ${project.github1 ? `<a href="${project.github1}" target="_blank">GitHub1</a><br>` : ''}
                    ${project.github2 ? `<a href="${project.github2}" target="_blank">GitHub2</a><br>` : ''}
                    ${project.github3 ? `<a href="${project.github3}" target="_blank">GitHub3</a><br>` : ''}
                    ${project.github4 ? `<a href="${project.github4}" target="_blank">GitHub4</a><br>` : ''}
                    ${project.youtube ? `<a href="${project.youtube}" target="_blank">YouTube</a><br>` : ''}
                    ${project.paper ? `<a href="${project.paper}" target="_blank">Paper</a><br>` : ''}
                    ${project.presentation ? `<a href="${project.presentation}" target="_blank">Presentation</a><br>` : ''}
                    <strong>Description:</strong> ${description}<br>
                `;
                document.getElementById('project-overlay').classList.add('active');
                document.getElementById('project-modal').classList.add('active');
            }
        </script>
        <!-- End #Projects -->

        <!-- Skills Section -->
        <h1 id="SkillTags" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-body-tertiary">Skills</h1>
        <div class="b-example-divider"></div>
        <div id="SkillTags" class="container">
            <div class="row">
                <!-- Technical Skills -->
                <div class="col-md-4 skills">
                    <h2 class="heading">Technical Skills</h2>
                    <h3>Programming</h3>
                    <ul>
                        <?php foreach ($technical_skills as $skill): ?>
                            <?php if ($skill['category'] == 'Programming'): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Tools</h3>
                    <ul>
                        <?php foreach ($technical_skills as $skill): ?>
                            <?php if ($skill['category'] == 'Tools'): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Hardware</h3>
                    <ul>
                        <?php foreach ($technical_skills as $skill): ?>
                            <?php if ($skill['category'] == 'Hardware'): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Soft Skills -->
                <div class="col-md-4 skills">
                    <h2 class="heading">Soft Skills</h2>
                    <h3>Methodologies</h3>
                    <ul>
                        <?php foreach ($soft_skills as $skill): ?>
                            <?php if ($skill['category'] == 'Methodologies'): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Research & Analysis</h3>
                    <ul>
                        <?php foreach ($soft_skills as $skill): ?>
                            <?php if ($skill['category'] == 'Research_Analysis'): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Language</h3>
                    <ul>
                        <?php foreach ($soft_skills as $skill): ?>
                            <?php if ($skill['category'] == 'Language'): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Interests -->
                <div class="col-md-4 skills">
                    <h2 class="heading">Interests</h2>
                    <ul>
                        <?php foreach ($interests as $skill): ?>
                            <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Managerial</h3>
                    <ul>
                        <?php foreach ($soft_skills as $skill): ?>
                            <?php if ($skill['category'] == 'Managerial'): ?>
                                <li><?php echo htmlspecialchars($skill['skill_name']); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End #skills -->


    </main>

    <br>

    <footer class="py-5 text-center text-body-secondary bg-body-tertiary">
        <p>Portfolio made by <a href="https://github.com/cinapr/TechCareerPortofolioTemplate/">Cindy Aprilia</a> @ 2024</p>
    </footer>
</body>
</html>