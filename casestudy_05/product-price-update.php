<?php
require __DIR__ . '/config.php';

$errors = [];
$messages = [];

// Fetch current products to drive both validation and display.
$stmt = $pdo->query('SELECT id, name, description, price_single, price_double FROM products ORDER BY id');
$products = $stmt->fetchAll();
$productsById = [];
foreach ($products as $product) {
    $productsById[(int) $product['id']] = $product;
}

// Handle updates when the form is submitted.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = isset($_POST['selected']) ? (array)$_POST['selected'] : [];

    if (empty($selected)) {
        $errors[] = 'Select at least one product to update.';
    } else {
        foreach ($selected as $id) {
            $id = (int) $id;
            $singleKey = "price_single_$id";
            $doubleKey = "price_double_$id";

            $singleInput = isset($_POST[$singleKey]) ? trim($_POST[$singleKey]) : '';
            $doubleInput = isset($_POST[$doubleKey]) ? trim($_POST[$doubleKey]) : '';

            if ($singleInput === '' && $doubleInput === '') {
                $errors[] = 'Provide at least one price to update for the selected product(s).';
                continue;
            }

            $fields = [];
            $params = [];

            if ($singleInput !== '') {
                if (!is_numeric($singleInput) || (float) $singleInput < 0) {
                    $errors[] = 'Single price must be a non-negative number.';
                } else {
                    $fields[] = 'price_single = ?';
                    $params[] = number_format((float) $singleInput, 2, '.', '');
                }
            }

            if ($doubleInput !== '') {
                if (!is_numeric($doubleInput) || (float) $doubleInput < 0) {
                    $errors[] = 'Double price must be a non-negative number.';
                } else {
                    $fields[] = 'price_double = ?';
                    $params[] = number_format((float) $doubleInput, 2, '.', '');
                }
            }

            if (!empty($fields) && empty($errors)) {
                $params[] = $id;
                $sql = 'UPDATE products SET ' . implode(', ', $fields) . ' WHERE id = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $productName = $productsById[$id]['name'] ?? ('Product #' . $id);
                $messages[] = 'Updated prices for ' . $productName . '.';
            }
        }
    }
}

// Retrieve the latest product data to display updated values.
if (!empty($messages)) {
    $stmt = $pdo->query('SELECT id, name, description, price_single, price_double FROM products ORDER BY id');
    $products = $stmt->fetchAll();
}

function currency($value)
{
    return number_format((float) $value, 2);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Price Update</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Verdana, Arial, sans-serif;
            background-color: #ffffcc;
            color: #330000;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #ccaa66;
            text-align: center;
        }

        #wrapper {
            box-shadow: 5px 5px 5px #3c1919;
            width: 80%;
            min-width: 800px;
            max-width: 1080px;
            margin: auto;
        }

        #main {
            display: flex;
        }

        #nav {
            padding-top: 20px;
            background-color: #e2d1b3;
            width: 20%;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            font-size: 20px;
        }

        #nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center; 
            width: 100%;
        }
        #nav li { width: 100%; }
        #nav a {
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: block;
            color: #523a25;
            font-weight: bold;
        }
        #nav a:hover {
            text-decoration: underline;
        }
        #nav a.active {
            color: #936743;
        }

        #right-column {
            flex: 1;
            padding: 0 20px;
            background-color: #f5f5dd;
        }

        #content {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        h1 {
            margin: 0;
        }

        h2 {
            padding: 0 10px;
            color: #4e2700;
        }

        form {
            background-color: #fff7e6;
            border: 1px solid #cca965;
            border-radius: 6px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background-color: #b8924c;
            color: #fff;
            padding: 12px;
            text-align: left;
        }

        tbody td {
            padding: 12px;
            border-bottom: 1px solid #d5b57a;
            vertical-align: top;
        }

        tbody tr:nth-child(odd) {
            background-color: #f0deb7;
        }

        tbody tr:nth-child(even) {
            background-color: #f8ead1;
        }

        .product-name {
            font-weight: bold;
        }

        .price-inputs {
            flex: 1;
            flex-direction: column;
            gap: 8px;
        }

        .price-inputs label {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .price-inputs input[type="text"] {
            width: 120px;
            padding: 6px 8px;
            border: 1px solid #936743;
            border-radius: 4px;
            background-color: #fff;
        }

        .actions {
            text-align: right;
            margin-top: 20px;
        }

        .actions button {
            background-color: #936743;
            color: #fff;
            border: none;
            padding: 10px 16px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .actions button:hover {
            filter: brightness(1.05);
        }

        .messages,
        .errors {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: 4px;
        }

        .messages {
            background-color: #dff0d8;
            border: 1px solid #3c763d;
            color: #3c763d;
        }

        .errors {
            background-color: #f2dede;
            border: 1px solid #a94442;
            color: #a94442;
        }

        footer {
            text-align: center;
            font-size: x-small;
            font-style: italic;
            padding: 20px 0;
            background-color: #cca965;
        }

        footer a {
            color: #330000;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <header>
            <h1>
                <img src="javalogo.gif" width="800" height="117" alt="JavaJam Coffee House logo">
            </h1>
        </header>

        <div id="main">
            <div id="nav">
                <nav>
                    <ul>
                        <li><a href="home.html">Home</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="music.html">Music</a></li>
                        <li><a href="jobs.html">Jobs</a></li>
                        <li><a href="product-price-update.php" class="active">Product Price Update</a></li>
                    </ul>
                </nav>
            </div>

            <div id="right-column">
                <h2>Product Price Update</h2>
                <div id="content">
                    <?php if (!empty($messages)): ?>
                        <div class="messages">
                            <ul>
                                <?php foreach ($messages as $msg): ?>
                                    <li><?= htmlspecialchars($msg) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="errors">
                            <ul>
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="">
                        <table>
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Current Prices (Single / Double)</th>
                                    <th>New Prices (Single / Double)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected[]" value="<?= (int) $product['id'] ?>">
                                        </td>
                                        <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['description']) ?></td>
                                        <td>$<?= currency($product['price_single']) ?> / $<?= currency($product['price_double']) ?></td>
                                        <td class="price-inputs">
                                            <label>
                                                Single: $
                                                <input type="text" name="price_single_<?= (int) $product['id'] ?>" placeholder="e.g., 2.50">
                                            </label>
                                            <label>
                                                Double: $
                                                <input type="text" name="price_double_<?= (int) $product['id'] ?>" placeholder="e.g., 3.50">
                                            </label>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="actions">
                            <button type="submit">Update Prices</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer>
            Copyright &copy; 2014 JavaJam Coffee House<br>
            <a href="mailto:yinghao@tang.com">yinghao@tang.com</a>
        </footer>
    </div>
</body>

</html>
