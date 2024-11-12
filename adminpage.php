<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Feedback Details</title>
    <link rel="stylesheet" href="css/styles2.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      /* Container with limited size */
      .chart-container {
        width: 90%;
        height: 500px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
      }

      .filters {
        margin: 20px;
        padding: 15px;
        background: #f5f5f5;
        border-radius: 8px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
      }

      .filters label {
        font-weight: bold;
      }

      .filters select {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ddd;
      }

      table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
      }

      th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
      }

      th {
        background-color: #f5f5f5;
      }

      .new-user-form {
        margin: 20px auto;
        width: 90%;
        max-width: 500px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: white;
      }

      .logout {
        text-align: right;
        padding: 10px;
      }

      button {
        margin: 20px;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      button:hover {
        background-color: #45a049;
      }
    </style>
  </head>

  <body>
    <form action="php/logout.php" method="POST">
      <div class="logout">
        <input type="submit" value="Log Out" name="logout" />
      </div>
    </form>

    <?php 
      session_start();
      require 'php/config.php';

      if (isset($_SESSION['login_user'])) {
        $userLoggedIn = $_SESSION['login_user'];
        
        // Fetch distinct values for filters
        $years = mysqli_query($con, "SELECT DISTINCT year FROM feedback");
        $branches = mysqli_query($con, "SELECT DISTINCT branch FROM feedback");
        $subjects = mysqli_query($con, "SELECT DISTINCT subject FROM feedback");
        $sections = mysqli_query($con, "SELECT DISTINCT section FROM feedback");
        $sems = mysqli_query($con, "SELECT DISTINCT sem FROM feedback");
        
        // Filter dropdowns
        echo "<div class='filters'>
                <label for='year'>Year: </label>
                <select id='year' onchange='applyFilter()'>
                  <option value=''>All</option>";
        while ($row = mysqli_fetch_assoc($years)) { echo "<option value='".$row['year']."'>".$row['year']."</option>"; }
        echo "</select>
                
                <label for='branch'>Branch: </label>
                <select id='branch' onchange='applyFilter()'>
                  <option value=''>All</option>";
        while ($row = mysqli_fetch_assoc($branches)) { echo "<option value='".$row['branch']."'>".$row['branch']."</option>"; }
        echo "</select>

                <label for='sem'>Semester: </label>
                <select id='sem' onchange='applyFilter()'>
                  <option value=''>All</option>";
        while ($row = mysqli_fetch_assoc($sems)) { echo "<option value='".$row['sem']."'>".$row['sem']."</option>"; }
        echo "</select>
                
                <label for='subject'>Subject: </label>
                <select id='subject' onchange='applyFilter()'>
                  <option value=''>All</option>";
        while ($row = mysqli_fetch_assoc($subjects)) { echo "<option value='".$row['subject']."'>".$row['subject']."</option>"; }
        echo "</select>
                
                <label for='section'>Section: </label>
                <select id='section' onchange='applyFilter()'>
                  <option value=''>All</option>";
        while ($row = mysqli_fetch_assoc($sections)) { echo "<option value='".$row['section']."'>".$row['section']."</option>"; }
        echo "</select>
              </div>";

        // Table for feedback details
        echo "<table border='1' id='students-table'>
                <tr>
                    <th>Year</th>
                    <th>Sem</th>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Clarity of Subject Explanation</th>
                    <th>Instructor's Knowledge</th>
                    <th>Interaction with Students</th>
                    <th>Use of Practical Examples</th>
                    <th>Pacing of the Class</th>
                    <th>Encouragement of Questions</th>
                    <th>Availability for Doubts</th>
                    <th>Use of Learning Resources</th>
                    <th>Remarks</th>
                </tr>";
        
        $result = mysqli_query($con, "SELECT * FROM feedback");
        while($row = mysqli_fetch_array($result)) {
          echo "<tr class='feedback-row' 
                    data-year='{$row['year']}' 
                    data-branch='{$row['branch']}' 
                    data-sem='{$row['sem']}' 
                    data-subject='{$row['subject']}' 
                    data-section='{$row['section']}'>";
          echo "<td>" . $row['year'] . "</td>";
          echo "<td>" . $row['sem'] . "</td>";
          echo "<td>" . $row['date'] . "</td>";
          echo "<td>" . $row['branch'] . "</td>";
          echo "<td>" . $row['section'] . "</td>";
          echo "<td>" . $row['subject'] . "</td>";
          echo "<td>" . $row['ques1'] . "</td>";
          echo "<td>" . $row['ques2i'] . "</td>";
          echo "<td>" . $row['ques2ii'] . "</td>";
          echo "<td>" . $row['ques2iii'] . "</td>";
          echo "<td>" . $row['ques2iv'] . "</td>";
          echo "<td>" . $row['ques2v'] . "</td>";
          echo "<td>" . $row['ques3'] . "</td>";
          echo "<td>" . $row['ques4'] . "</td>";
          echo "<td>" . $row['remarks'] . "</td>";
          echo "</tr>";
        }
        echo "</table>"; 
      }
    ?>

    <!-- Button to open the Add User form -->
    <button onclick="document.getElementById('new-user-form').style.display='block'">Add New User</button>

    <!-- Form to Add New User -->
    <div id="new-user-form" class="new-user-form" style="display:none;">
      <h3>Add a New User</h3>
      <form action="php/add_user.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" value="Add User">
      </form>
    </div>

    <!-- Chart container -->
    <div class="chart-container">
      <canvas id="feedbackChart"></canvas>
    </div>

    <script>
      // Initialize the chart
      const ctx = document.getElementById('feedbackChart').getContext('2d');
      let feedbackChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [
            'Subject Explanation',
            'Knowledge',
            'Student Interaction',
            'Practical Examples',
            'Pacing',
            'Question Encouragement',
            'Doubt Availability',
            'Learning Resources'
          ],
          datasets: [{
            label: 'Average Score',
            data: [0, 0, 0, 0, 0, 0, 0, 0],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              max: 5,
              ticks: {
                stepSize: 1
              }
            },
            x: {
              ticks: {
                autoSkip: false,
                maxRotation: 45,
                minRotation: 45
              }
            }
          },
          plugins: {
            title: {
              display: true,
              text: 'Feedback Metrics Overview',
              font: {
                size: 16
              }
            },
            legend: {
              display: true,
              position: 'top'
            }
          }
        }
      });

      // Function to filter table rows and update the chart
      function applyFilter() {
        const yearFilter = document.getElementById('year').value;
        const branchFilter = document.getElementById('branch').value;
        const semFilter = document.getElementById('sem').value;
        const subjectFilter = document.getElementById('subject').value;
        const sectionFilter = document.getElementById('section').value;

        const questionAverages = {
          ques1: 0, // Subject Explanation
          ques2i: 0, // Knowledge
          ques2ii: 0, // Student Interaction
          ques2iii: 0, // Practical Examples
          ques2iv: 0, // Pacing
          ques2v: 0, // Question Encouragement
          ques3: 0, // Doubt Availability
          ques4: 0 // Learning Resources
        };
        let visibleRowCount = 0;

        // Loop through all rows to apply filters and calculate averages
        document.querySelectorAll('.feedback-row').forEach(row => {
          const year = row.getAttribute('data-year');
          const branch = row.getAttribute('data-branch');
          const sem = row.getAttribute('data-sem');
          const subject = row.getAttribute('data-subject');
          const section = row.getAttribute('data-section');

          const matchesFilter = 
            (yearFilter === '' || yearFilter === year) &&
            (branchFilter === '' || branchFilter === branch) &&
            (semFilter === '' || semFilter === sem) &&
            (subjectFilter === '' || subjectFilter === subject) &&
            (sectionFilter === '' || sectionFilter === section);

          row.style.display = matchesFilter ? '' : 'none';

          if (matchesFilter) {
            visibleRowCount++;
            questionAverages.ques1 += parseFloat(row.cells[6].textContent);
            questionAverages.ques2i += parseFloat(row.cells[7].textContent);
            questionAverages.ques2ii += parseFloat(row.cells[8].textContent);
            questionAverages.ques2iii += parseFloat(row.cells[9].textContent);
            questionAverages.ques2iv += parseFloat(row.cells[10].textContent);
            questionAverages.ques2v += parseFloat(row.cells[11].textContent);
            questionAverages.ques3 += parseFloat(row.cells[12].textContent);
            questionAverages.ques4 += parseFloat(row.cells[13].textContent);
          }
        });

        // Calculate averages and update chart
        if (visibleRowCount > 0) {
          Object.keys(questionAverages).forEach(key => {
            questionAverages[key] = questionAverages[key] / visibleRowCount;
          });

          feedbackChart.data.datasets[0].data = [
            questionAverages.ques1.toFixed(2),
            questionAverages.ques2i.toFixed(2),
            questionAverages.ques2ii.toFixed(2),
            questionAverages.ques2iii.toFixed(2),
            questionAverages.ques2iv.toFixed(2),
            questionAverages.ques2v.toFixed(2),
            questionAverages.ques3.toFixed(2),
            questionAverages.ques4.toFixed(2)
          ];
          feedbackChart.update();
        }

        // Update filter dropdowns
        filterDropdownOptions('branch', 'year', yearFilter);
        filterDropdownOptions('sem', 'branch', branchFilter);
        filterDropdownOptions('subject', 'sem', semFilter);
        filterDropdownOptions('section', 'subject', subjectFilter);
      }

      // Function to filter dropdown options
      function filterDropdownOptions(dropdownId, filterType, filterValue) {
        const dropdown = document.getElementById(dropdownId);
        const options = dropdown.querySelectorAll('option');

        options.forEach(option => {
          const optionValue = option.value;
          if (optionValue === '') return;

          let showOption = false;
          document.querySelectorAll('.feedback-row').forEach(row => {
            const filterData = row.getAttribute('data-' + filterType);
            if ((filterValue === '' || filterValue === filterData) && 
                row.getAttribute('data-' + dropdownId) === optionValue) {
              showOption = true;
            }
          });

          option.style.display = showOption ? '' : 'none';
        });
      }

      // Initialize chart with all data
      applyFilter();
    </script>
  </body>
</html>