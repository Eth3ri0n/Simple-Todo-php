<?php
$filename = __DIR__ . '/data/todos.json'; // Get the file path
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Filter the query string
$id = $_GET['id'] ?? ''; // Get the id from the query string

// Check if the id is set
if ($id) {
    $todos = json_decode(file_get_contents($filename), true); // Get the todos
    $todoIndex = array_search($id, array_column($todos, 'id')); // Get the index of the todo item
    array_splice($todos, $todoIndex, 1); // Remove the todo item
    file_put_contents($filename, json_encode($todos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); // Save the updated todos
}
header("Location: /"); // Redirect to the home page
?>