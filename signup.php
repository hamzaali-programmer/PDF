<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <section>
        <div class="main-signup">
            <div class="col1">
                <div class="signup-img">
                    <a href="index.php"><img src="graphics/ilovepdf.svg" alt=""></a>
                </div>
                <h2 class="signup-first-heading">Create New account</h2>
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
                <form action="signup.php" method="post">
                <label for="" style="margin-left: 20%; font-size: 18px; color: black;">Name</label>
                    <input type="text" name="name" placeholder="Name" class="email" required>
                    <label for="" style="margin-left: 20%; font-size: 18px; color: black;">Email</label>
                    <input type="email" name="email" placeholder="Email" class="email" required>
                    <label for="" style="margin-left: 20%; font-size: 18px; color: black;">Password</label>
                    <input type="password" name="password" placeholder="Password" class="password" required>
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
                                                margin-left: 40%;">Sign Up</button>
                </form>



                <h3 class="create-account">Already member? <span><a href="login.php" class="sub-account">Log in</a></span></h3>
                <p class="serivce-policy">By creating an account, you agree to iLovePDF <span class="service"><a href="">Terms of Service</a></span> and <span class="policy"><a href="">Privacy Policy</a></span></p>
            </div>
            <div class="col2">
                <div class="signup-pdf-image">
                    <img src="graphics/ilovepdf.png" alt="">
                </div>
                <h2 class="pdf-heading">PDF tools for productive people</h2>
                <p class="pdf-paragraph">
                    iLovePDF helps you convert, edit, e-sign, and protect PDF files quickly
                    and easily. Enjoy a full suite of tools to effectively manage docume
                    nts —no matter where you’re working.
                </p>
                <div class="pdf-drop">
                    <a href="">See all tools</a>
                </div>
            </div>
        </div>
    </section>
</body>

</html>


<?php
include 'conn.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Insert data into the "sign" table
    $sql = "INSERT INTO sign (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the login page after successful signup
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>