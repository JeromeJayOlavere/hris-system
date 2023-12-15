<?php
session_start();
@include_once("connection.php");
@include_once("../Components/PopupAlert.php");

// prevent user from accessing the page without logging in
/*if (!isset($_SESSION['DatahasbeenFetched'])) {
    header("Location: ../Login.php");
} else {
    $ShowAlert = true;
    $_SESSION['isUpdated'] = 'false';
}*/

$sql = "SELECT COUNT(bpNo), COUNT(fname), COUNT(lname) FROM emp_table;";
$result = $conn->query($sql);

if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_NUM);
    $TotalTrainee = $row[0];
    $Deployed = $row[1];
    $Completed = $row[2];
}

function Program($column) {
    global $conn;
    $sql = "SELECT COUNT($column) FROM lnd_table";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_NUM);
        return $row[0];
    } else {
        return 0;
    }
}

function studentassign($columnID)
{
    global $conn;
    $sql = "SELECT COUNT(empNo) FROM lnd_table WHERE title = '$columnID'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}

//gender chart
function maleChart() {
    global $conn;
    $sql = "SELECT COUNT(bpNo) FROM emp_table WHERE sex = 'Male'";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_NUM);
        return $row[0];
    } else {
        return 0;
    }
}

function femaleChart() {
    global $conn;
    $sql = "SELECT COUNT(bpNo) FROM emp_table WHERE sex = 'Female'";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_NUM);
        return $row[0];
    } else {
        return 0;
    }
}

// for Formating the output of the number of gender
function formatNumberWithAbbreviation($number)
{
    $originalNumber = $number;
    $abbreviations = array(
        'K',
        'M',
        'B',
        'T',
    );
    $abbreviation = '';
    $index = 0;

    while ($number >= 1000 && $index < count($abbreviations)) {
        $number /= 1000;
        $abbreviation = $abbreviations[$index];
        $index++;
    }

    if ($abbreviation !== '') {
        $formattedNumber = number_format($number, 0) . $abbreviation;
        $originalFormatted = number_format($originalNumber);
    } else {
        $formattedNumber = number_format($number);
        $originalFormatted = number_format($originalNumber);
    }

    return array('formatted' => $formattedNumber, 'originalFormatted' => $originalFormatted);
}

$male = maleChart();
$female = femaleChart();

$maleFormattedData = formatNumberWithAbbreviation($male);
$femaleFormattedData = formatNumberWithAbbreviation($female);

$maleFormatted = $maleFormattedData['formatted'];
$maletitle = $maleFormattedData['originalFormatted'];

$femaleFormatted = $femaleFormattedData['formatted'];
$femaletitle = $femaleFormattedData['originalFormatted'];




function MonthlyChart($month)
{
    global $conn;
    $sql = "SELECT COUNT(empNo) FROM lnd_table WHERE MONTH(lndFrom) = $month";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}

?>
<!DOCTYPE html>
<html lang="en, fil">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../Style/ImportantImport.css">
    <script src="../Script/SidebarScript.js"></script>
    <script src="../Script/SweetAlert2.js"></script>
    <script src="../Script/chart.js"></script>
    <script src="../Script/DashTables_T.js"></script>
    <script src="../Script/DashTables_P.js"></script>
    <script src="../Script/DashTables_E.js"></script>
    <title>Admin Dashboard</title>
</head>

<body class="adminuser user-select-none" style="min-width: 1080px;">
    <?php
    @include_once '../Components/AdminSidebar.php';
    if (isset($ShowAlert)) {
        echo NewAlertBox();
        $_SESSION['Show'] = false;
    }
    ?>
    <section class="home">
        <div class="text">
            <h1 class="text-success">Dashboard</h1>
        </div>
        <div class="container-fluid" style="width: 98%;">
            <div class="row row-cols-1 row-cols-md-4 g-4">
                <div class="col">
                    <div class="card h-100"
                        style="background: linear-gradient(to right, #2a9134 1%,#3fa34d 53%,#2a9134 100%)">
                        <div class="card-body text-light">
                            <h5 class="card-title text-uppercase d-block text-truncate">Total Trainee's</h5>
                            <h1 class="card-text text-center fw-bold">
                                <?php echo $TotalTrainee; ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100" style="background: linear-gradient(to right, #8699fb 0%,#8340f6 100%);">
                        <div class="card-body text-light">
                            <h5 class="card-title text-uppercase d-block text-truncate">Deployed</h5>
                            <h1 class="card-text text-center fw-bold">
                                <?php echo $Deployed; ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100" style="background: linear-gradient(to right, #fc7588 0%,#e71c54 100%);">
                        <div class="card-body text-light">
                            <h5 class="card-title text-uppercase d-block text-truncate">Completed</h5>
                            <h1 class="card-text text-center fw-bold">
                                <?php echo $Completed; ?>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100" style="background: linear-gradient(to right, #668bff 0%,#104dfd 100%);">
                        <div class="card-body text-light">
                            <h5 class="card-title text-uppercase d-block text-truncate">Total Vaccinated</h5>
                            <h1 class="card-text text-center fw-bold">
                                <?php echo Program('title'); ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <style>
                .cardimg {
                    background-image: url("../Image/ProfBG.svg");
                    background-repeat: no-repeat;
                    background-size: cover;
                    background-position: center;
                }

                .blurback {
                    backdrop-filter: blur(10px);
                }
            </style>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    <div class="card h-100 cardimg border border-1 border-success" style="min-width: 380px;">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase d-block text-truncate">Gender</h5>
                            <canvas id="gender"
                                title="There are <?php echo $maleFormatted; ?> Male's, and <?php echo $femaleFormatted ?> Female's in the system."
                                style="cursor: pointer;"></canvas>
                            <div class=" text-center" hidden>
                                <div
                                    class="d-flex justify-content-evenly mt-2 text-center text-light rounded blurback shadow-lg border border-1 border-success bg-transparent">
                                    <p class="fs-5 fw-bold mt-3 p-0 text-dark " title="<?php echo $maletitle; ?>">
                                        <span class="text-uppercase" style="/*color: #059bff;*/">
                                            Male: </span>
                                        <?php echo $maleFormatted; ?>
                                    </p>
                                    <p class="fs-6 mt-3 p-0" title="<?php echo $femaletitle; ?>">
                                        <span class="text-uppercase" style="color: #ff3d67;">
                                            Female: </span>
                                        <?php echo $femaleFormatted; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 cardimg border border-1 border-success" style="min-width: 380px;">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase d-block text-truncate">Monthly Registered Trainee's
                            </h5>
                            <canvas id="Monthly"></canvas>
                            <?php @include_once '../Components/Chart/MonthlyChart.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-4 mb-4" style="background-color: white; height: 5px; border-radius: 5px;">

<div class="container-lg table-responsive-lg">
    <div class="container mt-5 text-bg-light rounded border border-1 border-success"
        style="min-width: fit-content;">
        <table class="table table-hover align-middle caption-top" id="TraineeTable">
            <caption>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <!-- In the future, I will add a Category Search -->
                                <span class="input-group-text user-select-none"
                                    title="You can search only by name">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20"
                                        viewBox="0 -960 960 960" width="20" fill="#3ea34c">
                                        <path
                                            d="M382.122-330.5q-102.187 0-173.861-71.674Q136.587-473.848 136.587-576q0-102.152 71.674-173.826Q279.935-821.5 382.087-821.5q102.152 0 173.826 71.674 71.674 71.674 71.674 173.861 0 40.859-12.022 76.292-12.021 35.434-33.065 64.956l212.087 212.326q12.674 12.913 12.674 28.945 0 16.033-12.913 28.707-12.674 12.674-29.326 12.674t-29.326-12.674L523.848-375.587q-29.761 21.044-65.434 33.065-35.672 12.022-76.292 12.022Zm-.035-83q67.848 0 115.174-47.326Q544.587-508.152 544.587-576q0-67.848-47.326-115.174Q449.935-738.5 382.087-738.5q-67.848 0-115.174 47.326Q219.587-643.848 219.587-576q0 67.848 47.326 115.174Q314.239-413.5 382.087-413.5Z" />
                                    </svg>
                                </span>
                                <input type="search" class="form-control form-control-sm"
                                    placeholder="Search by Name" id="TraineeSearchBar">
                                <a href="../Admin/AdminTrainees.php" class="btn btn-outline-success">Show
                                    more</a>
                            </div>
                        </div>
                        <div class="col-4">
                            <!-- piginations -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination pagination-sm">
                                    <li class="page-item">
                                        <a class="page-link user-select-none" id="TraineePrevious"
                                            style="cursor: pointer;">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item m-1"><small
                                            class="text-success text-center mx-1">Showing <span
                                                id="TraineeCurrentPage"></span> to <span
                                                id="TraineeTotalPage"></span> of
                                            <span id="TraineeTotalItem"></span> entries</small>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link user-select-none" id="TraineeNext"
                                            style="cursor: pointer;">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-center">
                                List of Trainee's in the System
                            </div>
                        </div>
                    </div>
                </div>
            </caption>
            <thead>
                <tr>
                    <th scope="col" title="BP NO">bpNo</th>
                    <th scope="col" title="Full Name">Full Name </th>
                    <th scope="col">Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM emp_table WHERE emp_status = 'Active' ORDER BY fname ASC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {

                        if ($row['sex'] == null) {
                            $GEN = 'UNK';
                        } else {
                            $GEN = strtoupper($row['sex']);
                        }

                        echo '<tr>
                        <td class="text-truncate" style="max-width: 100px;">' . $row['bpNo'] . '</td>                                 
                        <td class="text-truncate" style="max-width: 100px;" title="' . $row['fname'] . '">' . $row['lname'] . '</td>
                        <td class="text-truncate" style="max-width: 100px;">' . $row['sex'] . '</td>   
                        </tr>';
                        $i++;
                    }
                } else {
                    echo '<tr>
                    <th colspan="10" class="text-center">No Trainee\'s in the System</th>
                </tr>';
                }
                ?>
            </tbody>
            <tfoot id="TnoResult">
                <tr>
                    <th colspan="10" class="text-center"><span class="text-secondary">No Result</span>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<hr class="mt-4 mb-4" style="background-color: white; height: 5px; border-radius: 5px;">

<div class="container-lg table-responsive-lg">
    <div class="container mt-5 text-bg-light rounded border border-1 border-success"
        style="min-width: fit-content;">
        <table class="table table-hover align-middle caption-top" id="EveTable">
            <caption>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <!-- In the future, I will add a Category Search -->
                                <span class="input-group-text user-select-none"
                                    title="You can search only by name">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20"
                                        viewBox="0 -960 960 960" width="20" fill="#3ea34c">
                                        <path
                                            d="M382.122-330.5q-102.187 0-173.861-71.674Q136.587-473.848 136.587-576q0-102.152 71.674-173.826Q279.935-821.5 382.087-821.5q102.152 0 173.826 71.674 71.674 71.674 71.674 173.861 0 40.859-12.022 76.292-12.021 35.434-33.065 64.956l212.087 212.326q12.674 12.913 12.674 28.945 0 16.033-12.913 28.707-12.674 12.674-29.326 12.674t-29.326-12.674L523.848-375.587q-29.761 21.044-65.434 33.065-35.672 12.022-76.292 12.022Zm-.035-83q67.848 0 115.174-47.326Q544.587-508.152 544.587-576q0-67.848-47.326-115.174Q449.935-738.5 382.087-738.5q-67.848 0-115.174 47.326Q219.587-643.848 219.587-576q0 67.848 47.326 115.174Q314.239-413.5 382.087-413.5Z" />
                                    </svg>
                                </span>
                                <input type="search" class="form-control form-control-sm"
                                    placeholder="Search by Name" id="EveSearchBar">
                                <a href="../Admin/AdminEvents.php" class="btn btn-outline-success">Show
                                    more</a>
                            </div>
                        </div>
                        <div class="col-4">
                            <!-- piginations -->
                            <nav aria-label="Page navigation example">
                                <ul class="pagination pagination-sm">
                                    <li class="page-item">
                                        <a class="page-link user-select-none" id="EvePrevious"
                                            style="cursor: pointer;">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item m-1"><small
                                            class="text-success text-center mx-1">Showing <span
                                                id="EveCurrentPage"></span> to <span
                                                id="EveTotalPage"></span> of
                                            <span id="EveTotalItem"></span> entries</small>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link user-select-none" id="EveNext"
                                            style="cursor: pointer;">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-center">
                                List of Event's in the System
                            </div>
                        </div>
                    </div>
                </div>
            </caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" title="Title of the Event">Employee No.</th>
                    <th scope="col" title="Description of the Event">Title</th>
                    <th scope="col" title="Date of the Event">Type</th>
                    <th scope="col" title="Event type">Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM lnd_table ORDER BY lndFrom DESC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {

                        $date = date("M d, Y", strtotime($row['lndFrom']));
                        $enddate = date("M d, Y", strtotime($row['lndTo']));

                        if ($row['lndTo'] == 'true') {
                            $Ended = '<span class="text-secondary">Ended</span>';
                        } else {
                            $Ended = '<span class="text-primary">Ongoing</span>';
                        }

                        echo '<tr>
                        <th scope="row">' . $i . '</th>
                        <td class="text-truncate" style="max-width: 100px;">' . $row['empNo'] . '</td>
                        <td class="text-truncate" style="max-width: 100px;">' . $row['title'] . '</td>
                        <td class="text-truncate" style="max-width: 100px;">' . $row['type'] . '</td>
                        <td class="text-truncate" style="max-width: 100px;" title="' . $date . ' - ' . $enddate . '">' . $date . ' - ' . $enddate . '</td>
                        ';
                        $i++;
                    }
                } else {
                    echo '<tr>
                    <th colspan="10" class="text-center">NO EVENTS FOUND</th>
                </tr>';
                }
                ?>
            </tbody>
            <tfoot id="EnoResult">
                <tr>
                    <th colspan="10" class="text-center"><span class="text-secondary">No Result</span>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

            

            <hr class="mt-4 mb-4" style="background-color: white; height: 5px; border-radius: 5px;">

    </section>
    <script src="../Script/Bootstrap_Script/bootstrap.js"></script>
</body>

</html>