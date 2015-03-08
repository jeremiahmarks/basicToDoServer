<?php
session_start();
/**
 * @Author: Jeremiah Marks
 * @Date:   2015-02-16 17:58:39
 * @Last Modified by:   Jeremiah Marks
 * @Last Modified time: 2015-03-08 11:50:44
 */

/**
* This will start as a collection of functions from prior php projects.
**/

include_once 'connection.php';
include_once 'htmlElements.php';

  function add_task($taskname){
    global $conn;
    $error='';
    $stmt = "INSERT INTO tasks (taskname) VALUES ('" . $taskname . "')";
    $res = mysqli_query($conn,$stmt);
  }

  function addNewTag($tagname){
    global $conn;
    $stmt = "INSERT INTO tags (tagname) VALUES ('" . $tagname . "')";
    $res = mysqli_query($conn,$stmt);
  }

  function addTagToTask($tagid, $taskid){
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

  function addProjectToTask($projectid, $taskid){
    global $conn;
    $stmtToCheck = "SELECT COUNT(1) FROM taskProjectAssignments WHERE taskid=" . $taskid . " AND projectid=" . $projectid;
    $result=mysqli_query($conn,$stmtToCheck);
    $dataList= array();
    while ($eachRow = mysqli_fetch_array($result)){
      $dataList[] = $eachRow['COUNT(1)'] ;
    }
    if ($dataList[0]==0) {
      $addStmt= "INSERT INTO taskProjectAssignments (taskid, projectid) VALUES (" . $taskid . ", " . $projectid . ")";
      $res = mysqli_query($conn,$addStmt);;
    }else{
      echo "That Task is  already part of the project.";
    }
  }

  function markTaskAsComplete($taskid){
    global $conn;
    $stmt = "UPDATE tasks SET complete=1 WHERE taskid=" . $taskid;
    $res = mysqli_query($conn,$stmt);
  }

  function removeTagFromTask($tagid, $taskid){
    global $conn;
    $stmt = "DELETE FROM tagapplications WHERE taskid=" . $taskid . " AND tagid=" . $tagid;
    $res = mysqli_query($conn,$stmt);
  }

  function getActiveTasks(){
    global $conn;
    $stmt = "SELECT taskid, taskname FROM tasks WHERE complete=0";
    $dataList = array();
    $results = mysqli_query($conn, $stmt);
    while ($eachRow = mysqli_fetch_array($results))
    {
      $dataList[] = [ $eachRow['taskid'] =>$eachRow['taskname'] ] ;
    }
    return $dataList;

  }
  function addTagsToDisplay($arrayOfTags, $parentTaskID){
    $retStr='';
    if (is_array($arrayOfTags)) {
      foreach ($arrayOfTags as $key => $value) {
        if (is_array($value)){
          $retStr .= addTagsToDisplay($value, $parentTaskID);
        } else {
          $retStr .= '<li class="tagApplied"><span class="tagid">' . $key . '</span> <span class="cell tagname"> ' . $value . '</span><span class="cell removeTag"><input type="checkbox" name="removeTag[]" value="' . $parentTaskID . '_' . $key . '"></span></li>';
        }
      }
    } else {
      $retStr .= '<li>The argument was not an array.</li>';
    }
    //$retStr .= "</ul>";
    return $retStr;
  }
  function printTasks($arr){
    global $conn;
    // $retStr='<ul class="taskList">';
    $retStr='';
    if (is_array($arr)){
      foreach ($arr as $key => $value) {
        if (is_array($value)){
          $retStr .= printTasks($value);
        } else {
          $tagStmt = "SELECT tagapplications.tagid, tags.tagname FROM tagapplications LEFT JOIN tags on tagapplications.tagid = tags.tagid WHERE tagapplications.taskid=".$key;
          $dataList = array();
          $results = mysqli_query($conn, $tagStmt);
          while ($eachRow = mysqli_fetch_array($results)){
            $dataList[$eachRow['tagid']] = $eachRow['tagname'] ;
          }
          $retStr .= '<li class="taskListing"><span class="cell taskid">' . $key . ' </span> <span class="cell markComplete"><input class="looklikex" type="checkbox" name="taskid[]" value="' . $key . '" /></span> <span class="cell taskName" > ' . $value . '</span><span class="cell taglist">';
          $retStr .= '<ul class="tagList">';
          $retStr .= addTagsToDisplay($dataList, $key);
          $retStr .= "</ul></span></li>";
        }
      }
    } else {
      $retStr .= '<li>The argument was not an array.</li>';
    }
    // $retStr .= '</ul>';
    return $retStr;

  }

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
      $dataList[] = $eachRow;
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



  function get_all_tasks(){
    global $conn;
    $res = getAllTasks('tasks', $conn);
    return $res;
  }


  function tagPage(){
    global $conn;
    $res = getAllTags('tags', $conn);
    echo pp($res);
  }

  function main_page(){
    htmlHead();
    bodyStart();
    $currentTasks = getActiveTasks();
    echo '<div class="taskList"><h1>TASK LIST</h1><ul class="allTasks">';
    echo "<form method='post' action=''>";
    echo printTasks($currentTasks);
    echo '<input type="submit" name="markTasksAsDone" value="Complete Tasks">';
    echo '<input type="submit" name="updateTasks" value="Modify Tasks">';
    echo '</ul></div>';
    //tagPage();
    taskAddForm();
    tagAddForm();
    bodyEnd();
  }

  function taskEditPage($taskid){
    global $conn;
    include_once 'header.php';
    include_once 'bodyStart.php';
    include_once 'formStart.php';
    $getTagsStmt = "SELECT tagapplications.tagid as tagid, tags.tagname as tagname FROM tagapplications LEFT JOIN tags on tagapplications.tagid = tags.tagid WHERE tagapplications.taskid=".$taskid;
    $getProjectsStmt = "SELECT taskProjectAssignments.projectid as pid, taskProjects.projectname as pname, taskProjects.projectDescription AS descp FROM taskProjectAssignments LEFT JOIN taskProjects on taskProjectAssignments.projectid = taskProjects.projectid WHERE taskProjectAssignments.taskid=".$taskid;
    $getTaskStmt = "SELECT * FROM tasks WHERE taskid = " .$taskid;
    $taskData=array();
    $tagsOnRecord=array();
    $projectsOnRecord=array();
    $tagResults = mysqli_query($conn, $getTagsStmt);
    while ($eachRow = mysqli_fetch_array($tagResults)){
      # code...
      $tagsOnRecord[$eachRow['tagid']] = $eachRow['tagname'];
    }
    $projectResults = mysqli_query($conn, $getProjectsStmt);
    while ($eachRow = mysqli_fetch_array($projectResults)){
      # code...
      $projectsOnRecord[$eachRow['pid']] = [$eachRow['pname'],$eachRow['descp']];
    }
    $taskResults = mysqli_query($conn, $getTaskStmt);
    while ($eachRow = mysqli_fetch_array($taskResults)){
      $taskData[$eachRow['taskid']] = [$eachRow['taskname'],$eachRow['complete']];
    }
    taskEditView($taskData, $tagsOnRecord, $projectsOnRecord);

    // $stmt="SELECT * FROM tasks LEFT JOIN(tags, tagapplications, taskProjects, taskProjectAssignments)"
    // $stmt .= "ON (tagapplications.taskid=tasks.taskid AND tags."


  }

  function argument_parser(){
    global $conn;
    if (isset($_POST['newTask'])){
      add_task($_POST['name']);
    } elseif (isset($_POST['newTag'])) {
      addNewTag($_POST['name']);
    } elseif (isset($_POST['markTasksAsDone'])) {
      if (isset($_POST['taskid'])){
        foreach ($_POST['taskid'] as $key => $value) {
          markTaskAsComplete($value);
        }
      }
    } elseif (isset($_POST['updateTasks'])) {
      if (isset($_POST['taskid'])){
        foreach ($_POST['taskid'] as $key => $value) {
          taskEditPage($value);
          # code...
        }
        formEnd();
      }
    } elseif (isset($_POST['removeTag'])){
        foreach ($_POST['removeTag'] as $key => $value) {
          $taskAndTag=explode('_', $value);
          removeTagFromTask($taskAndTag[1], $taskAndTag[0]);
        }
    } elseif (isset($_POST['massUpdate'])) {
      $passedKeys = array_keys($_POST);
      foreach ($passedKeys as $key => $value) {
        $taskAndAddon=explode('_', $value);
        if ($taskAndAddon[1]=='name'){
          $updateStatement="UPDATE tasks SET taskname=" . $_POST[$value] . " WHERE taskid=" . $taskAndAddon[0];
          $results = mysqli_query($conn, $updateStatement);
        } elseif ($taskAndAddon[1]=='tags'){
          foreach ($_POST[$value] as $newkey => $newvalue) {
            addTagToTask($newvalue,$taskAndAddon[0]);
          }
        } elseif ($taskAndAddon[1]=='projects'){
          foreach ($_POST[$value] as $newkey => $newvalue) {
            addProjectToTask($newvalue,$taskAndAddon[0]);
          }
        }
      }
      main_page();
    } else {
      main_page();
    }
  }
?>