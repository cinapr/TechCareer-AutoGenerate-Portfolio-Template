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

    <link href="styles/header.css" rel="stylesheet">
    <link href="styles/timeline.css" rel="stylesheet">
    <link href="styles/modal.css" rel="stylesheet">

</head>

<body>
    <!-- HEADER -->
    <div class="header">
        <h1>Cindy Aprilia</h1>
        <hr>
        <p>
            Komplek Taman Setia Budi Indah (TASBIH) 1 Blok VV No.118 • Medan, Sumatera Utara, Indonesia 20122
        </p>
        <p class="contact-info">
            <span>Email:</span> <a href="mailto:cindyapriliaf@gmail.com">cindyapriliaf@gmail.com</a> •
            <span>Call & Whatsapp:</span> <a href="https://api.whatsapp.com/send/?phone=62822&text=&app_absent=0">+62822</a>
        </p>
        <p class="social-links">
            <span>LinkedIn:</span> <a href="https://www.linkedin.com/in/apriliacindy/" target="_blank">https://www.linkedin.com/in/apriliacindy/</a> •
            <span>GitHub:</span> <a href="https://github.com/cinapr" target="_blank">https://github.com/cinapr/</a> •
            <span>YouTube:</span> <a href="https://www.youtube.com/@CindyApriliaSE4GD" target="_blank">https://www.youtube.com/@CindyApriliaSE4GD</a>
        </p>
    </div>

    <!-- RESUME TIMELINE -->
    <div class="container">
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
                            <li class="work" style="grid-column: <?php echo $grid_start; ?> / span <?php echo $span_months; ?>"
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
                            <li class="education" style="grid-column: <?php echo $grid_start; ?> / span <?php echo $span_months; ?>"
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
                            <li class="publication" style="grid-column: <?php echo $grid_start; ?> / span <?php echo $span_months; ?>"
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
</body>

</html>
