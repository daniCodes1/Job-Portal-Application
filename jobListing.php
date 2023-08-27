<html>
    <head>
        <title> CPSC 304 2023S T2 PHP/Oracle Project</title>
    </head>
    <style>
        <?php include 'project.css'; ?>
    </style>
    <body>
        <h1> Application For Job </h1>
        <hr/>
        <script>
            function applyFor() {
                var a = document.getElementById("coverResume");
                var b = document.getElementById("resume");
                var c = document.getElementById("edit");
                a.style.display = "block";
                b.style.display = "block";
                c.style.display = "block";
            }
            function end() {
                var b = document.getElementById("group");
                b.style.display = "none";
                var frm = document.getElementsByName('coverResume');
                frm.submit(); 
                frm.reset();  
                return false;
            }
        </script>
        <?php
        include 'functions.php';
        $positionID = $_GET['posID'];
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('submitApp', $_POST)) {
                    handleSubmitRequest();
                } 
                disconnectFromDB();
            }
        }

        if (isset($_GET['posID'])) {
            handleGETRequest();
        } 
        

        function handleJobRequest() {
            global $db_conn;
            $positionID = $_GET['posID'];
            $resultName = executePlainSQL("SELECT * FROM 
                (SELECT p.PositionName, sn.referenceID, sn.num_of_Spots, ss.Salary, 
                ss.ShiftSchedule, q.Qualifications, dq.Duties
                FROM JR1_ScheduleSalary ss 
                JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule 
                JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.referenceID 
                JOIN JR9_ID_Qualifications q ON sn.referenceID = q.ReferenceID
                JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications 
                JOIN JR5_PositionDuties p ON dq.Duties = p.Duties)
                WHERE referenceID = $positionID");

            while ($row = OCI_Fetch_Array($resultName, OCI_BOTH)) {
                echo "<br>";
                echo "<b>Applying For Position:</b><ul><li>" . $row["POSITIONNAME"] . "</li></ul>"; //or just use "echo $row[0]"
                echo "<b>The qualifications for this job:</b><ul><li>" . $row["QUALIFICATIONS"] . "</li></ul>";
                echo "<b>The duties for this job:</b><ul><li>" . $row["DUTIES"] . "</li></ul><br>";
            }
            
            OCICommit($db_conn);
            
        }
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('posID', $_GET)) {
                    handleJobRequest();
                } 
                disconnectFromDB();
            }
        }
        ?>

        <div id="group" class="application">
            <button onclick="applyFor()">New Application</button>
            <button>Load Application</button>
        </div>

        <form id="buttons" method="GET" action="jobListing.php" name="fresh">
            <input type="hidden" id="yeet" name="posID" value="$_GET['posID']">
        </form>
        <br>
        
        <!-- form to submit application -->
        <form id="coverResume"  method="POST" style="display: none" > 
            <p>Cover Letter:</p>
            <input type="hidden" id="appRequest" name="appRequest">
            <input type="text" style="height:100px; width:350px;" name="insCover"><br><br>
            <p>Resume:</p>
            <p>Name: </p> 
            <input type="text" name="insName"> 
            <p>Experience:</p>
            <input type="text" style="height:100px; width:350px;" name="insExp">
            <p>Education:</p>
            <input type="text" style="height:100px; width:350px;" name="insEdu"><br><br>
            <input type="button" value="Save" name="saveApp">
            <input type="button" value="Edit" name="editApp"> 
            <input type="submit" value="submit" name="submitApp" >
        </form>

        <?php
        if (isset($_POST['appRequest'])) {
            handlePOSTRequest();
        }
        function handleSubmitRequest() {
            global $db_conn;
            $tupleApp = array (
                ":bind1" => "444",
                ":bind2" => date("mdY"),
                ":bind3" => "28485"
            );
            $tupleCover = array (
                ":bind1" => "444",
                ":bind2" => $_POST['insCover']
            );
            $tupleResume = array (
                ":bind1" => "444",
                ":bind2" => $_POST['insName'],
                ":bind3" => $_POST['insExp'],
                ":bind4" => $_POST['insEdu'],
            );
            $app = array (
                $tupleApp
            );
            $cover = array (
                $tupleCover
            );
            $resume = array (
                $tupleResume
            );
            executeBoundSQL("insert into StoreApplication values (:bind1, :bind2, :bind3)", $app);
            executeBoundSQL("insert into CoverLetter values (:bind1, :bind2)", $cover);
            executeBoundSQL("insert into Resumes values (:bind1, :bind2, :bind3, :bind4)", $resume);
            OCICommit($db_conn);
            
            echo "<br><b>Application Submitted, Thank You!</b>";
        }
        ?>
    </body>
</html>