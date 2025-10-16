<?php
require __DIR__ . '/config.php';

$stmt = $pdo->query("SELECT id, name, description, price_single, price_double FROM products ORDER BY id");
$rows = $stmt->fetchAll();

$byName = [];
foreach ($rows as $r) { $byName[$r['name']] = $r; }

// Helper to safely get a product with defaults if missing
function p($byName, $name) {
  $d = ['description'=>'', 'price_single'=>0.00, 'price_double'=>0.00];
  return isset($byName[$name]) ? array_merge($d, $byName[$name]) : $d;
}

$java = p($byName, 'Just Java');
$cafe = p($byName, 'Cafe au Lait');
$capp = p($byName, 'Iced Cappuccino');

function money($n) { return number_format((float)$n, 2); }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
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

        #right-column {
            padding: 0 20px;
            background-color: #f5f5dd;
            flex: 1;
        }

        #content {
            display: flex;
            justify-content: center;
        }

        #winding-road {
            margin: 10px;
        }

        #details {
            display: flex;
            flex-direction: column;
        }

        h1 {
            margin: 0;
        }

        h2 {
            padding: 0 10px;
            color: #4e2700;
        }

        body {
            font-family: Verdana, Arial, sans-serif;
            background-color: #ffffcc;
            color: #330000;
        }

        footer {
            text-align: center;
            font-size: x-small;
            font-style: italic;
            padding: 20px 0;
            background-color: #cca965;
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
        footer a {
            color: #330000;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        thead th {
            background-color: #b8924c;
            color: #fff;
            padding: 10px 20px;
            text-align: left;
        }

        tbody tr:nth-child(odd) {
            background-color: #cca965;
        }

        tbody tr:nth-child(even) {
            background-color: #e9d7b6;
        }

        th,
        td {
            padding: 10px 20px;
        }

        td.content {
            text-align: left;
        }

        td.title {
            font-weight: bold;
            text-align: center;
            max-width: 125px;
        }

        .shot-options {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .shot-options label {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .qty-input,
        .subtotal-input,
        #Price_Total {
            width: 100%;
            box-sizing: border-box;
            padding: 6px;
            border: 1px solid #936743;
            background-color: #fff7e6;
            font-size: 16px;
            color: #330000;
        }

        .subtotal-input,
        #Price_Total {
            background-color: #f9edd4;
        }

        .total-label {
            text-align: right;
            font-weight: bold;
            padding-right: 20px;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <header>
            <h1><img src="javalogo.gif" width="800" height="117" alt="java jam logo"></h1>
        </header>

        <div id="main">
            <div id="nav">
                <nav>
                    <ul>
                        <li><a href="home.html">Home</a></li>
                        <li><a href="menu.php" class="active">Menu</a></li>
                        <li><a href="music.html">Music</a></li>
                        <li><a href="jobs.html">Jobs</a></li>
                        <li><a href="product-price-update.php">Product Price Update</a></li>
                    </ul>
                </nav>
            </div>

            <div id="right-column">
                <h2>Coffee at JavaJam</h2>
                <div id="content">
                    <div id="details">
                        <table class="menu-table">
                            <thead>
                                <tr>
                                    <th>Coffee</th>
                                    <th>Description</th>
                                    <th>Shot</th>
                                    <th>Quantity</th>
                                    <th>Subtotal ($)</th>
                                </tr>
                            </thead>
                            <tbody>

                                    <!-- Just Java -->
                                <tr data-item="justJava">
                                    <td class="title">Just Java</td>
                                    <td class="content">
                                        <?= htmlspecialchars($java['description']) ?>
                                    </td>
                                    <td class="shot-options">
                                        <label for="Qty_Java_Single">
                                            <input type="radio" id="Qty_Java_Single" name="Shot_Java"
                                                value="<?= money($java['price_single']) ?>" checked>
                                            Single $
                                            <?= money($java['price_single']) ?>
                                        </label>
                                        <label for="Qty_Java_Double">
                                            <input type="radio" id="Qty_Java_Double" name="Shot_Java"
                                                value="<?= money($java['price_double']) ?>">
                                            Double $
                                            <?= money($java['price_double']) ?>
                                        </label>
                                    </td>
                                    <td><input type="text" id="QtyJava" name="QtyJava" class="qty-input" value="0"></td>
                                    <td><input type="text" id="Price_Java" name="Price_Java" class="subtotal-input"
                                            value="0.00" readonly></td>
                                </tr>

                                    <!-- Cafe au Lait -->
                                <tr data-item="cafeAuLait">
                                    <td class="title">Cafe au Lait</td>
                                    <td class="content">
                                        <?= htmlspecialchars($cafe['description']) ?>
                                    </td>
                                    <td class="shot-options">
                                        <label for="Qty_Cafe_Single">
                                            <input type="radio" id="Qty_Cafe_Single" name="Shot_Cafe"
                                                value="<?= money($cafe['price_single']) ?>" checked>
                                            Single $
                                            <?= money($cafe['price_single']) ?>
                                        </label>
                                        <label for="Qty_Cafe_Double">
                                            <input type="radio" id="Qty_Cafe_Double" name="Shot_Cafe"
                                                value="<?= money($cafe['price_double']) ?>">
                                            Double $
                                            <?= money($cafe['price_double']) ?>
                                        </label>
                                    </td>
                                    <td><input type="text" id="QtyCafe" name="QtyCafe" class="qty-input" value="0"></td>
                                    <td><input type="text" id="Price_Cafe" name="Price_Cafe" class="subtotal-input"
                                            value="0.00" readonly></td>
                                </tr>

                                    <!-- Iced Cappuccino -->
                                <tr data-item="icedCappuccino">
                                    <td class="title">Iced Cappuccino</td>
                                    <td class="content">
                                        <?= htmlspecialchars($capp['description']) ?>
                                    </td>
                                    <td class="shot-options">
                                        <label for="Qty_Capp_Single">
                                            <input type="radio" id="Qty_Capp_Single" name="Shot_Capp"
                                                value="<?= money($capp['price_single']) ?>" checked>
                                            Single $
                                            <?= money($capp['price_single']) ?>
                                        </label>
                                        <label for="Qty_Capp_Double">
                                            <input type="radio" id="Qty_Capp_Double" name="Shot_Capp"
                                                value="<?= money($capp['price_double']) ?>">
                                            Double $
                                            <?= money($capp['price_double']) ?>
                                        </label>
                                    </td>
                                    <td><input type="text" id="QtyCapp" name="QtyCapp" class="qty-input" value="0"></td>
                                    <td><input type="text" id="Price_Capp" name="Price_Capp" class="subtotal-input"
                                            value="0.00" readonly></td>
                                </tr>

                                </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="total-label">Total</td>
                                    <td><input type="text" id="Price_Total" name="Price_Total" value="0.00" readonly>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            Copyright &copy; 2014 JavaJam Coffee House<br>
            <a href="mailto:yinghao@tang.com">yinghao@tang.com</a>
        </footer>
    </div>

    <script src="menuUpdate.js"></script>
</body>

</html>
