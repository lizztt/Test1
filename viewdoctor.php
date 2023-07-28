<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "drug_dispensing";

// Create a new connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$recordsPerPage = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;


$totalRecordsQuery = "SELECT COUNT(*) AS total FROM doctors";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];


$totalPages = ceil($totalRecords / $recordsPerPage);


$sql = "SELECT * FROM doctors LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            color: orange;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid orange;
        }

        th, td {
            border: 1px solid orange;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: orange;
            color: black;
        }

        td form {
            display: inline-block;
            margin: 0;
        }

        td form input[type="submit"] {
            background-color: orange;
            color: black;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        td form input[type="submit"]:hover {
            background-color: #FF8C00;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            color: orange;
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color 0.3s;
            border-radius: 4px;
            margin-right: 5px;
        }

        .pagination a.active {
            background-color: orange;
            color: black;
        }

        .pagination a:hover:not(.active) {
            background-color: #FF8C00;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>SSN</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['SSN']; ?></td>
                <td><?php echo $row['Fname']; ?></td>
                <td><?php echo $row['Lname']; ?></td>
                <td>
                    <form action="editdoctor.php" method="POST">
                        <input type="hidden" name="SSN" value="<?php echo $row['SSN']; ?>">
                        <input type="submit" value="Edit">
                    </form>
                    <form action="deleteuser.php" method="POST">
                        <input type="hidden" name="SSN" value="<?php echo $row['SSN']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo ($page - 1); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <a href="?page=<?php echo $i; ?>" class="active"><?php echo $i; ?></a>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo ($page + 1); ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
