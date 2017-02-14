<html>

	<head>
		<title>ZotScheduler: Coming Soon</title>
		<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" id="screenr-fonts-css" href="https://fonts.googleapis.com/css?family=Open%2BSans%3A400%2C300%2C300italic%2C400italic%2C600%2C600italic%2C700%2C700italic%7CMontserrat%3A400%2C700&amp;subset=latin%2Clatin-ext" type="text/css" media="all">
		<link rel="stylesheet" type="text/css" href="libs/fullcalendar/fullcalendar.css">
		<script src='libs/fullcalendar/lib/jquery.min.js'></script>
	</head>

	<body>
		<header id="header">
			<div id="head_container">
				<div id="home"><a href="index.php">ZotScheduler</a></div>
				<div id="nav_bar">
					<ul id="nav">
						<li><a href="index.php">Home</a></li>
						<li><a href="about.php">About</a></li>
					</ul>
				</div>
			</div>

		</header>
		<div id="content">
			<div id="container">
				<p>ZotScheduler is a tool that generates class schedules for students. Coming soon!</p>

				<div class="columns">
					<div id="home-left" class="left">
						<div class="box">
							<h2>Select your courses</h2>
                            <hr/>

                            <table class="course_choice">
                                <tr>
                                    <td class="courses_1">Department:</td>
                                    <td class="courses_2">
                                        <select name="Dept" id="dept" onChange="populateCourses(this.value)" class="class_select">
                                        </select>
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
							<h2>How to use</h2>
						</div>
					</div>

					<div id="home-right" class="right box">
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
        								slotDuration:"00:20:00",
        								minTime:"06:00:00",
        								defaultDate:"2017-01-02",
        								timezone:"America/Los_Angeles",
        								allDaySlot:false,
									columnFormat:'ddd',
        								header: {
             									left:"title",
             									center:"",
             									right:"prev,next"
        								},
									titleFormat:"[Your Possible Schedules]",
									viewRender: function(currentView){
										var minDate = moment('2017-01-02');
										// Past
										if (minDate >= currentView.start && minDate <= currentView.end) {
											$(".fc-prev-button").prop('disabled', true); 
											$(".fc-prev-button").addClass('fc-state-disabled'); 
										}
										else {
											$(".fc-prev-button").removeClass('fc-state-disabled'); 
											$(".fc-prev-button").prop('disabled', false); 
										}
										// Future
										/*if (maxDate >= currentView.start && maxDate <= currentView.end) {
											$(".fc-next-button").prop('disabled', true); 
											$(".fc-next-button").addClass('fc-state-disabled'); 
										} else {
											$(".fc-next-button").removeClass('fc-state-disabled'); 
											$(".fc-next-button").prop('disabled', false); 
										}*/
									}
								/*,
								events:[{"title":"(20000) AC ENG 20A: ACADEMIC WRITING (School of Humanities) Lec","start":"2017-01-03T14:00:00-0800","end":"2017-01-03T15:20:00-0800"},{"title":"(20000) AC ENG 20A: ACADEMIC WRITING (School of Humanities) Lec","start":"2017-01-05T14:00:00-0800","end":"2017-01-05T15:20:00-0800"},{"title":"(20164) AC ENG 28: GRAMMAR (School of Humanities) Lec","start":"2017-01-03T09:30:00-0800","end":"2017-01-03T10:50:00-0800"},{"title":"(20164) AC ENG 28: GRAMMAR (School of Humanities) Lec","start":"2017-01-05T09:30:00-0800","end":"2017-01-05T10:50:00-0800"}]*/

   								 })

							});
						</script>
						

						<div id="calendar"></div>
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
