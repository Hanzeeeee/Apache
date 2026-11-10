# Skills Test Rubric Checklist: 20 Points

## Backend tables

- [x] Student (stuID primary key)
- [x] Equipment (equipID primary key)
- [x] Laboratory Staff (staffID primary key)
- [x] Borrowing (borrowID primary key)
- [x] Borrowing foreign keys: stuID, equipID, staffID
- [x] Prepopulated laboratory database

## Management (12 points)

| Module | Add | Search/Update | Search/Delete | View | Weight |
| --- | --- | --- | --- | --- | --- |
| Student | Yes | Yes | Yes | Yes | 3 |
| Equipment | Yes | Yes | Yes | Yes | 3 |
| Laboratory Staff | Yes | Yes | Yes | Yes | 3 |
| Borrowing Transaction | Yes | Yes | Yes | Yes | 3 |

## Inquiry UI (5 points)

- [x] Equipment matching a specified category
- [x] Students between two year levels
- [x] Borrowings by student ID
- [x] Borrowings by staff ID
- [x] Borrowings between two dates
- [x] Every result includes SQL COUNT(*) and matching records

## Miscellaneous (3 points)

- [x] Index/menu page and Local Admin login
- [x] Aggregate COUNT in displayed inquiry results
- [x] Simple, readable UI and basic input validation

## Total: 20 points

This is a practice implementation, not an examiner's certification or score.
