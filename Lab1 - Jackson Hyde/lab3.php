<?php

//indexed arrays
$timeSlots = ["Morning", "Afternoon", "Evening"];
echo $timeSlots[0] . "<br>";
echo $timeSlots[1] . "<br>";
echo $timeSlots[2] . "<br>";
echo "<br>";

//associative arrays
$request = [
    "course" => "IS 6465",
    "topic" => "PHP arrays",
    "status" => "Pending review"
];
echo $request["topic"];
echo "<br>";

//multidimensional arrays
$requests = [
    ["student" => "Amina", "course" => "IS 6465", "status" => "Pending review"],
    ["student" => "Diego", "course" => "IS 6495", "status" => "Matched"]
];

echo "<pre>";
print_r($requests);
echo "</pre>";

//foreach
$courses = ["IS 6465", "IS 6495", "IS 6570"];

echo '<label for="course">Course</label>';
echo '<select id="course" name="course">';

foreach ($courses as $course) {
    echo '<option value="' . htmlspecialchars($course, ENT_QUOTES, "UTF-8") . '">';
    echo htmlspecialchars($course, ENT_QUOTES, "UTF-8") . '</option>';
}

echo '</select>';