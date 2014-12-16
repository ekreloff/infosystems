<?php
    $config = parse_ini_file("../config/config.ini");
    $dbhost = $config['host'];
    $dbuser= $config['username'];
    $dbpass = $config['password'];
    $makedb = 1;
    $result = NULL;
    if(isset($_POST['makedb'])){
        $query = "CREATE DATABASE nfl_stats";
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass);
        $mysqli->query($query);
        $makedb = 2;
    }else{
        if(isset($_POST['submit'])){
            $query = $_POST['query'];
            $dbname = $config['dbname'];
            $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            if(mysqli_connect_errno()){
                die("Database connection failed. ".mysqli_connect_error()." : ".mysqli_connect_errno());
            }
            $makedb = 0;
        }else{
            if(isset($_POST['submit2'])){
                $query = $_POST['querylist'];
                $dbname = "nfl_stats";
                $db = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
                if(mysqli_connect_errno()){
                    die("Database connection failed. ".mysqli_connect_error()." : ".mysqli_connect_errno());
                }

                $selectedval = $_POST['querylist'];

                switch($selectedval){
                    case 'Create Tables':
                    {
                        mysqli_query($db, "CREATE TABLE QBs(Name VARCHAR(255), Yards int, Touchdowns int, Interceptions int, Team VARCHAR(255), PRIMARY KEY (Name))");
                        mysqli_query($db, "CREATE TABLE RBs(Name VARCHAR(255), Yards int, Touchdowns int, Fumbles int, Team VARCHAR(255), PRIMARY KEY (Name))");
                        mysqli_query($db, "CREATE TABLE WRs(Name VARCHAR(255), Yards int, Touchdowns int, Drops int, Team VARCHAR(255), PRIMARY KEY (Name))");
                        mysqli_query($db, "CREATE TABLE TeamCoaches(Team VARCHAR(255), QBCoach VARCHAR(255), RBCoach VARCHAR(255), WRCoach VARCHAR(255), PRIMARY KEY (Team))");
                        mysqli_query($db, "CREATE TABLE Coach(CoachName VARCHAR(255), Team VARCHAR(255), Wins int, Losses int, PRIMARY KEY (CoachName))");
                        mysqli_query($db, "CREATE TABLE CoachOffensiveFormations(CoachName VARCHAR(255), OffensiveFormation VARCHAR(255), PRIMARY KEY (CoachName, OffensiveFormation))");
                        mysqli_query($db, "CREATE TABLE CoachDefensiveFormations(CoachName VARCHAR(255), DefensiveFormation VARCHAR(255), PRIMARY KEY (CoachName, DefensiveFormation))");
                        mysqli_query($db, "CREATE TABLE TeamDefense(DefenseName VARCHAR(255), Interceptions int, FumbleRecoveries int, YardsAllowed int, DefensiveCoordinator VARCHAR(255), PRIMARY KEY (DefenseName))");
                        mysqli_query($db, "CREATE TABLE DefensiveCoordinators(DefensiveCoordinator VARCHAR(255), Playbook VARCHAR(255), PRIMARY KEY (DefensiveCoordinator))");
                        mysqli_query($db, "CREATE TABLE TeamOffense(OffenseName VARCHAR(255), Touchdowns int, Yards int, OffensiveRank int, PRIMARY KEY (OffenseName))");
                        mysqli_query($db, "CREATE TABLE Rankings(OverallRank int, OffensiveRank int, DefensiveRank int, PRIMARY KEY (OverallRank))");
                        mysqli_query($db, "CREATE TABLE Stadium(StadiumName VARCHAR(255), TeamName VARCHAR(255), Location VARCHAR(255), DateOpened VARCHAR(255), Occupancy int, PRIMARY KEY (StadiumName))");
                        mysqli_query($db, "CREATE TABLE Jerseys(TeamName VARCHAR(255), HomeJerseyColor VARCHAR(255), PRIMARY KEY (TeamName))");
                        mysqli_query($db, "CREATE TABLE FantasyLeaders(PlayerName VARCHAR(255), Points int, Touchdowns int, PositionRank int, Position VARCHAR(255), PRIMARY KEY (PlayerName))");
                        mysqli_query($db, "CREATE TABLE Positions(Position VARCHAR(255), NumberofPlayersAtPosition int, PRIMARY KEY (Position))");
                        mysqli_query($db, "CREATE TABLE Mascots(Mascot VARCHAR(255), Type VARCHAR(255), Team VARCHAR(255), Age int, PerformerName VARCHAR(255), PRIMARY KEY (Mascot))");
                        mysqli_query($db, "CREATE TABLE MascotNumbers(PerformerName VARCHAR(255), PhoneNumber VARCHAR(255), PRIMARY KEY (PerformerName))");
                        mysqli_query($db, "CREATE TABLE TeamSponsors(SponsorName VARCHAR(255), ContractAmount int, ContractYears int, EarlyTerminationClause bool, TeamOwner VARCHAR(255),PRIMARY KEY (SponsorName))");
                        mysqli_query($db, "CREATE TABLE TeamOwners(TeamOwner VARCHAR(255), OwnerNetWorth VARCHAR(255), PRIMARY KEY (TeamOwner))");

                        mysqli_query($db, "ALTER TABLE QBs ADD FOREIGN KEY (Team) REFERENCES TeamCoaches(Team)");
                        mysqli_query($db, "ALTER TABLE RBs ADD FOREIGN KEY (Team) REFERENCES TeamCoaches(Team)");
                        mysqli_query($db, "ALTER TABLE WRs ADD FOREIGN KEY (Team) REFERENCES TeamCoaches(Team)");
                        mysqli_query($db, "ALTER TABLE Coach ADD FOREIGN KEY (CoachName) REFERENCES CoachOffensiveFormations(CoachName)");
                        mysqli_query($db, "ALTER TABLE Coach ADD FOREIGN KEY (CoachName) REFERENCES CoachDefensiveFormations(CoachName)");
                        mysqli_query($db, "ALTER TABLE TeamDefense ADD FOREIGN KEY (DefensiveCoordinator) REFERENCES DefensiveCoordinators(DefensiveCoordinator)");
                        mysqli_query($db, "ALTER TABLE TeamOffense ADD FOREIGN KEY (OffensiveRank) REFERENCES Rankings(OffensiveRank)");
                        mysqli_query($db, "ALTER TABLE Stadium ADD FOREIGN KEY (TeamName) REFERENCES Jerseys(TeamName)");
                        mysqli_query($db, "ALTER TABLE FantasyLeaders ADD FOREIGN KEY (Position) REFERENCES Positions(Position)");
                        mysqli_query($db, "ALTER TABLE Mascots ADD FOREIGN KEY (PerformerName) REFERENCES MascotNumbers(PerformerName)");
                        mysqli_query($db, "ALTER TABLE TeamSponsors ADD FOREIGN KEY (TeamOwner) REFERENCES TeamOwners(TeamOwner)");

                        $query = "";
                        break;
                    }
                    case 'Insert Data':
                    {
                        mysqli_query($db, 'INSERT INTO TeamCoaches VALUES("Broncos", "Greg Knapp", "Eric Studesville", "Tyke Tolbert")');
                        mysqli_query($db, 'INSERT INTO TeamCoaches VALUES("Packers", "Alex Van Pelt", "Sam Gash", "Edgar Bennett")');
                        mysqli_query($db, 'INSERT INTO TeamCoaches VALUES("Cardinals", "Freddie Kitchens", "Stump Mitchell", "Darryl Drake")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("John Fox", "Shotgun Empty Bronco")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("John Fox", "Shotgun Deuce Slot")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("John Fox", "Singleback Jumbo Z")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("Jim Harbaugh", "Pistol Ace Twins")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("Mike McCarthy", "Gun Wing Offset Weak")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("Mike McCarthy", "Gun Pack Trips")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("Mike McCarthy", "Gun Empty Trey")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("Bruce Arians", "Gun Empty Trey")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("Bruce Arians", "Shotgun Trio")');
                        mysqli_query($db, 'INSERT INTO CoachOffensiveFormations VALUES("Bruce Arians", "Singleback Bunch Base")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("John Fox", "43 Over Bronco")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("John Fox", "Big Dime 3-3-6 Sam")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("John Fox", "Nickel Wide 9")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("Mike McCarthy", "Nickel Psycho")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("Mike McCarthy", "3-4 Solid")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("Mike McCarthy", "Nickel 3-3-5 Wide")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("Bruce Arians", "Big Dime 2-3-6 Will")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("Bruce Arians", "3-4 Under")');
                        mysqli_query($db, 'INSERT INTO CoachDefensiveFormations VALUES("Bruce Arians", "Nickel 2-4-5 Even")');
                        mysqli_query($db, 'INSERT INTO DefensiveCoordinators VALUES("Jack Del Rio", "4-3")');
                        mysqli_query($db, 'INSERT INTO DefensiveCoordinators VALUES("Dom Capers", "3-4")');
                        mysqli_query($db, 'INSERT INTO DefensiveCoordinators VALUES("Dick LeBeau", "3-4")');
                        mysqli_query($db, 'INSERT INTO DefensiveCoordinators VALUES("Dean Pees", "3-4")');
                        mysqli_query($db, 'INSERT INTO Rankings VALUES(4, 4, 4)');
                        mysqli_query($db, 'INSERT INTO Rankings VALUES(15, 6, 25)');
                        mysqli_query($db, 'INSERT INTO Rankings VALUES(14, 1, 27)');
                        mysqli_query($db, 'INSERT INTO Rankings VALUES(12, 9, 15)');
                        mysqli_query($db, 'INSERT INTO Jerseys VALUES("Broncos", "Blue")');
                        mysqli_query($db, 'INSERT INTO Jerseys VALUES("Packers", "Green")');
                        mysqli_query($db, 'INSERT INTO Jerseys VALUES("Eagles", "Green")');
                        mysqli_query($db, 'INSERT INTO Jerseys VALUES("Seahawks", "Blue")');
                        mysqli_query($db, 'INSERT INTO Jerseys VALUES("Browns", "Brown")');
                        mysqli_query($db, 'INSERT INTO Positions VALUES("Quarterback", 32)');
                        mysqli_query($db, 'INSERT INTO Positions VALUES("Running Back", 78)');
                        mysqli_query($db, 'INSERT INTO Positions VALUES("Wide Reciever", 113)');
                        mysqli_query($db, 'INSERT INTO Positions VALUES("Tight End", 48)');
                        mysqli_query($db, 'INSERT INTO MascotNumbers VALUES("Doug Stevens", "(230) 203-0203")');
                        mysqli_query($db, 'INSERT INTO MascotNumbers VALUES("Rick Davis", "(210) 210-0998")');
                        mysqli_query($db, 'INSERT INTO MascotNumbers VALUES("Mike Nickels", "(205) 876-9999")');
                        mysqli_query($db, 'INSERT INTO MascotNumbers VALUES("Gumbo the Dog", "(303) 440-8888")');
                        mysqli_query($db, 'INSERT INTO TeamOwners VALUES("Pat Bowlen", "$500,000,000")');
                        mysqli_query($db, 'INSERT INTO TeamOwners VALUES("Public", NULL)');
                        mysqli_query($db, 'INSERT INTO TeamOwners VALUES("Jerry Richardson", "$250,000,000")');
                        mysqli_query($db, 'INSERT INTO TeamOwners VALUES("Stan Kroenke", "$1,000,000,000")');

                        mysqli_query($db, 'INSERT INTO QBs VALUES("Peyton Manning", 4050, 50, 0, "Broncos")');
                        mysqli_query($db, 'INSERT INTO QBs VALUES("Brock Osweiler", 0, 0, 0, "Broncos")');
                        mysqli_query($db, 'INSERT INTO QBs VALUES("Aaron Rodgers", 5000, 60, 0, "Packers")');
                        mysqli_query($db, 'INSERT INTO QBs VALUES("Matt Flynn", 100, 1, 0, "Packers")');
                        mysqli_query($db, 'INSERT INTO QBs VALUES("Scott Tolzien", 0, 0, 0, "Packers")');
                        mysqli_query($db, 'INSERT INTO RBs VALUES("CJ Anderson", 750, 9, 0, "Broncos")');
                        mysqli_query($db, 'INSERT INTO RBs VALUES("Eddie Lacy", 1200, 13, 0, "Packers")');
                        mysqli_query($db, 'INSERT INTO RBs VALUES("James Starks", 450, 4, 0, "Packers")');
                        mysqli_query($db, 'INSERT INTO RBs VALUES("Andre Ellington", 1357, 6, 1, "Cardinals")');
                        mysqli_query($db, 'INSERT INTO WRs VALUES("Emmanuel Sanders", 1200, 65, 0, "Broncos")');
                        mysqli_query($db, 'INSERT INTO WRs VALUES("Jordy Nelson", 1200, 42, 0, "Packers")');
                        mysqli_query($db, 'INSERT INTO WRs VALUES("Randall Cobb", 1100, 52, 0, "Packers")');
                        mysqli_query($db, 'INSERT INTO WRs VALUES("Larry Fitzgerald", 890, 36, 3, "Cardinals")');
                        mysqli_query($db, 'INSERT INTO Coach VALUES("John Fox", "Broncos", 10, 3)');
                        mysqli_query($db, 'INSERT INTO Coach VALUES("Mike McCarthy", "Packers", 10, 3)');
                        mysqli_query($db, 'INSERT INTO Coach VALUES("Bruce Arians", "Cardinals", 11, 3)');
                        mysqli_query($db, 'INSERT INTO Coach VALUES("Jim Harbaugh", "49ers", 7, 6)');
                        mysqli_query($db, 'INSERT INTO TeamDefense VALUES("Broncos", 9, 5, 4000, "Jack Del Rio")');
                        mysqli_query($db, 'INSERT INTO TeamDefense VALUES("Packers", 13, 5, 4300, "Dom Capers")');
                        mysqli_query($db, 'INSERT INTO TeamDefense VALUES("Ravens", 7, 11, 4100, "Dean Pees")');
                        mysqli_query($db, 'INSERT INTO TeamDefense VALUES("Steelers", 6, 5, 4700, "Dick LeBeau")');
                        mysqli_query($db, 'INSERT INTO TeamOffense VALUES("Broncos", 48, 5273, 4)');
                        mysqli_query($db, 'INSERT INTO TeamOffense VALUES("Packers", 56, 5037, 6)');
                        mysqli_query($db, 'INSERT INTO TeamOffense VALUES("Colts", 65, 5700, 1)');
                        mysqli_query($db, 'INSERT INTO TeamOffense VALUES("Chargers", 48, 5400, 9)');
                        mysqli_query($db, 'INSERT INTO Stadium VALUES("Invesco Field at Mile High", "Broncos", "Denver, CO", "2001", 76125)');
                        mysqli_query($db, 'INSERT INTO Stadium VALUES("City Stadium", "Packers", "Green Bay, WI", "1925", 25000)');
                        mysqli_query($db, 'INSERT INTO Stadium VALUES("Lambeau Field", "Packers", "Green Bay, WI", "1957", 80735)');
                        mysqli_query($db, 'INSERT INTO Stadium VALUES("Lincoln Financial Field", "Eagles", "Philadelphia, PA", "2003", 69176)');
                        mysqli_query($db, 'INSERT INTO Stadium VALUES("Century Link Field", "Seahawks", "Seattle, WA", "2002", 67000)');
                        mysqli_query($db, 'INSERT INTO Stadium VALUES("FirstEnergy Stadium", "Browns", "Cleveland, OH", "1999", 67407)');
                        mysqli_query($db, 'INSERT INTO FantasyLeaders VALUES("Peyton Manning", 300, 50, 3, "Quarterback")');
                        mysqli_query($db, 'INSERT INTO FantasyLeaders VALUES("Aaron Rodgers", 319, 54, 2, "Quarterback")');
                        mysqli_query($db, 'INSERT INTO FantasyLeaders VALUES("Greg Olsen", 117, 8, 2, "Tight End")');
                        mysqli_query($db, 'INSERT INTO FantasyLeaders VALUES("Josh Gordon", 47, 0, 103, "Wide Reciever")');
                        mysqli_query($db, 'INSERT INTO FantasyLeaders VALUES("Matt Forte", 276, 15, 2, "Running Back")');
                        mysqli_query($db, 'INSERT INTO FantasyLeaders VALUES("Russell Wilson", 298, 43, 4, "Quarterback")');
                        mysqli_query($db, 'INSERT INTO FantasyLeaders VALUES("Megatron", 178, 7, 17, "Wide Reciever")');
                        mysqli_query($db, 'INSERT INTO Mascots VALUES("Miles", "Bronco", "Broncos", 8, "Doug Stevens")');
                        mysqli_query($db, 'INSERT INTO Mascots VALUES("Sourdough Sam", "Miner 49er", "49ers", 12, "Rick Davis")');
                        mysqli_query($db, 'INSERT INTO Mascots VALUES("Raider Rusher", "Raider", "Raiders", 2, "Mike Nickels")');
                        mysqli_query($db, 'INSERT INTO Mascots VALUES("Gumbo", "Dog", "Saints", 8, "Gumbo the Dog")');
                        mysqli_query($db, 'INSERT INTO TeamSponsors VALUES("Sports Authority", 5000000, 10, false, "Pat Bowlen")');
                        mysqli_query($db, 'INSERT INTO TeamSponsors VALUES("Miller Light", 1000000, 5, true, "Public")');
                        mysqli_query($db, 'INSERT INTO TeamSponsors VALUES("Verizon", 750000, 3, false, "Public")');
                        mysqli_query($db, 'INSERT INTO TeamSponsors VALUES("Oneida", 650000, 6, false, "Public")');
                        mysqli_query($db, 'INSERT INTO TeamSponsors VALUES("Bank of America", 6050000, 3, false, "Jerry Richardson")');
                        mysqli_query($db, 'INSERT INTO TeamSponsors VALUES("Edward Jones Dome", 7000000, 6, false, "Stan Kroenke")');

                        $query = "";
                        break;
                    }
                    case 'CREATE USER':
                    {
                        $query = "CREATE USER 'test'@'localhost'";
                        break;
                    }
                    case 'DROP USER':
                    {
                        $query = "DROP USER 'test'@'localhost'";
                        break;
                    }
                    case 'ROLLBACK':
                    {
                        break;
                    }
                }

                $makedb = 0;
            }else {
                $query = "";
            }
        }

        if(!empty($query)){
            $result = mysqli_query($db, $query);
            if(!$result){
                echo "Invalid query.";
            }
        }
    }


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head lang="en">
    <meta charset="UTF-8">
    <title>NFL Stats DB</title>
</head>
<body>
    <form action="query.php" method="post" id="query">
        Query String: <input type="text" name="query" value="<?php htmlspecialchars($query)?>" style="width: 600px;"><br/>
        <br/>
        <input type="submit" name="submit" value="Run Query">
        <br/>
        <br/>
        <br/>
        Select Query:
        <select name="querylist" form="query">
            <option value='Create Tables' >Create Tables</option>
            <option value='Insert Data'>Insert Data</option>
            <option value="SELECT * FROM Positions WHERE Position = 'Quarterback';">1) SELECT * FROM Positions WHERE Position = 'Quarterback';</option>
            <option value="SELECT * FROM Coach WHERE Losses < 5 ORDER BY Team;">2) SELECT * FROM Coach WHERE Losses < 5 ORDER BY Team;</option>
            <option value="SELECT PlayerName, Points FROM FantasyLeaders WHERE Points > 100 ORDER BY Points DESC LIMIT 3;">
                3) SELECT PlayerName, Points FROM FantasyLeaders WHERE Points > 100 ORDER BY Points DESC LIMIT 3;</option>
            <option value="SELECT TeamCoaches.RBCoach, RBs.* FROM TeamCoaches, RBs WHERE TeamCoaches.Team = RBs.Team;">
                4) SELECT TeamCoaches.RBCoach, RBs.* FROM TeamCoaches, RBs WHERE TeamCoaches.Team = RBs.Team;</option>
            <option value="SELECT CoachName FROM CoachDefensiveFormations WHERE CoachName NOT LIKE 'John Fox' GROUP BY CoachName;">
                5) SELECT CoachName FROM CoachDefensiveFormations WHERE CoachName NOT LIKE 'John Fox' GROUP BY CoachName;</option>
            <option value="SELECT Team, SUM(Yards) AS sum FROM QBs WHERE Yards > 0 GROUP BY Team HAVING sum >= 5000;">
                6) SELECT Team, SUM(Yards) AS sum FROM QBs WHERE Yards > 0 GROUP BY Team HAVING sum >= 5000;</option>
            <option value="select from where two implied joins">
                7) select from where two implied joins</option>
            <option value="SELECT StadiumName, DateOpened FROM Stadium WHERE StadiumName NOT IN (SELECT StadiumName FROM Stadium WHERE Occupancy > 50000);">
                8) SELECT StadiumName, DateOpened FROM Stadium WHERE StadiumName NOT IN (SELECT StadiumName FROM Stadium WHERE Occupancy > 50000);</option>
            <option value="SET command with nontrivial where">
                9) SET command with nontrivial where</option>
            <option value='UPDATE Stadium SET StadiumName = "Sports Authority Field" WHERE (Occupancy >= 60000 && Occupancy <= 80000) && StadiumName RLIKE BINARY "^I";'>
                10) UPDATE Stadium SET StadiumName = "Sports Authority Field" WHERE (Occupancy >= 60000 && Occupancy <= 80000) && StadiumName RLIKE BINARY "^I";</option>
            <option value="CREATE USER">11) CREATE USER ‘test’@‘localhost’;</option>
            <option value="DROP USER">12) DROP USER ‘test’@‘localhost’;</option>
            <option value="ROLLBACK">13) START TRANSACTION, ROLLBACK</option>

        </select>
        <br/>
        <br/>
        <input type="submit" name="submit2" value="Run Query">
        <br/>
        <br/>
        <?php
            if($result && $result !== TRUE){
                $result_string = "<br/>Query Result: <br/><br/>";
                while($result_row = mysqli_fetch_row($result)){
                     $result_string .= join(' <b>|</b> ', $result_row) . "<br/>";
                }
                echo $result_string . "<br/><br/>";
            }
        ?>
        <b>Database State:</b>
        <hr/>

        <?php
            switch($makedb){
                case 0:
                {
                    $query_all = "SHOW TABLES FROM nfl_stats";
                    $tables = mysqli_query($db, $query_all);
                    if($tables){
                        while($table_row = mysqli_fetch_row($tables)) {
                            $table = $table_row[0];
                            echo "<b><u>" . $table . "</b></u><br/>";

                            $query_columns = "SHOW COLUMNS FROM ";
                            $query_columns .= "{$table}";
                            $columns = mysqli_query($db, $query_columns);
                            while ($column = mysqli_fetch_array($columns)) {
                                echo "<b>" . $column[0]." | </b>";
                            }

                            echo "<br/><br/>";

                            $query_rows = "SELECT * FROM ";
                            $query_rows .= "{$table}";
                            $rows = mysqli_query($db, $query_rows);
                            while($row = mysqli_fetch_array($rows, MYSQLI_NUM)){
                                echo join(' <b>|</b> ', $row) . "<br/>";
                            }

                            echo "<hr/>";
                        }

                        if(!$tables === TRUE){
                            mysqli_free_result($tables);
                        }
                    }
                    break;
                }
                case 1:
                {
                    ?>
                    <input type="submit" name="makedb" value="Create Database 'nfl_stats'">
                    <?php
                    break;
                }
                case 2:
                {
                    echo "Database 'nfl_stats' Created. Enter a query.";
                }
            }
        ?>

    </form>
</body>
</html>

<?php
    if(!$makedb){
        mysqli_close($db);
    }
?>