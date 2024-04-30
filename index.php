<?php

const ERROR_REQUIRED = "Please enter a todo";
const ERROR_TOO_SHORT = "Todo must be at least 3 characters long";

$filename = __DIR__ . '/data/todos.json';
$error = "";
$todo = "";
$todos = [];

// Check if file exists
if (file_exists($filename)) {
    $data = file_get_contents($filename); // Get data from file
    $todos = json_decode($data, true) ?? []; // Decode data from json to array
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Sanitize input
    $todo = $_POST['todo'] ?? ''; // Get todo from input

    // Validate todo
    if (!$todo) {
        $error = ERROR_REQUIRED;
    } elseif (mb_strlen($todo) < 3) {
        $error = ERROR_TOO_SHORT;
    }

    // If no error, add todo to list
    if (!$error) {
        $todos = [
            ...$todos,
            [
                'id' => time(),
                'name' => $todo,
                'done' => false,
            ]
        ];
        file_put_contents($filename, json_encode($todos, JSON_PRETTY_PRINT)); // Save data to file
        header('Location: /'); // Redirect to home page
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php'; ?>
    <title>Todo</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php'; ?>
        <main class="content">
            <div class="todo-container">
                <h1>My Todo</h1>
                <form action="/" method="post" class="todo-form">
                    <input type="text" name="todo" value="<?= $todo ?>">
                    <button class="btn btn-primary">Add</button>
                </form>
                <?php if ($error): ?>
                <p class=" alert alert-danger">
                    <?= $error ?>
                </p>
                <?php endif; ?>
                <ul class="todo-list">
                    <?php foreach ($todos as $todo_listItem): ?>
                    <li class="todo-item <?= $todo_listItem['done'] ? 'done' : '' ?>">
                        <input type="checkbox" <?= $todo_listItem['done'] ? 'checked' : ''; ?>>
                        <span
                            class=" todo-list-item-name <?= $todo_listItem['done'] ? 'list-done' : '' ?>"><?= $todo_listItem['name'] ?></span>
                        <a href="/remove-todo.php?id=<?= $todo_listItem['id'] ?>">
                            <button class="btn btn-danger btn-small">Delete</button>
                        </a>
                        <a href="/edit-todo.php?id=<?= $todo_listItem['id'] ?>">
                            <button class="btn btn-success btn-small">
                                <?= $todo_listItem['done'] ? "Undone" : "Done" ?>
                            </button>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </main>
        <?php require_once 'includes/footer.php'; ?>
    </div>
</body>

</html>