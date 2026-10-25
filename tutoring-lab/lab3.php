<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Array Practice</title>
</head>
<body>
<h1>PHP Array Practice</h1>
<?php
// Indexed array
$timeSlots = ["Morning", "Afternoon", "Evening"];
echo "<h2>Time slots</h2>";
echo "<p>" . $timeSlots[0] . "</p>";
echo "<p>" . $timeSlots[1] . "</p>";

// Associative array
$request = [
    "course" => "IS 6465",
    "topic" => "PHP arrays",
    "status" => "Pending review"
];
echo "<h2>Request topic</h2>";
echo "<p>" . $request["topic"] . "</p>";

// Multidimensional array
$requests = [
    ["student" => "John", "course" => "IS 6465", "status" => "Pending review"],
    ["student" => "Kristina", "course" => "IS 6495", "status" => "Matched"]
];
echo "<h2>Tutoring requests</h2>";
foreach ($requests as $item) {
    echo "<p>" . htmlspecialchars($item["student"], ENT_QUOTES, "UTF-8") . " - "
        . htmlspecialchars($item["course"], ENT_QUOTES, "UTF-8") . " - "
        . htmlspecialchars($item["status"], ENT_QUOTES, "UTF-8") . "</p>";
}

$courses = ["IS 6465", "IS 6495", "IS 6570"];
?>
<h2>Course options</h2>
<label for="courses">Course</label>
<select id="courses" name="course">
<?php
foreach ($courses as $course) {
    echo '<option value="' . htmlspecialchars($course, ENT_QUOTES, "UTF-8") . '">';
    echo htmlspecialchars($course, ENT_QUOTES, "UTF-8") . "</option>";
}
?>
</select>
<p><a href="request-form.html">Tutoring request form</a></p>
</body>
</html>
