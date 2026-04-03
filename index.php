<?php
// Teacher/Admin Portal - Independent from Student Portal
session_start();

// Basic page router
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Check login
$loggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Handle login
if ($page === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Demo admin login
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = [
            'id' => 1,
            'username' => 'admin',
            'name' => 'Dr. Maria Santos',
            'role' => 'Associate Professor',
            'department' => 'Computer Science'
        ];
        header('Location: ?page=dashboard');
        exit;
    } else {
        $loginError = "Invalid credentials";
    }
}

// Note: Direct teacher login is now available via the login form
// Redirection from student portal is no longer needed
// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ?page=login');
    exit;
}

// If not logged in and not on login page, redirect to login
if (!$loggedIn && $page !== 'login') {
    header('Location: ?page=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #059669;
            --secondary: #7c3aed;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        body { font-family: 'Segoe UI', sans-serif; background: var(--light); }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav-link { color: var(--dark) !important; }
        .nav-link.active { color: var(--primary) !important; font-weight: 600; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card:hover { transform: translateY(-2px); }
        .btn-primary { background: var(--primary); border: none; }
        .btn-primary:hover { background: #047857; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, var(--primary), var(--secondary)); }
        .sidebar .nav-link { color: rgba(255,255,255,0.8) !important; padding: 12px 20px; }
        .sidebar .nav-link:hover { color: white !important; background: rgba(255,255,255,0.1); }
        .sidebar .nav-link.active { color: white !important; background: rgba(255,255,255,0.2); }
        .grade-table { font-size: 0.9rem; }
        .grade-input { width: 80px; text-align: center; border: none; background: transparent; }
        .grade-input:focus { background: white; }
        .status-excellent { background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; }
        .status-good { background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; }
        .status-average { background: rgba(245, 158, 11, 0.1); border-left: 4px solid #f59e0b; }
        .status-poor { background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; }

        /* Login form styles */
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1); }
        .input-group-text { background-color: #f8fafc; border-color: #e5e7eb; }
        .btn-primary:hover { background-color: #047857; transform: translateY(-1px); }
        .alert { border: none; border-radius: 8px; }

        /* Teacher-only warning */
        .bg-warning { background: linear-gradient(135deg, #fbbf24, #f59e0b) !important; border: 2px solid #d97706; }
        .bg-warning .text-dark { color: #92400e !important; font-weight: 600; }
    </style>
</head>
<body>

<?php if ($loggedIn): ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0 sidebar">
            <div class="d-flex flex-column">
                <div class="p-3 text-white border-bottom">
                    <h5 class="mb-1">👨‍🏫 Teacher Portal</h5>
                    <small><?php echo $_SESSION['admin_user']['name']; ?></small>
                </div>
                <nav class="nav flex-column py-3">
                    <a class="nav-link <?php echo $page=='dashboard'?'active':'' ?>" href="?page=dashboard">
                        <i class="bi bi-house-door me-2"></i>Dashboard
                    </a>
                    <a class="nav-link <?php echo $page=='classes'?'active':'' ?>" href="?page=classes">
                        <i class="bi bi-book me-2"></i>My Classes
                    </a>
                    <a class="nav-link <?php echo $page=='grades'?'active':'' ?>" href="?page=grades">
                        <i class="bi bi-bar-chart me-2"></i>Grades
                    </a>
                    <a class="nav-link <?php echo $page=='profile'?'active':'' ?>" href="?page=profile">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                    <hr class="my-3">
                    <a class="nav-link text-danger" href="?action=logout">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 px-4 py-4">
            <?php if ($page === 'dashboard'): ?>
                <!-- Simplified Teacher Dashboard -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>👋 Welcome back, <?php echo explode(' ', $_SESSION['admin_user']['name'])[0]; ?>!</h2>
                        <p class="text-muted mb-0"><?php echo $_SESSION['admin_user']['role']; ?> • <?php echo $_SESSION['admin_user']['department']; ?></p>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small"><?php echo date('l, F j, Y'); ?></div>
                    </div>
                </div>

                <!-- Minimal Overview -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 clickable-card" onclick="window.location.href='?page=classes'" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <i class="bi bi-book fs-1 text-primary"></i>
                                <h3 class="mt-2 text-primary">3</h3>
                                <small class="text-muted">My Classes</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="bi bi-arrow-right-circle me-1"></i>Click to view</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 clickable-card" onclick="window.location.href='?page=grades'" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                                <h3 class="mt-2 text-warning">2</h3>
                                <small class="text-muted">Pending Grade Submissions</small>
                                <div class="mt-2">
                                    <small class="text-warning"><i class="bi bi-arrow-right-circle me-1"></i>Click to grade</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Action Button -->
                <div class="text-center">
                    <a href="?page=classes" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="bi bi-pencil-square me-2 fs-4"></i>
                        Enter Grades
                    </a>
                </div>

            <?php elseif ($page === 'classes'): ?>
                <!-- My Classes - Simplified -->
                <h2>📚 My Classes</h2>
                <p class="text-muted">Click on a class to enter grades</p>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card clickable-card" onclick="window.location.href='?page=grades&class=cs101'">
                            <div class="card-body text-center">
                                <i class="bi bi-code-square fs-1 text-primary mb-3"></i>
                                <h5>CS101</h5>
                                <h6 class="text-muted">Programming Fundamentals</h6>
                                <small class="text-muted d-block">42 Students</small>
                                <div class="mt-3">
                                    <span class="badge bg-warning">Grades Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card clickable-card" onclick="window.location.href='?page=grades&class=cs201'">
                            <div class="card-body text-center">
                                <i class="bi bi-diagram-3 fs-1 text-success mb-3"></i>
                                <h5>CS201</h5>
                                <h6 class="text-muted">Data Structures</h6>
                                <small class="text-muted d-block">35 Students</small>
                                <div class="mt-3">
                                    <span class="badge bg-warning">Grades Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card clickable-card" onclick="window.location.href='?page=grades&class=cs301'">
                            <div class="card-body text-center">
                                <i class="bi bi-globe fs-1 text-info mb-3"></i>
                                <h5>CS301</h5>
                                <h6 class="text-muted">Web Development</h6>
                                <small class="text-muted d-block">28 Students</small>
                                <div class="mt-3">
                                    <span class="badge bg-success">Grades Submitted</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                .clickable-card {
                    cursor: pointer;
                    transition: transform 0.2s, box-shadow 0.2s;
                }
                .clickable-card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                </style>

            <?php elseif ($page === 'grades'): ?>
                <!-- Simplified Grade Entry - FINAL DESIGN -->
                <?php
                $classCode = isset($_GET['class']) ? strtoupper($_GET['class']) : 'CS101';
                $classNames = [
                    'CS101' => 'Programming Fundamentals',
                    'CS201' => 'Data Structures',
                    'CS301' => 'Web Development'
                ];
                $className = $classNames[$classCode] ?? 'Unknown Class';
                ?>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>📝 Grade Entry</h2>
                        <h5 class="text-muted"><?php echo $classCode; ?> - <?php echo $className; ?></h5>
                    </div>
                    <div>
                        <button class="btn btn-success btn-lg" onclick="submitFinalGrades()">
                            <i class="bi bi-lock me-2"></i>Submit Final Grades
                        </button>
                    </div>
                </div>

                <!-- Grade Submission Deadline -->
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-calendar-event me-2"></i>
                    <strong>Grade Submission Deadline:</strong> <?php echo date('F j, Y', strtotime('+7 days')); ?> (<?php echo 7 - date('j', strtotime('+7 days')) + 1; ?> days remaining)
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>How to use:</strong> Click the "Edit" button for each student to modify their grades.
                    Changes are saved automatically. When finished, click "Submit Final Grades" to lock all entries.
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                             <table class="table table-hover mb-0" id="gradesTable">
                                 <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                     <tr>
                                         <th class="border-end">#</th>
                                         <th class="border-end">Student Name</th>
                                         <th class="border-end text-center bg-light">Midterm</th>
                                         <th class="border-end text-center bg-light">Final</th>
                                         <th class="text-center">Remarks</th>
                                         <th class="text-center">Actions</th>
                                     </tr>
                                 </thead>
                                <tbody>
                                     <tr>
                                         <td class="border-end fw-bold">1</td>
                                         <td class="border-end fw-bold">Reynante Yu</td>
                                         <td class="border-end text-center">
                                             <span id="rey-yu-midterm-display">88</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="88" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="rey-yu-midterm">
                                         </td>
                                         <td class="border-end text-center">
                                             <span id="rey-yu-final-display">90</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="90" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="rey-yu-final">
                                         </td>
                                         <td class="text-center">
                                             <span class="badge bg-success fs-6" id="rey-yu-remarks">Passed</span>
                                         </td>
                                         <td class="text-center">
                                             <div class="btn-group btn-group-sm">
                                                 <button class="btn btn-outline-primary btn-sm" onclick="editGrade('rey-yu', 'midterm')" id="edit-rey-yu-midterm">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                                 <button class="btn btn-outline-success btn-sm" onclick="editGrade('rey-yu', 'final')" id="edit-rey-yu-final">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                             </div>
                                         </td>
                                     </tr>
                                     <tr>
                                         <td class="border-end fw-bold">2</td>
                                         <td class="border-end fw-bold">Anna Cruz</td>
                                         <td class="border-end text-center">
                                             <span id="anna-cruz-midterm-display">82</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="82" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="anna-cruz-midterm">
                                         </td>
                                         <td class="border-end text-center">
                                             <span id="anna-cruz-final-display">87</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="87" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="anna-cruz-final">
                                         </td>
                                         <td class="text-center">
                                             <span class="badge bg-success fs-6" id="anna-cruz-remarks">Passed</span>
                                         </td>
                                         <td class="text-center">
                                             <div class="btn-group btn-group-sm">
                                                 <button class="btn btn-outline-primary btn-sm" onclick="editGrade('anna-cruz', 'midterm')" id="edit-anna-cruz-midterm">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                                 <button class="btn btn-outline-success btn-sm" onclick="editGrade('anna-cruz', 'final')" id="edit-anna-cruz-final">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                             </div>
                                         </td>
                                     </tr>
                                     <tr>
                                         <td class="border-end fw-bold">3</td>
                                         <td class="border-end fw-bold">Miguel Santos</td>
                                         <td class="border-end text-center">
                                             <span id="miguel-santos-midterm-display">75</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="75" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="miguel-santos-midterm">
                                         </td>
                                         <td class="border-end text-center">
                                             <span id="miguel-santos-final-display">78</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="78" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="miguel-santos-final">
                                         </td>
                                         <td class="text-center">
                                             <span class="badge bg-danger fs-6" id="miguel-santos-remarks">Failed</span>
                                         </td>
                                         <td class="text-center">
                                             <div class="btn-group btn-group-sm">
                                                 <button class="btn btn-outline-primary btn-sm" onclick="editGrade('miguel-santos', 'midterm')" id="edit-miguel-santos-midterm">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                                 <button class="btn btn-outline-success btn-sm" onclick="editGrade('miguel-santos', 'final')" id="edit-miguel-santos-final">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                             </div>
                                         </td>
                                     </tr>
                                     <tr>
                                         <td class="border-end fw-bold">4</td>
                                         <td class="border-end fw-bold">Sarah Lim</td>
                                         <td class="border-end text-center">
                                             <span id="sarah-lim-midterm-display">70</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="70" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="sarah-lim-midterm">
                                         </td>
                                         <td class="border-end text-center">
                                             <span id="sarah-lim-final-display">72</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    value="72" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="sarah-lim-final">
                                         </td>
                                         <td class="text-center">
                                             <span class="badge bg-danger fs-6" id="sarah-lim-remarks">Failed</span>
                                         </td>
                                         <td class="text-center">
                                             <div class="btn-group btn-group-sm">
                                                 <button class="btn btn-outline-primary btn-sm" onclick="editGrade('sarah-lim', 'midterm')" id="edit-sarah-lim-midterm">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                                 <button class="btn btn-outline-success btn-sm" onclick="editGrade('sarah-lim', 'final')" id="edit-sarah-lim-final">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                             </div>
                                         </td>
                                     </tr>
                                     <tr>
                                         <td class="border-end fw-bold">5</td>
                                         <td class="border-end fw-bold">John Rivera</td>
                                         <td class="border-end text-center">
                                             <span id="john-rivera-midterm-display">--</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    placeholder="--" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="john-rivera-midterm">
                                         </td>
                                         <td class="border-end text-center">
                                             <span id="john-rivera-final-display">--</span>
                                             <input type="number" class="grade-input form-control text-center border-0"
                                                    placeholder="--" min="0" max="100" maxlength="3" style="display:none;"
                                                    id="john-rivera-final">
                                         </td>
                                         <td class="text-center">
                                             <span class="badge bg-secondary fs-6" id="john-rivera-remarks">--</span>
                                         </td>
                                         <td class="text-center">
                                             <div class="btn-group btn-group-sm">
                                                 <button class="btn btn-outline-primary btn-sm" onclick="editGrade('john-rivera', 'midterm')" id="edit-john-rivera-midterm">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                                 <button class="btn btn-outline-success btn-sm" onclick="editGrade('john-rivera', 'final')" id="edit-john-rivera-final">
                                                     <i class="bi bi-pencil"></i>
                                                 </button>
                                             </div>
                                         </td>
                                     </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <script>
                let gradesData = {
                    'rey-yu': { midterm: 88, final: 90 },
                    'anna-cruz': { midterm: 82, final: 87 },
                    'miguel-santos': { midterm: 75, final: 78 },
                    'sarah-lim': { midterm: 70, final: 72 },
                    'john-rivera': { midterm: null, final: null }
                };

                let isSubmitted = false;
                let editMode = {};

                function autoSave(input, student, type) {
                    if (isSubmitted) return;

                    const value = parseInt(input.value) || null;
                    gradesData[student][type] = value;

                    // Auto-update remarks
                    updateRemarks(student);

                    // Simulate save to database
                    console.log(`Saved ${student} ${type}: ${value}`);
                }

                function updateRemarks(student) {
                    const midterm = gradesData[student].midterm;
                    const final = gradesData[student].final;

                    if (midterm !== null && final !== null) {
                        const average = (midterm + final) / 2;
                        const passed = average >= 75; // Assuming 75 is passing grade

                        const remarksElement = document.getElementById(`${student}-remarks`);
                        remarksElement.textContent = passed ? 'Passed' : 'Failed';
                        remarksElement.className = `badge fs-6 ${passed ? 'bg-success' : 'bg-danger'}`;
                    }
                }

                function editGrade(student, type) {
                    if (isSubmitted) return;

                    const inputId = `${student}-${type}`;
                    const displayId = `${student}-${type}-display`;
                    const editBtn = document.getElementById(`edit-${student}-${type}`);

                    // Toggle edit mode
                    if (editMode[inputId]) {
                        // Save mode
                        const input = document.getElementById(inputId);
                        const value = parseInt(input.value) || null;
                        gradesData[student][type] = value;

                        // Update display
                        document.getElementById(displayId).textContent = value || '--';
                        document.getElementById(displayId).style.display = 'block';
                        input.style.display = 'none';

                        // Update button
                        editBtn.innerHTML = '<i class="bi bi-pencil"></i>';
                        editBtn.className = 'btn btn-outline-primary btn-sm';

                        // Update remarks
                        updateRemarks(student);

                        // Simulate save
                        console.log(`Saved ${student} ${type}: ${value}`);
                    } else {
                        // Edit mode
                        const display = document.getElementById(displayId);
                        const input = document.getElementById(inputId);

                        display.style.display = 'none';
                        input.style.display = 'block';
                        input.focus();

                        // Update button
                        editBtn.innerHTML = '<i class="bi bi-check"></i>';
                        editBtn.className = 'btn btn-success btn-sm';
                    }

                    editMode[inputId] = !editMode[inputId];
                }

                function submitFinalGrades() {
                    if (isSubmitted) return;

                    const confirmed = confirm('Are you sure you want to submit final grades? This action cannot be undone and all grades will be locked.');
                    if (!confirmed) return;

                    // Lock all inputs and hide edit buttons
                    const inputs = document.querySelectorAll('.grade-input');
                    const editBtns = document.querySelectorAll('[id^="edit-"]');

                    inputs.forEach(input => {
                        input.disabled = true;
                        input.style.backgroundColor = '#f8f9fa';
                        input.style.display = 'none';
                    });

                    editBtns.forEach(btn => {
                        btn.style.display = 'none';
                    });

                    // Show display values
                    const displays = document.querySelectorAll('[id$="-display"]');
                    displays.forEach(display => {
                        display.style.display = 'block';
                    });

                    // Update submit button
                    const submitBtn = document.querySelector('button[onclick="submitFinalGrades()"]');
                    submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Grades Submitted';
                    submitBtn.className = 'btn btn-secondary btn-lg';
                    submitBtn.disabled = true;

                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success mt-3';
                    alert.innerHTML = '<i class="bi bi-check-circle me-2"></i><strong>Success!</strong> Final grades have been submitted and locked. Students can now view their grades.';
                    document.querySelector('.card').insertAdjacentElement('afterend', alert);

                    isSubmitted = true;

                    // Simulate database update
                    console.log('Final grades submitted:', gradesData);
                }

                // Initialize remarks on page load
                document.addEventListener('DOMContentLoaded', function() {
                    Object.keys(gradesData).forEach(student => {
                        updateRemarks(student);
                    });
                });
                </script>

                <style>
                .grade-input:focus {
                    box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
                    background-color: white !important;
                }
                .table th {
                    font-weight: 600;
                    font-size: 0.9rem;
                }
                .table td {
                    vertical-align: middle;
                }
                </style>



            <?php endif; ?>
        </div>
    </div>
</div>

<?php else: ?>
<!-- Direct Teacher Login Page -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, var(--primary), var(--secondary));">
                    <h3><i class="bi bi-chalkboard-teacher me-2"></i>👨‍🏫 Teacher Login</h3>
                    <p class="mb-0"><strong>Exclusive Access for Faculty Members</strong></p>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($loginError)): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?php echo $loginError; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="?page=login">
                        <div class="mb-3">
                            <label for="username" class="form-label">👨‍🏫 Teacher Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input type="text" class="form-control" id="username" name="username"
                                       placeholder="Enter your faculty username" required
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">🔐 Faculty Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="Enter your faculty password" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>👨‍🏫 Access Teacher Portal
                        </button>
                    </form>

                    <div class="mt-4 p-3 bg-warning rounded">
                        <h6 class="mb-2"><i class="bi bi-exclamation-triangle text-dark me-1"></i>⚠️ Faculty Only Access</h6>
                        <small class="text-dark">
                            This portal is exclusively for teachers and faculty members.<br>
                            Students should use the <strong>Student Portal</strong> instead.
                        </small>
                    </div>

                    <div class="mt-3 p-3 bg-light rounded">
                        <h6 class="mb-2"><i class="bi bi-key text-primary me-1"></i>Demo Teacher Account</h6>
                        <small>
                            <strong>Username:</strong> <code>admin</code><br>
                            <strong>Password:</strong> <code>admin123</code><br>
                            <em>Dr. Maria Santos - Computer Science</em>
                        </small>
                    </div>

                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            Secure faculty authentication system
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-focus username field and add form enhancements
document.addEventListener('DOMContentLoaded', function() {
    const usernameField = document.getElementById('username');
    if (usernameField) {
        usernameField.focus();
    }

    // Form submission feedback
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!username || !password) {
                e.preventDefault();
                alert('⚠️ Faculty Access Required: Please enter both username and password.');
                return false;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>🔐 Authenticating Faculty Access...';
            }
        });
    }

    // Toggle password visibility
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        // Add toggle button if needed
        const inputGroup = passwordInput.parentElement;
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'btn btn-outline-secondary';
        toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
        toggleBtn.onclick = function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        };
        inputGroup.appendChild(toggleBtn);
    }
});
</script>

<?php endif; ?>

<?php if ($loggedIn): ?>
    <?php if ($page === 'profile'): ?>
                <!-- Simple Teacher Profile -->
                <div class="card mb-4" style="background: linear-gradient(135deg, #059669, #7c3aed);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-white p-3 me-4">
                                <i class="bi bi-person-circle fs-1 text-primary"></i>
                            </div>
                            <div class="text-white">
                                <h2><?php echo $_SESSION['admin_user']['name']; ?></h2>
                                <p class="mb-0" style="opacity:0.75"><?php echo $_SESSION['admin_user']['role']; ?> • <?php echo $_SESSION['admin_user']['department']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">👨‍🏫 Teacher Information</h5></div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Name:</strong></div>
                                    <div class="col-sm-8"><?php echo $_SESSION['admin_user']['name']; ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Role:</strong></div>
                                    <div class="col-sm-8"><?php echo $_SESSION['admin_user']['role']; ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Department:</strong></div>
                                    <div class="col-sm-8"><?php echo $_SESSION['admin_user']['department']; ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Employee ID:</strong></div>
                                    <div class="col-sm-8"><?php echo $_SESSION['admin_user']['username']; ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8">maria.santos@university.edu</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Phone:</strong></div>
                                    <div class="col-sm-8">+63 917 123 4567</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Office:</strong></div>
                                    <div class="col-sm-8">Room 204, Computer Science Building</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Join Date:</strong></div>
                                    <div class="col-sm-8">August 2018</div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-sm-4"><strong>Specialization:</strong></div>
                                    <div class="col-sm-8">Web Development, Data Structures, Algorithms</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">📊 Teaching Summary</h5></div>
                            <div class="card-body">
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="p-2 bg-primary text-white rounded">
                                            <h4 class="mb-1">3</h4>
                                            <small>Active Classes</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 bg-success text-white rounded">
                                            <h4 class="mb-1">105</h4>
                                            <small>Total Students</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="p-2 bg-info text-white rounded">
                                            <h4 class="mb-1">1</h4>
                                            <small>Grades Submitted</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 bg-warning text-white rounded">
                                            <h4 class="mb-1">2</h4>
                                            <small>Pending Grades</small>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h6>📚 Current Semester Classes:</h6>
                                <ul class="list-unstyled small">
                                    <li><i class="bi bi-book me-2"></i>CS101 - Programming Fundamentals (42 students)</li>
                                    <li><i class="bi bi-diagram-3 me-2"></i>CS201 - Data Structures (35 students)</li>
                                    <li><i class="bi bi-globe me-2"></i>CS301 - Web Development (28 students)</li>
                                </ul>
                                <hr>
                                <h6>🏆 Achievements:</h6>
                                <ul class="list-unstyled small">
                                    <li><i class="bi bi-trophy me-2 text-warning"></i>Teacher of the Year 2023</li>
                                    <li><i class="bi bi-star me-2 text-warning"></i>95% Student Satisfaction Rate</li>
                                    <li><i class="bi bi-award me-2 text-warning"></i>Published 5 Research Papers</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

    <?php endif; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>