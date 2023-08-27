<html>
    <head>
        <title> CPSC 304 2023S T2 PHP/Oracle Project</title>
    </head>
    <style>
        <?php include 'project.css'; ?>
    </style>
    <body>
        <h1> Welcome to application portal for the BEST company! </h1>
        <p>Please click any button to begin with:</p>

        <form id="resetAll" style="display: none" method="POST" action="project.php"> 
            <input type="hidden" id="resetRequest" name="resetRequest">
            <input type="submit" value="Reset DB"name="resetAll">
        </form>
        <!-- button group for green navigation bar -->
        <div id="group" class="options-employee">
            <button onclick="browseJob()">Browse Job Listings</button>
            <button onclick="viewApp()">Past Applications</button>
            <button onclick="upcomingInterviews()">Upcoming Interviews</button>
            <button onclick="acceptDeny()">Accept/Deny Offer</button>
            <button onclick="manageAccount()">Manage Account</button>
            <button onclick="extraInfo()">Extra Information</button>
        </div><br><br><br>

        <!-- form for filtering by category -->
        <form id="filterCatForm" style="display: none" method="POST" action="project.php"> 
            <input type="hidden" id="filterCatRequest" name="filterCatRequest">
            Position<input type="checkbox" name="POSITIONNAME" value="POSITIONNAME"> 
            , Spots Left<input type="checkbox" name="NUM_OF_SPOTS" value="NUM_OF_SPOTS"> 
            , Annual Salary <input type="checkbox" name="SALARY" value="SALARY"> 
            , Work Schedule<input type="checkbox" name="SHIFTSCHEDULE" value="SHIFTSCHEDULE"> 
            <input type="submit" value="Filter By Category" name="filterCat" style="font-weight: bold;"></p>
        </form>

        <!-- form for global selection search -->
        <form name="findForm" id="form1" method="POST" action="project.php">
            <input type="hidden" id="filterFindRequest" name="filterFindRequest">
            Table: <select name="tables" id="table">
                <option value="" selected="selected">Select Table</option>
            </select>
            Category: <select name="cat" id="category">
                <option value="" selected="selected">Please select Table</option>
            </select>
            Keyword: <input type="text" name="key">
            <input type="submit" value="Find Tuples" name="filterFind" style="font-weight: bold;">
        </form>
        <hr>
        <!-- join form embedded in job listing -->
        <form id="joinForm" style="display: none" method="POST" action="project.php"> 
            <input type="hidden" id="joinRequest" name="joinRequest">
            Reference ID: <input type="text" name="referenceID">
            <input type="submit" value="Join" name="joinForm"></p>
        </form>

        <script>
            var show = false;
            function browseJob() {
                document.getElementById('printJobForm').submit(); }

            function viewApp() {
                document.getElementById('printAppForm').submit(); }

            function upcomingInterviews() {
                document.getElementById('printInterviewForm').submit(); }

            function acceptDeny() {
                document.getElementById('printOfferForm').submit(); }

            function manageAccount() {
                document.getElementById('manageAccForm').submit(); }

            // jss function group for extra info GET printing
            function extraInfo() {document.getElementById('printExInfo').submit();} // print buttons
            function getAG(){ document.getElementById('printAggregateGroup').submit();}
            function getAH(){document.getElementById('printAggregateHaving').submit();}
            function getNS(){document.getElementById('printNested').submit();}
            function getDivi(){document.getElementById('printDivision').submit();}

            var tableData = {
                "Job Listing": [
                    "PositionName",
                    "num_of_spots",
                    "Salary",
                    "ss.ShiftSchedule",
                    "sn.ReferenceID"
                ],
                "Past Application": [
                    "job_app_num",
                    "ApplyDate",
                    "account_acc_num_sa"
                ],
                "Upcoming Interviews": [
                    "Interviewer",
                    "Interviewee",
                    "date_"
                ]
            }
            window.onload = function() {
                var tableSel = document.getElementById("table");
                var catSel = document.getElementById("category");
                // var chapterSel = document.getElementById("chapter");
                for (var x in tableData) {
                    tableSel.options[tableSel.options.length] = new Option(x, x);
                }
                tableSel.onchange = function() {
                    //empty Chapters- and Topics- dropdowns
                    //chapterSel.length = 1;
                    catSel.length = 1;
                    //display correct values
                    for (var y in tableData[this.value]) {
                        catSel.options[catSel.options.length] = new Option(tableData[this.value][y], tableData[this.value][y]);
                    }
                }
            }
        </script>

        
        <?php
        include 'functions.php';

        // HANDLE ALL GET REQUESTS
        // this is the position where all requests are printed
        if (isset($_GET['printRequest']) || isset($_GET['manageRequest']) || isset($_GET['printRequestInterview']) 
            || isset($_GET['printRequestAccount'])|| isset($_GET['printAppRequest'])) {
            handleGETRequest();
        }
        if (isset($_GET['printExInfo']) || isset($_GET['aggregateGroup']) || isset($_GET['aggregateHaving'])
            || isset($_GET['nested']) || isset($_GET['division']) || isset($_GET['printRequestOffer'])){
            handleGETRequest();
        }
        // HANDLE filter request, here due to position being printed
        if (isset($_POST['filterCat'])) {
            handlePOSTRequest();
        }



        // ECHO POST BUTTONS: function for all buttons in extra info tab
        function printInfoButtons() {
            echo "Please choose an option from below: <br><br>";
            echo "<div><button onclick='getAG()'>Average Salary By Type of Work</button><button onclick='getAH()'>Jobs With Salaries Above $50,000</button>
            <button onclick='getNS()'>Positions With The Highest Average Salaries</button><button onclick='getDivi()'>Jobs With Less Than 20 Spots Left</button></div>";
        }

        // ECHO POST BUTTONS: for accept deny offer buttons
        function acceptDenyButtons() {
            echo "<br><div><button onclick='updateAccept()'>Accept Offer</button><button onclick='updateDeny()'>Deny Offer</button></div>";
            // for the words inside the button only
        } 

        // ECHO POST BUTTONS: function to echo all the buttons to manage account and print
        function printManageButtons() {
            echo "Please choose an option from below: <br><br>";
            echo "<div><button onclick='createAcc()'>Create an Account</button><button onclick='updateAddy()'>Update Address</button>
                <button onclick='updatePhone()'>Update Phone Number</button><form id='printAccount' method='GET' action='project.php'> 
                <input type='hidden' id='printRequestAccount' name='printRequestAccount'>
                <input type='submit' value='Print Account Tables'name='printAccount'>
                </form></div>";
        }

        // ---------------------------------
        // BEGIN of all printing GET functions
        // ---------------------------------

        // ECHO TABLE: for accept deny all offers table
        function handlePrintOffer() {
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM AcceptDenyOffer");
            echo "<b>All Offers:</b><br><br>";
            echo "<table>";
            echo "<tr><th>Employee #</th><th>Start Date</th><th>Email</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>";
                echo $row["OFFER_EMPLOYEE_NUM"] . "</td><td>" . $row["STARTDATE"] . "</td><td>" . $row["APPLICANT_EMAIL"] . "</td></tr>";
            }
            echo "</table>";
        }

        // GOBAL FIND: handle global find from dropdown menu
        function handleFindRequest() {
            global $db_conn;
            $find = $_POST['tables'];
            $cat = $_POST['cat'];
            $key = $_POST['key'];
            if ($find == "Job Listing") {
                echo "<b>Find tuples result from Job Listing Table:</b><br><br>";
                $result = executePlainSQL("SELECT p.PositionName, sn.referenceID, sn.num_of_Spots, ss.Salary, ss.ShiftSchedule, sn.referenceID
                FROM JR1_ScheduleSalary ss 
                JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule 
                JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.referenceID 
                JOIN JR9_ID_Qualifications q ON sn.referenceID = q.ReferenceID
                JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications 
                JOIN JR5_PositionDuties p ON dq.Duties = p.Duties 
                WHERE $cat = '$key'");
                echo "<table>";
                echo "<tr><th>Position</th><th>Spots Left</th><th>Annual Salary</th><th>Work Schedule</th><th>Job Reference ID</th></tr>";
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . '<a target = "_blank" 
                        href="https://www.students.cs.ubc.ca/~fulino/jobListing.php?posID='. $row[1].' ">' . 
                        $row[0] . "</a>" . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>";
                }
                echo "</table>";
            } if ($find == "Past Application") {
                echo "<b>Find request result from Past Applications Table:</b><br><br>";
                $result = executePlainSQL("SELECT * FROM StoreApplication WHERE $cat = '$key'");
                echo "<table>";
                echo "<tr><th>App Num #</th><th>Apply Date</th><th>Account Number</th></tr>";

                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>" . '<a target = "_blank" 
                        href="https://www.students.cs.ubc.ca/~daniren/applications.php?appID='.$row['JOB_APP_NUM'].' ">' . 
                        $row['JOB_APP_NUM'] . "</a>" . "</td><td>" . $row["APPLYDATE"] . "</td><td>" . $row["ACCOUNT_ACC_NUM_SA"] . "</td></tr>";
                }
                echo "</table>";
            } if ($find == "Upcoming Interviews") {
                echo "<b>Find request result from Upcoming Interviews Table:</b><br><br>";
                $result = executePlainSQL("SELECT * FROM Interview WHERE $cat = '$key'");
                echo "<table>";
                echo "<tr><th>Interviewer</th><th>Interviewee</th><th>IntDate</th></tr>";

                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr><td>";
                    echo $row["INTERVIEWER"] . "</td><td>" . $row["INTERVIEWEE"] . "</td><td>" . $row["DATE_"] . "</td></tr>";
                }
                echo "</table>";
            }
        }

        // FIND EMPLOYER: find and echo employer
        function handleJoinRequest() {
            global $db_conn;
            $id = $_POST['referenceID'];
            $result = executePlainSQL("SELECT en.EmpName
            FROM R3_EmployeeNumName en
            JOIN Creates c ON en.employee_num = c.emp_employee_num
            JOIN JR3_ID_SpotNum sn ON c.job_referID = sn.ReferenceID
            WHERE sn.ReferenceID = $id");
            echo "<br>The employer for Reference ID = ". $id ." is :<br>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo $row[0];
            }
        }

        // PRINT TABLE JOB: prints job listing table, one result will only allow for one fetch from OCI, so only one while loop for printing
        function handlePrintJobListing() {
            global $db_conn;
            echo "<b>All Job Listings:</b><br><br>";
            echo "<table>";
            echo "<tr><th>Position</th><th>Spots Left</th><th>Annual Salary</th><th>Work Schedule</th><th>Job Reference ID</th></tr>";
            $result = executePlainSQL("SELECT p.PositionName, sn.referenceID, sn.num_of_Spots, ss.Salary, ss.ShiftSchedule, sn.referenceID
                FROM JR1_ScheduleSalary ss 
                JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule 
                JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.referenceID 
                JOIN JR9_ID_Qualifications q ON sn.referenceID = q.ReferenceID
                JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications 
                JOIN JR5_PositionDuties p ON dq.Duties = p.Duties");
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . '<a target = "_blank" 
                    href="https://www.students.cs.ubc.ca/~fulino/jobListing.php?posID='. $row[1].' ">' . 
                    $row[0] . "</a>" . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></tr>";
            }
            echo "</table>";
            echo "<br>";

            echo "Check any desired boxes for filtering by category:";
            // form for filtering for projection
            echo "<ul><li><form id='filterCatForm' method='POST' action='project.php'> 
                <input type='hidden' id='filterCatRequest' name='filterCatRequest'>
                Position<input type='checkbox' name='POSITIONNAME' value='POSITIONNAME'> 
                , Spots Left<input type='checkbox' name='NUM_OF_SPOTS' value='NUM_OF_SPOTS'> 
                , Annual Salary <input type='checkbox' name='SALARY' value='SALARY'> 
                , Work Schedule<input type='checkbox' name='SHIFTSCHEDULE' value='SHIFTSCHEDULE'> 
                <input type='submit' value='Filter By Category' name='filterCat' style='font-weight: bold;'></p>
                </form></li></ul>";
            // for to find employer
            echo "Input Reference ID to find employer: ";
            echo "<ul><li><form id='joinForm' method='POST' action='project.php'> 
                <input type='hidden' id='joinRequest' name='joinRequest'>
                Reference ID: <input type='text' name='referenceID'>
                <input type='submit' value='Join' name='joinForm'></p>
                </form></li></ul>";
        }

        // PRINT TABLE INTERVIEW
        function handlePrintInterview() {
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM Interview");
            echo "<b>All Upcoming Scheduled Interviews:</b><br><br>";
            echo "<table>";
            echo "<tr><th>Interviewer</th><th>Interviewee</th><th>IntDate</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>";
                echo $row["INTERVIEWER"] . "</td><td>" . $row["INTERVIEWEE"] . "</td><td>" . $row["DATE_"] . "</td></tr>";
            }
            echo "</table>";
        }

        // PRINT TABLE ACCOUNT + APPLICANT
        function handlePrintAccount() {
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM Applicant");
            
            echo "<br>Retrieved from table Applicant:<br>";
            echo "<table>";
            echo "<tr><th>email</th><th>name</th><th>Phone Number</th><th>Address</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>";
                echo $row["APPLICANT_EMAIL"] . "</td><td>" . $row["NAME_"] . "</td><td>" . $row["PHONE_NUM"] . 
                    "</td><td>" . $row["ADDRESS_"] . "</td></tr>";
            }
            echo "</table>";

            $result2 = executePlainSQL("SELECT * FROM CreateAccount");
            echo "<br>Retrieved from table Account:<br>";
            echo "<table>";
            echo "<tr><th>email</th><th>account num</th></tr>";
            while ($row = OCI_Fetch_Array($result2, OCI_BOTH)) {
                echo "<tr><td>";
                echo $row["APPLICANT_EMAIL"] . "</td><td>" . $row["ACCOUNT_ACC_NUM"] . "</td></tr>";
            }
            echo "</table>";
        }

        // PRINT TABLE PAST APP
        function handlePrintPastApplication() {
            global $db_conn;
            $result = executePlainSQL("SELECT * FROM StoreApplication");
            echo "<b>All Past Job Applications:</b><br><br>";
            echo "<table>";
            echo "<tr><th>App Num #</th><th>Apply Date</th><th>Account Number</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . '<a target = "_blank" 
                    href="https://www.students.cs.ubc.ca/~fulino/applications.php?appID='.$row['JOB_APP_NUM'].' ">' . 
                    $row['JOB_APP_NUM'] . "</a>" . "</td><td>" . $row["APPLYDATE"] . "</td><td>" . $row["ACCOUNT_ACC_NUM_SA"] . "</td></tr>";
            }
            echo "</table>";
        }


        // FILTERING: function to handle filtering 
        function handleFilterRequest() {
            global $db_conn;

            $tuple = array ();
            if (array_key_exists('POSITIONNAME', $_POST)) {
                array_push($tuple,"PositionName, ");
            } 
            if (array_key_exists('NUM_OF_SPOTS', $_POST)) {
                array_push($tuple,"Num_of_Spots, ");
            } 
            if (array_key_exists('SALARY', $_POST)) {
                array_push($tuple,"Salary, ");
            } 
            if (array_key_exists('SHIFTSCHEDULE', $_POST)) {
                array_push($tuple,"ShiftSchedule, ");
            } 
            if (!empty($tuple)) {
                array_push($tuple,"referenceID, "); // add reference ID to grab from sql
                $string = implode("",$tuple);
                $sub = substr($string, 0, -2);
                // echo $sub;
                echo "<b>All Filtered Job Listings:</b><br><br>";
                $result = executePlainSQL("SELECT $sub FROM 
                (SELECT p.PositionName, sn.referenceID, sn.num_of_Spots, ss.Salary, 
                ss.ShiftSchedule, q.Qualifications, dq.Duties
                FROM JR1_ScheduleSalary ss 
                JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule 
                JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.referenceID 
                JOIN JR9_ID_Qualifications q ON sn.referenceID = q.ReferenceID
                JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications 
                JOIN JR5_PositionDuties p ON dq.Duties = p.Duties)");
                
                array_pop($tuple); // to remove the reference ID from header
                $reverse = array_reverse($tuple);
                $size = sizeof($reverse);
                echo "<table><tr>";
                while(!empty($reverse)) {
                    echo "<th>" . substr(end($reverse), 0, -2) . "</th>";
                    array_pop($reverse);
                }
                echo "</tr>";
                while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                    echo "<tr>";
                    for ($x = 0; $x < $size; $x++) {
                        if ($x == 0) {
                            echo "<td>" . '<a target = "_blank" 
                            href="https://www.students.cs.ubc.ca/~fulino/jobListing.php?posID='. $row["REFERENCEID"].' ">' . 
                            $row[0] . "</a>" . "</td>";
                        } else {
                            echo "<td>" . $row[$x] . "</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</table><br>";
            }
            
        }

        
        // PRINT AGG GROUP
        function printAggregateGroup() {
            global $db_conn;
            $query = executePlainSQL("SELECT ShiftSchedule, AVG(Salary) FROM JR1_ScheduleSalary GROUP BY ShiftSchedule");
            echo "Print Aggregate Group Query:<br><br>";
            echo "<b>Print Avg Salary by type of work:</b><br><br>";
            echo "<table>";
            echo "<tr><th>Work Mode</th><th>Salary</th></tr>";
            while ($row = OCI_Fetch_Array($query, OCI_BOTH)) {
                echo "<tr><td>"  . $row['SHIFTSCHEDULE'] . "</td><td>" . $row['AVG(SALARY)'] . "</tr>";
            }
            echo "</table>";
        }
        // PRINT AGG NESTED
        function printNested() {
            global $db_conn;
            $query = executePlainSQL("SELECT PositionName, ShiftSchedule, aves
            FROM (
                SELECT p.PositionName, ss.ShiftSchedule, AVG(Salary) AS aves
                FROM JR1_ScheduleSalary ss
                JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule
                JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.ReferenceID
                JOIN JR9_ID_Qualifications q ON sn.referenceID = q.ReferenceID
                JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications
                JOIN JR5_PositionDuties p ON dq.duties = p.Duties
                GROUP BY p.PositionName, ss.ShiftSchedule
            ) nested
            WHERE aves = (SELECT MAX(aves) FROM (
                SELECT p.PositionName, AVG(Salary) AS aves
                FROM JR1_ScheduleSalary ss
                JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule
                JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.ReferenceID
                JOIN JR9_ID_Qualifications q ON sn.referenceID = q.ReferenceID
                JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications
                JOIN JR5_PositionDuties p ON dq.duties = p.Duties
                GROUP BY p.PositionName
            ))");
            echo "Print Aggregate Nested Query:<br><br>";
            echo "<b>Positions with highest average salaries:</b><br><br>";
            echo "<table>";
            echo "<tr><th>Work Mode</th><th>Position Name</th><th>Salary</th></tr>";
            while ($row = OCI_Fetch_Array($query, OCI_BOTH)) {
                echo "<tr><td>" . $row['POSITIONNAME'] . "</td><td>" . $row['SHIFTSCHEDULE'] ."</td><td>". $row['AVES'] ."</tr>";
            }
            echo "</table>";
        }
        // PRINT AGG HAVING
        function printAggregateHaving() {
            global $db_conn;
            $query = executePlainSQL("SELECT ss.ShiftSchedule, p.PositionName, AVG(Salary)
            FROM JR1_ScheduleSalary ss JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule
            JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.ReferenceID JOIN JR9_ID_Qualifications q
            ON sn.referenceID = q.ReferenceID JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications
            JOIN JR5_PositionDuties p ON dq.duties = p.Duties
            GROUP BY ss.ShiftSchedule, p.PositionName
            HAVING AVG(Salary) > 50000");
            echo "Print Aggregate Having Query:<br><br>";
            echo "<b>Jobs with Salaries Above $50,000:</b><br><br>";
            echo "<table>";
            echo "<tr><th>Work Mode</th><th>Position Name</th><th>Salary</th></tr>";
            while ($row = OCI_Fetch_Array($query, OCI_BOTH)) {
                echo "<tr><td>" . $row['SHIFTSCHEDULE'] . "</td><td>" . $row['POSITIONNAME'] . "</td><td>" . $row['AVG(SALARY)'] ."</tr>";
            }
            echo "</table>";
        }
        // PRINT AGG DIVISION
        function printDivision() {
            global $db_conn;
            $query = executePlainSQL("SELECT p.PositionName, sn.num_of_Spots, ss.Salary, ss.ShiftSchedule
            FROM JR1_ScheduleSalary ss JOIN JR10_ID_Shift s ON ss.ShiftSchedule = s.ShiftSchedule
            JOIN JR3_ID_SpotNum sn ON s.ReferenceID = sn.ReferenceID JOIN JR9_ID_Qualifications q
            ON sn.referenceID = q.ReferenceID JOIN JR7_DutyQualifications dq ON q.Qualifications = dq.Qualifications
            JOIN JR5_PositionDuties p ON dq.duties = p.Duties
            WHERE q.ReferenceID IN (
                (SELECT q.ReferenceID FROM JR9_ID_Qualifications
                 MINUS
                 SELECT sn.referenceID FROM JR3_ID_SpotNum
                 WHERE sn.num_of_Spots >= 20)
            )");
            echo "Print Aggregate Division Query:<br><br>";
            echo "<b>Jobs With Less Than 20 Spots Left:</b><br><br>";
            echo "<table>";
            echo "<tr><th>Work Mode</th><th>Position Name</th><th>Salary</th></tr>";
            while ($row = OCI_Fetch_Array($query, OCI_BOTH)) {
                echo "<tr><td>" . $row['POSITIONNAME'] . "</td><td>" . $row['NUM_OF_SPOTS'] . "</td><td>" . $row['SALARY'] . "</td><td>" . $row['SHIFTSCHEDULE'] . "</tr>";
            }
            echo "</table>";
        }
        // ---------------------------------
        // END of all printing GET functions
        // ---------------------------------
        ?>
        <br>

        <!-- javascript for managing account button group and managing offer -->
        <script>
            show = false;
            function createAcc() {
                var accGroup = document.getElementById('accountGroup');
                var addyGroup = document.getElementById('addyGroup');
                var phoneGroup = document.getElementById('phoneGroup');
                if (accGroup.style.display === "none") {
                    phoneGroup.style.display = "none";
                    addyGroup.style.display = "none";
                    accGroup.style.display = "block";
                } else {
                    accGroup.style.display = "none";
                }
                show = true;
            }
            function updateAddy() {
                var addyGroup = document.getElementById('addyGroup');
                var phoneGroup = document.getElementById('phoneGroup');
                var accGroup = document.getElementById('accountGroup');
                if (addyGroup.style.display === "none") {
                    phoneGroup.style.display = "none";
                    accGroup.style.display = "none";
                    addyGroup.style.display = "block";
                } else {
                    addyGroup.style.display = "none";
                }
                show = true;
            }
            function updatePhone() {
                var phoneGroup = document.getElementById('phoneGroup');
                var addyGroup = document.getElementById('addyGroup');
                var accGroup = document.getElementById('accountGroup');
                if (phoneGroup.style.display === "none") {
                    addyGroup.style.display = "none";
                    accGroup.style.display = "none";
                    phoneGroup.style.display = "block";
                } else {
                    phoneGroup.style.display = "none";
                }
                show = true;
            }

            // for accept/deny offer
            function updateDeny() {
                var id = document.getElementById('deny');
                 id.style.display = "block";
                show = true;
            }
        </script>

        
        <!-- button group of elements for creating account -->
        <div id="accountGroup" style="display: none">
            <h2> Create Account </h2>
            <form method="POST" action="project.php"> 
                <input type="hidden" id="insertAccountQueryRequest" name="insertAccountQueryRequest">
                Name: <input type="text" name="insName"> <br /><br />
                Email: <input type="text" name="insEmail"> <br /><br />
                PhoneNumber: <input type="text" name="insPhone"> <br /><br />
                Address: <input type="text" name="insAddress"> <br /><br />
                <input type="submit" value="Create Account" name="insertSubmitAccount">
            </form>
            <hr/>
        </div>

        <!-- button group of elements for editing address -->        
        <div id="addyGroup" style="display: none">
            <h2> Update Address </h2>
            <p> Input values are sensitive, please ensure address is spelled correctly. </p>
            <form method="POST" action="project.php"> 
                <input type="hidden" id="updateAddyRequest" name="updateAddyRequest">
                Old Address: <input type="text" name="oldAddress"> <br /><br />
                New Address: <input type="text" name="newAddress"> <br /><br />
                <input type="submit" value="Update Address" name="updateSubmitAddy">
            </form>
            <hr/>
        </div>
        
        <!-- button group of elements for editing phone number -->
        <div id ="phoneGroup" style="display: none">
        <h2 id="phoneHead">Update Phone Number</h2>
        <p id="phoneBody">Input values are sensitive, please ensure phone number is correct and in the format xxx-xxx-xxxx.</p>
        <form id="phoneForm"method="POST" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updatePhoneQueryRequest" name="updatePhoneQueryRequest">
            Old Phone Number: <input type="text" name="oldPhone"> <br /><br />
            New Phone Number: <input type="text" name="newPhone"> <br /><br />
            <input type="submit" value="UpdatePhone" name="updateSubmitPhone">
        </form>
        <hr/>
        </div>

        <!-- button group of elements for denying offer -->
        <div id ="deny" style="display: none">
        <h2 >Deny offer</h2>
        <p >Input values are sensitive, please ensure info is inputted correctly.</p>
        <form method="POST" action="project.php">  
            <input type="hidden" id="updateDenyQueryRequest" name="updateDenyQueryRequest">
            Employee number: <input type="text" name="empNum"> <br /><br />
            <input type="submit" value="Update" name="updateSubmitDeny">
        </form>
        <hr/>
        </div>

        <!-- form for inserting interview items -->
        <form id="insertInterview" style="display: none" method="POST" action="project.php"> 
            <input type="hidden" id="insertInterviewQueryRequest" name="insertInterviewQueryRequest">
            Interviewer: <input type="text" name="insInt"> <br /><br />
            Interviewee: <input type="text" name="insIntee"> <br /><br />
            IntDate: <input type="text" name="insIntDate>"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmitInterview">
        </form>

        <!-- form for inserting offers -->
        <form id="insertOffer" style="display: none" method="POST" action="project.php"> 
            <input type="hidden" id="insertOfferQueryRequest" name="insertOfferQueryRequest">
            Employee Number: <input type="text" name="insNum"> <br /><br />
            Start Date: <input type="text" name="insStart"> <br /><br />
            Applicant Email: <input type="text" name="insEmail>"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmitOffer">
        </form>

        <!-- form for updating address -->
        <form id="updateAccount" style="display: none" method="POST" action="project.php"> 
            <input type="hidden" id="updateAddyRequest" name="updateAddyRequest">
            Old Address: <input type="text" name="oldAddress"> <br /><br />
            New Address: <input type="text" name="newAddress"> <br /><br />
            <input type="submit" value="UpdateAccount" name="updateSubmitAddy">
        </form>
        <!-- for for updating phone number -->
        <form id="updatePhone" style="display: none" method="POST" action="project.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updatePhoneQueryRequest" name="updatePhoneQueryRequest">
            Old Phone Number: <input type="text" name="oldPhone"> <br /><br />
            New Phone Number: <input type="text" name="newPhone"> <br /><br />
            <input type="submit" value="UpdatePhone" name="updateSubmitAddy">
        </form>

        <!-- ----------------------------------------------------- -->
        <!-- All hidden button group using GET for printing ------ -->
        <!-- ----------------------------------------------------- -->
        <form id="manageAccForm" method="GET" action="project.php"> 
            <input type="hidden" id="manageRequest" name="manageRequest">
            <input type="hidden" name="manageAcc">
        </form>

        <form id="printJobForm" method="GET" action="project.php"> 
            <input type="hidden" id="printRequest" name="printRequest">
            <input type="hidden" name="printJob">
        </form>

        <form id="printAppForm" method="GET" action="project.php">
            <input type="hidden" id="printAppRequest" name="printAppRequest">
            <input type="hidden" name="printApp">
        </form>

        <form id="printInterviewForm" method="GET" action="project.php"> 
            <input type="hidden" id="printRequestInterview" name="printRequestInterview">
            <input type="hidden" name="printInterview">
        </form>

        <form id="printAccount" style="display: none" method="GET" action="project.php"> 
            <input type="hidden" id="printRequestAccount" name="printRequestAccount">
            <input type="submit" value="Print Account Tables"name="printAccount">
        </form>

        <form id="printOfferForm" method="GET" action="project.php"> 
            <input type="hidden" id="printRequestOffer" name="printRequestOffer">
            <input type="hidden" name="printOffer">
        </form>

        <!-- for extra info tab below -->
        <form id="printExInfo" style="display: none" method="GET" action="project.php"> 
            <input type="hidden" id="printExInfo" name="printExInfo">
            <input type="hidden" value="Print Extra Info"name="exInfo">
        </form>

        <form id="printAggregateGroup" style="display: none" method="GET" action="project.php"> 
            <input type="hidden" id="aggregateGroup" name="aggregateGroup">
            <input type="hidden" value="Print Extra Info"name="ag">
        </form>

        <form id="printAggregateHaving" style="display: none" method="GET" action="project.php"> 
            <input type="hidden" id="aggregateHaving" name="aggregateHaving">
            <input type="hidden" value="Print Extra Info"name="ah">
        </form>

        <form id="printNested" style="display: none" method="GET" action="project.php"> 
            <input type="hidden" id="aggNested" name="nested">
            <input type="hidden" value="Print Extra Info"name="ns">
        </form>

        <form id="printDivision" style="display: none" method="GET" action="project.php"> 
            <input type="hidden" id="division" name="division">
            <input type="hidden" value="Print Extra Info"name="divi">
        </form>
        <!-- ----------------------------------------------------- -->
        <!-- ----end of all GET hidden print buttons ------------- -->
        <!-- ----------------------------------------------------- -->

        <?php
        // ALL POST FUNCTIONS 

        // INSERT INTERVIEW
        function handleInsertInterviewRequest() {
            global $db_conn;
            $tuple = array (
                ":bind1" => $_POST['insInt'],
                ":bind2" => $_POST['insIntee'],
                ":bind3" => $_POST['insIntDate'],
            );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("insert into interviewTable values (:bind1, :bind2, :bind3)", $alltuples);
            OCICommit($db_conn);
        }

        // INSERT ACCOUNT
        function handleInsertAccountRequest() {
            global $db_conn;
            $tuple = array (
                ":bind1" => $_POST['insName'],
                ":bind2" => $_POST['insEmail'],
                ":bind3" => $_POST['insPhone'],
                ":bind4" => $_POST['insAddress'],
            );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("insert into Applicant values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
            echo "<b>SUCCESS</b>: account created!";
            OCICommit($db_conn);
        }

        // INSERT OFFER
        function handleInsertOfferRequest() {
            global $db_conn;
            $tuple = array (
                ":bind1" => $_POST['insNum'],
                ":bind2" => $_POST['insStart'],
                ":bind3" => $_POST['insEmail'],
            );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("insert into AcceptDenyOffer values (:bind1, :bind2, :bind3)", $alltuples);
            OCICommit($db_conn);
        }

        // UPDATE ADDRESS
        function handleAddressUpdateRequest() {
            global $db_conn;
            $old_address = $_POST['oldAddress'];
            $new_address = $_POST['newAddress'];
            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Applicant SET address_='" . $new_address . "' WHERE address_='" . $old_address . "'");
            echo "<b>SUCCESS</b>: address updated!";
            OCICommit($db_conn);
        }

        // UPDATE PHONE
        function handlePhoneUpdateRequest() {
            global $db_conn;
            $old_phone = $_POST['oldPhone'];
            $new_phone = $_POST['newPhone'];
            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Applicant SET phone_num='" . $new_phone . "' WHERE phone_num='" . $old_phone . "'");
            echo "<b>SUCCESS</b>: phone number updated!";
            OCICommit($db_conn);
        }

        // UPDATE OFFER
        function handleDenyUpdateRequest() {
            global $db_conn;
            $emp_num = $_POST['empNum'];
            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("DELETE FROM AcceptDenyOffer 
                WHERE offer_employee_num='" . $emp_num . "'");
            echo "<b>SUCCESS</b>: offer associated with employer ID = $emp_num was deleted";
            OCICommit($db_conn);
        }
        
        // RESET DB
        function handleResetAll() {
            global $db_conn;
            echo "nothing";
            executePlainSQL("START project_tables.sql");
            OCICommit($db_conn);
        }
        // HANDLE ALL POST REQUESTS
        // This is where all post statements will print
        if (isset($_POST['resetAll']) || isset($_POST['insertSubmitInterview']) || isset($_POST['insertSubmitAccount']) || 
            isset($_POST['updateSubmitAddy']) || isset($_POST['updateSubmitPhone']) || isset($_POST['joinForm']) || isset($_POST['filterFind'])) {
            handlePOSTRequest();
        } 
        if (isset($_POST['insertSubmitOffer']) || isset($_POST['updateSubmitDeny'])) {
            handlePOSTRequest();
        } 

        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetAllRequest', $_POST)) {
                    handleResetAllRequest();
                } else if (array_key_exists('updateAddyRequest', $_POST)) {
                    handleAddressUpdateRequest();
                } else if (array_key_exists('updatePhoneQueryRequest', $_POST)) {
                    handlePhoneUpdateRequest(); 
                } else if (array_key_exists('insertInterviewQueryRequest', $_POST)) {
                    handleInsertInterviewRequest();
                } else if (array_key_exists('insertAccountQueryRequest', $_POST)) {
                    handleInsertAccountRequest();
                } else if (array_key_exists('filterCatRequest', $_POST)) {
                    handleFilterRequest();
                } else if (array_key_exists('joinRequest', $_POST)) {
                    handleJoinRequest();
                } else if (array_key_exists('filterFindRequest', $_POST)) {
                    handleFindRequest();
                } else if (array_key_exists('insertOfferQueryRequest', $_POST)) {
                    handleInsertOfferRequest();
                } else if (array_key_exists('updateDenyQueryRequest', $_POST)) {
                    handleDenyUpdateRequest();
                } else if (array_key_exists('resetRequest', $_POST)) {
                    handleResetAll();
                } 
                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
        // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('printJob', $_GET)) {
                    handlePrintJobListing();
                } else if (array_key_exists('printInterview', $_GET)) {
                    handlePrintInterview();
                } else if (array_key_exists('printAccount', $_GET)) {
                    handlePrintAccount();
                } else if (array_key_exists('printApp', $_GET)) {
                    handlePrintPastApplication();
                } else if (array_key_exists('manageAcc', $_GET)) {
                    printManageButtons();
                } else if(array_key_exists('exInfo', $_GET)) {
                    printInfoButtons();
                } else if(array_key_exists('ag', $_GET)) {
                    printAggregateGroup();
                } else if(array_key_exists('ah', $_GET)) {
                    printAggregateHaving();
                } else if(array_key_exists('ns', $_GET)) {
                    printNested();
                } else if(array_key_exists('divi', $_GET)) {
                    printDivision();
                } else if (array_key_exists('printOffer', $_GET)) {
                    handlePrintOffer();
                    acceptDenyButtons();
                }
                disconnectFromDB();
            }
        }
        ?>
    </body>
</html>