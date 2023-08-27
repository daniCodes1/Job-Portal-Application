<html>
    <head>
        <title> CPSC 304 2023S T2 PHP/Oracle Project</title>
    </head>
    <style>
        <?php include 'project.css'; ?>
    </style>
    <body>
        <h1> Resume and Cover Letter Details</h1>
        <hr/>

        <?php
        include 'functions.php';
        $positionID = $_GET['appID'];


        if (isset($_POST['Save']) || isset($_POST['reset'])){
            handlePOSTRequest();
            echo "handling post request";
        } else if (isset($_GET['appID'])) { // important
            handleGETRequest();
        } 
        if (isset($_POST['appRequest'])) {
            handlePOSTRequest();
        } else if (isset($_GET['printRequest']) || isset($_GET['printRequestInterview']) || isset($_GET['printRequestAccount'])) {
            handleGETRequest();
        }

     
        function handleAppRequest() {
            global $db_conn;
            $resumeName = $_GET['appID'];
            $coverName = $_GET['appID'];
            $resumeName = executePlainSQL("SELECT * FROM resumeTable WHERE app_num = $resumeName");
            $coverName = executePlainSQL("SELECT * FROM coverTable WHERE app_num = $coverName");

            while ($row = OCI_Fetch_Array($resumeName, OCI_BOTH)) {
                echo "Name: " . $row["NAME"] . "<br><br><br>"; //or just use "echo $row[0]"
                echo "RESUME INFO". "<br><br>";
                echo "My experience: " . $row["EXPERIENCE"] . "<br>";
                echo "My education: " . $row["EDUCATION"] . "<br><br>";
            }

            while ($row = OCI_Fetch_Array($coverName, OCI_BOTH)) {
                echo "<br><br>";
                echo "COVER LETTER INFO:". "<br><br>";
                // echo "Name: " . $row["NAME"] . "<br><br>"; //or just use "echo $row[0]" 
                // this name isn't printing properly BUT i realized we don't need to print it again since it's printed above already in Resume
                echo "Introduction: " . $row["INTRODUCTION"] . "<br>";
            }
            
            OCICommit($db_conn);
            
        }


        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('appID', $_GET)) {
                    handleAppRequest();
                } 
                disconnectFromDB();
            }
        }
        
        ?>
  
        <form id="buttons" method="GET" action="applications.php" name="fresh">
            <input type="hidden" id="yeet" name="appID" value="$_GET['appID']">
        </form>
        <br>
   
        <!-- <?php
        function handleSubmitRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tupleCover = array (
                ":bind1" => "333",
                ":bind2" => $_POST['insCover']
            );

            $tupleResume = array (
                ":bind1" => "333",
                ":bind2" => $_POST['insName'],
                ":bind3" => $_POST['insExp'],
                ":bind4" => $_POST['insEdu'],
            );

            $cover = array (
                $tupleCover
            );

            $resume = array (
                $tupleResume
            );

            executeBoundSQL("insert into coverTable values (:bind1, :bind2)", $cover);
            executeBoundSQL("insert into resumeTable values (:bind1, :bind2, :bind3, :bind4)", $resume);
            OCICommit($db_conn);
            echo "Application Submitted, Thank You!";
        }
        ?> -->
    </body>
</html>