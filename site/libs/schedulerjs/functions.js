var selectedSched = 0;
        function populateCourses(str) {
            jQuery(function($) {
                   $( document ).ready(function() {
                                       $.ajax({
                                              type: "GET",
                                              url: "scheduler/ui/coursesretrieval.php",
                                              data:{_action:'coursebydept', _param:str}, //name is a $_GET variable name here,
                                              // and 'youwant' is its value to be passed
                                              success: function(data){
                                              $("#course").html(data)
                                              
                                              }
                                              });
                                       });
                   });
        }

        function populateAddedCourses(str) {
            jQuery(function($) {
                   $( document ).ready(function() {
                                       $.ajax({
                                              type: "GET",
                                              url: "scheduler/ui/coursesretrieval.php",
                                              data:{_action:'addcourse', _param:str}, //name is a $_GET variable name here,
                                              // and 'youwant' is its value to be passed 
                                              success: function(data){
                                              $("#addedCourses").html(data)
                                              
                                              }
                                              });
                                       });
                   });
        }

        function delPopCourses(str) {
            jQuery(function($) {
                   $( document ).ready(function() {
                                       $.ajax({
                                              type: "GET",
                                              url: "scheduler/ui/coursesretrieval.php",
                                              data:{_action:'delcourse', _param:str}, //name is a $_GET variable name here,
                                              // and 'youwant' is its value to be passed
                                              success: function(data){
                                              $("#addedCourses").html(data)
                                              
                                              }
                                              });
                                       });
                   });
        }

        function getSchedText(index){
                       return $.ajax({
                              type: "GET",
                              url: "scheduler/ui/coursesretrieval.php",
                              data:{_action:'schedule', _param:index.toString()}, //name is a $_GET variable name here,
                              // and 'youwant' is its value to be passed
                              success: function(data){
                                $(".fc-clear").html(data);
                                return true;
                              }
                        });
        }

        function generateSchedules() {
            jQuery(function($) {
                   $( document ).ready(function() {
                                       $("#calendar").fullCalendar('removeEvents');
                                       $("#calendar").fullCalendar( 'gotoDate', '2017-01-02' );
                                       selectedSched = 0;
                                       $(".fc-clear").html('Loading schedules...');
                                       $.when(
                                             getSchedText(selectedSched)
                                        ).then( function() {
                                               $.ajax({
                                                      type: "GET",
                                                      contentType: "application/json; charset=utf-8",
                                                      url: "scheduler/ui/coursesretrieval.php",
                                                      dataType:"json",
                                                      data:{_action:'schedview', _param:'0'}, //name is a $_GET variable name here,
                                                      // and 'youwant' is its value to be passed
                                                      success: function(data){
                                                      
                                                        $("#calendar").fullCalendar( 'addEventSource', data );
                                                      
                                                      }
                                                      
                                                });
                                        });
                      });
             });
        }
