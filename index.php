<!DOCTYPE html>
<html>
  <head>
    <title>PESU Student Feedback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
    <script type="application/x-javascript">
      addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
      function hideURLbar(){ window.scrollTo(0,1); }
    </script>
    <link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript">
      // Define the courses by semester
      const courses = {
        "I": [
          { code: "UE24CS151A", title: "Python for Computational Problem Solving", credits: 5 },
        ],
        "II": [
          { code: "UE24CS151B", title: "Problem Solving with C", credits: 5 },
        ],
        "III": [
          { code: "UE23CS251A", title: "Digital Design and Computer Organization", credits: 5 },
          { code: "UE23CS252A", title: "Data Structures and its Applications", credits: 5 },
          { code: "UE23CS241A", title: "Statistics for Data Science", credits: 4 },
          { code: "UE23CS242A", title: "Web Technologies", credits: 4 },
          { code: "UE23CS243A", title: "Automata Formal Languages and Logic", credits: 4 },
        ],
        "IV": [
          { code: "UE23CS251B", title: "Microprocessor and Computer Architecture", credits: 5 },
          { code: "UE23CS252B", title: "Computer Networks", credits: 5 },
          { code: "UE23CS241B", title: "Design and Analysis of Algorithms", credits: 4 },
          { code: "UE23CS242B", title: "Operating Systems", credits: 4 },
          { code: "UE23CS243B", title: "Vector Spaces and Linear Algebra", credits: 4 },
        ],
        "V": [
          { code: "UE22CS351A", title: "Database Management System", credits: 5 },
          { code: "UE22CS352A", title: "Machine Learning", credits: 5 },
          { code: "UE22CS341A", title: "Software Engineering", credits: 4 },
          { code: "UE22CS342AAX", title: "Elective I", credits: 4 },
          { code: "UE22CS343ABX", title: "Elective II", credits: 4 },
        ],
        "VI": [
          { code: "UE22CS351B", title: "Cloud Computing", credits: 5 },
          { code: "UE22CS352B", title: "Object Oriented Analysis and Design", credits: 5 },
          { code: "UE22CS341B", title: "Compiler Design", credits: 4 },
          { code: "UE22CS342BAX", title: "Elective III", credits: 4 },
          { code: "UE22CS343BBX", title: "Elective IV", credits: 4 },
          { code: "UE22CS320A", title: "Capstone Project Phase-1", credits: 2 },
        ],
        "VII": [
          { code: "UE21CS461A", title: "Capstone Phase-2", credits: 6 },
          { code: "U20CS461AX", title: "Special Topic/Directed Independent Study", credits: 4 },
        ],
        "VIII": [
          { code: "UE21CS421B", title: "Capstone Phase-3", credits: 4 },
          { code: "UE21CS461XB/UE21CS462XB", title: "Internship/Special Topic/Directed Independent Study", credits: 6 },
        ],
      };

      function updateCourses() {
        const sem = document.getElementById("sem").value;
        const courseSelect = document.getElementById("subject");

        // Clear previous options
        courseSelect.innerHTML = "";

        // Add a default placeholder option
        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "Select a Course";
        courseSelect.appendChild(defaultOption);

        // Add new options based on the selected semester
        if (courses[sem]) {
          courses[sem].forEach(course => {
            const option = document.createElement("option");
            option.value = course.code;
            option.textContent = `${course.code}: ${course.title} (${course.credits} Credits)`;
            courseSelect.appendChild(option);
          });
        }
      }
    </script>
  </head>
  <body>
    <form action="php/admin.php" method="POST">
      <div class="admin-login">
        <input type="submit" name="admin" value="Admin Login" id="admin" />
      </div>
    </form>
    <h1 class="main-heading">PESU Student Feedback Form</h1>

    <div class="container">
      <h3>
        Fill this student feedback form so that we can make our teaching better.
      </h3>
      
      <form action="php/feedback.php" method="post" class="student-form">
        <div class="student-details">
          <label for="year">Academic Year</label>
          <select name="year" id="year">
            <option value="2024">2024</option>
            <option value="2023">2023</option>
          </select>
          <br />

          <label for="sem">Semester</label>
          <select name="sem" id="sem" onchange="updateCourses()">
            <option value="I">Semester I</option>
            <option value="II">Semester II</option>
            <option value="III">Semester III</option>
            <option value="IV">Semester IV</option>
            <option value="V">Semester V</option>
            <option value="VI">Semester VI</option>
            <option value="VII">Semester VII</option>
            <option value="VIII">Semester VIII</option>
          </select>
          <br />

          <label for="date">Date of Feedback</label>
          <input type="date" id="date" name="date" />
          <br />

          <label for="branch">Branch</label>
          <select name="branch" id="branch">
            <option value="CSE">CSE</option>
            <option value="AIML">AIML</option>
          </select>
          <br />

          <label for="section">Section</label>
          <select name="section" id="section">
            <option value="A">Sec A</option>
            <option value="B">Sec B</option>
            <option value="C">Sec C</option>
            <option value="D">Sec D</option>
            <option value="E">Sec E</option>
            <option value="F">Sec F</option>
            <option value="G">Sec G</option>
            <option value="H">Sec H</option>
            <option value="I">Sec I</option>
            <option value="J">Sec J</option>
            <option value="K">Sec K</option>
            <option value="L">Sec L</option>
          </select>
          <br />


          <label for="subject">Subject</label>
          <select name="subject" id="subject">
            <!-- Options will be added here based on semester selection -->
          </select>
          <br />
        </div>
        <div class="student-feedback">
          <!-- Question 1 -->
          <h4>
            1) How would you rate the overall quality of the course content?
          </h4>
          <label>
            <input type="radio" id="ques-1-5" name="ques1" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques-1-4" name="ques1" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques-1-3" name="ques1" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques-1-2" name="ques1" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques-1-1" name="ques1" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 2 -->
          <h4>2) Effectiveness of Teacher in terms of:</h4>
          <!-- Question 2-i -->
          <h4>i. Knowledge of Subject Matter</h4>
          <label>
            <input type="radio" id="ques-2i-5" name="ques-2i" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques-2i-4" name="ques-2i" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques-2i-3" name="ques-2i" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques-2i-2" name="ques-2i" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques-2i-1" name="ques-2i" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 2-ii -->
          <h4>ii. Communication Skills</h4>
          <label>
            <input type="radio" id="ques-2ii-5" name="ques-2ii" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques-2ii-4" name="ques-2ii" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques-2ii-3" name="ques-2ii" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques-2ii-2" name="ques-2ii" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques-2ii-1" name="ques-2ii" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 2-iii -->
          <h4>
            iii. Availability beyond normal classes and approachability
          </h4>
          <label>
            <input type="radio" id="ques-2iii-5" name="ques-2iii" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques-2iii-4" name="ques-2iii" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques-2iii-3" name="ques-2iii" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques-2iii-2" name="ques-2iii" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques-2iii-1" name="ques-2iii" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 2-iv -->
          <h4>iv. Keeping students engaged in class</h4>
          <label>
            <input type="radio" id="ques-2iv-5" name="ques-2iv" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques-2iv-4" name="ques-2iv" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques-2iv-3" name="ques-2iv" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques-2iv-2" name="ques-2iv" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques-2iv-1" name="ques-2iv" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 2-v -->
          <h4>v. Overall effectiveness</h4>
          <label>
            <input type="radio" id="ques-2v-5" name="ques-2v" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques-2v-4" name="ques-2v" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques-2v-3" name="ques-2v" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques-2v-2" name="ques-2v" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques-2v-1" name="ques-2v" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 3 -->
          <h4>3) How conducive was the learning environment (classroom setting, online platform) to your learning?</h4>
          <label>
            <input type="radio" id="ques3-5" name="ques3" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques3-4" name="ques3" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques3-3" name="ques3" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques3-2" name="ques3" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques3-1" name="ques3" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 4 -->
          <h4>4) How well have you achieved the learning outcomes of this course?</h4>
          <label>
            <input type="radio" id="ques4-5" name="ques4" value="5" />
            5- Excellent
          </label>
          <label>
            <input type="radio" id="ques4-4" name="ques4" value="4" />
            4- Very Good
          </label>
          <label>
            <input type="radio" id="ques4-3" name="ques4" value="3" />
            3- Good
          </label>
          <label>
            <input type="radio" id="ques4-2" name="ques4" value="2" />
            2- Average
          </label>
          <label>
            <input type="radio" id="ques4-1" name="ques4" value="1" />
            1- Below Average
          </label>
          <br /><br />
          <!-- Question 5 -->
          <h4>5) Any Remarks</h4>
          <textarea name="remarks" rows="5"></textarea>
          <br /><br />
        </div>

        <div class="submit-form">
          <input type="submit" name="submit" value="Submit" id="submit" />
        </div>
      </form>
      <br />
    </div>
    
    <div class="copyright-text">
      <p>© Copyright Sandeep Elayath PES1UG22CS521 2024</p>
    </div>
  </body>
</html>
