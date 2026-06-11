<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sticky Notes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    } 
.text-content {
margin: 0;
}
.notes-list {
border: 1px solid #ddd;
border-radius: 6px;
padding: 15px;
height: 60vh;
overflow-y: auto;

/* Nuovo: layout flex orizzontale con wrap */
display: flex;
flex-wrap: wrap;
gap: 10px; /* spazio tra le note */
}

.sticky-note {
background: #198754; /* colore chiaro per le note (opzionale, puoi cambiarlo) */
padding: 8px; /* ridotto rispetto prima */
border-radius: 4px;
white-space: pre-wrap;
position: relative;
border: 1px solid #000;
font-size: 1.1rem;
/* Nuova: dimensioni più piccole e più compatte */
width: 24rem;
height: auto;
min-height: 10rem;
max-height: 16rem; /* limita l’altezza superiore */
overflow-y: auto;
display: flex;
flex-direction: column;
}

/* Opzionale: testo note che occupa tutto lo spazio disponibile */
.sticky-note textarea {
flex: 1;
resize: none;
border: none;
background: transparent;
width: 100%;
font-family: inherit;
font-size: 1.2em;
}

.btn-task {
position: absolute;
top: 5px;
right: 5px;
display: flex;
gap: 6px;
z-index: 10;
}
  //stili per badge utente      
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
<div>
<h1 class="mb-2">
<i class="fas fa-columns text-primary me-3"></i>
Sticky Notes
</h1>
<p class="text-muted mb-0">Prendi appunti veloci mentre lavori</p>
</div>
<a href="dashboard.php" class="btn btn-outline-secondary">
<i class="fas fa-arrow-left me-2"></i>
Back to Dashboard
</a>
</div>
</div>
<div class="notes-list" id="notes-list"></div>
<hr/>
<h4 class="mt-4 mb-3">Add New Note</h4>
<div class="card shadow-sm border-success bg-opacity-25 mb-4" style="background-color: #d1f1d8;">
<div class="card-body p-3">
<form id="notes-form" class="row g-2 align-items-end" aria-label="Modulo aggiunta nota">
<div class="col-md-8">
<label for="note-text" class="form-label mb-1">Note Content</label>
<textarea id="note-text" rows="2" class="form-control" placeholder="Note content" required></textarea>
</div>
<input type="hidden" id="user-team" />
<div class="col-md-1 d-grid">
<button type="submit" class="btn btn-success">Add</button>
</div>
</form>
</div>
</div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="notesEditModal" tabindex="-1" aria-labelledby="notesEditModalLabel" aria-hidden="true">
<div class="modal-dialog">
<form class="modal-content" id="notes-edit-form">
<div class="modal-header">
<h5 class="modal-title" id="notesEditModalLabel">Edit Sticky Note</h5>
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
<script>
let notes = [];
const notesEditModal = new bootstrap.Modal(document.getElementById('notesEditModal'));

async function fetchJSON(data){
const res = await fetch('api.php', {
method: 'POST',
headers: {'Content-Type': 'application/json'},
body: JSON.stringify(data)
});
return res.json();
}

// Ottieni l'utente corrente
const currentUser = getCurrentUser();
if (!currentUser) {
alert('Utente corrente non trovato.');
}

async function loadData(){
const notesRes = await fetchJSON({action:'getNotes'});
if (notesRes.success){
notes = notesRes.data;
renderNotes();
}
}

function escapeHtml(text) {
let map = {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'};
return text.replace(/[&<>"']/g, m => map[m]);
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

function renderNotes(){
const container = document.getElementById('notes-list');
container.innerHTML = '';

notes.forEach((n,i)=>{
const div = document.createElement('div');
div.className = 'sticky-note';
div.innerHTML = `
<div class="text-content">
${escapeHtml(n.text).replace(/\n/g,'<br>')}
</div>
<small>Team: ${escapeHtml(n.team)}</small>
<div class="btn-task">
<button class="btn btn-sm btn-warning btn-notes-edit" data-index="${i}" title="Edit">&#9998;</button>
<button class="btn btn-sm btn-danger btn-notes-delete" data-index="${i}" title="Delete">&times;</button>
</div>
`;
container.appendChild(div);
});

document.querySelectorAll('.btn-notes-edit').forEach(btn=>{
btn.onclick = e => {
openNotesEditModal(e.target.dataset.index);
}
});
document.querySelectorAll('.btn-notes-delete').forEach(btn=>{
btn.onclick = async e => {
if(!confirm('Delete this note?')) return;
const idx = parseInt(e.target.dataset.index);
const res = await fetchJSON({action:'deleteNote', index: idx});
if(res.success){
notes.splice(idx,1);
renderNotes();
} else alert(res.message || "Error deleting note");
}
});
}

function openNotesEditModal(index){
const note = notes[index];
if(!note) return;
document.getElementById('notes-edit-index').value = index;
document.getElementById('notes-edit-text').value = note.text; // Preservati per eventuali modifiche
notesEditModal.show();
}

document.getElementById('notes-edit-form').addEventListener('submit', async e => {
e.preventDefault();
const index = parseInt(document.getElementById('notes-edit-index').value);
const text = document.getElementById('notes-edit-text').value.trim();
const team = currentUser.username || 'Unknown'; 
if(!text) return alert('All fields required');

const res = await fetchJSON({action:'editNote', index, text, team});
if(res.success){
notes = res.data;
renderNotes();
notesEditModal.hide();
} else alert(res.message || 'Error editing note');
});

document.getElementById('notes-form').addEventListener('submit', async e => {
e.preventDefault();
const text = document.getElementById('note-text').value.trim();
const team = currentUser.username || 'Unknown'; // Usa il team dell'utente corrente
if(!text) return alert('All fields required');

const res = await fetchJSON({action:'addNote', text, team});
        console.log(text, team);
if(res.success){
notes = res.data;
renderNotes();
e.target.reset();
} else alert(res.message || 'Error adding note');
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
            return 'bg-primary text-white'; // Blu per user
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
                
        

loadData();
</script>
</body>
</html>
