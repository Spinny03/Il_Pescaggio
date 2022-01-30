<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <title>Il Pescaggio</title>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="img/logo.png" alt="ciao" id="logo">
            <div class="log">
                <h1>Accedi</h1>
                <span>Accedi con i dati che hai inserito durante la registrazione.</span>
                <form action="">
                    <label for="email"><b>Email</b></label>
                    <input type="text" placeholder="name@example.com" name="uname" required>

                    <label for="psw"><b>Password</b></label>
                    <input type="password" placeholder="min. 8 characters" name="psw" required>
                    <label><input type="checkbox" id="remember" name="remember"><span id="labelCheck">Ricordami su questo dispositivo</span></label>
                    <button type="submit" name="login" class="logbtn">Accedi</button>
                </form>
                <div class="pswDiv">
                    <a href="#" class="Link">Password dimenticata?</a>
                </div>
            </div>
            <div class="bottom">
                <span>Non hai un account? <a href="#" class="Link">Registrati</a></span>
            </div>
        </div>
        <div class="right">

        </div>
    </div>
    
</body>
</html>