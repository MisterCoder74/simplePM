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
            position: relative;
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

        <!-- Upload Section (Conditional) -->
        <div class="card mb-4" id="upload-section" style="display: none;">
            <div class="card-body">
                <h5>Upload Files</h5>
                <div id="upload-zone" class="upload-zone">
                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                    <h5>Drop files here or click to browse</h5>
                    <p class="text-muted">Maximum file size: 50MB</p>
                    <p class="text-muted">Supported: Images, Documents, PDFs, TXT</p>
                </div>
                <input type="file" id="file-input" class="d-none" multiple accept=".jpg, .jpeg, .png, .gif, .doc, .docx, .pdf, .txt">
                
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
        <div id="files-container" class="row"></div>

        <!-- No Files Message -->
        <div id="no-files-message" class="text-center py-5" style="display: none;">
            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No files found</h4>
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
                <div class="modal-body text-center" id="previewModalBody"></div>
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
    <script src="auth.js"></script>
    <script>
        let files = [];
        let currentCategory = 'all';
        let quotaInfo = {};

        document.addEventListener('DOMContentLoaded', function() {
            updateDashboardUserInfo();
            if (isAdminOrManager()) {
                document.getElementById('upload-section').style.display = 'block';
            }
            loadQuota();
            loadFiles();
        });

        async function loadQuota() {
            const res = await fetchWithAuth({action: 'getQuota'});
            if (res.success) {
                quotaInfo = res.data;
                updateQuotaDisplay();
            }
        }

        function updateQuotaDisplay() {
            const usedGB = (quotaInfo.used / (1024 * 1024 * 1024)).toFixed(2);
            const totalGB = (quotaInfo.total / (1024 * 1024 * 1024)).toFixed(2);
            const percentage = (quotaInfo.used / quotaInfo.total * 100).toFixed(1);
            document.getElementById('quota-text').textContent = `${usedGB}GB / ${totalGB}GB (${percentage}%)`;
            const progressBar = document.getElementById('quota-progress');
            progressBar.style.width = percentage + '%';
            if (percentage > 95) progressBar.className = 'progress-bar bg-danger';
            else if (percentage > 80) progressBar.className = 'progress-bar bg-warning';
            else progressBar.className = 'progress-bar bg-success';

            if (quotaInfo.used >= quotaInfo.limit && isAdminOrManager()) {
                document.getElementById('upload-zone').style.opacity = '0.5';
                document.getElementById('upload-zone').style.pointerEvents = 'none';
                document.querySelector('.upload-zone h5').textContent = 'Upload disabled - Storage quota exceeded';
            }
        }

        async function loadFiles() {
            const res = await fetchWithAuth({action: 'getFiles'});
            if (res.success) {
                res.data.sort((a, b) => new Date(b.uploaded_at) - new Date(a.uploaded_at));    
                files = res.data;
                renderFiles();
            }
        }

        function renderFiles() {
            const container = document.getElementById('files-container');
            const noFilesMsg = document.getElementById('no-files-message');
            let filteredFiles = currentCategory === 'all' ? files : files.filter(file => file.category === currentCategory);

            if (filteredFiles.length === 0) {
                container.innerHTML = '';
                noFilesMsg.style.display = 'block';
                return;
            }

            noFilesMsg.style.display = 'none';
            container.innerHTML = '';
            filteredFiles.forEach((file) => {
                container.appendChild(createFileCard(file));
            });
        }

        function createFileCard(file) {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-lg-3 mb-4';
            const fileIcon = getFileIcon(file.type, file.category);
            const fileSize = formatFileSize(file.size);
            const uploadDate = new Date(file.uploaded_at).toLocaleDateString();

            let deleteBtn = '';
            if (isAdmin()) {
                deleteBtn = `<button class="btn btn-outline-danger btn-sm" onclick="deleteFile('${file.filename}')">
                                <i class="fas fa-trash"></i>
                             </button>`;
            }

            col.innerHTML = `
                <div class="card file-card h-100">
                    <div class="card-body text-center">
                        <h6 class="card-title" title="${file.original_name}">${truncateFileName(file.original_name)}</h6>
                        <p class="card-text"><small class="text-muted">Type: ${fileIcon} Size: ${fileSize}<br>Uploaded: ${uploadDate}</small></p>
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-primary btn-sm" onclick="previewFile('${file.filename}', '${file.original_name}', '${file.category}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="downloadFile('${file.filename}', '${file.original_name}')">
                                <i class="fas fa-download"></i>
                            </button>
                            ${deleteBtn}
                        </div>
                    </div>
                </div>
            `;
            return col;
        }

        function getFileIcon(type, category) {
            if (category === 'images') return '<i class="fas fa-image"></i>';
            if (category === 'pdfs') return '<i class="fas fa-file-pdf text-danger"></i>';
            const lowerType = type.toLowerCase();
            if (lowerType.includes('word') || lowerType.includes('doc')) return '<i class="fas fa-file-word text-primary"></i>';
            if (lowerType.includes('excel') || lowerType.includes('sheet')) return '<i class="fas fa-file-excel text-success"></i>';
            if (lowerType.includes('powerpoint') || lowerType.includes('presentation')) return '<i class="fas fa-file-powerpoint text-warning"></i>';
            if (lowerType.includes('text') || lowerType.includes('txt')) return '<i class="fas fa-file-alt text-secondary"></i>';
            return '<i class="fas fa-file text-muted"></i>';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function truncateFileName(name, maxLength = 20) {
            if (name.length <= maxLength) return name;
            const extension = name.split('.').pop();
            const nameWithoutExt = name.substring(0, name.lastIndexOf('.'));
            return nameWithoutExt.substring(0, maxLength - extension.length - 4) + '...' + '.' + extension;
        }

        function previewFile(filename, originalName, category) {
            document.getElementById('previewModalTitle').textContent = originalName;
            const modalBody = document.getElementById('previewModalBody');
            if (category === 'images') {
                modalBody.innerHTML = `<img src="shareddocs/images/${filename}" class="img-fluid" alt="${originalName}">`;
            } else {
                modalBody.innerHTML = `<div class="text-center py-4"><i class="fas fa-file fa-4x text-muted mb-3"></i><h5>${originalName}</h5><p>Preview not available.</p></div>`;
            }
            document.getElementById('downloadBtn').onclick = () => downloadFile(filename, originalName);
            new bootstrap.Modal(document.getElementById('previewModal')).show();
        }

        function downloadFile(filename, originalName) {
            const user = getCurrentUser();
            window.location.href = `api.php?action=downloadFile&filename=${encodeURIComponent(filename)}&token=${user.token}`;
        }

        async function deleteFile(filename) {
            if (!confirm('Sicuro di voler eliminare questo file?')) return;
            const res = await fetchWithAuth({action: 'deleteFile', filename: filename});
            if (res.success) {
                loadFiles();
                loadQuota();
            } else alert(res.message);
        }

        document.querySelectorAll('#category-tabs button').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#category-tabs button').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentCategory = btn.dataset.category;
                renderFiles();
            });
        });

        const uploadZone = document.getElementById('upload-zone');
        const fileInput = document.getElementById('file-input');
        if (uploadZone) {
            uploadZone.addEventListener('click', () => fileInput.click());
            uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
            uploadZone.addEventListener('dragleave', () => { uploadZone.classList.remove('dragover'); });
            uploadZone.addEventListener('drop', (e) => { e.preventDefault(); uploadZone.classList.remove('dragover'); handleFiles(e.dataTransfer.files); });
            fileInput.addEventListener('change', (e) => { handleFiles(e.target.files); });
        }

        async function handleFiles(fileList) {
            if (quotaInfo.used >= quotaInfo.limit) { alert('Storage quota exceeded'); return; }
            const filesToUpload = Array.from(fileList);
            const progressContainer = document.getElementById('upload-progress');
            const progressBar = document.getElementById('upload-progress-bar');
            const statusText = document.getElementById('upload-status');
            const user = getCurrentUser();

            progressContainer.style.display = 'block';
            let uploadedCount = 0;
            for (let file of filesToUpload) {
                if (file.size > 50 * 1024 * 1024) { alert(`File "${file.name}" too large.`); continue; }
                const formData = new FormData();
                formData.append('file', file);
                formData.append('action', 'uploadFile');
                formData.append('token', user.token);

                try {
                    const response = await fetch('api.php', { method: 'POST', body: formData });
                    const result = await response.json();
                    if (!result.success) alert(`Error uploading "${file.name}": ${result.message}`);
                } catch (error) { alert(`Error uploading "${file.name}"`); }
                uploadedCount++;
                progressBar.style.width = (uploadedCount / filesToUpload.length * 100) + '%';
                statusText.textContent = `Uploaded ${uploadedCount} of ${filesToUpload.length}`;
            }
            setTimeout(() => { progressContainer.style.display = 'none'; loadFiles(); loadQuota(); }, 1000);
        }

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
    </script>
</body>
</html>
