<?php

// Lab 2B: Functions and Objects
// Chapter 5 slides 9-12: parameters, return values, and arrays.
echo '<h1>Lab 2B: Functions and Objects</h1>';
echo '<h2>1. Function with Parameters and Return Value</h2>';

function formatName($first, $middle, $last)
{
    $first = ucfirst(strtolower($first));
    $middle = ucfirst(strtolower($middle));
    $last = ucfirst(strtolower($last));

    return $first . ' ' . $middle . ' ' . $last;
}

$displayName = formatName('mARIA', 'lee', 'CHEN');
echo $displayName;

echo '<h2>2. Function That Returns an Array</h2>';
function cleanNameParts($first, $middle, $last)
{
    return [
        ucfirst(strtolower($first)),
        ucfirst(strtolower($middle)),
        ucfirst(strtolower($last))
    ];
}

$nameParts = cleanNameParts('mARIA', 'lee', 'CHEN');
echo $nameParts[0];

// Chapter 5 slide 16: global variables versus function parameters.
echo '<h2>3. Variable Scope</h2>';
$first = 'Maria';
$last = 'Chen';

function buildFullName()
{
    global $first, $last;
    return "$first $last";
}

echo buildFullName();
echo '<br>';

// The template's second name prevents duplicate function declarations.
function buildFullName2($first, $last)
{
    return "$first $last";
}

echo buildFullName2($first, $last);

// Chapter 5 slide 17.
echo '<h2>4. Static Variable</h2>';
function nextTicketNumber()
{
    static $number = 0;
    $number++;
    return $number;
}

echo nextTicketNumber();
echo '<br>';
echo nextTicketNumber();

// Chapter 5 slides 25-26.
echo '<h2>5. User Class</h2>';
class User
{
    public $name;
    public $email;

    public function display()
    {
        return 'User: ' . $this->name;
    }
}

$user = new User();
$user->name = 'Maria Chen';
$user->email = 'maria@example.com';
echo $user->display();

// Chapter 5 slide 27; User2 is the name required by the template.
echo '<h2>6. Constructor and Password Hashing</h2>';
class User2
{
    public $name;
    private $passwordHash;

    public function __construct($name, $password)
    {
        $this->name = $name;
        $this->passwordHash =
            password_hash($password, PASSWORD_DEFAULT);
    }
}

$user = new User2('Maria Chen', 'temporary-password');
// Property output is shown on slide 31; print_r() is not in these decks.
echo $user->name;
