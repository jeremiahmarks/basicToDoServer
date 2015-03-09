<?php
/**
 * @Author: Jeremiah Marks
 * @Date:   2015-03-07 19:28:55
 * @Last Modified by:   Jeremiah Marks
 * @Last Modified time: 2015-03-08 11:35:14
 */

function htmlHead(){
    ?>
    <head>
        <meta name="viewport" content="width=device-width" />
        <title>
            Basic todo list for jlmarks
        </title>
        <script type="text/javascript" src="https://if188.infusionsoft.com/app/webTracking/getTrackingCode?trackingId=c7a7941f01a1106bc621716f90f98391"></script>
        <link rel='stylesheet' id='style-css'  href='./style.css' type='text/css' media='all' />
    </head>
    <?php
}

function bodyStart(){
    ?>
    <body>
    <?php 
    include_once("analytics.pw.php");
}

function footer(){

}

function bodyEnd(){
    footer();
    ?>
    </body>
    <?php
}

function taskAddForm(){
    ?>
    <div class="newTask">
        <form method='post' action=''>
            <table class='newTaskTable'>
                <tr>
                    <td colspan="2"><h2>Add a Task!</h2></td>
                </tr>
              <tr>
                <td>Task Name</td>
                <td><input type='text' name='name'></td>
              </tr>
              <tr>
                <td colspan="2"><input type="submit" name="newTask" value="New Task"></td>
              </tr>
            </table>
        </form>
    </div>
    <?php
}

function tagAddForm(){
    ?>
    <div class="newTag">
        <form method='post' action=''>
            <table class="newTagTable">
                <tr>
                    <td colspan="2"><h2>Add a tag!</h2></td>
                </tr>
                <tr>
                    <td>Tag Name</td>
                    <td><input type='text' name='name'></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="newTag" value="New Tag"></td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}

function taskEditView($taskData, $tagsOnRecord, $projectsOnRecord){
    $taskid = array_keys($taskData)[0];
    $taskname = $taskData[$taskid][0];
    $taskCompletion = $taskData[$taskid][1];
    ?>
    <div class="editTask">
        <h3>Task Record</h3>
        <label>Task Name: <br>
            <input 
                type="text" 
                name="<?php echo $taskid;?>_name" 
                value="<?php echo $taskname;?>"
                class="tasknameInput"
            />
        </label>
        <span class="compMover">
            <label>Completed: 
                <input 
                    type="checkbox" 
                    name="completed[]" 
                    value="<?php echo $taskid;?>" 
                    <?php 
                        $echoVal = ($taskCompletion == 1) ? "checked": ""; 
                        echo $echoVal;
                    ?> />
            </label>
        </span>
        <div class="tagArea">
            <h3>Tags</h3>
            <div class="tagHolder">
                <?php
                foreach ($tagsOnRecord as $key => $value) {
                    ?>
                    <div class="tagApplied">
                        <span class="tagid">
                            <?php echo $key; ?>
                        </span>
                        <span class="tagName">
                            <?php echo $value; ?>
                        </span>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="tagPicker">
                <h4>Tags to apply</h4>
                <?php
                listOfAvailableTags($taskid);
                ?>
            </div>
        </div>
        <div class="projectArea">
            <h3>Projects</h3>
            <div class="projectHolder">
                <?php
                foreach ($projectsOnRecord as $key => $value) {
                    ?>
                    <div class="partOfProject">
                        <span class="projectid">
                            <?php echo $key; ?>
                        </span>
                        <span class="projectName">
                            <?php echo $value[0]; ?>
                        </span>
                        <span class="projectDescp">
                            <?php echo $value[1]; ?>
                        </span>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="projectPicker">
                <?php
                listOfAvailableProjects($taskid);
                ?>
            </div>
        </div>
    </div>
    <?php
}

function listOfAvailableTags($taskid){
    global $conn;
    $tagStmt = "SELECT * from tags";
    $tagsData=array();
    $tagResults = mysqli_query($conn, $tagStmt);
    while ($eachRow = mysqli_fetch_array($tagResults)){
      # code...
      $tagsData[$eachRow['tagid']] = $eachRow['tagname'];
    }

    ?>
    <select 
        name="<?php echo $taskid; ?>_tags[]"
        multiple
        size="10"
        class="tagList"
    >
        <?php
        foreach ($tagsData as $key => $value) {
            ?>
            <option value="<?php echo $key; ?>">
                <?php echo $value; ?>
            </option>
            <?php
        }
        ?>
    </select>
    <?php
}

function listOfAvailableProjects($taskid){
    global $conn;
    $projectStatement = "SELECT * from taskProjects";
    $projectData=array();
    $projectResults = mysqli_query($conn, $projectStatement);
    while ($eachRow = mysqli_fetch_array($projectResults)){
      # code...
      $projectData[$eachRow['projectid']] = $eachRow['projectname'];
    }

    ?>
    <select 
        name="<?php echo $taskid; ?>_projects[]"
        multiple
        size="10"
        class="projectList"
    >
        <?php
        foreach ($projectData as $key => $value) {
            ?>
            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}
