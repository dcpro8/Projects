<?php
require '../backend/db_connection.php'; // Include your database connection

// Check if the ID is provided and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Query to fetch product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($product['product_name']); ?></title>
            <link rel="stylesheet" href="css/style.css">

            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #1a1a1a;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }

                .main-content {
                    padding: 20px;
                    font-family: 'Arial', sans-serif;
                }

                .container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                }

                .navbar {
                    display: flex;
                    background-color: #1a1a1a;
                    padding: 10px;
                    justify-content: center;
                    align-items: center;
                    gap: 20px;
                }

                .navbar div {
                    display: flex;
                    gap: 15px;
                }

                .navbar a {
                    color: #f4e04d;
                    text-decoration: none;
                    font-weight: bold;
                    padding: 8px 12px;
                    font-size: 1rem;
                    transition: all 0.2s ease-in;
                }

                .navbar a:hover {
                    text-decoration: underline;
                    transform: scale(1.1);
                    color: white;
                }

                .section-title {
                    color: white !important;
                    letter-spacing: 2.3px;
                }

                .product-header {
                    background-color:rgb(255, 236, 70);
                    padding: 15px;
                    border-radius: 8px;
                    text-align: center;
                    font-size: 28px;
                    font-weight: bold;
                }

                .product-section {
                    margin-top: 20px;
                    color: white;
                    font-size: 1rem;
                }

                .product-section p {
                    font-size: 14px;
                    color: #333;
                    line-height: 1.6;
                    word-wrap: break-word;
                    white-space: normal;
                    max-width: 100%;
                    text-align: justify;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                table th,
                table td {
                    border: 1px solid #ddd;
                    padding: 12px;
                    text-align: left;
                    color: white;
                    font-size: 1rem;
                }

                table th {
                    background-color:rgb(245, 225, 52);
                    color: #000;
                }

                .section-title {
                    font-size: 22px;
                    color: #333;
                    border-bottom: 2px solid #ffd700;
                    display: inline-block;
                    margin-bottom: 10px;
                }

                .styled-pre {
                    font-family: 'Arial', sans-serif;
                    font-size: 16px;
                    color: white;
                    /* background-color: #f9f9f9; */
                    padding: 10px;
                    /* border: 1px solid #ddd; */
                    border-radius: 5px;
                    white-space: pre-wrap;
                    /* This allows text wrapping inside the pre block */
                    word-wrap: break-word;
                    line-height: 1.8;
                    /* Prevents overflow for long words */
                }


                @media screen and (max-width: 768px) {
                    .section-title {
                        font-size: 18px;
                    }

                    .product-section p {
                        font-size: 12px;
                    }
                }
            </style>

        </head>

        <body>
            <header>
                <div class="navbar">
                    <div>
                        <a href="/chemical_website/products">More Products</a>
                    </div>
                </div>
            </header>

            <div class="container">
                <div class="product-header">
                    <?php echo htmlspecialchars($product['product_name']); ?>
                </div>

                <!-- Product Table for Properties -->
                <div class="product-section">
                    <h2 class="section-title">Product Details</h2><br><br>
                    <table>
                        <tr>
                            <th>Chemical Name</th>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Formula</th>
                            <td><?php echo htmlspecialchars($product['formula']); ?></td>
                        </tr>
                        <tr>
                            <th>Molecular Weight</th>
                            <td><?php echo htmlspecialchars($product['molecular_weight']); ?></td>
                        </tr>
                        <tr>
                            <th>Appearance</th>
                            <td><?php echo htmlspecialchars($product['appearance']); ?></td>
                        </tr>
                        <tr>
                            <th>pH</th>
                            <td><?php echo htmlspecialchars($product['ph']); ?></td>
                        </tr>
                        <tr>
                            <th>Specific Gravity</th>
                            <td><?php echo htmlspecialchars($product['specific_gravity']); ?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Application Section -->
                <section class="main-content">

                    <div class="product-section">
                        <h2 class="section-title">Description</h2>
                        <pre class="styled-pre"><?php echo htmlspecialchars($product['description']); ?></pre>
                    </div>
                    <div class="product-section">
                        <h2 class="section-title">Application</h2>
                        <pre class="styled-pre"><?php echo htmlspecialchars($product['application']); ?></pre>
                    </div>
                    <div class="product-section">
                        <h2 class="section-title">Packaging Details</h2>
                        <pre class="styled-pre"><?php echo htmlspecialchars($product['packaging_details']); ?></pre>
                    </div>
                    <div class="product-section">
                        <h2 class="section-title">Storage Conditions</h2>
                        <pre class="styled-pre"><?php echo htmlspecialchars($product['storage_info']); ?></pre>
                    </div>
            </div>
            </section>
            </div>
        </body>

        </html>

<?php
    } else {
        echo "<p>Product not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>Invalid product ID.</p>";
}
$conn->close();
?>