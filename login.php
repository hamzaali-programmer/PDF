<?php
include 'conn.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the entered credentials exist in the database
    $sql = "SELECT * FROM sign WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Credentials are valid, redirect to the index page
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <section>
        <div class="main-login">
            <div class="col1">
                <div class="login-img">
                    <a href="index.php"><img src="graphics/ilovepdf.svg" alt=""></a>
                </div>
                <h2 class="login-first-heading">Login to your account</h2>
                <div class="social-icons">
                    <a href="" class="facebook">
                        <i class="fa-brands fa-facebook"></i>
                        Facebook
                    </a>
                    <a href="" class="Google">
                        <i class="fa-brands fa-google"></i>
                        Google
                    </a>
                </div>
                <form action="login.php" method="post">
                    <label for="" style="margin-left: 20%; font-size: 18px; color: black;">Email</label>
                    <input type="email" name="email" placeholder="Enter Your email" class="email" required>
                    <label for="" style="margin-left: 20%; font-size: 18px; color: black;">Password</label>
                    <input type="password" name="password" placeholder="Enter Your Password" class="password" required>
                    <button type="submit" style="text-decoration: none;
                                                color: white;
                                                padding: 0.8% 4%;
                                                font-size: 18px;
                                                font-weight: 500;
                                                box-sizing: border-box;
                                                border: 1px solid transparent;
                                                border-radius: 6px;
                                                background-color: #e5322d;
                                                margin-top: 40px;
                                                margin-bottom: 40px;
                                                margin-left: 40%;">Login</button>
                </form>
                <h3 class="create-account">Don't have an account? <span><a href="signup.php" class="sub-account">Create an account</a></span></h3>
            </div>
            <div class="col2">
                <div class="login-pdf-image">
                    <img src="graphics/ilovepdf.png" alt="">
                </div>
                <h2 class="pdf-heading">Log in to your workspace</h2>
                <p class="pdf-paragraph">
                    Enter your email and password to access your iLovePDF acc
                    ount. You are one step closer to boosting your document pro
                    ductivity.
                </p>
                <div class="pdf-drop">
                    <a href="">See all tools</a>
                </div>
            </div>
        </div>
    </section>
</body>

</html>