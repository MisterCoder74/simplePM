<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Sharing System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #6c757d 100%);
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

        .upload-zone {
            border: 2px dashed #007bff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-zone:hover, .upload-zone.dragover {
            border-color: #0056b3;
            background: rgba(255, 255, 255, 0.2);
        }

        .quota-bar {
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }

        .file-icon {
            font-size: 1.5em;
            margin-right: 10px;
        }

        .file-card {
            transition: transform 0.2s ease;
        }

        .file-card:hover {
            transform: translateY(-2px);
        }

        .category-tabs .nav-link {
            border-radius: 25px;
            margin: 0 5px;
        }

        .category-tabs .nav-link.active {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border-color: transparent;
        }

        .upload-progress {
            display: none;
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
        <!-- Header -->
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
                <h1><i class="fas fa-share-alt me-2"></i>File Sharing System</h1>
                <a href="dashboard.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Storage Quota -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Storage Usage</h5>
                    <span id="quota-text" class="text-muted">Loading...</span>
                </div>
                <div class="progress quota-bar">
                    <div id="quota-progress" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
                <small class="text-muted">Maximum 2GB storage available</small>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>Upload Files</h5>
                <div id="upload-zone" class="upload-zone">
                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                    <h5>Drop files here or click to browse</h5>
                    <p class="text-muted">Maximum file size: 50MB</p>
                    <p class="text-muted">Supported: Images (JPG, PNG, GIF, etc.), Documents (DOC, PDF, TXT, etc.)</p>
                </div>
                <input type="file" id="file-input" class="d-none" multiple accept=".jpg, .jpeg, .png, .gif, .doc, .docx, .pdf, .txt" onchange="validateFiles()">
                
                <!-- Upload Progress -->
                <div id="upload-progress" class="upload-progress mt-3">
                    <div class="progress">
                        <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                    <small id="upload-status" class="text-muted">Uploading...</small>
                </div>
            </div>
        </div>

        <!-- File Categories Tabs -->
        <ul class="nav nav-pills category-tabs justify-content-center mb-4 bg-light" id="category-tabs">
            <li class="nav-item">
                <button class="nav-link active" data-category="all">
                    <i class="fas fa-list me-2"></i>All Files
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-category="images">
                    <i class="fas fa-image me-2"></i>Images
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-category="docs">
                    <i class="fas fa-file-alt me-2"></i>Documents
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-category="pdfs">
                    <i class="fas fa-file-pdf me-2"></i>PDFs
                </button>
            </li>
        </ul>

        <!-- Files Grid -->
        <div id="files-container" class="row">
            <!-- Files will be loaded here -->
        </div>

        <!-- No Files Message -->
        <div id="no-files-message" class="text-center py-5" style="display: none;">
            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No files found</h4>
            <p class="text-muted">Upload your first file to get started!</p>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalTitle">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center" id="previewModalBody">
                    <!-- Preview content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="downloadBtn">
                        <i class="fas fa-download me-2"></i>Download
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let files = [];
        let currentCategory = 'all';
        let quotaInfo = {};
            
function validateFiles() {
const input = document.getElementById('file-input');
const acceptedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
const files = input.files;
let valid = true;

for (let i = 0; i < files.length; i++) {
const file = files[i];
if (!acceptedTypes.includes(file.type)) {
valid = false;
alert('Invalid file type: ' + file.name + '. Please select a valid file.');
break;
}
}

if (!valid) {
input.value = ''; // Clear the input if invalid file is selected
}
}
        // API Helper
        async function fetchJSON(data) {
            const res = await fetch('api.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            return res.json();
        }
            

        // Load quota information
        async function loadQuota() {
            const res = await fetchJSON({action: 'getQuota'});
            if (res.success) {
                quotaInfo = res.data;
                updateQuotaDisplay();
            }
        }

        // Update quota display
        function updateQuotaDisplay() {
            const usedGB = (quotaInfo.used / (1024 * 1024 * 1024)).toFixed(2);
            const totalGB = (quotaInfo.total / (1024 * 1024 * 1024)).toFixed(2);
            const percentage = (quotaInfo.used / quotaInfo.total * 100).toFixed(1);
            
            document.getElementById('quota-text').textContent = `${usedGB}GB / ${totalGB}GB (${percentage}%)`;
            
            const progressBar = document.getElementById('quota-progress');
            progressBar.style.width = percentage + '%';
            
            // Change color based on usage
            if (percentage > 95) {
                progressBar.className = 'progress-bar bg-danger';
            } else if (percentage > 80) {
                progressBar.className = 'progress-bar bg-warning';
            } else {
                progressBar.className = 'progress-bar bg-success';
            }

            // Disable upload if quota exceeded
            if (quotaInfo.used >= quotaInfo.limit) {
                document.getElementById('upload-zone').style.opacity = '0.5';
                document.getElementById('upload-zone').style.pointerEvents = 'none';
                document.querySelector('.upload-zone h5').textContent = 'Upload disabled - Storage quota exceeded';
            }
        }

        // Load files
        async function loadFiles() {
            const res = await fetchJSON({action: 'getFiles'});
            if (res.success) {
                res.data.sort((a, b) => new Date(b.uploaded_at) - new Date(a.uploaded_at));    
                files = res.data;
                renderFiles();
            }
        }

        // Render files based on current category
        function renderFiles() {
            const container = document.getElementById('files-container');
            const noFilesMsg = document.getElementById('no-files-message');
            
            let filteredFiles = files;
            if (currentCategory !== 'all') {
                filteredFiles = files.filter(file => file.category === currentCategory);
            }

            if (filteredFiles.length === 0) {
                container.innerHTML = '';
                noFilesMsg.style.display = 'block';
                return;
            }

            noFilesMsg.style.display = 'none';
            container.innerHTML = '';

            filteredFiles.forEach((file, index) => {
                const fileCard = createFileCard(file, index);
                container.appendChild(fileCard);
            });
        }

        // Create file card element
        function createFileCard(file, index) {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-lg-3 mb-4';

            const fileIcon = getFileIcon(file.type, file.category);
            const fileSize = formatFileSize(file.size);
            const uploadDate = new Date(file.uploaded_at).toLocaleDateString();

            col.innerHTML = `
                <div class="card file-card h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title" title="${file.original_name}">${truncateFileName(file.original_name)}</h6>
                        <p class="card-text">
                            <small class="text-muted">
                                Type: ${fileIcon} Size: ${fileSize}<br>
                                Uploaded: ${uploadDate}<br>
                            </small>
                        </p>
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-primary btn-sm" onclick="previewFile('${file.filename}', '${file.original_name}', '${file.category}')">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="downloadFile('${file.filename}', '${file.original_name}')">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                    </div>
                </div>
            `;

            return col;
        }

        // Get appropriate icon for file type
        function getFileIcon(type, category) {
            if (category === 'images') {
                return '<i class="fas fa-image"></i>';
            } else if (category === 'pdfs') {
                return '<i class="fas fa-file-pdf text-danger"></i>';
            } else {
                // Documents category
                const lowerType = type.toLowerCase();
                if (lowerType.includes('word') || lowerType.includes('doc')) {
                    return '<i class="fas fa-file-word text-primary"></i>';
                } else if (lowerType.includes('excel') || lowerType.includes('sheet')) {
                    return '<i class="fas fa-file-excel text-success"></i>';
                } else if (lowerType.includes('powerpoint') || lowerType.includes('presentation')) {
                    return '<i class="fas fa-file-powerpoint text-warning"></i>';
                } else if (lowerType.includes('text') || lowerType.includes('txt')) {
                    return '<i class="fas fa-file-alt text-secondary"></i>';
                } else {
                    return '<i class="fas fa-file text-muted"></i>';
                }
            }
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Truncate long file names
        function truncateFileName(name, maxLength = 25) {
            if (name.length <= maxLength) return name;
            const extension = name.split('.').pop();
            const nameWithoutExt = name.substring(0, name.lastIndexOf('.'));
            const truncated = nameWithoutExt.substring(0, maxLength - extension.length - 4) + '...';
            return truncated + '.' + extension;
        }

        // Preview file
        function previewFile(filename, originalName, category) {
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            document.getElementById('previewModalTitle').textContent = originalName;
            
            const modalBody = document.getElementById('previewModalBody');
            
            if (category === 'images') {
                modalBody.innerHTML = `<img src="shareddocs/images/${filename}" class="img-fluid" alt="${originalName}">`;
            } else {
                modalBody.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-file fa-4x text-muted mb-3"></i>
                        <h5>${originalName}</h5>
                        <p class="text-muted">Preview not available for this file type.</p>
                        <p class="text-muted">Click download to view the file.</p>
                    </div>
                `;
            }
            
            // Set download button
            document.getElementById('downloadBtn').onclick = () => downloadFile(filename, originalName);
            
            modal.show();
        }

        // Download file
        function downloadFile(filename, originalName) {
            const link = document.createElement('a');
            link.href = `api.php?action=downloadFile&filename=${encodeURIComponent(filename)}`;
            link.download = originalName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Handle category tab clicks
        document.querySelectorAll('#category-tabs button').forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active tab
                document.querySelectorAll('#category-tabs button').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Update current category and render
                currentCategory = btn.dataset.category;
                renderFiles();
            });
        });

        // Handle file upload
        const uploadZone = document.getElementById('upload-zone');
        const fileInput = document.getElementById('file-input');

        uploadZone.addEventListener('click', () => fileInput.click());

        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });

        uploadZone.addEventListener('dragleave', () => {
            uploadZone.classList.remove('dragover');
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        // Handle file uploads
        async function handleFiles(fileList) {
            if (quotaInfo.used >= quotaInfo.limit) {
                alert('Upload disabled: Storage quota exceeded');
                return;
            }

            const files = Array.from(fileList);
            const progressContainer = document.getElementById('upload-progress');
            const progressBar = document.getElementById('upload-progress-bar');
            const statusText = document.getElementById('upload-status');

            let uploadedCount = 0;
            let totalFiles = files.length;

            progressContainer.style.display = 'block';

            for (let file of files) {
                // Check file size
                if (file.size > 50 * 1024 * 1024) { // 50MB
                    alert(`File "${file.name}" is too large. Maximum size is 50MB.`);
                    continue;
                }

                const formData = new FormData();
                formData.append('file', file);
                formData.append('action', 'uploadFile');
                 

                try {
                    const response = await fetch('api.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    
                    if (!result.success) {
                        alert(`Error uploading "${file.name}": ${result.message}`);
                    }
                } catch (error) {
                    alert(`Error uploading "${file.name}": ${error.message}`);
                }

                uploadedCount++;
                const progress = (uploadedCount / totalFiles) * 100;
                progressBar.style.width = progress + '%';
                statusText.textContent = `Uploaded ${uploadedCount} of ${totalFiles} files`;
            }

            // Hide progress and reload data
            setTimeout(() => {
                progressContainer.style.display = 'none';
                progressBar.style.width = '0%';
                fileInput.value = '';
                loadFiles();
                loadQuota();
            }, 1000);
        }
            
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
           
            

        // Initialize
        loadFiles();
        loadQuota();
    </script>
</body>
</html>