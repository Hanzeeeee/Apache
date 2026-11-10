<?php
declare(strict_types=1);

// One configuration for each of the four management modules.
const MODULES = [
    'students' => [
        'label' => 'Students', 'singular' => 'Student',
        'table' => 'student', 'pk' => 'stuID',
        'fields' => [
            'stuFName' => ['label' => 'First Name', 'type' => 'text', 'max' => 50],
            'stuLName' => ['label' => 'Last Name', 'type' => 'text', 'max' => 50],
            'stuCourse' => ['label' => 'Course / Program', 'type' => 'text', 'max' => 80],
            'stuYear' => ['label' => 'Year Level', 'type' => 'number', 'min' => 1, 'max' => 6],
        ],
        'search' => ['stuID', 'stuFName', 'stuLName', 'stuCourse', 'stuYear'],
    ],
    'equipment' => [
        'label' => 'Equipment', 'singular' => 'Equipment',
        'table' => 'equipment', 'pk' => 'equipID',
        'fields' => [
            'equipName' => ['label' => 'Equipment Name', 'type' => 'text', 'max' => 100],
            'equipCategory' => ['label' => 'Category', 'type' => 'text', 'max' => 80],
            'equipBrand' => ['label' => 'Brand', 'type' => 'text', 'max' => 80],
            'equipRoom' => ['label' => 'Storage Room', 'type' => 'text', 'max' => 80],
        ],
        'search' => ['equipID', 'equipName', 'equipCategory', 'equipBrand', 'equipRoom'],
    ],
    'staff' => [
        'label' => 'Laboratory Staff', 'singular' => 'Staff Member',
        'table' => 'laboratory_staff', 'pk' => 'staffID',
        'fields' => [
            'staffFName' => ['label' => 'First Name', 'type' => 'text', 'max' => 50],
            'staffLName' => ['label' => 'Last Name', 'type' => 'text', 'max' => 50],
            'staffRole' => ['label' => 'Role', 'type' => 'text', 'max' => 80],
            'staffTelNo' => ['label' => 'Contact Number', 'type' => 'text', 'max' => 30],
        ],
        'search' => ['staffID', 'staffFName', 'staffLName', 'staffRole', 'staffTelNo'],
    ],
    'borrowings' => [
        'label' => 'Borrowing Transactions', 'singular' => 'Borrowing',
        'table' => 'borrowing', 'pk' => 'borrowID',
        'fields' => [
            'stuID' => [
                'label' => 'Student', 'type' => 'select',
                'sql' => "SELECT stuID AS id, CONCAT(stuFName, ' ', stuLName) AS label FROM student ORDER BY stuLName, stuFName",
            ],
            'equipID' => [
                'label' => 'Equipment', 'type' => 'select',
                'sql' => 'SELECT equipID AS id, equipName AS label FROM equipment ORDER BY equipName',
            ],
            'staffID' => [
                'label' => 'Releasing Staff', 'type' => 'select',
                'sql' => "SELECT staffID AS id, CONCAT(staffFName, ' ', staffLName) AS label FROM laboratory_staff ORDER BY staffLName, staffFName",
            ],
            'borrowDate' => ['label' => 'Borrowed Date / Time', 'type' => 'datetime-local'],
            'dueDate' => ['label' => 'Due Date', 'type' => 'date'],
            'returnDate' => ['label' => 'Return Date (optional)', 'type' => 'date', 'required' => false],
            'remarks' => ['label' => 'Remarks (optional)', 'type' => 'textarea', 'required' => false],
        ],
        'search' => ['borrowID', 'stuID', 'equipID', 'staffID', 'borrowDate', 'remarks'],
    ],
];

function get_module(string $key): array
{
    if (!isset(MODULES[$key])) {
        http_response_code(404);
        exit('Unknown module.');
    }
    return MODULES[$key];
}
