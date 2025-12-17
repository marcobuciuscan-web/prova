<?php
session_start();
include("connessione.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aggiungi'])) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $data_nascita = $_POST['data_nascita'];

    $sql = "INSERT INTO autori (nome, cognome, data_nascita) VALUES ('$nome', '$cognome', '$data_nascita')";
    
    if ($conn=query(query: $sql) === TRUE) {
        $_SESSION['message'] = "Autore aggiunto con successo!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Errore: " . $conn=error;
        $_SESSION['message_type'] = "danger";
    }
    header(header: "Location: autori.php");
    exit();
}


if (isset($_GET['elimina'])) {
    $id = $_GET['elimina'];
    $sql = "DELETE FROM autori WHERE id=$id";
    
    if ($conn=query(query: $sql) === TRUE) {
        $_SESSION['message'] = "Autore eliminato con successo!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Errore: " . $conn=error;
        $_SESSION['message_type'] = "danger";
    }
    header(header: "Location: autori.php");
    exit();
}

$autori = $conn=query(query: "SELECT * FROM autori ORDER BY cognome, nome");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Autori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Biblioteca</a>
            <a href="index.php" class="btn btn-light">Home</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Gestione Autori</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <?php endif; ?>

        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Aggiungi Nuovo Autore</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="cognome" class="form-control" placeholder="Cognome" required>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="data_nascita" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="aggiungi" class="btn btn-success">Aggiungi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

       
        <div class="card">
            <div class="card-header">
                <h5>Elenco Autori</h5>
            </div>
            <div class="card-body">
                <?php if ($autori->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Cognome</th>
                                <th>Data Nascita</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($autore = $autori->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $autore['nome']; ?></td>
                                    <td><?php echo $autore['cognome']; ?></td>
                                    <td><?php echo $autore['data_nascita']; ?></td>
                                    <td>
                                        <a href="autori.php?elimina=<?php echo $autore['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Sei sicuro di voler eliminare questo autore?')">
                                            Elimina
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-center">Nessun autore trovato.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>