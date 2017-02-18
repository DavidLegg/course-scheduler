var selectedSched = 0;
var schedMax = 0;
var clickable = true;



function populateCourses() {
    jQuery(function($) {
           $( document ).ready(function() {
                               $("#course").prop('disabled', true);
                               $("#course").html("<option>Loading</option>");
                               $.ajax({
                                      type: "GET",
                                      url: "scheduler/ui/coursesretrieval.php",
                                      data:{_action:'coursebydept', _param:$("#dept").val(), _yt:$("#term").val()},
                                      success: function(data){
                                      $("#course").html(data);
                                      $("#course").prop('disabled', false);
                                      
                                      }
                                      });
                               });
           });
}

function populateAddedCourses(str) {
    jQuery(function($) {
           $( document ).ready(function() {
                               $("#addedCourses").html("Loading...");
                               $.ajax({
                                      type: "GET",
                                      url: "scheduler/ui/coursesretrieval.php",
                                      data:{_action:'addcourse', _param:str},
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
                                      data:{_action:'delcourse', _param:str},
                                      success: function(data){
                                      $("#addedCourses").html(data)
                                      
                                      }
                                      });
                               });
           });
}

function getSchedText(div, prevNext){
    if (prevNext == -1)
    {
        selectedSched = (selectedSched==0)?schedMax-1:selectedSched-1;
    }
    else if (prevNext == 1)
    {
        selectedSched = (selectedSched==schedMax-1)?0:selectedSched+1;
    }
    else if (prevNext == -2)
    {
        selectedSched = 0;
    }
    if (schedMax == 0 && selectedSched != 0)
        selectedSched = 0;
    return $.ajax({
                  type: "GET",
                  url: "scheduler/ui/coursesretrieval.php",
                  data:{_action:'schedule', _param:selectedSched.toString()},
                  success: function(data){
                  $(div).html(data);
                  console.log("Selected:", selectedSched);
                  console.log("Max:", schedMax);
                  //                                $(".fc-clear").html(data);
                  return true;
                  }
                  });
}

function generateSchedules() {
    jQuery(function($) {
           $( document ).ready(function() {
                               $("#calendar").fullCalendar('removeEvents');
                               $("#calendar").fullCalendar( 'gotoDate', '2017-01-02' );
                               clickable = false;
                               $(".fc-prev-button").prop("disabled", true);
                               $(".fc-prev-button").addClass("fc-state-disabled");
                               //                                       alert('disbled prev');
                               $(".fc-next-button").prop("disabled", true);
                               $(".fc-next-button").addClass("fc-state-disabled");
                               $(".fc-clear").html('Loading schedules...');
                               $.when(
                                      getSchedText(".fc-clear",-2)
                                      ).then(
                                             $.ajax({
                                                    type: "GET",
                                                    contentType: "application/json; charset=utf-8",
                                                    url: "scheduler/ui/coursesretrieval.php",
                                                    dataType:"json",
                                                    data:{_action:'schedcount', _param:'-1'},
                                                    success: function(data){
                                                    
                                                    console.log(data);
                                                    schedMax = parseInt(data);
                                                    $(".fc-prev-button").prop("disabled", false);
                                                    $(".fc-prev-button").removeClass('fc-state-disabled');
                                                    $(".fc-next-button").prop("disabled", false);
                                                    $(".fc-next-button").removeClass('fc-state-disabled');
                                                    
                                                    }
                                                    
                                                    })
                                             
                                             )
                               .done( getSchedule("#calendar", -2));
                               clickable = true;
                               $(".fc-prev-button").prop("disabled", false);
                               $(".fc-prev-button").removeClass('fc-state-disabled');
                               $(".fc-next-button").prop("disabled", false);
                               $(".fc-next-button").removeClass('fc-state-disabled');
                               });});}

function getSchedule(div, prevNext){
    if (prevNext == -1)
    {
        selectedSched = (selectedSched==0)?schedMax-1:selectedSched-1;
    }
    else if (prevNext == 1)
    {
        selectedSched = (selectedSched==schedMax-1)?0:selectedSched+1;
    }
    else if (prevNext == -2)
    {
        selectedSched = 0;
    }
    if (schedMax == 0 && selectedSched != 0)
        selectedSched = 0;
    
    $(div).fullCalendar('removeEvents');
    $(div).fullCalendar( 'gotoDate', '2017-01-02' );
    return $.ajax({
                  
                  type: "GET",
                  contentType: "application/json; charset=utf-8",
                  url: "scheduler/ui/coursesretrieval.php",
                  dataType:"json",
                  data:{_action:'schedview', _param:selectedSched.toString()},
                  success: function(data){
                  
                  $(div).fullCalendar( 'addEventSource', data );
                      console.log("derp");
                  
                      $('.fc-prev-button').removeClass('fc-state-disabled');
                      $('.fc-next-button').removeClass('fc-state-disabled');
                      $('.fc-prev-button').prop("disabled", false);
                      $('.fc-next-button').prop("disabled", false);
                  clickable = true;
                  }
                  }
                  
                  );
    
}
