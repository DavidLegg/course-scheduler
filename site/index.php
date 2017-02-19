<?php
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    //    phpinfo();
    
    //    if (!isset($_SESSION['availableCourses']))
    //        $_SESSION['availableCourses'] = array();
    //    if (!isset($_SESSION['addedCourses']))
    //        $_SESSION['addedCourses'] = array();
    require_once('scheduler/controller.php');
    
    $first = true;
    
    ?>
<html>
    
    <head>
        <title>ZotScheduler</title>
        <meta name="theme-color" content="#eeee22">
            <meta name="viewport" content="width=device-width">
                <link rel="stylesheet" type="text/css" href="style.css">
                    <link rel="stylesheet" id="screenr-fonts-css" href="https://fonts.googleapis.com/css?family=Open%2BSans%3A400%2C300%2C300italic%2C400italic%2C600%2C600italic%2C700%2C700italic%7CMontserrat%3A400%2C700&amp;subset=latin%2Clatin-ext" type="text/css" media="all">
                        <link rel="stylesheet" type="text/css" href="libs/fullcalendar/fullcalendar.css">
                            <script src='libs/fullcalendar/lib/jquery.min.js'></script>
                            <script type="text/javascript" src='libs/schedulerjs/functions.js' > </script>
                            
                            </head>
    
    <body>
        <header id="header">
            <div id="head_container">
                <div id="home"><a href="index.php">ZotScheduler</a></div>
                <div id="nav_bar">
                    <ul id="nav">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="help.php">Help</a></li>
                    </ul>
                </div>
            </div>
            
        </header>
        <div id="content">
            <div id="container">
                <div class="columns">
                    <div id="home-top-left" class="left">
                        <div class="box">
                            <h2>Select your courses</h2>
                            
                            <table class="course_choice">
                                <tr>
                                    <td class="courses_1">Term:</td>
                                    <td class="courses_2">
                                        <?php
                                            echo '<select name="YearTerm" id="term" onChange="populateCourses();" class="class_select">';
                                            $options = getDropDownItems('YearTerm');
                                            foreach($options as $option){
                                                echo $dom->saveHTML($option);
                                            }
                                        echo '</select>';
                                            ?>
                                        
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="courses_1">Department:</td>
                                    <td class="courses_2">
                                        <?php
                                            echo '<select name="Dept" id="dept" onChange="populateCourses();" class="class_select">';
                                            $options = getDropDownItems('Dept');
                                            $optCount = 0;
                                            foreach($options as $option){
                                                if ($optCount++>0) echo preg_replace('((\W?[.]\W?)+)',': ',$dom->saveHTML($option));
                                            }
                                        echo '</select>';
                                        echo "<script>
                                        var sel = document.getElementById('dept');
                                        populateCourses(sel.value);
                                        </script>";
                                            ?>
                                        
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="courses_1">
                                        Course:
                                    </td>
                                    <td class="courses_2">
                                        <select name="Course" id="course" class="class_select">
                                        </select>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="courses_1">
                                    </td>
                                    <td class="courses_2" style="text-align:right">
                                        <button type="button" onClick="populateAddedCourses(document.getElementById('course').value)">Select Course</button>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                        <div class="box">
                            <h2>Select your preferences</h2>
                            
                            <table class="course_choice">
                                <tr>
                                    <td class="courses_1">
                                    
                                    </td>
                                    <td class="courses_2" >
                                        <span style="font-size:0.8em;">Higher ratings mean you want more of that category.</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="courses_1">
                                        Mornings:
                                    </td>
                                    <td class="courses_2" >
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="courses_1">
                                         Evenings:
                                    </td>
                                    <td class="courses_2" >
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="courses_1">
                                        Mondays:
                                    </td>
                                    <td class="courses_2" >
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="courses_1">
                                        Fridays:
                                    </td>
                                    <td class="courses_2" >
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="courses_1">
                                        Balanced Days:
                                    </td>
                                    <td class="courses_2" >
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="courses_1">
                                         Gaps:
                                    </td>
                                    <td class="courses_2" >
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="courses_1">
                                         Openings:
                                    </td>
                                    <td class="courses_2" >
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td class="courses_1">
                                    </td>
                                    <td class="courses_2" style="text-align:right">
                                        <button disabled id="genSched" type="button" onClick="generateSchedules();" >Schedule classes</button>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                        
                    </div>
                    <div id="home-top-right" class="right">
                        <div class="box">
                            <h2>Added Courses</h2>
                            <ul id="addedCourses">
                                <?php
                                    echo listAddedCourses($first);
                                    $first = false;
                                    ?>
                                
                                
                                
                            </ul>
                        </div>
                    </div>
                    
                </div>
                <div class="columns box">
                    <h2>Compare possible schedules</h2>
                    <div id="home-left" class="left box">
                        <!—- Calendar —>
                        <script src='libs/fullcalendar/lib/moment.min.js'></script>
                        <script src='libs/fullcalendar/fullcalendar.js'></script>
                        
                        <script type="text/javascript">
                            $(document).ready(function() {
                                              
                                              // page is now ready, initialize the calendar...
                                              
                                              $('#calendar').fullCalendar({
                                                                          // put your options and callbacks here
                                                                          weekends: false,
                                                                          defaultView: "agendaWeek",
                                                                          slotDuration:"00:30:00",
                                                                          minTime:"08:00:00",
                                                                          defaultDate:"2017-01-02",
                                                                          timezone:"America/Los_Angeles",
                                                                          allDaySlot:false,
                                                                          height:700,
                                                                          contentHeight:800,
                                                                          columnFormat:'ddd',
                                                                          header: {
                                                                          left:"title",
                                                                          center:"",
                                                                          right:"prev,next"
                                                                          },
                                                                          titleFormat:"[Schedule #1]",
                                                                          eventOverlap:false,
                                                                          loading: function(isLoading, view){
                                                                          alert('loading');
                                                                          if (isLoading){
                                                                          $(".fc-prev-button").prop("disabled", true);
                                                                          $(".fc-prev-button").addClass("fc-state-disabled");
                                                                          $(".fc-next-button").prop("disabled", true);
                                                                          $(".fc-next-button").addClass("fc-state-disabled");
                                                                          }
                                                                          else
                                                                          {
                                                                          $(".fc-prev-button").removeClass("fc-state-disabled");
                                                                          $(".fc-prev-button").prop("disabled", false);
                                                                          $(".fc-next-button").removeClass("fc-state-disabled");
                                                                          $(".fc-next-button").prop("disabled", false);
                                                                          }
                                                                          
                                                                          }
                                                                          
                                                                          });
                                              
                                              $('.fc-prev-button').click(function(){
                                                                         console.log("Clickable", clickable);
                                                                         if (schedMax >0){
                                                                         if (clickable){
                                                                         clickable = false;
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').prop("disabled", true);
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').prop("disabled", true);
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').addClass('fc-state-disabled');
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').addClass('fc-state-disabled');
                                                                         $.when(
                                                                                getSchedText(0,-1),
                                                                                console.log("when")
                                                                                ).then(
                                                                                       getSchedule(0,0),console.log("then")
                                                                                       );
                                                                         }
                                                                         }
                                                                         });
                                              
                                              $('.fc-next-button').click(function(){
                                                                         console.log("Clickable", clickable);
                                                                         if (schedMax > 0){
                                                                         if (clickable){
                                                                         clickable = false;
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').prop("disabled", true);
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').prop("disabled", true);
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').addClass('fc-state-disabled');
                                                                         $('#calendar div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').addClass('fc-state-disabled');
                                                                         $.when(getSchedText(0,1)
                                                                                ).then(
                                                                                       getSchedule(0,0)
                                                                                       );
                                                                         
                                                                         
                                                                         }
                                                                         }
                                                                         });
                                              });
                                              
                            </script>
                        
                        
                        <div id="calendar"></div>
                        <!—- End Calendar —>
                        
                    </div>
                    <div id="home-right" class="right box">
                        <!—- Calendar —>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                              
                                              // page is now ready, initialize the calendar...
                                              
                                              $('#calendar-right').fullCalendar({
                                                                                // put your options and callbacks here
                                                                                weekends: false,
                                                                                defaultView: "agendaWeek",
                                                                                slotDuration:"00:30:00",
                                                                                minTime:"08:00:00",
                                                                                defaultDate:"2017-01-02",
                                                                                timezone:"America/Los_Angeles",
                                                                                allDaySlot:false,
                                                                                height:700,
                                                                                contentHeight:800,
                                                                                columnFormat:'ddd',
                                                                                header: {
                                                                                left:"title",
                                                                                center:"",
                                                                                right:"prev,next"
                                                                                },
                                                                                titleFormat:"[Schedule #2]",
                                                                                eventOverlap:false,
                                                                                loading: function(isLoading, view){
                                                                                alert('loading');
                                                                                if (isLoading){
                                                                                $(".fc-prev-button").prop("disabled", true);
                                                                                $(".fc-prev-button").addClass("fc-state-disabled");
                                                                                $(".fc-next-button").prop("disabled", true);
                                                                                $(".fc-next-button").addClass("fc-state-disabled");
                                                                                }
                                                                                else
                                                                                {
                                                                                $(".fc-prev-button").removeClass("fc-state-disabled");
                                                                                $(".fc-prev-button").prop("disabled", false);
                                                                                $(".fc-next-button").removeClass("fc-state-disabled");
                                                                                $(".fc-next-button").prop("disabled", false);
                                                                                }
                                                                                
                                                                                }
                                                                                
                                                                                });
                                              
                                              $('.fc-prev-button').click(function(){
                                                                         console.log("Clickable", clickable);
                                                                         if (schedMax >0){
                                                                         if (clickable){
                                                                         clickable = false;
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').prop("disabled", true);
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').prop("disabled", true);
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').addClass('fc-state-disabled');
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').addClass('fc-state-disabled');
                                                                         $.when(
                                                                                getSchedText(1,-1),
                                                                                console.log("when")
                                                                                ).then(
                                                                                       getSchedule(1,0),console.log("then")
                                                                                       );
                                                                         }
                                                                         }
                                                                         });
                                              
                                              $('.fc-next-button').click(function(){
                                                                         console.log("Clickable", clickable);
                                                                         if (schedMax > 0){
                                                                         if (clickable){
                                                                         clickable = false;
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').prop("disabled", true);
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').prop("disabled", true);
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-prev-button').addClass('fc-state-disabled');
                                                                         $('#calendar-right div.fc-toolbar div.fc-right div.fc-button-group .fc-next-button').addClass('fc-state-disabled');
                                                                         $.when(getSchedText(1,1)
                                                                                ).then(
                                                                                       getSchedule(1,0)
                                                                                       );
                                                                         
                                                                         
                                                                         }
                                                                         }
                                                                         });
                                              });
                                              
                            </script>
                        
                        
                        <div id="calendar-right"></div>
                        <!—- End Calendar —>
                        
                    </div>
                    
                </div>
            </div>
            
        </div>
        
        <div id="footer">
            <div id="copyright">
                Copyright &copy; 2017 David Legg, Alex I. Ramirez.
            </div>
            <div id="foot-links">
                <a href="https://github.com/DavidLegg/course-scheduler">Github</a>
            </div>
        </div>
        
    </body>
    
</html>
