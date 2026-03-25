<!DOCTYPE html>
<html>

<head>
    <title>AJAX Fetch Data Using Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <h2>Fetch Student Data Using AJAX Form</h2>

    <form id="fetchForm">
        <input type="submit" value="Fetch Data">
    </form>

    <div id="result" style="margin-top:20px;"></div>

    <script>
    $(document).ready(function() {
        $("#fetchForm").submit(function(e) {
            e.preventDefault(); // stop page reload

            $.ajax({
                url: "content.php",
                type: "POST",
                success: function(data) {
                    $("#result").html(data);
                }
            });
        });
    });
    </script>

</body>

</html>