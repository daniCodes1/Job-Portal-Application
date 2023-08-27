<?php
// ADAPTED FROM oracle-test.php 
//this tells the system that it's no longer just parsing html; it's now parsing PHP

//keep track of errors so it redirects the page only if there are no errors
$success = True; 

// edit the login credentials in connectToDB()
$db_conn = NULL; 

// set to True if you want alerts to show you which methods are being triggered 
// (see how it is used in debugAlertMessage())
$show_debug_alert_messages = False; 

function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,



    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}

function getDefaultResumeTuples() {
    $resumeTuple = array (":bind1" => "111", ":bind2" => "Yan", ":bind3" => "Groundbreaking database research", ":bind4" => "Undergrad, Masters, PHD: Harvard, Additional AWS certifications");
    $resumeTuple2 = array (":bind1" => "222", ":bind2" => "Yan", ":bind3" => "25 Microsoft internships", ":bind4" => "Undergrad, Masters, PHD: Harvard, 60 coding bootcamps");
    $resumeTuple3 = array (":bind1" => "333", ":bind2" => "Yan", ":bind3" => "50 years of experience with C++", ":bind4" => "Undergrad, Masters, PHD: Harvard, Certified Analytics Professional accredication");
    $allRestuples = array ($resumeTuple, $resumeTuple2, $resumeTuple3);
    return $allRestuples;
}

function getDefaultCoverTuples() {
    $coverTuple = array (":bind1" => "111", ":bind2" => "I saw this job and thought I was a really good fit. I want this job.");
    $coverTuple2 = array (":bind1" => "222", ":bind2" => "I am most qualified. I have a lot of experience in this field.");
    $coverTuple3 = array (":bind1" => "333", ":bind2" => "I love databases. Databases are life-changing and I can help you change more lives!!!!!");
    $allCovertuples = array ($coverTuple, $coverTuple2, $coverTuple3);
    return $allCovertuples;
}
function getDefaultJobTuples() {
    $jobTuple1 = array (":bind1" => "Assistant Chef", ":bind2" => "00001", ":bind3" => "5",":bind4" => "40534", ":bind5" => "Part",
                ":bind6" => "<ul> <li>Food Safe Level 3</li> <li> 2 years of kitchen prep </li></ul>",
                ":bind7" => "<ul> <li>Kitchen food prep</li> <li> assist in creation of new menu </li></ul>" );
    $jobTuple2 = array (":bind1" => "Head Janitor", ":bind2" => "00002", ":bind3" => "2",":bind4" => "49998", ":bind5" => "Full",
        ":bind6" => "<ul><li> 2 years of janitoring </li></ul>",
        ":bind7" => "<ul><li> cleaning of the entire 30 floor office tower </li></ul>" );
    $jobTuple3 = array (":bind1" => "Software Enginner", ":bind2" => "00003", ":bind3" => "20",":bind4" => "90615", ":bind5" => "Full",
        ":bind6" => "<ul><li> 100 years of related experience </li></ul>",
        ":bind7" => "<ul><li> make whatever that makes big money </li></ul>" );
    $jobTuple4 = array (":bind1" => "Customer Service Rep", ":bind2" => "00004", ":bind3" => "20",":bind4" => "62983", ":bind5" => "Full",
        ":bind6" => "<ul><li> 2 years of related experience</li></ul>",
        ":bind7" => "duti<ul><li> be nice to customers </li></ul>es" );
    $jobTuple5 = array (":bind1" => "Database Intern", ":bind2" => "00005", ":bind3" => "30",":bind4" => "0", ":bind5" => "Part",
        ":bind6" => "<ul><li> none required </li></ul>",
        ":bind7" => "<ul><li> learn and not get paid unlucky </li></ul>" );
    $jobTuple6 = array (":bind1" => "Marketing Analyst", ":bind2" => "00006", ":bind3" => "10",":bind4" => "76526", ":bind5" => "Part",
        ":bind6" => "<ul><li> 2 years of related experience </li></ul>",
        ":bind7" => "<ul><li> analyze the market </li></ul>" );
    $jobTuple7 = array (":bind1" => "Sales Assistant", ":bind2" => "00007", ":bind3" => "15",":bind4" => "54219", ":bind5" => "Part",
        ":bind6" => "<ul><li> 2 years of related experience </li></ul>",
        ":bind7" => "duti<ul><li> assist in sales? </li></ul>es" );
    $allJobtuples = array ($jobTuple1, $jobTuple2, $jobTuple3, $jobTuple4, $jobTuple5, $jobTuple6, $jobTuple7);
    return $allJobtuples;
}

function getDefaultInterviewTuples() {
    $interviewTuple1 = array (":bind1" => "Elon Musk", ":bind2" => "Yan", ":bind3" => "120923");
    $interviewTuple2 = array (":bind1" => "Steve Jobs", ":bind2" => "Yan", ":bind3" => "121023");
    $interviewTuple3 = array (":bind1" => "Bill Gates", ":bind2" => "Yan", ":bind3" => "120923");
    $interviewTuple4 = array (":bind1" => "Elon Musk", ":bind2" => "Yan", ":bind3" => "122323");
    $interviewTuple5 = array (":bind1" => "Mark Zuckerberg", ":bind2" => "Yan", ":bind3" => "122323");
    $allInterviewtuples = array ($interviewTuple1, $interviewTuple2, $interviewTuple3, $interviewTuple4, $interviewTuple5);
    return $allInterviewtuples;
}

function getDefaultAccountTuples() {
    $accountTuple = array (":bind1" => "Yan", ":bind2" => "yan@gmail.com", 
    ":bind3" => "778-866-9999", ":bind4" => "123 TA Street", ":bind5" => "1");
    $accountTuple2 = array (":bind1" => "Yan", ":bind2" => "yan@gmail.com", 
    ":bind3" => "778-866-9999", ":bind4" => "123 TA Street", ":bind5" => "2");
    $accountTuple3 = array (":bind1" => "Yan", ":bind2" => "yan@gmail.com", 
    ":bind3" => "778-866-9999", ":bind4" => "123 TA Street", ":bind5" => "3");
    $allAccounttuples = array ($accountTuple, $accountTuple2, $accountTuple3);
    return $allAccounttuples;
}

function getDefaultAppTuples() {
    $accountTuple = array (":bind1" => "111", ":bind2" => "20001219", ":bind3" => "1");
    $accountTuple2 = array (":bind1" => "222", ":bind2" => "20001219", ":bind3" => "2");
    $accountTuple3 = array (":bind1" => "333", ":bind2" => "20001219", ":bind3" => "3");
    $allApptuples = array ($accountTuple, $accountTuple2, $accountTuple3);
    return $allApptuples;
}

// UNUSED FUNCTIONS KEPT HERE FOR BACKUP
function handleResetJobRequest() {
    $jobTuple1 = array (":bind1" => "Assistant Chef", ":bind2" => "00001", ":bind3" => "5",b":bind4" => "40534", ":bind5" => "Part",
        ":bind6" => "<ul> <li>Food Safe Level 3</li> <li> 2 years of kitchen prep </li></ul>",
        ":bind7" => "<ul> <li>Kitchen food prep</li> <li> assist in creation of new menu </li></ul>" );
    $jobTuple2 = array (":bind1" => "Head Janitor", ":bind2" => "00002", ":bind3" => "2",b":bind4" => "49998", ":bind5" => "Full",
        ":bind6" => "<ul><li> 2 years of janitoring </li></ul>",
        ":bind7" => "<ul><li> cleaning of the entire 30 floor office tower </li></ul>" );
    $jobTuple3 = array (":bind1" => "Software Enginner", ":bind2" => "00003", ":bind3" => "20",b":bind4" => "90615", ":bind5" => "Full",
        ":bind6" => "<ul><li> 100 years of related experience </li></ul>",
        ":bind7" => "<ul><li> make whatever that makes big money </li></ul>" );
    $jobTuple4 = array (":bind1" => "Customer Service Rep", ":bind2" => "00004", ":bind3" => "20",b":bind4" => "62983", ":bind5" => "Full",
        ":bind6" => "<ul><li> 2 years of related experience</li></ul>",
        ":bind7" => "duti<ul><li> be nice to customers </li></ul>es" );
    $jobTuple5 = array (":bind1" => "Database Intern", ":bind2" => "00005", ":bind3" => "30",b":bind4" => "0", ":bind5" => "Part",
        ":bind6" => "<ul><li> none required </li></ul>",
        ":bind7" => "<ul><li> learn and not get paid unlucky </li></ul>" );
    $jobTuple6 = array (":bind1" => "Marketing Analyst", ":bind2" => "00006", ":bind3" => "10",b":bind4" => "76526", ":bind5" => "Part",
        ":bind6" => "<ul><li> 2 years of related experience </li></ul>",
        ":bind7" => "<ul><li> analyze the market </li></ul>" );
    $jobTuple7 = array (":bind1" => "Sales Assistant", ":bind2" => "00007", ":bind3" => "15",b":bind4" => "54219", ":bind5" => "Part",
        ":bind6" => "<ul><li> 2 years of related experience </li></ul>",
        ":bind7" => "duti<ul><li> assist in sales? </li></ul>es" );
    $allJobtuples = array ($jobTuple1, $jobTuple2, $jobTuple3, $jobTuple4, $jobTuple5, $jobTuple6, $jobTuple7);
    global $db_conn;
    // Drop old table
    executePlainSQL("DROP TABLE jobTable");
    executePlainSQL("DROP TABLE storeTable");
    executePlainSQL("DROP TABLE coverTable");
    executePlainSQL("DROP TABLE resumeTable");
    echo "<br> all old tables dropped <br>";
    // Create new table
    echo "<br> creating new tables <br>";
    executePlainSQL("CREATE TABLE jobTable (position char(30), referenceID char(30) PRIMARY KEY, spots_left int, annual_salary int, work_type char(30), qualification char(100), duty char(100))");
    executeBoundSQL("insert into jobTable values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $allJobtuples);
    echo "<br> default tuples inserted <br>";
    executePlainSQL("CREATE TABLE storeTable (app_num int PRIMARY KEY, apply_date int)");
    executePlainSQL("CREATE TABLE coverTable (app_num int PRIMARY KEY, introduction char(300))");
    executePlainSQL("CREATE TABLE resumeTable (app_num int PRIMARY KEY, name char(30), experience char(300), education char(300))");
    echo "<br> tables created for store, cover, resume created <br>";
    OCICommit($db_conn);
}

function handleResetInterviewRequest() {
    $interviewTuple1 = array (":bind1" => "Elon Musk", ":bind2" => "Yan", b":bind3" => "120923");
    $interviewTuple2 = array (":bind1" => "Steve Jobs", ":bind2" => "Yan", b":bind3" => "121023");
    $interviewTuple3 = array (":bind1" => "Bill Gates", ":bind2" => "Yan", b":bind3" => "120923");
    $interviewTuple4 = array (":bind1" => "Elon Musk", ":bind2" => "Yan", b":bind3" => "122323");
    $interviewTuple5 = array (":bind1" => "Mark Zuckerberg", ":bind2" => "Yan", b":bind3" => "122323");
    $allInterviewtuples = array ($interviewTuple1, $interviewTuple2, $interviewTuple3, $interviewTuple4, $interviewTuple5);
    global $db_conn;
    // Drop old table
    executePlainSQL("DROP TABLE interviewTable");
    echo "<br> old interview table dropped <br>";
    // Create new table
    echo "<br> creating new table <br>";
    executePlainSQL("CREATE TABLE interviewTable (interviewer char(30), interviewee char(30), intDate int, PRIMARY KEY (interviewer, interviewee, intDate))");
    echo "<br> new table created <br>";
    executeBoundSQL("insert into interviewTable values (:bind1, :bind2, :bind3)", $allInterviewtuples);
    echo "<br> default tuples inserted <br>";
    OCICommit($db_conn);
    }

function handleResetAccountRequest() {
    $accountTuple = array (":bind1" => "Yan", ":bind2" => "yan@gmail.com", ":bind3" => "778-866-9999", ":bind4" => "123 TA Street", b":bind5" => "123456");
    $allAccounttuples = array ($accountTuple);
    global $db_conn;
    // Drop old table
    executePlainSQL("DROP TABLE accountTable");
    echo "<br> old account table dropped <br>";
    // Create new table
    echo "<br> creating new table <br>";
    executePlainSQL("CREATE TABLE accountTable (name char(30), email char(30), phone_number char(30), address char(100), account_number int PRIMARY KEY)");
    echo "<br> new table created <br>";
    executeBoundSQL("insert into accountTable values (:bind1, :bind2, :bind3, :bind4, :bind5)", $allAccounttuples);
    echo "<br> default tuples inserted <br>";
    OCICommit($db_conn);
}

// PRINT TABLE COVER LETTER 
function printApplicationCover($result) {
    echo "<br><b>Retrieved tableData from table coverTable:</b><br><br>";
    echo "<table>";
    echo "<tr><th>Application #</th><th>Intro</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>";
        echo $row["JOB_APP_NUM_CV"] . "</td><td>" . $row["INTRODUCTION"] . "</td></tr>";
    }
    echo "</table>";
}
// PRINT TABLE RESUME
function printApplicationResume($result) {
    echo "<br><b>Retrieved tableData from table resumeTable:</b><br><br>";
    echo "<table>";
    echo "<tr><th>Application #</th><th>Name</th><th>Experience</th><th>Education</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>";
        echo $row["JOB_NUM"] . "</td><td>" . $row["RESNAME"] . "</td><td>" . $row["EXPERIENCE"] . "</td><td>" . $row["EDUCATION"] . "</td></tr>";
    }
    echo "</table>";
}

?>