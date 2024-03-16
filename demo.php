<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted
    if (isset($_POST['submit'])) {
        // Get the start and end dates
        $startDate = new DateTime($_POST['start_date']);
        $endDate = new DateTime($_POST['end_date']);

        // Get the selected days
        $selectedDays = isset($_POST['days']) ? $_POST['days'] : [];

        // Print the table header
        echo "<table border='1'>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                </tr>";

        // Loop through each day in the range
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $dayOfWeek = strtolower($currentDate->format('l'));

            // Check if the day is selected
            if (in_array($dayOfWeek, $selectedDays)) {
                // Print the row with date and day name
                echo "<tr>
                        <td>{$currentDate->format('Y-m-d')}</td>
                        <td>{$currentDate->format('l')}</td>
                      </tr>";
            }

            // Move to the next day
            $currentDate->modify('+1 day');
        }

        // Close the table
        echo "</table>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Date Range Table</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="start_date">Start Date:</label>
        <input type="date"  name="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required>

        <br>

        <label>Select days:</label>
        <br>
        <input type="checkbox" name="days[]" value="monday"> Monday
        <input type="checkbox" name="days[]" value="tuesday"> Tuesday
        <input type="checkbox" name="days[]" value="wednesday"> Wednesday
        <input type="checkbox" name="days[]" value="thursday"> Thursday
        <input type="checkbox" name="days[]" value="friday"> Friday
        <input type="checkbox" name="days[]" value="saturday"> Saturday
        <input type="checkbox" name="days[]" value="sunday"> Sunday

        <br>

        <input type="submit"  name="submit" value="Generate Table">
    </form>
</body>
</html>
