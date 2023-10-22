<?php
include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pokédex</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css"> <!-- Mogelijke CSS bestand -->
</head>
<body>
<h1>Welcome to the Pokédex!</h1>

<?php
$sql = "SELECT id, pokemon_name FROM pokedex"; // eenvoudige SQL om alle Pokémon te halen
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["pokemon_name"]. "<br>";
    }
} else {
    echo "0 Pokémon in the Pokédex";
}
$conn->close();
?>

</body>
</html>
