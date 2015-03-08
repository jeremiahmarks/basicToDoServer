<?php
session_start();
/**
 * @Author: Jeremiah Marks
 * @Date:   2015-02-16 17:58:39
 * @Last Modified by:   Jeremiah Marks
 * @Last Modified time: 2015-03-07 21:43:08
 */

/**
* This will start as a collection of functions from prior php projects.
**/

include_once 'connection.php';
include_once 'htmlElements.php';

  function pp($arr){ /*pretty print*/
    //Provides a pretty way to see what an array contains. 
    //Also is a recursive function.  
      $retStr = '<ul>';
      if (is_array($arr)){
          foreach ($arr as $key=>$val){
              if (is_array($val)){
                  //$retStr .= '<li class="containsSublist"><span class="cell">' . $key . '</span> <span class="cell">' . pp($val) . '</span></li>';
                $retStr .= pp($val);
              }else{
                  $retStr .= '<li class="noList"><span class="cell">' . $key . ' </span> <span class="cell"> ' . $val . '</span></li>';
              }
          }
      } else {
        $retStr .= '<li>The argument was not an array.</li>';
      }
      $retStr .= '</ul>';
      return $retStr;
  }

  function getAllTasks($tablename, $conn){/*gets all of the results from $tablename */
    $query = "SELECT * FROM {$tablename}";
    $dataList= array();
    $results = mysqli_query( $conn , $query );
    while ($eachRow = mysqli_fetch_array($results))
    {
      $dataList[] = [ $eachRow['taskid'] =>$eachRow['taskname'] ] ;
    }
    return $dataList;
    // if($results = mysqli_query( $conn , $query )){
    //   return $results;
    // }
  }

  function getAllTags($tablename, $conn){/*gets all of the results from $tablename */
    $query = "SELECT * FROM {$tablename}";
    $dataList= array();
    $results = mysqli_query( $conn , $query );
    while ($eachRow = mysqli_fetch_array($results))
    {
      $dataList[] = [ $eachRow['tagid'] =>$eachRow['tagname'] ] ;
    }
    return $dataList;
    // if($results = mysqli_query( $conn , $query )){
    //   return $results;
    // }
  }

  function getTasks (){
    global $conn;
    $res = getAllTasks('tasks', $conn);
    echo pp($res);
  }

  function get_tables(){
    global $conn;
    $tableList = array();
    $res = mysqli_query($conn,"SHOW TABLES");
    while($cRow = mysqli_fetch_array($res))
    {
      $tableList[] = $cRow[0];
    }
    echo pp($tableList);
  }

  function add_task($taskname){
    global $conn;
    $error='';
    $stmt = "INSERT INTO tasks (taskname) VALUES ('" . $taskname . "')";
    $res = mysqli_query($conn,$stmt);
  }

  function get_all_tasks(){
    global $conn;
    $res = getAllTasks('tasks', $conn);
    return $res;
  }

  function addNewTag($tagname){
    global $conn;
    $stmt = "INSERT INTO tags (tagname) VALUES ('" . $tagname . "')";
    $res = mysqli_query($conn,$stmt);
  }

  function tagTask($tagid, $taskid){
    global $conn;
    $stmtToCheck = "SELECT COUNT(1) FROM tagapplications WHERE taskid=" . $taskid . " AND tagid=" . $tagid;
    $result=mysqli_query($conn,$stmtToCheck);
    $dataList= array();
    while ($eachRow = mysqli_fetch_array($result)){
      $dataList[] = $eachRow['COUNT(1)'] ;
    }
    if ($dataList[0]==0) {
      $addStmt= "INSERT INTO tagapplications (taskid, tagid) VALUES (" . $taskid . ", " . $tagid . ")";
      $res = mysqli_query($conn,$addStmt);;
    }else{
      echo "That Tag already exists on that task!";
    }
  }

  function tagPage(){
    global $conn;
    echo "I was called!";
    $res = getAllTags('tags', $conn);
    echo pp($res);
  }

  function main_page(){
    htmlHead();
    bodyStart();
    $currentTasks = get_all_tasks();
    echo pp($currentTasks);
    tagPage();
    taskAddForm();
    bodyEnd();
  }

  function argument_parser(){
    if (isset($_POST['newTask'])){
      add_task($_POST['name']);
    }
    main_page();
  }
?>