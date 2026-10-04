<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?></title>
    <style>
        body {
            display: grid;
            min-height: 100vh;
            margin: 0;
            place-items: center;
            background: #f4f7fb;
            color: #1f2937;
            font-family: Arial, sans-serif;
        }

        main {
            max-width: 600px;
            padding: 2rem;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 8px 24px rgb(0 0 0 / 10%);
            text-align: center;
        }

        h1 {
            color: #2563eb;
        }
    </style>
</head>
<body>
    <main>
        <h1><?= esc($title) ?></h1>
        <p><?= esc($message) ?></p>
    </main>
</body>
</html>
