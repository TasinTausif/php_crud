<?php
define('DB_NAME', 'C:/Random/Mobin/HasinPractice/8.Crud/data/db.txt');

function seed()
{
    $data = array(
        array(
            'id' => 1,
            'fname' => 'Andrew',
            'lname' => 'NG',
            'roll' => '57'
        ),
        array(
            'id' => 2,
            'fname' => 'Sulaiman',
            'lname' => 'Shukhon',
            'roll' => '44'
        ),
        array(
            'id' => 3,
            'fname' => 'Jennifer',
            'lname' => 'Lopez',
            'roll' => '31'
        ),
        array(
            'id' => 4,
            'fname' => 'Amzad',
            'lname' => 'Khan',
            'roll' => '29'
        ),
        array(
            'id' => 5,
            'fname' => 'Fatema',
            'lname' => 'Amin',
            'roll' => '12'
        )
    );

    $serializeData = serialize($data);
    file_put_contents(DB_NAME, $serializeData, LOCK_EX);
}

function generateReport()
{
    $serializeData = file_get_contents(DB_NAME);
    $students = unserialize($serializeData);
?>
    <table>
        <tr>
            <th>Name</th>
            <th>Roll</th>
            <?php
            if (hasPrevilege()) :
            ?>
                <th width="25%">Action</th>
            <?php
            endif;
            ?>
        </tr>
        <?php
        foreach ($students as $student) {
        ?>
            <tr>
                <td><?php printf('%s %s', $student['fname'], $student['lname']); ?></td>
                <td><?php printf('%s', $student['roll']); ?></td>
                <?php
                if (isAdmin()) :
                ?>
                    <td><?php printf('<a href="index.php?task=edit&id=%s">Edit</a> | <a class="delete" href="index.php?task=delete&id=%s">Delete</a>', $student['id'], $student['id']); ?></td>
                <?php
                elseif (isEditor()) :
                ?>
                    <td><?php printf('<a href="index.php?task=edit&id=%s">Edit</a>', $student['id']); ?></td>
                <?php
                endif;
                ?>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}

function addStudent($fname, $lname, $roll)
{
    $serializeData = file_get_contents(DB_NAME);
    $students = unserialize($serializeData);

    //Checking duplicate roll
    $found = false;
    foreach ($students as $student) {
        if ($student['roll'] == $roll) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        $newId = getNewId($students);
        $student =
            array(
                'id' => $newId,
                'fname' => $fname,
                'lname' => $lname,
                'roll' => $roll
            );
        array_push($students, $student);

        $serializeData = serialize($students);
        file_put_contents(DB_NAME, $serializeData, LOCK_EX);
        return true;
    }
    return false;
}

//Function to edit data

function getStudent($id)
{
    $serializeData = file_get_contents(DB_NAME);
    $students = unserialize($serializeData);

    foreach ($students as $student) {
        if ($student['id'] == $id) {
            return $student;
        }
    }
    return false;
}

function updateStudent($id, $fname, $lname, $roll)
{
    $serializeData = file_get_contents(DB_NAME);
    $students = unserialize($serializeData);

    $found = false;
    foreach ($students as $stud) {
        if ($stud['roll'] == $roll && $stud['id'] != $id) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        $students[$id - 1]['fname'] = $fname;
        $students[$id - 1]['lname'] = $lname;
        $students[$id - 1]['roll'] = $roll;

        $serializeData = serialize($students);
        file_put_contents(DB_NAME, $serializeData, LOCK_EX);
        return true;
    }
    return false;
}

function deleteStudent($id)
{
    $serializeData = file_get_contents(DB_NAME);
    $students = unserialize($serializeData);

    foreach ($students as $offset => $student) {
        if ($student['id'] == $id) {
            unset($students[$offset]);
        }
    }

    $serializeData = serialize($students);
    file_put_contents(DB_NAME, $serializeData, LOCK_EX);
}

function getNewId($students)
{
    $maxId = max(array_column($students, 'id'));
    return $maxId + 1;
}

function isAdmin()
{
    return (isset($_SESSION['role'])) && 'admin' == $_SESSION['role'];
}

function isEditor()
{
    return (isset($_SESSION['role'])) && 'editor' == $_SESSION['role'];
}

function hasPrevilege()
{
    return (isAdmin() || isEditor());
}
