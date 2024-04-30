<?php
$filename = __DIR__ . '/data/todos.json'; // Get the file path

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Filter the query string
$id = $_GET['id'] ?? ''; // Get the id from the query string

if ($id) {
    $data = file_get_contents($filename); // Get the file content
    $todos = json_decode($data, true) ?? []; // Decode the file content

    if (count($todos)) {
        $todoIndex = (int) array_search($id, array_column($todos, 'id')); // Get the index of the todo item
        $todos[$todoIndex]['done'] = !$todos[$todoIndex]['done'];  // Toggle the done status
        file_put_contents($filename, json_encode($todos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); // Save the updated todos
    }
}
header("Location: /"); // Redirect to the home page
?>