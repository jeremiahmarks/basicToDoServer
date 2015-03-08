<?php
/**
 * @Author: Jeremiah Marks
 * @Date:   2015-03-07 19:28:55
 * @Last Modified by:   Jeremiah Marks
 * @Last Modified time: 2015-03-07 19:53:44
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
    <form method='post' action=''>
        <table class='newproject'>
          <tr>
            <td>Task Name</td>
            <td><input type='text' name='name'></td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" name="newTask"></td>
          </tr>
        </table>
    </form>
    <?php
}