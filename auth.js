function getCurrentUser() {
    try {
        const userData = localStorage.getItem('currentUser');
        return userData ? JSON.parse(userData) : null;
    } catch (e) { 
        console.error('Error parsing currentUser from localStorage', e);
        return null; 
    }
}

function getLevelBadgeClass(level) {
    switch(level) {
        case 'admin': return 'bg-danger text-white';
        case 'manager': return 'bg-warning text-dark';
        default: return 'bg-secondary text-white';
    }
}

function getAvatarColor(level) {
    switch(level) {
        case 'admin': return '#dc3545';
        case 'manager': return '#ffc107';
        default: return '#007bff';
    }
}

function getInitials(fullName) {
    if (!fullName) return 'U';
    const names = fullName.trim().split(' ');
    if (names.length === 1) return names[0].charAt(0).toUpperCase();
    return (names[0].charAt(0) + names[names.length - 1].charAt(0)).toUpperCase();
}

function isAdmin() { 
    const u = getCurrentUser(); 
    return u && u.level === 'admin'; 
}

function isManager() { 
    const u = getCurrentUser(); 
    return u && u.level === 'manager'; 
}

function isAdminOrManager() { 
    const u = getCurrentUser(); 
    return u && (u.level === 'admin' || u.level === 'manager'); 
}

function isUser() { 
    const u = getCurrentUser(); 
    return u && u.level === 'user'; 
}

/**
 * Funzione helper per chiamate API con token
 */
async function fetchWithAuth(data) {
    const user = getCurrentUser();
    if (user && user.token) {
        data.token = user.token;
    }
    
    const response = await fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': user && user.token ? `Bearer ${user.token}` : ''
        },
        body: JSON.stringify(data)
    });
    
    const result = await response.json();
    
    if (!result.success && result.message === 'Authentication required') {
        // Token scaduto o non valido, logout
        localStorage.removeItem('currentUser');
        window.location.href = 'index.html';
    }
    
    return result;
}

async function logout() {
    try {
        await fetchWithAuth({action: 'logout'});
    } catch (e) {
        console.error('Error during API logout:', e);
    }
    localStorage.removeItem('currentUser');
    window.location.href = 'index.html';
}
