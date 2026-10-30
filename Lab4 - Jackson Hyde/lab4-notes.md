# Lab 4 Notes

Jackson Hyde

Codespaces link:

## Part 1: Run the application

I submitted a valid request on `request-form.php`. The form sent the values to `process-request.php`, which validated them and showed "Request received" with my course, request details, topics, and the status "Pending review."

## Part 2: Trace the templates

**What content does header.php provide to every page?**
It provides the start of the HTML page, including the head section, the Bootstrap CSS link, the `styles.css` link, and the page title.

**What content does nav.php provide?**
It provides the navigation bar with links to the Requests page and the Request a Session page.

**What content does footer.php provide?**
It provides the footer text, the Bootstrap script, and the closing `body` and `html` tags.

**Why is require_once appropriate for these files?**
The page cannot run without these files, and `require_once` makes sure each file is only loaded one time.

**Where is the shared CSS file linked?**
It is linked in `includes/header.php`.

## Part 3: Observe the form controls

**reason:** A textarea where the student describes what they need help with. PHP receives one string.

**topics[]:** Checkboxes that let the student pick one or more topics. PHP receives an array.

**preferredtime:** Radio buttons that let the student pick one time. PHP receives one string.

**preferreddate:** A date input for the day the student wants tutoring. PHP receives one string.

**form_name:** A hidden field that identifies the form. PHP receives one string.

## Part 4: Test validation behavior

**Valid request**
- Values: IS 6465, 2026-11-05, "I need help reviewing PHP arrays.", Exam review and Practice problems, Afternoon.
- Result: Accepted.
- Message: "Request received."
- Rule: All fields must have valid values.

**Missing or invalid value**
- Values: IS 6465, 2026-11-05, "Help", Study plan, Afternoon.
- Result: Rejected.
- Message: "Describe your request using 10 to 500 characters."
- Rule: The reason must be 10 to 500 characters.

**Past date**
- Values: IS 6465, 2026-10-20, "I need help reviewing PHP arrays.", Exam review, Morning.
- Result: Rejected.
- Message: "Preferred date cannot be in the past."
- Rule: The preferred date cannot be in the past.

## Part 5: Review testing and CRUD pages

All ten test cases in `tests/validation-tests.php` showed PASS.

| CRUD action | Page |
|---|---|
| Create | `request-form.php` |
| Read one request | `request-details.php` |
| Read all requests | `requests.php` |
| Update | `edit-request.php` |
| Delete | `delete-request.php` |

**What query parameter appears in the request details URL?**
`request_id`, for example `request-details.php?request_id=12`.

**Why is a GET query parameter appropriate for viewing one request?**
Viewing a request only reads data and does not change anything.

**Why should an actual update or delete later use POST rather than GET?**
Update and delete change data, and GET should only be used to read data.
