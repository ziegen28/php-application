<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google SSO Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Google Sign-In Platform Library -->
    <script src="accounts.google.com" async></script>
    <style>
        /* Optional custom styles for centering and aesthetics */
        body {
            background-color: #9d46f0ea;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            max-height: 1000px;
            background-color: white;
            border-radius: 0.25rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .g_id_signin {
            /* Ensures the Google button is centered */
            display: inline-block; 
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Sign In with Google</h2>
        <p>Use your Google account for single sign-on.</p>
        
        <!-- Google One Tap / Sign-In Button -->
        <div id="g_id_onload"
             data-client_id="YOUR_GOOGLE_CLIENT_ID"
             data-context="signin"
             data-ux_mode="popup"
             data-callback="handleCredentialResponse"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-shape="pill"
             data-theme="outline"
             data-text="signin_with"
             data-size="large"
             data-logo_alignment="left">
        </div>

        <!-- Placeholder for icons from original HTML (optional) -->
        <div class="icons mt-4">
             <!-- Note: These social icons are purely aesthetic, as only Google SSO is implemented -->
             <i class="fa-brands fa-github" style="padding: 0 10px;"></i>
             <i class="fa-brands fa-google" style="padding: 0 10px;"></i>
             <i class="fa-brands fa-facebook" style="padding: 0 10px;"></i>
        </div>
    </div>

    <!-- Script to handle the Google Sign-In response -->
    <script>
        function handleCredentialResponse(response) {
            // response.credential contains the Google ID token (JWT)
            console.log("Encoded ID token: " + response.credential);
            
            // Here, you would typically send the 'response.credential' JWT to your 
            // backend server using an AJAX call (fetch API or similar) for verification
            // and session management.
            alert("Signed in successfully! Check the console for the ID token.");
            // Example of where you might redirect the user:
            // window.location.href = "/dashboard"; 
        }
    </script>
    
    <!-- Font Awesome for the extra icons -->
    <script src="kit.fontawesome.com" crossorigin="anonymous"></script>

</body>
</html>
