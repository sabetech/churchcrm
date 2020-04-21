<?php
    //this script is read f file from a custom folder: ChurchCRM/custom_file_store and then populate the db ...

    function executeSQLQuery($sSQL){
        $mysqli = new mysqli("localhost","root","blender3D","church_crm");
        
        if ($result = $mysqli -> query($sSQL)) {
            $mysqli->close();
            return $result;
        }
    }

    echo "Importing People ..." . PHP_EOL;
    
    $path  = realpath('custom_file_store/Importing_Wacenta_People_OP_August_2020.csv');
    
    echo "Full Path {$path}" . PHP_EOL;

    $row = 0;
    $countWacentaLeaders = 0;
    $_currentGroupID = 0;

    $saveWacentaLeader = true;
        //read the csv file and map the pictures also in the database ... 
        if (($handle = fopen($path, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $row++;
                if ($row == 1) continue;

                if (trim($data[0]) == ""){
                    $saveWacentaLeader = true;
                    continue;
                }
                $per_Title = "";
                $names = explode(" ", $data[0]);
                $per_FirstName = $names[0];
                $per_LastName = isset($names[1]) ? $names[1] : "";
                $_groupName = trim($data[0]).'_Wacenta';

                

                if ($saveWacentaLeader){
                    $newLstID = getLatestLstID() + 1;
                    $insertList_lstSQL = "INSERT INTO list_lst (`lst_ID`,
                                                                `lst_OptionID`,
                                                                `lst_OptionSequence`,
                                                                `lst_OptionName`) 
                                                        VALUES (
                                                            $newLstID,
                                                            1,
                                                            1,
                                                            'Member'
                                                        )";

                    executeSQLQuery($insertList_lstSQL);
                    
                    $latestLst_ID = getLatestLstID();
                    $insertGroupSQL = "INSERT INTO group_grp (`grp_Type`, 
                                                              `grp_RoleListID`
                                                              `grp_DefaultRole`, 
                                                              `grp_Name`,
                                                              `grp_Description`,
                                                              `grp_hasSpecialProps`,
                                                              `grp_active`,
                                                              `grp_include_email_export`)
                                                  VALUES (
                                                        1, 
                                                        '$latestLst_ID'
                                                        1, 
                                                        '$_groupName',
                                                        'Wacenta Group For $data[0]',
                                                        0,
                                                        1,  
                                                        1
                                                        )";

                    echo "Wacenta Leader detected {$data[0]} " . PHP_EOL;
                    
                    echo $insertGroupSQL . PHP_EOL;

                    executeSQLQuery($insertGroupSQL);

                    $countWacentaLeaders++;
                    $saveWacentaLeader = false;

                    $per_Title = "Wacenta Leader";

                    $_currentGroupID = getLatestGroupID();
                }
                
                //before you save the new set, great a group with the wacenta leader's name ...
                $insertPersonSQL = "INSERT INTO person_per (`per_Title`,
                                                            `per_FirstName`,
                                                            `per_LastName`,
                                                            `per_DateEntered`,
                                                            `per_EnteredBy`)
                                                VALUES (
                                                    '$per_Title',
                                                    '$per_FirstName',
                                                    '$per_LastName',
                                                    NOW(),
                                                    1
                                                )";
                
                executeSQLQuery($insertPersonSQL);

                //assign person to group ...
                createPersonGroupRelation(($saveWacentaLeader) ? 2:1, $_currentGroupID);

            }
            
            echo "Wacenta Leader Count {$countWacentaLeaders}";
        }

        function createPersonGroupRelation($wacenta_leader_Flag, $_currentGroupID){
            
            $_personID = getLatestPersonID();

            $sSQL = "INSERT INTO person2group2role_p2g2r (`p2g2r_per_ID`,
                                                          `p2g2r_grp_ID`,
                                                          `p2g2r_rle_ID`)
                                                          VALUES
                                                          (
                                                              '$_personID',
                                                              '$_currentGroupID',
                                                              '$wacenta_leader_Flag'
                                                           )";
            echo $sSQL . PHP_EOL;
            executeSQLQuery($sSQL);
        }

        function getLatestGroupID(){
            $sSQL = "SELECT MAX(`grp_id`) FROM group_grp";
            $result = executeSQLQuery($sSQL);
            // Numeric array
            $row = $result -> fetch_array(MYSQLI_NUM);
            return $row[0];
        }

        function getLatestPersonID(){
            $sSQL = "SELECT MAX(`per_ID`) FROM person_per";
            $result = executeSQLQuery($sSQL);
            // Numeric array
            $row = $result -> fetch_array(MYSQLI_NUM);
            return $row[0];
        }


        function getLatestLstID(){
            $sSQL = "SELECT MAX(`lst_ID`) FROM list_lst";
            $result = executeSQLQuery($sSQL);
            // Numeric array
            $row = $result -> fetch_array(MYSQLI_NUM);
            return $row[0];
        }

?>

