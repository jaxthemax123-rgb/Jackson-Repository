# Lab 4 Notes

Jackson Hyde
IS 6465 Web Based Applications

Codespaces link: **[JACKSON: paste your Codespaces link here]**

## Part 1: Run the application

I opened the project in GitHub Codespaces, started the PHP development server with `php -S 0.0.0.0:8000`, and visited `request-form.php`. I chose IS 6465, picked a future preferred date, typed "I need help reviewing PHP arrays." as my reason, checked Exam review and Practice problems, and chose Afternoon.

When I clicked Request session, the browser sent the form values to `process-request.php` with the POST method. PHP read each value from `$_POST`, checked it against the business rules, and found no errors. The page then showed "Request received" with the course, my request details, the topics I selected, and the status "Pending review." The confirmation page does not show the navigation bar or the site styling, because `process-request.php` does not use the shared templates.

**[JACKSON: add your screenshot of the completed form or the confirmation page here]**

## Part 2: Trace the templates

**What content does header.php provide to every page?**
`header.php` starts every page. It provides the doctype, the opening `html` and `head` tags, the charset and viewport meta tags, the Bootstrap 5.3.3 CSS link, the link to the shared `css/styles.css` file, the page title "Peer Tutoring Hub", and the opening `body` tag.

**What content does nav.php provide?**
`nav.php` provides the Bootstrap navigation bar. It shows the Peer Tutoring Hub brand link and links to the Requests page (`requests.php`) and the Request a Session page (`request-form.php`). The brand link points to `index.php`, which is not included in the starter, so that one link does not open a page.

**What content does footer.php provide?**
`footer.php` provides the footer with the text "Peer Tutoring Hub", loads the Bootstrap bundle script, and closes the `body` and `html` tags.

**Why is require_once appropriate for these files?**
`require_once` is appropriate because a page cannot run correctly without its header, navigation, and footer. If one of these files is missing, `require` stops the page instead of showing a broken page. The "once" part prevents the same file from being loaded twice by accident. Sharing the templates this way gives every page the same presentation and makes maintenance easier, because a change in one template updates every page.

**Where is the shared CSS file linked?**
The shared CSS file is linked one time in `includes/header.php` with `<link rel="stylesheet" href="css/styles.css">`. It comes after the Bootstrap CSS link, so the custom styles load after Bootstrap. Every page that requires `header.php` uses the same stylesheet.

## Part 3: Observe the form controls

**reason**
`reason` is a textarea where the student describes what they need help with. A textarea is used because the answer can be longer than one line. PHP receives one string value. The `maxlength="500"` attribute helps in the browser, but PHP still checks that the trimmed reason is 10 to 500 characters.

**topics[]**
`topics[]` is a group of checkboxes for Exam review, Practice problems, and Study plan. Checkboxes are used because the student may choose more than one topic. The square brackets in the name tell PHP to collect the checked values, so PHP receives an array of strings. If no box is checked, `process-request.php` uses an empty array.

**preferredtime**
`preferredtime` is a group of radio buttons for Morning, Afternoon, and Evening. Radio buttons are used because the student must choose exactly one option from a small set. PHP receives one string value.

**preferreddate**
`preferreddate` is a date input where the student chooses the day they would like tutoring. PHP receives one string value in the `Y-m-d` format, such as 2026-11-05. PHP then checks that it is a real date and that it is not in the past.

**form_name**
`form_name` is a hidden field with the value `tutoring_request`. The student does not see or edit it, and it tells the processor which form sent the data. PHP receives one string value. Because a hidden field can be changed, it is still untrusted, so PHP checks that the value equals `tutoring_request`.

## Part 4: Test validation behavior

**[JACKSON: run these three tests in your Codespace and confirm the results. If you use different values or dates, update them below.]**

### Valid request

- **Values entered:** Course IS 6465, preferred date 2026-11-05, reason "I need help reviewing PHP arrays.", topics Exam review and Practice problems, preferred time Afternoon.
- **Result:** Accepted.
- **Message displayed:** "Request received", followed by Course: IS 6465, Request details: I need help reviewing PHP arrays., Topics: Exam review, Practice problems, and Status: Pending review.
- **Business rule checked:** Every rule must pass. The course and time must be on the approved lists, the reason must be 10 to 500 characters, at least one approved topic must be selected, the date must be valid and not in the past, and the form name must equal `tutoring_request`.

### Missing or invalid value

- **Values entered:** Course IS 6465, preferred date 2026-11-05, reason "Help", topic Study plan, preferred time Afternoon.
- **Result:** Rejected.
- **Message displayed:** "Request could not be submitted" with the error "Describe your request using 10 to 500 characters."
- **Business rule checked:** The reason is required and must be between 10 and 500 characters after leading and trailing spaces are removed. "Help" is only 4 characters, so PHP rejected it. I used a short reason because the browser's `required` attribute will not let me submit a blank reason.

### Past date

- **Values entered:** Course IS 6465, preferred date 2026-10-20, reason "I need help reviewing PHP arrays.", topic Exam review, preferred time Morning.
- **Result:** Rejected.
- **Message displayed:** "Request could not be submitted" with the error "Preferred date cannot be in the past."
- **Business rule checked:** The preferred date must be a valid date that is today or later, because a session cannot be scheduled for a day that has already passed.

## Part 5: Review testing and CRUD pages

**Validation test results**
When I opened `tests/validation-tests.php`, all ten test cases showed PASS: Valid time, Missing time, Altered value, Multiple valid topics, Missing topics, Altered topic, Minimum length, Maximum length, Too short, and Too long. This shows that the functions in `includes/validation.php` accept normal and boundary values and reject missing, altered, too short, and too long values.

**CRUD pages**

| CRUD action | Page |
|---|---|
| Create | `request-form.php` (submitted to `process-request.php`) |
| Read one request | `request-details.php` |
| Read all requests | `requests.php` |
| Update | `edit-request.php` |
| Delete | `delete-request.php` |

**What query parameter appears in the request details URL?**
The `request_id` query parameter appears in the URL, for example `request-details.php?request_id=12`. The page reads it with `filter_input(INPUT_GET, 'request_id', FILTER_VALIDATE_INT)`, and it shows "Choose a valid request." if the value is not a positive whole number.

**Why is a GET query parameter appropriate for viewing one request?**
GET is appropriate because viewing a request only reads information and does not change it. The `request_id` identifies which request the user wants to see, so each View link on `requests.php` can open the correct details page. The value is still untrusted, so the page validates it before using it.

**Why should an actual update or delete later use POST rather than GET?**
An update or delete changes application data, and GET should only be used to read a resource, not to create, update, or delete one. POST is used when an action changes the application, so the change only happens when the user submits a form, such as the Save changes form on `edit-request.php` or the confirmation form on `delete-request.php`. If delete were a GET link, simply opening or reloading the link could cancel a request.
