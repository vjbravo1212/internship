<?php
$host = 'localhost';
$db = 'company';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle CRUD operations
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['create_employee'])) {
            $stmt = $pdo->prepare("INSERT INTO employees (name, gender, dob, phone, email, address, pin, state, remark) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['name'], $_POST['gender'], $_POST['dob'], $_POST['phone'], $_POST['email'], $_POST['address'], $_POST['pin'], $_POST['state'], $_POST['remark']]);
        } elseif (isset($_POST['create_dependent'])) {
            $stmt = $pdo->prepare("INSERT INTO dependents (employee_id, name, relation, age, gender, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['employee_id'], $_POST['dep_name'], $_POST['relation'], $_POST['age'], $_POST['dep_gender'], $_POST['dep_phone']]);
        } elseif (isset($_POST['delete_employee'])) {
            $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
            $stmt->execute([$_POST['id']]);
        } elseif (isset($_POST['delete_dependent'])) {
            $stmt = $pdo->prepare("DELETE FROM dependents WHERE id = ?");
            $stmt->execute([$_POST['dep_id']]);
        }
    }

    // Fetch all employees and dependents
    $employees = $pdo->query("SELECT * FROM employees")->fetchAll(PDO::FETCH_ASSOC);
    $dependents = $pdo->query("SELECT * FROM dependents")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee and Dependents Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Employee and Dependents Management</h1>

    <!-- Employee Form -->
    <h2 class="mt-4">Add New Employee</h2>
    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="col-md-4">
            <label for="gender" class="form-label">Gender</label>
            <select id="gender" name="gender" class="form-select" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="col-md-4">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="col-md-4">
            <label for="pin" class="form-label">PIN</label>
            <input type="text" class="form-control" id="pin" name="pin" required>
        </div>
        <div class="col-md-4">
            <label for="state" class="form-label">State</label>
            <input type="text" class="form-control" id="state" name="state" required>
        </div>
        <div class="col-md-4">
            <label for="remark" class="form-label">Remark</label>
            <input type="text" class="form-control" id="remark" name="remark">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary" name="create_employee">Add Employee</button>
        </div>
    </form>

    <!-- Dependent Form -->
    <h2 class="mt-5">Add New Dependent</h2>
    <form method="POST" class="row g-3">
        <div class="col-md-4">
            <label for="employee_id" class="form-label">Employee</label>
            <select id="employee_id" name="employee_id" class="form-select" required>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['id'] ?>"><?= $employee['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="dep_name" class="form-label">Dependent Name</label>
            <input type="text" class="form-control" id="dep_name" name="dep_name" required>
        </div>
        <div class="col-md-4">
            <label for="relation" class="form-label">Relation</label>
            <input type="text" class="form-control" id="relation" name="relation" required>
        </div>
        <div class="col-md-4">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" name="age" required>
        </div>
        <div class="col-md-4">
            <label for="dep_gender" class="form-label">Gender</label>
            <select id="dep_gender" name="dep_gender" class="form-select" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="dep_phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="dep_phone" name="dep_phone">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary" name="create_dependent">Add Dependent</button>
        </div>
    </form>

    <!-- Employee Table -->
    <h2 class="mt-5">Employees List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>PIN</th>
                <th>State</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= $employee['id'] ?></td>
                    <td><?= $employee['name'] ?></td>
                    <td><?= $employee['gender'] ?></td>
                    <td><?= $employee['dob'] ?></td>
                    <td><?= $employee['phone'] ?></td>
                    <td><?= $employee['email'] ?></td>
                    <td><?= $employee['address'] ?></td>
                    <td><?= $employee['pin'] ?></td>
                    <td><?= $employee['state'] ?></td>
                    <td><?= $employee['remark'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $employee['id'] ?>">
                            <button type="submit" name="delete_employee" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Dependents Table -->
    <h2 class="mt-5">Dependents List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>Dependent Name</th>
                <th>Relation</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dependents as $dependent): ?>
                <tr>
                    <td><?= $dependent['id'] ?></td>
                    <td><?= $dependent['employee_id'] ?></td>
                    <td><?= $dependent['name'] ?></td>
                    <td><?= $dependent['relation'] ?></td>
                    <td><?= $dependent['age'] ?></td>
                    <td><?= $dependent['gender'] ?></td>
                    <td><?= $dependent['phone'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="dep_id" value="<?= $dependent['id'] ?>">
                            <button type="submit" name="delete_dependent" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
