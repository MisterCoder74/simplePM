<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Issue Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body, html {
height: 100vh;
margin: 0;
background: linear-gradient(135deg, #667eea 0%, #dc3545 100%);
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.main-header {
background: rgba(255, 255, 255, 0.95);
backdrop-filter: blur(10px);
border-radius: 15px;
margin-bottom: 20px;
padding: 20px;
box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
position: relative;
}

.issues-table {
margin-top: 20px;
}

.status-badge {
font-size: 0.9em;
font-weight: bold;
}
        
.user-info {
    transition: all 0.3s ease;
}

.avatar-circle {
    transition: transform 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 2px solid rgba(255,255,255,0.2);
}

.avatar-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
</style>
</head>
<body>
<div class="container mt-3">
<div class="main-header">
    <div id="user-info" class="position-absolute top-0 end-0 d-flex align-items-center me-3 mb-3" style="display: none;">
        <div class="me-3 text-end">
            <div class="fw-bold text-dark" id="user-name">Nome Utente</div>
            <div class="small">
                <span class="badge" id="user-level-badge">user</span>
            </div>
        </div>
        <div class="avatar-circle d-flex align-items-center justify-content-center" 
             style="width: 40px; height: 40px; background-color: #007bff; border-radius: 50%; color: white; font-weight: bold;" 
             id="user-avatar">
            U
        </div>
    </div>        
    <br>        
<div class="d-flex justify-content-between align-items-center">
<h1>Issue Tracker</h1>
<a href="dashboard.php" class="btn btn-outline-secondary">
<i class="fas fa-arrow-left me-2"></i>Back to Dashboard
</a>
</div>
</div>

<div class="card p-3 mb-4 bg-light">
<h4>Register a New Issue</h4>
<form id="issue-form" class="row g-2 align-items-end">
<div class="col-md-4">
<label for="task-select" class="form-label">Select Task</label>
<select id="task-select" class="form-select" required>
<option value="">Loading tasks...</option>
</select>
</div>
<div class="col-md-6">
<label for="issue-desc" class="form-label">Problem Description</label>
<textarea id="issue-desc" class="form-control" rows="2" placeholder="Describe the problem" required></textarea>
</div>
<div class="col-md-2 d-grid">
<button type="submit" class="btn btn-danger">Add Issue</button>
</div>
</form>
</div>

<h4>All Issues</h4>
<div class="table-responsive issues-table">
<table class="table table-striped table-bordered" id="issues-table">
<thead class="table-dark">
<tr>
<th scope="col">ID</th>        
<th scope="col">Task</th>
<th scope="col">Description</th>
<th scope="col">Status</th>
<th scope="col">Actions</th>
</tr>
</thead>
<tbody>
<!-- dinamico -->
</tbody>
</table>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="auth.js"></script>
<script>
let issues = [];
let tasksUser = [];

async function loadAllTasks() {
    const res = await fetchWithAuth({action:'getGantt'});
    if (res.success) {
        tasksUser = res.data;
        const select = document.getElementById('task-select');
        select.innerHTML = '<option value="">Select a task</option>';
        res.data.forEach((task, idx) => {
            const option = document.createElement('option');
            option.value = idx;
            option.textContent = `${task.title} (${task.start} - ${task.end})`;
            select.appendChild(option);
        });
    } else {
        document.getElementById('task-select').innerHTML = '<option value="">No tasks found</option>';
    }
}

async function loadIssues() {
    const res = await fetchWithAuth({action:'getIssues'});
    if(res.success){
        issues = res.data;
        renderIssues();
    }
}

function renderIssues() {
    const tbody = document.querySelector('#issues-table tbody');
    tbody.innerHTML = '';
    issues.forEach((issue, idx) => {
        const tr = document.createElement('tr');
        
        let statusBadge = '';
        let statusClass = '';
        if(issue.status === 'pending') {
            statusBadge = 'Pending'; statusClass = 'bg-warning text-dark';
        } else if(issue.status === 'solved') {
            statusBadge = 'Solved'; statusClass = 'bg-success text-white';
        } else if(issue.status === 'escalated') {
            statusBadge = 'Escalated'; statusClass = 'bg-danger text-white';
        }
        
        let actionsTd = '';
        if (isAdminOrManager()) {
            actionsTd += `<button class="btn btn-sm btn-danger me-2" onclick="updateIssueStatus(${idx}, 'escalated')">Escalate</button>`;
            actionsTd += `<button class="btn btn-sm btn-success me-2" onclick="updateIssueStatus(${idx}, 'solved')">Solved</button>`;
        }
        if (isAdmin()) {
            actionsTd += `<button class="btn btn-sm btn-outline-dark" onclick="deleteIssue(${idx})"><i class="fas fa-trash"></i></button>`;
        }

        tr.innerHTML = `
            <td>${issue.id}</td>
            <td>${issue.taskTitle || 'Unknown'}</td>
            <td>${issue.description}</td>
            <td><span class="badge ${statusClass} status-badge">${statusBadge}</span></td>
            <td>${actionsTd}</td>
        `;
        tbody.appendChild(tr);
    });
}

async function updateIssueStatus(idx, status) {
    const res = await fetchWithAuth({action:'updateIssueStatus', index: idx, status: status});
    if(res.success){
        loadIssues();
    } else alert(res.message);
}

async function deleteIssue(idx) {
    if (!confirm('Eliminare questo issue?')) return;
    const res = await fetchWithAuth({action:'deleteIssue', index: idx});
    if(res.success){
        loadIssues();
    } else alert(res.message);
}

document.getElementById('issue-form').addEventListener('submit', async e => {
    e.preventDefault();
    const taskIdx = document.getElementById('task-select').value;
    const desc = document.getElementById('issue-desc').value.trim();
    if(taskIdx === '' || desc === '') return alert('Select task and description');
    
    const task = tasksUser[taskIdx];
    const res = await fetchWithAuth({ action: 'addIssue', taskTitle: task.title, description: desc });
    if(res.success){
        loadIssues();
        e.target.reset();
    } else alert(res.message);
});

function updateDashboardUserInfo() {
    const currentUser = getCurrentUser();
    if (!currentUser) { window.location.href = 'index.html'; return; }
    const userInfoDiv = document.getElementById('user-info');
    userInfoDiv.style.display = 'flex';
    document.getElementById('user-name').textContent = currentUser.fullName || currentUser.username;
    const badge = document.getElementById('user-level-badge');
    badge.textContent = currentUser.level.toUpperCase();
    badge.className = 'badge ' + getLevelBadgeClass(currentUser.level);
    const avatar = document.getElementById('user-avatar');
    avatar.textContent = getInitials(currentUser.fullName || currentUser.username);
    avatar.style.backgroundColor = getAvatarColor(currentUser.level);
}

document.addEventListener('DOMContentLoaded', function() {
    updateDashboardUserInfo();
    loadAllTasks();
    loadIssues();
});
</script>
</body>
</html>
