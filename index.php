<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <title>Il Pescaggio</title>
    </head>
    <body>
        <div class="login">
            <h1>Accedi</h1>
            <h2>Accedi con i dati che hai inserito durante la registrazione.</h2>
            <form>
                <label for="fname">Email</label>
                <input type="text" id="email" name="email" placeholder="name@example.com" required><br>
                <label for="lname">Password</label>
                <input type="text" id="lname" name="lname" placeholder="min. 8 characters" required><br>
                <input type="checkbox" name="remeDev" value="remeDev">
                <label for="remeDev">Ricordami su questo dispositivo</label><br>
                <input class="ConfButton" type="submit" value="Accedi">
            </form>
            <p> <a class="linkToPage" href="dd1">Password dimenticata??</a> </p>
            <p>Non hai un account? <a class="linkToPage" href="dd2">Registrati</a> </p>
        </div>
        <div class="carousel">
            <!-- dd3 -->
        </div>
    </body>
</html>