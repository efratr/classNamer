<html>
    <head>
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    	<title>Class Namer Home Task</title>
    </head>
    <body>
    	<h1>Refresh for get words from classNamer.org</h1>
    	<h3>The number in the prenthesis is the word count's in database</h3>

    	<form action="base.php" method="POST">
    		<input type="submit" value="Empty words in DB"/>
        </form>
        <span id="words"></span>

        <script type="text/javascript">
        	$(document).ready(function(){
        		$.ajax({
        			url: 'base.php',
        			type: "GET",
        			success: function(result){
        				$("#words").html(result);
        			},
        			error: function(error){
        				console.log(error);
        			}
        		})
        	})
        </script>

    </body>
</html>