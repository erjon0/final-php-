<?php
include "includes/db.php";
$searchTerm = $_GET['q'] ?? '';
$stmt = $conn->prepare("SELECT * FROM homework WHERE title LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Search Results</h2><form method='GET'><input name='q' placeholder='Search' value='".htmlspecialchars($searchTerm)."'><input type='submit' value='Search'></form><br>";

while ($row = $result->fetch_assoc()) {
    echo "<div><strong>" . htmlspecialchars($row['title']) . "</strong><br>" .
         htmlspecialchars($row['description']) . "<br>" .
         "<a href='download.php?id=" . $row['id'] . "'>Download</a></div><br>";
}
?>