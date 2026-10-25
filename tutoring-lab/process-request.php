<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Please submit the tutoring request form.");
}

date_default_timezone_set("America/Denver");

$course = $_POST["course"] ?? "";
$topic = trim($_POST["topic"] ?? "");
$preferredDate = $_POST["preferreddate"] ?? "";
$preferredTime = $_POST["preferredtime"] ?? "";

$errors = [];
$allowedCourses = ["IS 6465", "IS 6495", "IS 6570"];
$allowedTimes = ["Morning", "Afternoon", "Evening"];

if (!in_array($course, $allowedCourses, true)) {
    $errors[] = "Select a valid course.";
}

if (!in_array($preferredTime, $allowedTimes, true)) {
    $errors[] = "Select a valid preferred time.";
}

if ($topic === "" || mb_strlen($topic) > 120) {
    $errors[] = "Enter a topic of 1 to 120 characters.";
}

$date = DateTime::createFromFormat("Y-m-d", $preferredDate);
$validDate = $date && $date->format("Y-m-d") === $preferredDate;

if (!$validDate) {
    $errors[] = "Enter a valid date.";
} elseif ($preferredDate < date("Y-m-d")) {
    $errors[] = "The preferred date cannot be in the past.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tutoring Request Result</title>
</head>
<body>
<?php
if ($errors) {
    echo "<h1>Please correct these errors</h1>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error, ENT_QUOTES, "UTF-8") . "</li>";
    }
    echo "</ul>";
} else {
    echo "<h1>Request received</h1>";
    echo "<p>Course: " . htmlspecialchars($course, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Topic: " . htmlspecialchars($topic, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Preferred date: " . htmlspecialchars($preferredDate, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Preferred time: " . htmlspecialchars($preferredTime, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Status: Pending review</p>";
}
?>
    <p><a href="request-form.html">Back to the form</a></p>
</body>
</html>
