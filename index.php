<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
include 'includes/db.php';

// Get announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to OTTMS</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: var(--primary-color);
            padding: 80px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .hero-content {
            color: white;
        }
        
        .main-title {
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .tagline {
            font-weight: 300;
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin: 0 10px;
        }
        
        .btn-primary {
            background-color: white;
            border-color: white;
            color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: rgba(255,255,255,0.9);
            border-color: rgba(255,255,255,0.9);
            transform: translateY(-2px);
            color: var(--primary-color);
        }
        
        .btn-secondary {
            background-color: transparent;
            border-color: white;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: rgba(255,255,255,0.1);
            border-color: white;
            transform: translateY(-2px);
            color: white;
        }
        
        .announcement-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .announcement-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .announcement-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .announcement-card {
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .announcement-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .announcement-time {
            color: #7f8c8d;
            font-size: 0.8rem;
        }
        
        .register-links a {
            color: var(--primary-color);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .register-links a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        .divider {
            color: #bdc3c7;
            margin: 0 10px;
        }
        
        .features-section {
            padding: 50px 0;
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            text-align: center;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .train-icon {
            animation: moveTrain 4s infinite;
            display: inline-block;
        }

        @keyframes moveTrain {
            0% { transform: translateX(0); }
            50% { transform: translateX(10px); }
            100% { transform: translateX(0); }
        }

        /* Chatbot Styles */
        .chatbot-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 999;
        }

        .chatbot-window {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            z-index: 1000;
        }

        .chatbot-header {
            background: var(--primary-color);
            color: white;
            padding: 10px;
            border-radius: 15px 15px 0 0;
            font-weight: 600;
            text-align: center;
        }

        .chatbot-messages {
            padding: 10px;
            height: 200px;
            overflow-y: auto;
            font-size: 14px;
        }

        .chatbot-input {
            display: flex;
            border-top: 1px solid #ddd;
        }

        .chatbot-input input {
            border: none;
            padding: 10px;
            flex: 1;
            font-size: 14px;
        }

        .chatbot-input button {
            border: none;
            background: var(--primary-color);
            color: white;
            padding: 0 15px;
            cursor: pointer;
        }

        .bot-msg {
            background: #ecf0f1;
            padding: 6px 10px;
            border-radius: 10px;
            margin-bottom: 5px;
            max-width: 80%;
        }

        .user-msg {
            background: var(--primary-color);
            color: white;
            padding: 6px 10px;
            border-radius: 10px;
            margin-bottom: 5px;
            max-width: 80%;
            align-self: flex-end;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content text-center">
            <h1 class="main-title display-4"><span class="train-icon">🚆</span> Online Train Ticket Management System</h1>
            <p class="tagline">Book your train journey with ease and comfort</p>
            <div class="mt-4">
                <a href="users/login.php" class="btn btn-primary btn-custom"><i class="fas fa-user mr-2"></i> User Login</a>
                <a href="admin/login.php" class="btn btn-secondary btn-custom"><i class="fas fa-lock mr-2"></i> Admin Login</a>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Features Section -->
        <section class="features-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h4>Easy Booking</h4>
                        <p>Book your train tickets in just a few clicks from anywhere, anytime.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-route"></i>
                        </div>
                        <h4>Multiple Routes</h4>
                        <p>Access to hundreds of train routes across the country with various options.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Secure Payments</h4>
                        <p>Safe and secure payment gateway for all your train ticket transactions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Announcements Section -->
        <section class="announcement-section">
            <h4 class="announcement-title"><i class="fas fa-bullhorn mr-2"></i>Latest Announcements</h4>
            <?php while($a = $announcements->fetch()): ?>
                <div class="alert alert-info announcement-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong><?= $a['title'] ?>:</strong> <?= $a['message'] ?>
                        </div>
                        <div class="announcement-time">
                            <i class="far fa-clock"></i> <?= date('M j, Y g:i A', strtotime($a['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </section>

        <!-- Registration Links -->
        <div class="text-center register-links mt-4 mb-5">
            <small>
                <a href="users/register.php"><i class="fas fa-user-plus mr-1"></i> New User? Register</a>
                <span class="divider">|</span>
                <a href="admin/register.php"><i class="fas fa-user-shield mr-1"></i> Register Admin</a>
            </small>
        </div>
    </div>

    <!-- Chatbot -->
    <div class="chatbot-button" id="chatbotBtn">
        💬
    </div>
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">OTTMS Assistant</div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="bot-msg">Hello! 👋 How can I help you today?</div>
        </div>
        <div class="chatbot-input">
            <input type="text" id="chatbotInput" placeholder="Type your message...">
            <button id="chatbotSend">Send</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        const chatbotBtn = document.getElementById("chatbotBtn");
        const chatbotWindow = document.getElementById("chatbotWindow");
        const chatbotMessages = document.getElementById("chatbotMessages");
        const chatbotInput = document.getElementById("chatbotInput");
        const chatbotSend = document.getElementById("chatbotSend");

        chatbotBtn.addEventListener("click", () => {
            chatbotWindow.style.display = chatbotWindow.style.display === "flex" ? "none" : "flex";
        });

        chatbotSend.addEventListener("click", sendMessage);
        chatbotInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") sendMessage();
        });

        function sendMessage() {
            const msg = chatbotInput.value.trim();
            if (!msg) return;
            addMessage(msg, "user");
            chatbotInput.value = "";

            // Simple bot replies
            let reply = "I'm not sure about that. Please try again.";
            if (msg.toLowerCase().includes("book")) reply = "You can book tickets by logging in as a User.";
            if (msg.toLowerCase().includes("login")) reply = "Use the User Login or Admin Login buttons above.";
            if (msg.toLowerCase().includes("pay")) reply = "Payments are secured via our integrated gateways.";
            if (msg.toLowerCase().includes("hello")) reply = "Hello! How can I assist you today?";
            
            setTimeout(() => addMessage(reply, "bot"), 600);
        }

        function addMessage(text, sender) {
            const div = document.createElement("div");
            div.className = sender === "user" ? "user-msg" : "bot-msg";
            div.innerText = text;
            chatbotMessages.appendChild(div);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }
    </script>
</body>
</html>
