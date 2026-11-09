## Part 1: Create the database tables

```
sqlite> .tables
students           tutoring_requests
sqlite> .schema
CREATE TABLE students (
  student_id INTEGER PRIMARY KEY,
  student_name TEXT NOT NULL,
  student_email TEXT NOT NULL UNIQUE
);
CREATE TABLE tutoring_requests (
  request_id INTEGER PRIMARY KEY,
  student_id INTEGER NOT NULL,
  course TEXT NOT NULL,
  topic TEXT NOT NULL,
  preferred_date TEXT NOT NULL,
  preferred_time TEXT NOT NULL,
  status TEXT NOT NULL DEFAULT 'Open',
  created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(student_id)
);
```

**Which column is the primary key in each table?**

student_id is the primary key in students, and request_id is the primary key in tutoring_requests.

**Which column is the foreign key, and what relationship does it represent?**

tutoring_requests.student_id is the foreign key that references students.student_id. It represents a one to many relationship where one student can submit many tutoring requests and each request belongs to one student.

**Why is student_email marked UNIQUE?**

student_email is UNIQUE so two student rows cannot use the same email address, which would make it unclear which student a request belongs to.

## Part 2: Insert students and tutoring requests

```sql
INSERT OR ignore INTO students (student_name, student_email)
VALUES ('Jordan Lee', 'jordan.lee@example.edu');

INSERT OR ignore INTO students (student_name, student_email)
VALUES ('Avery Patel', 'avery.patel@example.edu');

INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (1, 'IS 6465', 'Forms', '2026-11-16', '14:00');

INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (1, 'IS 6465', 'SQL SELECT', '2026-11-18', '15:00');

INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (2, 'IS 6465', 'SQLite CRUD', '2026-11-17', '10:00');
```

```
sqlite> select * from students;
1|Jordan Lee|jordan.lee@example.edu
2|Avery Patel|avery.patel@example.edu
sqlite> select * from tutoring_requests;
1|1|IS 6465|Forms|2026-11-16|14:00|Open|2026-11-09 01:41:00
2|1|IS 6465|SQL SELECT|2026-11-18|15:00|Open|2026-11-09 01:41:00
3|2|IS 6465|SQLite CRUD|2026-11-17|10:00|Open|2026-11-09 01:41:00
```

**Why does Jordan have one student row but two request rows?**

Jordan is one student, so Jordan's name and email are stored once in students. Each of Jordan's two requests is its own row in tutoring_requests, and both rows use Jordan's student_id of 1 to connect back to that one student row.

## Part 3: Read the stored data

```
sqlite> SELECT
   ...>   r.request_id,
   ...>   s.student_name,
   ...>   s.student_email,
   ...>   r.course, r.topic,
   ...>   r.preferred_date, r.preferred_time, r.status
   ...> FROM tutoring_requests AS r
   ...> JOIN students AS s ON r.student_id = s.student_id
   ...> ORDER BY r.preferred_date;
1|Jordan Lee|jordan.lee@example.edu|IS 6465|Forms|2026-11-16|14:00|Open
3|Avery Patel|avery.patel@example.edu|IS 6465|SQLite CRUD|2026-11-17|10:00|Open
2|Jordan Lee|jordan.lee@example.edu|IS 6465|SQL SELECT|2026-11-18|15:00|Open
```

**What does the JOIN condition connect?**

The JOIN condition r.student_id = s.student_id connects each tutoring request to the student row with the matching student_id, so the student's name and email can be shown with the request.

**What is the difference between the purpose of JOIN and WHERE?**

JOIN combines related facts from two tables into one result. WHERE controls which rows appear in the result.

**Why should the application retrieve one request by request_id instead of by topic alone?**

request_id is the primary key, so it always identifies exactly one request. Different requests can have the same topic, so searching by topic could return the wrong request or more than one.

## Part 4: Update one request

```sql
UPDATE tutoring_requests
SET preferred_date = '2026-11-19',
    preferred_time = '16:00'
WHERE request_id = 2;

UPDATE tutoring_requests
SET status = 'Cancelled'
WHERE request_id = 3;
```

Before:

```
sqlite> SELECT * FROM tutoring_requests
   ...> WHERE request_id = 2;
2|1|IS 6465|SQL SELECT|2026-11-18|15:00|Open|2026-11-09 01:41:00
```

After:

```
sqlite> SELECT * FROM tutoring_requests
   ...> WHERE request_id = 2;
2|1|IS 6465|SQL SELECT|2026-11-19|16:00|Open|2026-11-09 01:41:00
```

Avery's request:

```
sqlite> SELECT * FROM tutoring_requests
   ...> WHERE request_id = 3;
3|2|IS 6465|SQLite CRUD|2026-11-17|10:00|Cancelled|2026-11-09 01:41:00
```

**Why is a specific WHERE clause essential in an UPDATE statement?**

A specific WHERE clause makes sure only the intended request changes. Without it, the UPDATE would change every row in the table.

**Why does the Peer Tutoring Hub use a Cancelled status instead of immediately deleting a real request?**

Changing the status to Cancelled keeps the request history while still removing it from the list of open requests. A DELETE would remove the row permanently.

## Part 5: Delete one test request

```sql
INSERT INTO tutoring_requests
  (student_id, course, topic, preferred_date, preferred_time)
VALUES
  (1, 'IS 6465', 'Delete Practice', '2026-11-20', '09:00');
```

```sql
DELETE from tutoring_requests
WHERE request_id = 4;
```

Before the DELETE:

```
sqlite> select * from tutoring_requests;
1|1|IS 6465|Forms|2026-11-16|14:00|Open|2026-11-09 01:41:00
2|1|IS 6465|SQL SELECT|2026-11-19|16:00|Open|2026-11-09 01:41:00
3|2|IS 6465|SQLite CRUD|2026-11-17|10:00|Cancelled|2026-11-09 01:41:00
4|1|IS 6465|Delete Practice|2026-11-20|09:00|Open|2026-11-09 01:41:00
```

After the DELETE:

```
sqlite> select * from tutoring_requests;
1|1|IS 6465|Forms|2026-11-16|14:00|Open|2026-11-09 01:41:00
2|1|IS 6465|SQL SELECT|2026-11-19|16:00|Open|2026-11-09 01:41:00
3|2|IS 6465|SQLite CRUD|2026-11-17|10:00|Cancelled|2026-11-09 01:41:00
```

**Why is a WHERE clause essential in a DELETE statement?**

The WHERE clause makes sure only the chosen row is removed. Without it, the DELETE would remove every row in the table.

## Part 6: Reflect on the CRUD workflow

| CRUD action | SQL statement used in this lab | Peer Tutoring Hub example |
|---|---|---|
| Create | INSERT | Add Jordan and Avery to students and add their tutoring requests |
| Read | SELECT, WHERE, and JOIN | Show the request list with each student's name and email |
| Update | UPDATE | Change the date and time of Jordan's request and cancel Avery's request |
| Delete | DELETE | Remove the Delete Practice test request |

**How does the database preserve the connection between a student and that student's requests?**

Each request stores the student's student_id as a foreign key that references students.student_id, so every request stays linked to one student row.

**Which database constraint prevents two student rows from using the same email address?**

The UNIQUE constraint on student_email.

**What could happen if an UPDATE or DELETE statement did not include a WHERE clause?**

The statement would change or delete every row in the table instead of just the intended request.

**When the application later receives values from an HTML form, why should PHP send those values as parameters instead of placing them directly into SQL text?**

Parameters send the form values separately from the SQL instruction, which protects against SQL injection.
