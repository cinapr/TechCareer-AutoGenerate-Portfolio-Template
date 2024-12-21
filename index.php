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
    <title>Your Career Timeline</title>

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
                                                onclick="openModal('<?php echo htmlspecialchars($work['job_title']); ?>', '<?php echo htmlspecialchars($work['company_name']); ?>', '<?php echo htmlspecialchars($work['start_date']); ?> - <?php echo htmlspecialchars($work['end_date']); ?>', '<?php echo htmlspecialchars($work['details']); ?>')">
                                                <div class="title"><?php echo htmlspecialchars($work['job_title']); ?></div>
                                                <div class="subtitle"><?php echo htmlspecialchars($work['company_name']); ?></div>
                                                <div class="dates"><?php echo htmlspecialchars($work['start_date']); ?> - <?php echo htmlspecialchars($work['end_date']); ?></div>
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
                                                onclick="openModal('<?php echo htmlspecialchars($education['degree_program']); ?>', '<?php echo htmlspecialchars($education['university_name']); ?>', '<?php echo htmlspecialchars($education['start_date']); ?> - <?php echo htmlspecialchars($education['end_date']); ?>', '<?php echo htmlspecialchars($education['thesis_title'] ?? ''); ?>')">
                                                <div class="title"><?php echo htmlspecialchars($education['degree_program']); ?></div>
                                                <div class="subtitle"><?php echo htmlspecialchars($education['university_name']); ?></div>
                                                <div class="dates"><?php echo htmlspecialchars($education['start_date']); ?> - <?php echo htmlspecialchars($education['end_date']); ?></div>
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
                                            onclick="openModal('<?php echo htmlspecialchars($publication['title']); ?>', '<?php echo htmlspecialchars($publication['citation_details'] ?? ''); ?>', '<?php echo htmlspecialchars($publication['year'] ?? 'Unknown'); ?>', '')">
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
                    <div class="overlay" id="overlay"></div>
                    <div class="modal" id="modal">
                        <div class="modal-header">
                            <h5 id="modal-title" class="modal-title"></h5>
                            <button class="btn-close" onclick="closeModal()" aria-label="Close">&times;</button>
                        </div>
                        <div class="modal-body" id="modal-content"></div>
                    </div>

                    <script>
                        function openModal(title, subtitle, dates, details) {
                            document.getElementById('modal-title').textContent = title;
                            document.getElementById('modal-content').innerHTML = `
                                <strong>Subtitle:</strong> ${subtitle}<br>
                                <strong>Dates:</strong> ${dates}<br>
                                <strong>Details:</strong> ${details || 'N/A'}
                            `;
                            document.getElementById('overlay').classList.add('active');
                            document.getElementById('modal').classList.add('active');
                        }

                        function closeModal() {
                            document.getElementById('overlay').classList.remove('active');
                            document.getElementById('modal').classList.remove('active');
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
        //
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
                                <!-- Project Name -->
                                <h4 class="card-title"><?= htmlspecialchars($project['Name']); ?></h4>
                                <!-- Short Description -->
                                <p class="card-text"><?= htmlspecialchars($project['ShortDescription']); ?></p>
                                <!-- Tags -->
                                <p class="card-tags">
                                    <?php
                                    $tags = explode(',', $project['Tags']); // Assuming tags are stored as comma-separated values in the database
                                    foreach ($tags as $tag):
                                    ?>
                                        <span class="badge bg-primary"><?= htmlspecialchars(trim($tag)); ?></span>
                                    <?php endforeach; ?>
                                </p>
                                <!-- Links -->
                                <p>
                                    <?php if (!empty($project['GithubLink'])): ?>
                                        <a href="<?= htmlspecialchars($project['GithubLink']); ?>" target="_blank" class="card-link">Github</a>
                                    <?php endif; ?>
                                    <?php if (!empty($project['YoutubeLink'])): ?>
                                        <a href="<?= htmlspecialchars($project['YoutubeLink']); ?>" target="_blank" class="card-link">YouTube</a>
                                    <?php endif; ?>
                                    <?php if (!empty($project['PaperLink'])): ?>
                                        <a href="<?= htmlspecialchars($project['PaperLink']); ?>" target="_blank" class="card-link">Paper</a>
                                    <?php endif; ?>
                                    <?php if (!empty($project['PresentationLink'])): ?>
                                        <a href="<?= htmlspecialchars($project['PresentationLink']); ?>" target="_blank" class="card-link">Presentation</a>
                                    <?php endif; ?>
                                </p>
                                <!-- Modal Trigger -->
                                <button class="btn btn-primary" onclick="openProjectModal(
                                    '<?= htmlspecialchars($project['Name'], ENT_QUOTES); ?>',
                                    '<?= htmlspecialchars($project['LongDescription'], ENT_QUOTES); ?>',
                                    '<?= htmlspecialchars($project['GithubLink'] ?? '', ENT_QUOTES); ?>',
                                    '<?= htmlspecialchars($project['YoutubeLink'] ?? '', ENT_QUOTES); ?>',
                                    '<?= htmlspecialchars($project['PaperLink'] ?? '', ENT_QUOTES); ?>',
                                    '<?= htmlspecialchars($project['PresentationLink'] ?? '', ENT_QUOTES); ?>'
                                )">More Details</button>
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
            function openProjectModal(name, description, github, youtube, paper, presentation) {
                document.getElementById('project-modal-title').textContent = name;
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

            function closeProjectModal() {
                document.getElementById('project-overlay').classList.remove('active');
                document.getElementById('project-modal').classList.remove('active');
            }
        </script>


    </main>

    <br>

    <footer class="py-5 text-center text-body-secondary bg-body-tertiary">
        <p>Portfolio built based on Template made by <a href="https://github.com/cinapr/TechCareerPortofolioTemplate/">Cindy Aprilia</a> @ 2024</p>
    </footer>
</body>
</html>