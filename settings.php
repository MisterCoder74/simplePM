<?php
// settings.php
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Impostazioni - SimplePM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .main-header {
      background: white;
      padding: 2rem;
      border-radius: 0 0 2rem 2rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
      margin-bottom: 2rem;
    }
    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .btn-save {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      color: white;
      padding: 0.8rem 2rem;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <div class="main-header mb-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h1 class="h3 mb-1"><i class="fas fa-cog me-2 text-primary"></i>Impostazioni Sistema</h1>
          <p class="text-muted mb-0">Gestisci i parametri dell'agenzia e dell'applicazione</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-2"></i>Dashboard
        </a>
      </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card">
          <div class="card-body p-4">
            <h5 class="card-title mb-4 border-bottom pb-2">Informazioni Agenzia</h5>
            <form id="settings-form">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nome Agenzia</label>
                  <input type="text" class="form-control" name="agencyname" id="agencyname">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Proprietario</label>
                  <input type="text" class="form-control" name="ownername" id="ownername">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Partita IVA / Licenza</label>
                  <input type="text" class="form-control" name="iva_licence" id="iva_licence">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email Contatto</label>
                  <input type="email" class="form-control" name="email" id="email">
                </div>
                <div class="col-12">
                  <label class="form-label">Indirizzo</label>
                  <input type="text" class="form-control" name="address" id="address">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Città</label>
                  <input type="text" class="form-control" name="city" id="city">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Stato/Provincia</label>
                  <input type="text" class="form-control" name="state" id="state">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Telefono</label>
                  <input type="text" class="form-control" name="telephone" id="telephone">
                </div>
                <div class="col-md-8">
                  <label class="form-label">Sito Web</label>
                  <input type="url" class="form-control" name="website" id="website">
                </div>
                <div class="col-md-6">
                  <label class="form-label"><i class="fab fa-instagram me-1"></i> Instagram</label>
                  <input type="text" class="form-control" name="instagram" id="instagram">
                </div>
                <div class="col-md-6">
                  <label class="form-label"><i class="fab fa-facebook me-1"></i> Facebook</label>
                  <input type="text" class="form-control" name="facebook" id="facebook">
                </div>
              </div>
              
              <div class="mt-4 text-end">
                <button type="submit" class="btn btn-save">
                  <i class="fas fa-save me-2"></i>Salva Impostazioni
                </button>
              </div>
            </form>
          </div>
        </div>
        
        <div class="card mt-4 bg-light border-0">
          <div class="card-body">
            <h6><i class="fas fa-info-circle me-2 text-info"></i>Nota</h6>
            <p class="small text-muted mb-0">Queste impostazioni vengono utilizzate per personalizzare i documenti generati dal sistema e le informazioni di contatto visualizzate agli utenti.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="auth.js"></script>
  <script>
    // Protezione pagina: solo Admin
    if (!isAdmin()) {
        window.location.href = 'dashboard.php';
    }

    async function loadSettings() {
        const res = await fetchWithAuth({action: 'getSetup'});
        if (res.success) {
            const data = res.data;
            for (const key in data) {
                const el = document.getElementById(key);
                if (el) el.value = data[key];
            }
        }
    }

    document.getElementById('settings-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const settings = {};
        formData.forEach((value, key) => {
            settings[key] = value;
        });

        const res = await fetchWithAuth({
            action: 'saveSetup',
            setup: settings
        });

        if (res.success) {
            alert('Impostazioni salvate con successo!');
        } else {
            alert('Errore: ' + (res.message || 'Errore sconosciuto'));
        }
    });

    document.addEventListener('DOMContentLoaded', loadSettings);
  </script>
</body>
</html>
