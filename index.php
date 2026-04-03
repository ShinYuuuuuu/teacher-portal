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
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-book fs-1 text-primary"></i>
                                <h3 class="mt-2 text-primary">3</h3>
                                <small class="text-muted">My Classes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                                <h3 class="mt-2 text-warning">2</h3>
                                <small class="text-muted">Pending Grade Submissions</small>
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

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>How to use:</strong> Click on any grade cell to edit. Changes are saved automatically.
                    When finished, click "Submit Final Grades" to lock all entries.
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border-end fw-bold">1</td>
                                        <td class="border-end fw-bold">Reynante Yu</td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="88" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'rey-yu', 'midterm')" id="rey-yu-midterm">
                                        </td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="90" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'rey-yu', 'final')" id="rey-yu-final">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6" id="rey-yu-remarks">Passed</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-end fw-bold">2</td>
                                        <td class="border-end fw-bold">Anna Cruz</td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="82" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'anna-cruz', 'midterm')" id="anna-cruz-midterm">
                                        </td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="87" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'anna-cruz', 'final')" id="anna-cruz-final">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success fs-6" id="anna-cruz-remarks">Passed</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-end fw-bold">3</td>
                                        <td class="border-end fw-bold">Miguel Santos</td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="75" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'miguel-santos', 'midterm')" id="miguel-santos-midterm">
                                        </td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="78" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'miguel-santos', 'final')" id="miguel-santos-final">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger fs-6" id="miguel-santos-remarks">Failed</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-end fw-bold">4</td>
                                        <td class="border-end fw-bold">Sarah Lim</td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="70" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'sarah-lim', 'midterm')" id="sarah-lim-midterm">
                                        </td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   value="72" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'sarah-lim', 'final')" id="sarah-lim-final">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger fs-6" id="sarah-lim-remarks">Failed</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-end fw-bold">5</td>
                                        <td class="border-end fw-bold">John Rivera</td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   placeholder="--" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'john-rivera', 'midterm')" id="john-rivera-midterm">
                                        </td>
                                        <td class="border-end text-center">
                                            <input type="number" class="grade-input form-control text-center border-0 bg-transparent"
                                                   placeholder="--" min="0" max="100" maxlength="3"
                                                   oninput="autoSave(this, 'john-rivera', 'final')" id="john-rivera-final">
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary fs-6" id="john-rivera-remarks">--</span>
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

                function submitFinalGrades() {
                    if (isSubmitted) return;

                    const confirmed = confirm('Are you sure you want to submit final grades? This action cannot be undone and all grades will be locked.');
                    if (!confirmed) return;

                    // Lock all inputs
                    const inputs = document.querySelectorAll('.grade-input');
                    inputs.forEach(input => {
                        input.disabled = true;
                        input.style.backgroundColor = '#f8f9fa';
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
<!-- Login Page -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3><i class="bi bi-chalkboard-teacher me-2"></i>Teacher Portal</h3>
                    <p class="mb-0">Administrative Access</p>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($loginError)): ?>
                    <div class="alert alert-danger"><?php echo $loginError; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
                    </form>
                    <div class="mt-4 p-3 bg-light rounded">
                        <small><strong>Demo Access:</strong><br>
                        Username: <code>admin</code><br>
                        Password: <code>admin123</code></small>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">Looking for Student Portal? <a href="../">Click here</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            <div class="card-header"><h5 class="mb-0">Teacher Information</h5></div>
                            <div class="card-body">
                                <p><strong>Name:</strong> <?php echo $_SESSION['admin_user']['name']; ?></p>
                                <p><strong>Role:</strong> <?php echo $_SESSION['admin_user']['role']; ?></p>
                                <p><strong>Department:</strong> <?php echo $_SESSION['admin_user']['department']; ?></p>
                                <p><strong>Employee ID:</strong> <?php echo $_SESSION['admin_user']['username']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">Teaching Summary</h5></div>
                            <div class="card-body">
                                <p><strong>Active Classes:</strong> 3</p>
                                <p><strong>Total Students:</strong> 105</p>
                                <p><strong>Grades Submitted:</strong> 1</p>
                                <p><strong>Pending Grades:</strong> 2</p>
                            </div>
                        </div>
                    </div>
                </div>

    <?php endif; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>