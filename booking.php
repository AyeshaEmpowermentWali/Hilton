<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel_id = $_POST['hotel_id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    
    $stmt = $conn->prepare("INSERT INTO bookings (hotel_id, user_name, user_email, checkin_date, checkout_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $hotel_id, $user_name, $user_email, $checkin, $checkout);
    if ($stmt->execute()) {
        $confirmation = "Booking confirmed! You'll receive a confirmation email soon.";
    } else {
        $confirmation = "Error in booking. Please try again.";
    }
}

$hotel_id = isset($_GET['hotel_id']) ? $_GET['hotel_id'] : 0;
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : '';

$stmt = $conn->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->bind_param("i", $hotel_id);
$stmt->execute();
$hotel = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilton Hotels - Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: #f5f7fa;
        }
        .header {
            background: #1a2a44;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .booking-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .booking-container h2 {
            color: #1a2a44;
            margin-bottom: 20px;
        }
        .booking-container img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .booking-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .booking-container button {
            width: 100%;
            padding: 10px;
            background: #d4a017;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .booking-container button:hover {
            background: #b38b12;
        }
        .confirmation {
            color: green;
            text-align: center;
            margin: 10px 0;
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
        @media (max-width: 768px) {
            .booking-container {
                margin: 10px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Hilton Hotels - Book Your Stay</h1>
    </div>
    <div class="booking-container">
        <h2><?php echo htmlspecialchars($hotel['name']); ?></h2>
        <img src="<?php echo htmlspecialchars($hotel['image']); ?>" alt="Hotel">
        <p>Location: <?php echo htmlspecialchars($hotel['location']); ?></p>
        <p>Price: $<?php echo $hotel['price_per_night']; ?>/night</p>
        <p>Rating: <?php echo $hotel['rating']; ?> ★</p>
        <p>Amenities: <?php echo htmlspecialchars($hotel['amenities']); ?></p>
        <form method="POST">
            <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
            <input type="hidden" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>">
            <input type="hidden" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>">
            <input type="text" name="user_name" placeholder="Your Name" required>
            <input type="email" name="user_email" placeholder="Your Email" required>
            <button type="submit">Confirm Booking</button>
        </form>
        <?php if (isset($confirmation)): ?>
            <p class="<?php echo strpos($confirmation, 'Error') === false ? 'confirmation' : 'error'; ?>">
                <?php echo $confirmation; ?>
            </p>
        <?php endif; ?>
    </div>
    <script>
        function goBack() {
            window.location.href = 'hotels.php';
        }
    </script>
</body>
</html>
