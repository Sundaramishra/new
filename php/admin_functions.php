<?php
require_once 'config.php';

// Doctor Management
function addDoctor($data) {
    global $pdo;
    $sql = "INSERT INTO doctors (name, midname, surname, contact, address, education, experience, certificates, awards, vitals, image, department_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['midname'],
        $data['surname'],
        $data['contact'],
        $data['address'],
        $data['education'],
        $data['experience'],
        $data['certificates'],
        $data['awards'],
        $data['vitals'],
        $data['image'],
        $data['department_id']
    ]);
}
function editDoctor($id, $data) {
    global $pdo;
    $sql = "UPDATE doctors SET name=?, midname=?, surname=?, contact=?, address=?, education=?, experience=?, certificates=?, awards=?, vitals=?, image=?, department_id=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['midname'],
        $data['surname'],
        $data['contact'],
        $data['address'],
        $data['education'],
        $data['experience'],
        $data['certificates'],
        $data['awards'],
        $data['vitals'],
        $data['image'],
        $data['department_id'],
        $id
    ]);
}
function deleteDoctor($id) {
    global $pdo;
    $sql = "UPDATE doctors SET is_deleted=1 WHERE id=?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$id]);
}
function listDoctors() {
    global $pdo;
    $sql = "SELECT * FROM doctors WHERE is_deleted=0 ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
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