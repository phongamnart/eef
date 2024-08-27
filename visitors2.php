<?php
include("_check_session.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $visitors = 2;
    $ismenu = 4;
    $current_menu = "visitors_admin";
    $condition2 = "";
    $condition = "";
    include_once('_head.php');
    $conDB = new db_conn();
    $visitors_type = isset($_SESSION['visitors_type']) ? $_SESSION['visitors_type'] : '';
    $visitors_status = isset($_SESSION['visitors_status']) ? $_SESSION['visitors_status'] : '';
    $visitors_register_status = isset($_SESSION['visitors_register_status']) ? $_SESSION['visitors_register_status'] : '';

    if ($visitors_register_status != "") {
        if ($visitors_register_status == "1") {
            $condition2 = " AND `register_date` IS NOT NULL";
        } else {
            $condition2 = " AND `register_date` IS NULL";
        }
    } else {
        $condition2 .= "";
    }
    $condition .= $condition2;
    if ($visitors_type != "") {
        $condition2 .= " AND `type` = '" . $visitors_type . "'";
        $condition .= $condition2;
    } else {
        $condition .= "";
    }
    if ($visitors_status != "") {
        $condition2 .= " AND `status` = '" . $visitors_status . "'";
        $condition .= $condition2;
    } else {
        $condition .= "";
    }

    $strSQLType = "SELECT DISTINCT `type` FROM `visitors` WHERE `enable` = 1";
    $objQuery = $conDB->sqlQuery($strSQLType);
    while ($objResult = mysqli_fetch_assoc($objQuery)) {
        $types[] = $objResult['type'];
    }
    $strSQL = "SELECT * FROM `visitors` WHERE `enable` = 1" . $condition;
    $_SESSION['excel_visitors']  = $strSQL;
    $objQuery = $conDB->sqlQuery($strSQL);
    ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include_once('_navbar.php'); ?>
        <?php include_once('_menu.php'); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>ผู้เข้าร่วมงาน (Admin)</h1>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <button type="button" class="btn btn-app flat" data-toggle="modal" data-target="#createvisitorModal"
                    title="เพิ่มข้อมูล">
                    <img src="dist/img/icon/add.svg" style="padding:3px;" width="24"><br>
                    เพิ่มข้อมูล
                </button>
                <button type="button" class="btn btn-app flat" onclick="window.location.href='excel_visitors.php'"
                    title="ส่งออกข้อมูล">
                    <img src="dist/img/icon/excel.png" style="padding:1px;" width="24"><br>
                    ส่งออกข้อมูล
                </button>
                <button type="button" class="btn btn-app flat" onclick="" title="พิมพ์ Badge ชุด" data-toggle="modal" data-target="#printModal">
                    <img src="dist/img/icon/print.svg" style="padding:1px;" width="24"><br>
                    พิมพ์ Badge ชุด
                </button>
                <button type="button" class="btn btn-app flat" onclick="" title="ส่งใบประกาศ" data-toggle="modal" data-target="#certModal">
                    <img src="dist/img/icon/award.png" style="padding:1px;" width="24"><br>
                    ส่งใบประกาศ
                </button>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>กลุ่มการลงทะเบียน <em></em></label>
                                                <select class="custom-select" style="width: 100%;"
                                                    onchange="setFilter('visitors_type',this.value)">
                                                    <option value="">แสดงทั้งหมด</option>
                                                    <?php
                                                    foreach ($types as $type) {
                                                        $selected = ($visitors_type == $type) ? 'selected="selected"' : '';
                                                        echo "<option value=\"" . htmlspecialchars($type) . "\" $selected>" . htmlspecialchars($type) . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>สถานะการเช็คอิน<em></em></label>
                                                <select class="custom-select" style="width: 100%;"
                                                    onchange="setFilter('visitors_status',this.value)">
                                                    <option value=""
                                                        <?php if ($visitors_status == "") {
                                                            echo 'selected="selected"';
                                                        }; ?>>
                                                        แสดงทั้งหมด</option>
                                                    <option value="0"
                                                        <?php if ($visitors_status == "0") {
                                                            echo 'selected="selected"';
                                                        }; ?>>
                                                        ยังไม่เช็คอิน</option>
                                                    <option value="1"
                                                        <?php if ($visitors_status == "1") {
                                                            echo 'selected="selected"';
                                                        }; ?>>
                                                        เช็คอินแล้ว</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>สถานะ<em></em></label>
                                                <select class="custom-select" style="width: 100%;"
                                                    onchange="setFilter('visitors_register_status',this.value)">
                                                    <option value=""
                                                        <?php if ($visitors_register_status == "") {
                                                            echo 'selected="selected"';
                                                        }; ?>>
                                                        แสดงทั้งหมด</option>
                                                    <option value="0"
                                                        <?php if ($visitors_register_status == "0") {
                                                            echo 'selected="selected"';
                                                        }; ?>>
                                                        รอลงทะเบียน</option>
                                                    <option value="1"
                                                        <?php if ($visitors_register_status == "1") {
                                                            echo 'selected="selected"';
                                                        }; ?>>
                                                        ลงทะเบียนแล้ว</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="datatable" class="table table-bordered table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="50">ไอดี<br><em></em></th>
                                                <th width="100">เครื่องมือ<br><em></em></th>
                                                <th width="100">รหัสลงทะเบียน<br><em></em></th>
                                                <th width="150">ชื่อ-สกุล<br><em></em></th>
                                                <th width="100" align="center">สถานะ<br><em></em></th>
                                                <th width="100" align="center">สถานะลงทะเบียน<br><em></em></th>
                                                <th width="150">เบอร์โทร<br><em></em></th>
                                                <th width="150">อีเมล<br><em></em></th>
                                                <th width="150">หน่วยงาน<br><em></em></th>
                                                <th width="150">กลุ่มการลงทะเบียน<br><em></em></th>
                                                <th width="50">จำนวนการส่งอีเมล<br><em></em></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $index = 1;
                                            while ($objResult = mysqli_fetch_assoc($objQuery)) {
                                                if ($objResult['status'] == '0') {
                                                    $status = "<span class=\"text-danger\">ยังไม่เช็คอิน</span>";
                                                } else {
                                                    $status = "<span class=\"text-success\">เช็คอินแล้ว</span>";
                                                }
                                                if ($objResult['register_date'] == '') {
                                                    $status2 = "<span class=\"text-danger\">รอลงทะเบียน</span>";
                                                } else {
                                                    $status2 = "<span class=\"text-success\">ลงทะเบียนแล้ว</span>";
                                                }
                                            ?>
                                                <tr
                                                    onDblClick="window.location.href='visitors_edit2.php?no=<?php echo md5($objResult['id']); ?>'">
                                                    <td><?php echo $objResult['id']; ?></td>
                                                    <td align="center">
                                                        <img src="dist/img/icon/edit.svg"
                                                            onclick="window.location.href='visitors_edit2.php?no=<?php echo md5($objResult['id']); ?>'"
                                                            title="แก้ไข" width="25"
                                                            style="padding-right: 10px;cursor: pointer;" />
                                                        <img src="dist/img/icon/delete.png"
                                                            onclick="postDelete('visitors', '<?php echo md5($objResult['id']); ?>', '<?php echo $objResult['name']; ?>', '')"
                                                            title="ลบ" width="25"
                                                            style="padding-right: 10px;cursor: pointer;" />
                                                        <img src="dist/img/icon/print.svg"
                                                            onclick="window.open('visitors_badge.php?no=<?php echo md5($objResult['id']); ?>', '_blank');"
                                                            title="พิมพ์ Badge" width="25"
                                                            style="padding-right: 10px;cursor: pointer;" />
                                                        <img src="dist/img/icon/award.png"
                                                            onclick="sendMail('<?php echo md5($objResult['id']); ?>')"
                                                            title="ใบประกาศ" width="25"
                                                            style="padding-right: 10px;cursor: pointer;" />
                                                    </td>
                                                    <td><?php echo $objResult['type'] . $objResult['code'] ?></td>
                                                    <td><?php echo $objResult['prefix'] . " " . $objResult['name'] . " " . $objResult['last_name']; ?>
                                                    </td>
                                                    <td align="center"><?php echo $status; ?></td>
                                                    <td align="center"><?php echo $status2; ?></td>
                                                    <td><?php echo $objResult['phone'] ?></td>
                                                    <td><?php echo $objResult['email'] ?></td>
                                                    <td><?php echo $objResult['company'] ?></td>
                                                    <td><?php echo $objResult['type'] ?></td>
                                                    <td><?php echo $objResult['count_email'] ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <div id="createvisitorModal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">เพิ่มผู้เข้าร่วมงาน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form method="post" id="form_createvisitor" class="row">
                            <input type="hidden" name="table" value="visitors" />
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>ชื่อ-สกุล</label>
                                    <input type="text" class="form-control" name="name" required />
                                </div>
                            </div>
                            <div class="col-sm-12 text-danger">
                                <div class="form-group">
                                    <span id="error_form_createvisitor"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">ตกลง</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="printModal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">กำหนดจำนวนการพิมม์ Badge</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form method="post" id="form_print" class="row">
                            <input type="hidden" name="table" value="visitors" />
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>ลำดับที่เริ่ม</label>
                                    <input type="text" class="form-control" name="start" id="start" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>จำนวนที่จะพิมพ์</label>
                                    <input type="text" class="form-control" name="limit" id="limit" required />
                                </div>
                            </div>
                            <div class="col-sm-12 text-danger">
                                <div class="form-group">
                                    <span>**ไม่ควรเลือกจำนวนมากๆอาจจะทำให้เกิดปัญหา</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-primary" onclick="printBadge()">ตกลง</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="certModal" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"
            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">กำหนดจำนวนการส่งใบประกาศ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form action="sendmail_range.php" method="post" id="form_cert" class="row">
                            <input type="hidden" name="table" value="visitors" />
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>ลำดับที่</label>
                                    <input type="text" class="form-control" name="start_id" id="start_id" required />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>ถึงลำดับที่</label>
                                    <input type="text" class="form-control" name="end_id" id="end_id" required />
                                </div>
                            </div>
                            <div class="col-sm-12 text-danger">
                                <div class="form-group">
                                    <span>**ไม่ควรเลือกจำนวนมากๆอาจจะทำให้เกิดปัญหา</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-primary" onclick="sendMailRange()">ตกลง</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="spinnerModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">กำลังส่งอีเมล...</span>
                        </div>
                        <div style="margin-top: 10px;">กำลังส่งอีเมล...</div>
                    </div>
                </div>
            </div>
        </div>


        <?php include_once('_footer.php'); ?>
    </div>
    <!-- ./wrapper -->
    <?php include_once('_script.php'); ?>
    <script>

        function sendMail(visitor_id) {
            $('#spinnerModal').modal('show');

            var formData = new FormData();
            formData.append('visitor_id', visitor_id);

            fetch('sendmail.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    $('#spinnerModal').modal('hide');

                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert("เกิดข้อผิดพลาดในการส่งอีเมล");
                    }
                })
                .catch(error => {
                    $('#spinnerModal').modal('hide');
                    console.error('There was a problem with the fetch operation:', error);
                    alert("เกิดข้อผิดพลาดในการเชื่อมต่อหรือข้อมูลที่ได้รับไม่ถูกต้อง");
                });
        }

        function sendMailRange() {
            var start_id = $("#start_id").val();
            var end_id = $("#end_id").val();

            $('#certModal').modal('hide');
            $('#spinnerModal').modal('show');

            var formData = new FormData();
            formData.append('start_id', start_id);
            formData.append('end_id', end_id);

            fetch('sendmail_range.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    $('#spinnerModal').modal('hide');

                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert("เกิดข้อผิดพลาดในการส่งอีเมล");
                    }
                })
                .catch(error => {
                    $('#spinnerModal').modal('hide');
                    console.error('There was a problem with the fetch operation:', error);
                    alert("เกิดข้อผิดพลาดในการเชื่อมต่อหรือข้อมูลที่ได้รับไม่ถูกต้อง");
                });
        }

        function printBadge() {
            var start = $("#start").val();
            var limit = $("#limit").val();
            window.open('print_badge.php?start=' + start + '&limit=' + limit, '_blank');
        }
        setTimeout(function() {
                $('#datatable').DataTable({
                    "stateSave": true,
                    "paging": true,
                    "responsive": true,
                    "lengthChange": true,
                    "searching": true,
                    "autoWidth": true,
                    "ordering": true,
                    "info": true,
                });
            },
            500);
        $("#form_createvisitor").on("submit", function(e) {
            e.preventDefault();
            postCreate("form_createvisitor");
        });
    </script>

</html>