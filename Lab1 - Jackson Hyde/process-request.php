<?php


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Please submit the request form.");
}

$course = trim($_POST["course"] ?? "");
$topic = trim($_POST["topic"] ?? "");
$preferredDate = trim($_POST["preferreddate"] ?? "");
$preferredTime = trim($_POST["preferredtime"] ?? "");

$errors = [];
$allowedCourses = ["IS 6465", "IS 6495", "IS 6570"];
$allowedTimes = ["Morning", "Afternoon", "Evening"];

if (!in_array($course, $allowedCourses, true)) {
    $errors["course"] = "Select a valid course.";
}

if (!in_array($preferredTime, $allowedTimes, true)) {
    $errors["preferred_time"] = "Select a valid preferred time.";
}

if ($topic === "" || mb_strlen($topic, "UTF-8") > 120) {
    $errors["topic"] = "Enter a topic of 1 to 120 characters.";
}

date_default_timezone_set("America/Denver");

$date = DateTime::createFromFormat("!Y-m-d", $preferredDate);
$validDate = $date && $date->format("Y-m-d") === $preferredDate;

if (!$validDate) {
    $errors["preferred_date"] = "Enter a valid preferred date.";
} elseif ($date < new DateTime("today")) {
    $errors["preferred_date"] = "Preferred date cannot be in the past.";
}

if ($errors) {
    echo "<h1>Please correct the request</h1>";
    echo "<ul>";

    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error, ENT_QUOTES, "UTF-8") . "</li>";
    }

    echo "</ul>";
} else {
    echo "<h1>Request received</h1>";
    echo "<p>Your tutoring request was received.</p>";
    echo "<p>Course: " . htmlspecialchars($course, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Topic: " . htmlspecialchars($topic, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Preferred date: " . htmlspecialchars($preferredDate, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Preferred time: " . htmlspecialchars($preferredTime, ENT_QUOTES, "UTF-8") . "</p>";
    echo "<p>Status: Pending review</p>";
}

echo '<p><a href="request-form.html">Return to the request form</a></p>';