<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sticky Notes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
body, html {
height: 100vh;
margin: 0;
background: linear-gradient(135deg, #667eea 0%, #198754 100%);
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
.notes-list {
border: 1px solid #ddd;
border-radius: 6px;
padding: 15px;
height: 60vh;
overflow-y: auto;
display: flex;
flex-wrap: wrap;
gap: 10px;
}
.sticky-note {
background: #ffffff;
padding: 15px;
border-radius: 8px;
white-space: pre-wrap;
position: relative;
border: 1px solid #ddd;
font-size: 1rem;
width: 18rem;
height: auto;
min-height: 10rem;
display: flex;
flex-direction: column;
box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.btn-task {
position: absolute;
top: 5px;
right: 5px;
display: flex;
gap: 6px;
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
            <div class="small"><span class="badge" id="user-level-badge">user</span></div>
        </div>
        <div class="avatar-circle d-flex align-items-center justify-content-center" 
             style="width: 40px; height: 40px; background-color: #007bff; border-radius: 50%; color: white; font-weight: bold;" 
             id="user-avatar">
            U
        </div>
    </div>        
    <br>
<div class="d-flex justify-content-between align-items-center">
<div>
<h1 class="mb-2"><i class="fas fa-sticky-note text-success me-3"></i>Sticky Notes</h1>
<p class="text-muted mb-0">Prendi appunti veloci mentre lavori</p>
</div>
<a href="dashboard.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
</div>
</div>

<div class="notes-list" id="notes-list"></div>
<hr/>
<h4 class="mt-4 mb-3">Add New Note</h4>
<div class="card shadow-sm border-success bg-opacity-25 mb-4" style="background-color: #d1f1d8;">
<div class="card-body p-3">
<form id="notes-form" class="row g-2 align-items-end">
<div class="col-md-10">
<label for="note-text" class="form-label mb-1">Note Content</label>
<textarea id="note-text" rows="2" class="form-control" placeholder="Note content" required></textarea>
</div>
<div class="col-md-2 d-grid">
<button type="submit" class="btn btn-success">Add</button>
</div>
</form>
</div>
</div>
</div>

<div class="modal fade" id="notesEditModal" tabindex="-1">
<div class="modal-dialog">
<form class="modal-content" id="notes-edit-form">
<div class="modal-header">
<h5 class="modal-title">Edit Sticky Note</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input type="hidden" id="notes-edit-index" />
<div class="mb-3">
<label for="notes-edit-text" class="form-label">Note Content</label>
<textarea id="notes-edit-text" rows="3" class="form-control" required></textarea>
</div>
</div>
<div class="modal-footer">
<button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" type="button">Cancel</button>
<button class="btn btn-primary btn-sm" type="submit">Save</button>
</div>
</form>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="auth.js"></script>
<script>
let notes = [];
const notesEditModal = new bootstrap.Modal(document.getElementById('notesEditModal'));

async function loadData(){
    const res = await fetchWithAuth({action:'getNotes'});
    if (res.success){
        notes = res.data;
        renderNotes();
    }
}

function escapeHtml(text) {
    let map = {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'};
    return text.replace(/[&<>"']/g, m => map[m]);
}

function renderNotes(){
    const container = document.getElementById('notes-list');
    container.innerHTML = '';
    const currentUser = getCurrentUser();
    
    notes.forEach((n,i)=>{
        const div = document.createElement('div');
        div.className = 'sticky-note';
        
        let actionButtons = '';
        if (isAdminOrManager() || (currentUser && n.team === currentUser.username)) {
            actionButtons = `
                <div class="btn-task">
                    <button class="btn btn-sm btn-outline-primary" onclick="openNotesEditModal(${i})" title="Edit">&#9998;</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteNote(${i})" title="Delete">&times;</button>
                </div>
            `;
        }

        div.innerHTML = `
            <div class="text-content mb-2">${escapeHtml(n.text).replace(/\n/g,'<br>')}</div>
            <div class="mt-auto"><small class="text-muted">Author: ${escapeHtml(n.team)}</small></div>
            ${actionButtons}
        `;
        container.appendChild(div);
    });
}

function openNotesEditModal(index){
    const note = notes[index];
    if(!note) return;
    document.getElementById('notes-edit-index').value = index;
    document.getElementById('notes-edit-text').value = note.text;
    notesEditModal.show();
}

async function deleteNote(idx) {
    if(!confirm('Delete this note?')) return;
    const res = await fetchWithAuth({action:'deleteNote', index: idx});
    if(res.success) loadData();
    else alert(res.message);
}

document.getElementById('notes-edit-form').addEventListener('submit', async e => {
    e.preventDefault();
    const index = parseInt(document.getElementById('notes-edit-index').value);
    const text = document.getElementById('notes-edit-text').value.trim();
    const res = await fetchWithAuth({action:'editNote', index, text});
    if(res.success){
        loadData();
        notesEditModal.hide();
    } else alert(res.message);
});

document.getElementById('notes-form').addEventListener('submit', async e => {
    e.preventDefault();
    const text = document.getElementById('note-text').value.trim();
    const res = await fetchWithAuth({action:'addNote', text});
    if(res.success){
        loadData();
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

document.addEventListener('DOMContentLoaded', () => {
    updateDashboardUserInfo();
    loadData();
});
</script>
</body>
</html>
