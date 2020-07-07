<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>LVQ</title>
</head>
<body>
    <form action="lvq.php" method="post">
        <label>
            Iterasi
            <input type = "text" name="iterasi">
        </label>
        <br>
        <label>
            Alpha
            <input type = "text" name="alpha">
        </label>
        <br>
        <label>
            Dec Alpha
            <input type = "text" name="decAlpha">
        </label>
        <br>
        <label>
            Min Alpha
            <input type = "text" name="minAlpha">
        </label>
        <br>
        <input type="submit" name="submit" value="Submit" >
    </form>
</body>

</html>