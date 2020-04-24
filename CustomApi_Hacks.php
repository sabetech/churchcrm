<?php
// $allowedOrigins = array(
//     '(http(s)://)?(www\.)?my\-domain\.com'
//   );
   
//   if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
//     foreach ($allowedOrigins as $allowedOrigin) {
//       if (preg_match('#' . $allowedOrigin . '#', $_SERVER['HTTP_ORIGIN'])) {
//         header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
//         header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
//         header('Access-Control-Max-Age: 1000');
//         header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
//         break;
//       }
//     }
//   }



//this page is for custom api hacks with no need for authentication ...
function executeSQLQuery($sSQL){
    $mysqli = new mysqli("localhost","root","blender3D","blender3D");
    
    if ($result = $mysqli -> query($sSQL)) {
        $mysqli->close();
        return $result;
    }
}

function getGroupsInJSON($search = ""){

    $sSQL = "SELECT `grp_ID` AS id, `grp_Name` AS `text` FROM group_grp WHERE grp_Name LIKE '%$search%' LIMIT 10";

    $result = executeSQLQuery($sSQL);

    $dataArray = $result -> fetch_all(MYSQLI_ASSOC);

    return json_encode($dataArray, JSON_PRETTY_PRINT);

}

function searchGroupInJSON(){
    $searchText = $_GET['search_text'];
    var_dump($searchText);
}

function getGroupMembers($grpID){
    $sSQL = "SELECT person_per.per_ID, group_grp.grp_Name, person_per.per_FirstName, person_per.per_LastName  FROM group_grp INNER JOIN person2group2role_p2g2r ON group_grp.grp_ID = person2group2role_p2g2r.p2g2r_grp_ID
                INNER JOIN person_per ON person_per.per_ID = person2group2role_p2g2r.p2g2r_per_ID
                WHERE group_grp.grp_ID = '$grpID'";

    $result = executeSQLQuery($sSQL);
    $dataArray = $result -> fetch_all(MYSQLI_ASSOC);

    return json_encode($dataArray, JSON_PRETTY_PRINT);
}


if (isset($_GET['getallgroups'])){
    $myJSON = getGroupsInJSON($_GET['search']);
    echo $myJSON;
}

if (isset($_GET['getgroup'])){

    $grp_id = $_GET['id'];
    $myJSON = getGroupMembers($grp_id);
    echo $myJSON;
}

if (isset($_POST['save_attendance'])){

    $grp_id = $_POST['id'];
    var_dump($_POST);

    //save attendance data here ..


}
    






?>