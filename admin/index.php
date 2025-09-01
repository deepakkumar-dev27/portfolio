<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

// Handle status updates
if ($_POST['action'] ?? '' === 'update_status') {
    $contact_id = (int)($_POST['contact_id'] ?? 0);
    $status = $_POST['status'] ?? 'unread';
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "UPDATE contacts SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $contact_id);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Get contacts with pagination
$page = (int)($_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Get total count
    $count_query = "SELECT COUNT(*) as total FROM contacts";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->execute();
    $total_contacts = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_contacts / $limit);
    
    // Get contacts
    $query = "SELECT * FROM contacts ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get stats
    $stats_query = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'unread' THEN 1 ELSE 0 END) as unread,
        SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as `read`,
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today
        FROM contacts";
    $stats_stmt = $conn->prepare($stats_query);
    $stats_stmt->execute();
    $stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 250px;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 15px 0;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        .main-content {
            padding: 20px;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 70px;
        }
        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.3);
            transform: scale(1.05);
        }
        .sidebar-toggle.collapsed {
            left: 25px;
        }
        .sidebar-toggle i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-unread {
            background-color: #ffeaa7;
            color: #d63031;
        }
        .status-read {
            background-color: #00b894;
            color: white;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 0.8rem;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="p-3">
            <h4><i class="fas fa-user-shield"></i> <span class="sidebar-text">Admin Panel</span></h4>
            <hr style="border-color: rgba(255,255,255,0.3);">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i> 
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contacts" title="Contact Messages">
                        <i class="fas fa-envelope"></i> 
                        <span class="sidebar-text">Contact Messages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" title="Logout">
                        <i class="fas fa-sign-out-alt"></i> 
                        <span class="sidebar-text">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Dashboard</h2>
                    <div>
                        <span class="text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm ms-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                            <h3><?php echo $stats['total'] ?? 0; ?></h3>
                            <p class="text-muted">Total Messages</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="fas fa-envelope-open fa-2x text-warning mb-2"></i>
                            <h3><?php echo $stats['unread'] ?? 0; ?></h3>
                            <p class="text-muted">Unread</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h3><?php echo $stats['read'] ?? 0; ?></h3>
                            <p class="text-muted">Read</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card text-center">
                            <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                            <h3><?php echo $stats['today'] ?? 0; ?></h3>
                            <p class="text-muted">Today</p>
                        </div>
                    </div>
                </div>

                <!-- Contacts Table -->
                <div class="table-container" id="contacts">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4><i class="fas fa-envelope"></i> Contact Messages</h4>
                        <button class="btn btn-primary btn-sm" onclick="location.reload()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php elseif (empty($contacts)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No messages yet</h5>
                            <p class="text-muted">Contact messages will appear here when submitted.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($contacts as $contact): ?>
                                        <tr>
                                            <td><?php echo $contact['id']; ?></td>
                                            <td><?php echo htmlspecialchars($contact['name']); ?></td>
                                            <td>
                                                <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>">
                                                    <?php echo htmlspecialchars($contact['email']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                            <td>
                                                <button class="btn btn-link btn-sm p-0" onclick="showMessage(<?php echo $contact['id']; ?>, '<?php echo htmlspecialchars(addslashes($contact['message'])); ?>')">
                                                    <?php echo substr(htmlspecialchars($contact['message']), 0, 50) . '...'; ?>
                                                </button>
                                            </td>
                                            <td><?php echo date('M j, Y H:i', strtotime($contact['created_at'])); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $contact['status']; ?>">
                                                    <?php echo ucfirst($contact['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($contact['status'] === 'unread'): ?>
                                                    <button class="btn btn-success btn-action" onclick="updateStatus(<?php echo $contact['id']; ?>, 'read')">
                                                        <i class="fas fa-check"></i> Mark Read
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-warning btn-action" onclick="updateStatus(<?php echo $contact['id']; ?>, 'unread')">
                                                        <i class="fas fa-undo"></i> Mark Unread
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <nav class="mt-3">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
        function showMessage(id, message) {
            Swal.fire({
                title: 'Message #' + id,
                text: message,
                icon: 'info',
                confirmButtonText: 'Close',
                confirmButtonColor: '#667eea'
            });
        }

        function updateStatus(contactId, status) {
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('contact_id', contactId);
            formData.append('status', status);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Status updated successfully',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to update status',
                        icon: 'error',
                        confirmButtonColor: '#667eea'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Network error occurred',
                    icon: 'error',
                    confirmButtonColor: '#667eea'
                });
            });
        }

        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                sidebarToggle.classList.toggle('collapsed');
                
                // Change toggle icon
                const icon = sidebarToggle.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon.className = 'fas fa-chevron-right';
                } else {
                    icon.className = 'fas fa-bars';
                }
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
            
            // Restore sidebar state from localStorage
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                sidebarToggle.classList.add('collapsed');
                sidebarToggle.querySelector('i').className = 'fas fa-chevron-right';
            }
        });
    </script>
</body>
</html>