<?php
session_start();
require_once '../config/db_con.php'; // database connection
require_once '../config/uuid_func.php'; // UUID generator

$statusMessage = "";
$ticketStatusMessage = ""; // Variabli i ri për statusin e tiketës

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Merr të dhënat nga formulari
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $product = $_POST['product'];
    $category = $_POST['category'];
    $contactTimeframe = $_POST['contact_timeframe'];
    $description = $_POST['description'];

    try {
        
        // Gjej role_id për 'guest'
        $sql = "SELECT role_id FROM Roles WHERE role_name = 'guest' LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        $roleId = $role['role_id'];

        // Gjenero UUID për user dhe ticket
        $userId = generate_uuid();
        $ticketId = generate_uuid();

        // Shto të dhënat e guesti në tabelën Users
        $sqlUser = "INSERT INTO Users (user_id, role_id, first_name, last_name, phone_number, email, created_at, modified_at) 
                    VALUES (:user_id, :role_id, :first_name, :last_name, :phone_number, :email, NOW(), NOW())";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bindParam(':user_id', $userId);
        $stmtUser->bindParam(':role_id', $roleId);
        $stmtUser->bindParam(':first_name', $firstName);
        $stmtUser->bindParam(':last_name', $lastName);
        $stmtUser->bindParam(':phone_number', $phone);
        $stmtUser->bindParam(':email', $email);
        $stmtUser->execute();

        // Gjej agentin me numrin më të vogël të biletave
        $sqlAgent = "SELECT user_id FROM Users 
                     WHERE role_id = (SELECT role_id FROM Roles WHERE role_name = 'agent') 
                     ORDER BY current_tickets ASC LIMIT 1";
        $stmtAgent = $conn->prepare($sqlAgent);
        $stmtAgent->execute();
        $agent = $stmtAgent->fetch(PDO::FETCH_ASSOC);
        $agentId = $agent['user_id'];

        // Gjej product_id dhe category_id nga dropdown
        $sqlProduct = "SELECT product_id FROM Products WHERE product_name = :product LIMIT 1";
        $stmtProduct = $conn->prepare($sqlProduct);
        $stmtProduct->bindParam(':product', $product);
        $stmtProduct->execute();
        $productResult = $stmtProduct->fetch(PDO::FETCH_ASSOC);
        $productId = $productResult['product_id'];

        $sqlCategory = "SELECT category_id FROM Category WHERE category_name = :category LIMIT 1";
        $stmtCategory = $conn->prepare($sqlCategory);
        $stmtCategory->bindParam(':category', $category);
        $stmtCategory->execute();
        $categoryResult = $stmtCategory->fetch(PDO::FETCH_ASSOC);
        $categoryId = $categoryResult['category_id'];

        // Vendos status_id për 'Delivered'
        $sqlStatus = "SELECT status_id FROM Status WHERE status_name = 'Delivered' LIMIT 1";
        $stmtStatus = $conn->prepare($sqlStatus);
        $stmtStatus->execute();
        $statusResult = $stmtStatus->fetch(PDO::FETCH_ASSOC);
        $statusId = $statusResult['status_id'];

        // Shto të dhënat e tiketës në tabelën Ticket
        $sqlTicket = "INSERT INTO Ticket (ticket_id, client_id, agent_id, product_id, status_id, category_id, is_urgent, issue_details, contact_timeframe, created_at, modified_at) 
                      VALUES (:ticket_id, :client_id, :agent_id, :product_id, :status_id, :category_id, FALSE, :issue_details, :contact_timeframe, NOW(), NOW())";
        $stmtTicket = $conn->prepare($sqlTicket);
        $stmtTicket->bindParam(':ticket_id', $ticketId);
        $stmtTicket->bindParam(':client_id', $userId);
        $stmtTicket->bindParam(':agent_id', $agentId);
        $stmtTicket->bindParam(':product_id', $productId);
        $stmtTicket->bindParam(':status_id', $statusId);
        $stmtTicket->bindParam(':category_id', $categoryId);
        $stmtTicket->bindParam(':issue_details', $description);
        $stmtTicket->bindParam(':contact_timeframe', $contactTimeframe);
        $stmtTicket->execute();

        // Update the agent's current_tickets after assigning the ticket
        $sqlUpdateAgentTickets = "UPDATE Users SET current_tickets = current_tickets + 1 WHERE user_id = :agent_id";
        $stmtUpdateAgentTickets = $conn->prepare($sqlUpdateAgentTickets);
        $stmtUpdateAgentTickets->bindParam(':agent_id', $agentId);
        $stmtUpdateAgentTickets->execute();

        // Mesazh suksesi
        $statusMessage = "Ticket submitted successfully! Your ticket ID is: " . $ticketId;
    } catch (PDOException $e) {
        // Gabim
        $statusMessage = "Error: " . $e->getMessage();
    }
}

// Për ndjekjen e tiketës
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ticket_code'])) {
    $ticketCode = $_GET['ticket_code'];

    try {
        // Merr statusin e tiketës nga tabela Ticket dhe Status
        $sqlTicketStatus = "SELECT s.status_name 
                            FROM Ticket t 
                            JOIN Status s ON t.status_id = s.status_id 
                            WHERE t.ticket_id = :ticket_id LIMIT 1";
        $stmtTicketStatus = $conn->prepare($sqlTicketStatus);
        $stmtTicketStatus->bindParam(':ticket_id', $ticketCode);
        $stmtTicketStatus->execute();
        $ticketStatus = $stmtTicketStatus->fetch(PDO::FETCH_ASSOC);

        if ($ticketStatus) {
            $ticketStatusMessage = "The status of your ticket is: " . $ticketStatus['status_name'];
        } else {
            $ticketStatusMessage = "Ticket not found.";
        }
    } catch (PDOException $e) {
        $ticketStatusMessage = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Dashboard</title>
    <style>
        html {
            scroll-behavior: smooth;
        }
        nav {
            background-color: #333;
            overflow: hidden;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #ddd;
            color: black;
        }
        .container {
            padding: 20px;
        }
        .content {
            margin-top: 30px;
        }
        .content h3 {
            font-size: 24px;
            font-weight: bold;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
        }
        .track-section {
            margin-top: 40px;
        }
        .track-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .track-form input[type="text"],
        .track-form input[type="email"],
        .track-form textarea,
        .track-form select {
            padding: 10px;
            font-size: 16px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .track-form textarea {
            resize: vertical;
        }
        .track-form button {
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .track-form button:hover {
            background-color: #0b7dda;
        }
        .status-message {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-left: 5px solid #2196F3;
            font-size: 18px;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="#home">Home</a>
        <a href="#create-ticket">Create a Ticket</a>
        <a href="#track-ticket">Track a Ticket</a>
        <a href="javascript:void(0);" onclick="window.location.href='../index.php';">Login</a>
    </nav>

    <div class="container">
        <div class="content" id="home">
            <h3>Support System</h3>
            <p>Support System offers an intuitive and simple interface, providing users with an easy way to manage their support requests. Our free ticketing system ensures efficient handling of issues, so you can focus on what really matters. Start using the system today and enjoy the benefits of seamless customer support.</p>
        </div>

        <!-- Guest Ticket Submission Form -->
        <div id="create-ticket">
            <h3>Create a Ticket as Guest</h3>
            <form action="" method="post" class="track-form" style="flex-direction: column; align-items: flex-start;">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="email" name="email" placeholder="Email" required>

                <select name="product" required>
                    <option value="">Select a product</option>
                    <option value="Product 1">Product 1</option>
                    <option value="Product 2">Product 2</option>
                    <option value="Product 3">Product 3</option>
                </select>

                <select name="category" required>
                    <option value="">Issue category</option>
                    <option value="Audio">Audio</option>
                    <option value="Video">Video</option>
                    <option value="Damaged Product">Damaged Product</option>
                </select>

                <label for="contact_timeframe">Preferred Contact Time:</label>
                <input type="datetime-local" name="contact_timeframe" required>

                <textarea name="description" placeholder="Issue details" rows="5" required></textarea>
                <button type="submit">Submit Ticket</button>
            </form>
            <?php if ($statusMessage): ?>
                <div class="status-message">
                    <?php echo $statusMessage; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ticket Tracking Section -->
        <div class="track-section" id="track-ticket">
            <h3>Track Your Ticket</h3>
            <form action="" method="get" class="track-form">
                <input type="text" name="ticket_code" placeholder="Enter your ticket code" required>
                <button type="submit">Track Ticket</button>
            </form>
            <?php if ($ticketStatusMessage): ?>
                <div class="status-message">
                    <?php echo $ticketStatusMessage; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
