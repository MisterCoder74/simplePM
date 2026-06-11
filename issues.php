<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Issue Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let issues = [];
let tasksUser = [];
const userName = "CURRENT_USER"; // Sostituisci con metodo di autenticazione/utente reale

// API Helper
async function fetchJSON(data) {
const res = await fetch('api.php', {
method: 'POST',
headers: {'Content-Type': 'application/json'},
body: JSON.stringify(data)
});
return res.json();
}

//carica tutti i tasks        
async function loadAllTasks() {
const res = await fetchJSON({action:'getGantt'});
if (res.success) {
// Salva tutti i task
tasksUser = res.data; // puoi rinominarla anche semplicemente in tasks
// Popola la select list con tutti i task
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

// Carica tutte le issues
async function loadIssues() {
const res = await fetchJSON({action:'getIssues'});
if(res.success){
issues = res.data;
renderIssues();
}
}

// Rende la tabella delle issues
function renderIssues() {
const tbody = document.querySelector('#issues-table tbody');
tbody.innerHTML = '';
issues.forEach((issue, idx) => {
const tr = document.createElement('tr');
const taskId = issue.id;
const taskName = issue.taskTitle || 'Unknown';
const desc = issue.description;

// Stato e azioni
let statusBadge = '';
let statusClass = '';
if(issue.status === 'pending') {
statusBadge = 'Pending';
statusClass = 'bg-warning text-dark';
} else if(issue.status === 'solved') {
statusBadge = 'Solved';
statusClass = 'bg-success text-white';
} else if(issue.status === 'escalated') {
statusBadge = 'Escalated';
statusClass = 'bg-danger text-white';
}

const statusTd = `<span class="badge ${statusClass} status-badge">${statusBadge}</span>`;

const actionsTd = `
<button class="btn btn-sm btn-danger me-2" data-action="escalate" data-issue="${idx}">Escalate</button>
<button class="btn btn-sm btn-success" data-action="resolve" data-issue="${idx}">Solved</button>
`;

tr.innerHTML = `
<td>${taskId}</td>
<td>${taskName}</td>
<td>${desc}</td>
<td>${statusTd}</td>
<td>${actionsTd}</td>
`;
tbody.appendChild(tr);
});

// Setup pulsanti
document.querySelectorAll('button[data-action]').forEach(btn => {
btn.onclick = async () => {
const action = btn.dataset.action;
const issueIdx = parseInt(btn.dataset.issue);
let newStatus = '';
if(action === 'escalate') newStatus = 'escalated';
if(action === 'resolve') newStatus = 'solved';

const res = await fetchJSON({action:'updateIssueStatus', index: issueIdx, status: newStatus});
if(res.success){
issues = res.data;
renderIssues();
} else {
alert('Error updating issue');
}
}
});
}

// Invia nuovo issue
document.getElementById('issue-form').addEventListener('submit', async e => {
e.preventDefault();
const taskIdx = document.getElementById('task-select').value;
const desc = document.getElementById('issue-desc').value.trim();

if(taskIdx === '' || desc === '') {
alert('Please select a task and enter description');
return;
}

const task = tasksUser[taskIdx];
const res = await fetchJSON({
action: 'addIssue',
taskTitle: task.title,
description: desc
});
if(res.success){
issues = res.data;
renderIssues();
document.getElementById('issue-form').reset();
} else {
alert('Error adding issue');
}
});
        
        
// Funzione per aggiornare le informazioni utente nell'header
function updateUserInfoInHeader() {
    const currentUser = getCurrentUser();
    const userInfoDiv = document.getElementById('user-info');
    const userNameSpan = document.getElementById('user-name');
    const userLevelBadge = document.getElementById('user-level-badge');
    const userAvatar = document.getElementById('user-avatar');
    
    if (currentUser) {
        // Mostra la sezione info utente
        userInfoDiv.style.display = 'flex';
        
        // Imposta il nome
        userNameSpan.textContent = currentUser.fullName || currentUser.username;
        
        // Imposta il badge del livello con colori appropriati
        userLevelBadge.textContent = currentUser.level.toUpperCase();
        userLevelBadge.className = 'badge ' + getLevelBadgeClass(currentUser.level);
        
        // Imposta l'avatar con iniziali
        const initials = getInitials(currentUser.fullName || currentUser.username);
        userAvatar.textContent = initials;
        userAvatar.style.backgroundColor = getAvatarColor(currentUser.level);
        
    } else {
        // Nascondi la sezione se non c'è utente
        userInfoDiv.style.display = 'none';
    }
}

// Funzione per ottenere la classe CSS del badge in base al livello
function getLevelBadgeClass(level) {
    switch(level) {
        case 'admin':
            return 'bg-danger text-white'; // Rosso per admin
        case 'manager':
            return 'bg-warning text-dark'; // Giallo per manager
        case 'user':
        default:
            return 'bg-secondary text-white'; // Grigio per user
    }
}

// Funzione per ottenere il colore dell'avatar in base al livello
function getAvatarColor(level) {
    switch(level) {
        case 'admin':
            return '#dc3545'; // Rosso per admin
        case 'manager':
            return '#ffc107'; // Giallo per manager
        case 'user':
        default:
            return '#007bff'; // Blu per user
    }
}

// Funzione per estrarre le iniziali dal nome
function getInitials(fullName) {
    if (!fullName) return 'U';
    
    const names = fullName.trim().split(' ');
    if (names.length === 1) {
        return names[0].charAt(0).toUpperCase();
    }
    
    return (names[0].charAt(0) + names[names.length - 1].charAt(0)).toUpperCase();
}

// Funzione helper per ottenere l'utente corrente (se non già presente)
function getCurrentUser() {
    try {
        const userData = localStorage.getItem('currentUser');
        return userData ? JSON.parse(userData) : null;
    } catch (error) {
        console.error('Errore nel parsing dei dati utente:', error);
        return null;
    }
}

// Funzione per aggiornare le informazioni utente nell'header della dashboard
function updateDashboardUserInfo() {
    const currentUser = getCurrentUser();
    const userInfoDiv = document.getElementById('user-info');
    const mobileUserInfoDiv = document.getElementById('mobile-user-info');
    const userNameSpan = document.getElementById('user-name');
    const mobileUserNameSpan = document.getElementById('mobile-user-name');
    const userLevelBadge = document.getElementById('user-level-badge');
    const mobileUserLevelBadge = document.getElementById('mobile-user-level');
    const userAvatar = document.getElementById('user-avatar');
    
    if (currentUser) {
        // Mostra le sezioni info utente
        if (userInfoDiv) userInfoDiv.style.display = 'flex';
        if (mobileUserInfoDiv) mobileUserInfoDiv.style.display = 'block';
        
        // Imposta il nome (desktop e mobile)
        if (userNameSpan) userNameSpan.textContent = currentUser.fullName || currentUser.username;
        if (mobileUserNameSpan) mobileUserNameSpan.textContent = currentUser.fullName || currentUser.username;
        
        // Imposta il badge del livello con colori appropriati
        const levelText = currentUser.level.toUpperCase();
        const badgeClass = getLevelBadgeClass(currentUser.level);
        
        if (userLevelBadge) {
            userLevelBadge.textContent = levelText;
            userLevelBadge.className = 'badge ' + badgeClass;
        }
        
        if (mobileUserLevelBadge) {
            mobileUserLevelBadge.textContent = levelText;
            mobileUserLevelBadge.className = 'badge ' + badgeClass;
        }
        
        // Imposta l'avatar con iniziali (solo desktop)
        if (userAvatar) {
            const initials = getInitials(currentUser.fullName || currentUser.username);
            userAvatar.textContent = initials;
            userAvatar.style.backgroundColor = getAvatarColor(currentUser.level);
        }
        
    } else {
        // Nascondi le sezioni se non c'è utente
        if (userInfoDiv) userInfoDiv.style.display = 'none';
        if (mobileUserInfoDiv) mobileUserInfoDiv.style.display = 'none';
    }
}

// Aggiorna le informazioni utente al caricamento della pagina
document.addEventListener('DOMContentLoaded', function() {
    updateDashboardUserInfo();
    if (typeof checkUserPermissions === 'function') {
        checkUserPermissions(); // Se hai già questa funzione
    }
});

// Se hai già un event listener per il caricamento, aggiungi la chiamata lì
window.addEventListener('load', function() {
    updateDashboardUserInfo();
});                
                        

// Inizializza
loadAllTasks();
loadIssues();
</script>
</body>
</html>