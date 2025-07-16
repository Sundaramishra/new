<?php
require_once 'config.php';

// Doctor Management
function addDoctor($data) {
    global $pdo;
    // TODO: Insert doctor into DB
}
function editDoctor($id, $data) {
    global $pdo;
    // TODO: Update doctor in DB
}
function deleteDoctor($id) {
    global $pdo;
    // TODO: Soft delete doctor in DB
}
function listDoctors() {
    global $pdo;
    // TODO: Fetch all doctors from DB
}

// Department Management
function addDepartment($data) {
    global $pdo;
    // TODO: Insert department into DB
}
function editDepartment($id, $data) {
    global $pdo;
    // TODO: Update department in DB
}
function deleteDepartment($id) {
    global $pdo;
    // TODO: Soft delete department in DB
}
function listDepartments() {
    global $pdo;
    // TODO: Fetch all departments from DB
}

// Role Management
function enableRole($role) {
    global $pdo;
    // TODO: Enable role in DB
}
function disableRole($role) {
    global $pdo;
    // TODO: Disable role in DB
}

// Audit Log
function addAuditLog($action, $userId) {
    global $pdo;
    // TODO: Insert audit log in DB
}
function listAuditLogs() {
    global $pdo;
    // TODO: Fetch audit logs from DB
}

// Settings (Customization)
function updateSettings($data) {
    global $pdo;
    // TODO: Update settings in DB
}
function getSettings() {
    global $pdo;
    // TODO: Fetch settings from DB
}

// Multi-hospital/clinic management (base)
function addHospital($data) {
    global $pdo;
    // TODO: Insert hospital in DB
}
function listHospitals() {
    global $pdo;
    // TODO: Fetch hospitals from DB
}