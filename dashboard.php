<?php
include 'config.php';
session_start();
if(!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT pokemons.name AS pokemon_name, pokemons.image AS image, pokemons.type AS type, pokemons.description AS description FROM pokemon_collection JOIN pokemons ON pokemon_collection.pokemon_id = pokemons.id WHERE pokemon_collection.user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$pokemons = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Menu -->
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="account.php">Account</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Welcome to Your Pokedex</h1>

        <div class="pokedex">
            <?php if(empty($pokemons)): ?>
                <p>You don't have any Pokémon in your collection yet.</p>
            <?php else: ?>
                <?php foreach($pokemons as $pokemon): ?>
                    <div class="pokemon" data-name="<?php echo htmlspecialchars($pokemon['pokemon_name']); ?>" data-image="<?php echo htmlspecialchars($pokemon['image']); ?>" data-type="<?php echo htmlspecialchars($pokemon['type']); ?>" data-description="<?php echo htmlspecialchars($pokemon['description']); ?>">
                        <img src="<?php echo htmlspecialchars($pokemon['image']); ?>" alt="<?php echo htmlspecialchars($pokemon['pokemon_name']); ?>">
                        <div><?php echo htmlspecialchars($pokemon['pokemon_name']); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Modal Structure -->
        <div id="pokemonModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle"></h2>
                <img id="modalImage" src="" alt="Pokemon Image">
                <p id="modalType"></p>
                <p id="modalDescription"></p>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal Functionality -->
    <script>
        var modal = document.getElementById("pokemonModal");
        var pokemons = document.querySelectorAll(".pokemon");
        var span = document.getElementsByClassName("close")[0];

        pokemons.forEach(pokemon => {
            pokemon.onclick = function() {
                document.getElementById("modalTitle").innerText = this.getAttribute("data-name");
                document.getElementById("modalImage").src = this.getAttribute("data-image");
                document.getElementById("modalType").innerText = "Type: " + this.getAttribute("data-type");
                document.getElementById("modalDescription").innerText = this.getAttribute("data-description");
                modal.style.display = "block";
            }
        });

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
