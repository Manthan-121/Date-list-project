<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Date List</title>
  <!-- <title>Date List | Devoleped by Manthan Mistry</title> -->
  <link rel="stylesheet" href="assets/css/bootstrap.css">
</head>

<body>
  <div class="text-white">
    <h3 class="text-center p-3 bg-dark">Date and day list</h3>
  </div>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="row d-flex justify-content-center mt-5">
      <div class="border border-2 w-50 p-3 rounded">
        <div class="row">
          <div class="col-6">
            <label class="form-label">Start Date</label>
            <input type="date" class="form-control" aria-label="First name" name="start_date" required>
          </div>
          <div class="col-6">
            <label class="form-label">End Date</label>
            <input type="date" class="form-control" aria-label="Last name" name="end_date" required>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-4">
            <input class="form-check-input me-2" type="checkbox" name="days[]" value="monday">
            <label class="form-label">Monday</label>
          </div>
          <div class="col-4">
            <input class="form-check-input me-2" type="checkbox" name="days[]" value="tuesday">
            <label class="form-label">Tuesday</label>
          </div>
          <div class="col-4">
            <input class="form-check-input me-2" type="checkbox" name="days[]" value="wednesday">
            <label class="form-label">Wednesday</label>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-4">
            <input class="form-check-input me-2" type="checkbox" name="days[]" value="thursday">
            <label class="form-label">Thursday</label>
          </div>
          <div class="col-4">
            <input class="form-check-input me-2" type="checkbox" name="days[]" value="friday">
            <label class="form-label">Friday</label>
          </div>
          <div class="col-4">
            <input class="form-check-input me-2" type="checkbox" name="days[]" value="saturday">
            <label class="form-label">Saturday</label>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col">
            <button type="submit" class="btn btn-dark" name="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <div class="row d-flex justify-content-center mt-5">
    <div class="border border-2 w-75 p-3 rounded">
      <div>
        <div class="btn-group" role="group" aria-label="Basic example">
          <button type="button" class="btn btn-dark me-2" onclick="download_table_as_csv('table-to-pdf');">CSV</button>
          <button type="button" class="btn btn-dark me-2" onclick="tableToExcel('table-to-pdf', 'Date list with day name')">XLS</button>
          <button type="button" class="btn btn-dark me-2" onclick="exportPdf()">PDF</button>
        
        </div>
      </div>
      <div class="mt-3">
        <table class="table table-bordered border-primary rounded" id="table-to-pdf">
          <tr class="table-dark">
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Day</th>
          </tr>
          <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if the form is submitted
            if (isset($_POST['submit'])) {
              // Get the start and end dates
              $startDate = new DateTime($_POST['start_date']);
              $endDate = new DateTime($_POST['end_date']);


              $i = 1;
              // Get the selected days
              $selectedDays = isset($_POST['days']) ? $_POST['days'] : [];

              // Loop through each day in the range
              // print_r($selectedDays);
              $currentDate = clone $startDate;
              while ($currentDate <= $endDate) {
                $dayOfWeek = strtolower($currentDate->format('l'));

                // Check if the day is selected
                if (in_array($dayOfWeek, $selectedDays)) {
                  // Print the row with date and day name
          ?>
                  <tr>
                    <td scope="row"><?php echo $i ?></td>
                    <td><?php echo $currentDate->format('d-m-Y') ?></td>
                    <td><?php echo $currentDate->format('l') ?></td>
                  </tr>
                <?php
                  $i++;
                }

                if (empty($selectedDays)) {
                ?>
                  <tr>
                    <td scope="row"><?php echo $i ?></td>
                    <td><?php echo $currentDate->format('d-m-Y') ?></td>
                    <td><?php echo $currentDate->format('l') ?></td>
                  </tr>
            <?php
                  $i++;
                }
                // Move to the next day
                $currentDate->modify('+1 day');
              }
            }
          } else {
            ?>
            <tr>
              <td colspan="3" class="text-center">
                <h2 class="text-danger">No data found</h2>
              </td>
            </tr>
          <?php
          }
          ?>
        </table>
      </div>
    </div>
  </div>
  <script src="assets/js/bootstrap.js"></script>

  <!-- html to pdf  -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
  <script>
    function exportPdf() {
      var pdf = new jsPDF();
      pdf.text(20, 20, "Date list with day name");
      pdf.autoTable({
        html: '#table-to-pdf',
        startY: 25,
        theme: 'grid',
        columnStyles: {
          0: {
            cellWidth: 20
          },
          1: {
            cellWidth: 60
          },
          2: {
            cellWidth: 40
          },
          3: {
            cellWidth: 60
          }
        },
        bodyStyles: {
          lineColor: [1, 1, 1]
        },
        styles: {
          minCellHeight: 10
        }
      });
      window.open(URL.createObjectURL(pdf.output("blob")))
    }
  </script>


  <!-- html to XLS -->
  <script type="text/javascript">
    var tableToExcel = (function() {
      var uri = 'data:application/vnd.ms-excel;base64,',
        template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>',
        base64 = function(s) {
          return window.btoa(unescape(encodeURIComponent(s)))
        },
        format = function(s, c) {
          return s.replace(/{(\w+)}/g, function(m, p) {
            return c[p];
          })
        }
      return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        var ctx = {
          worksheet: name || 'Worksheet',
          table: table.innerHTML
        }
        window.location.href = uri + base64(format(template, ctx))
      }
    })()
  </script>

  <!-- html to csv  -->
  <script>
    // Quick and simple export target #table_id into a csv
    function download_table_as_csv(table_id, separator = ',') {
      // Select rows from table_id
      var rows = document.querySelectorAll('table#' + table_id + ' tr');
      // Construct csv
      var csv = [];
      for (var i = 0; i < rows.length; i++) {
        var row = [],
          cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length; j++) {
          // Clean innertext to remove multiple spaces and jumpline (break csv)
          var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
          // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
          data = data.replace(/"/g, '""');
          // Push escaped string
          row.push('"' + data + '"');
        }
        csv.push(row.join(separator));
      }
      var csv_string = csv.join('\n');
      // Download it
      var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
      var link = document.createElement('a');
      link.style.display = 'none';
      link.setAttribute('target', '_blank');
      link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
      link.setAttribute('download', filename);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  </script>
</body>

</html>