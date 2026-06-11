<?php
session_start();


// Termina la sessione
session_destroy();

?>
<script>
localStorage.removeItem('currentUser');
window.location.href = 'index.html';
</script>