<?php
// Existing code...

// Query to count pending registrations
$query_pending_count = "SELECT COUNT(*) AS pending_count FROM pat_treatment WHERE status = 'menunggu'";
$result_pending_count = $mysqli->query($query_pending_count);
$row_pending_count = $result_pending_count->fetch_assoc();
$pending_count = $row_pending_count['pending_count'];
?>
<style>
    .badge-danger {
    font-size: 12px;
    line-height: 1;
    display: inline-block;
    min-width: 10px;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    padding: 5px;
    border-radius: 50%;
}

</style>

<div class="left-side-menu">

                <div class="slimscroll-menu">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <ul class="metismenu" id="side-menu">

                            <li class="menu-title">Navigation</li>

                            <li>
                                <a href="his_admin_dashboard.php">
                                    <i class="fe-airplay"></i>
                                    <span> Dashboard </span>
                                </a>
                                
                            </li>

                            <li>
                                <a href="his_admin_alur.php">
                                    <i class="fe-airplay"></i>
                                    <span> Data Alur </span>
                                </a>
                                
                            </li>
                            <!--<li>
                                <a href="javascript: void(0);">
                                    <i class="fab fa-accessible-icon "></i>
                                    <span> Pasien </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_register_patient.php">Register Pasien</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_view_patients.php">Lihat Pasien</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_patient.php">Kelola Pasien</a>
                                    </li>
                                    <hr>
                                    <li>
                                        <a href="his_admin_discharge_patient.php">Pemulangan Pasien</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_patient_transfer.php">Patient Transfers</a>
                                    </li>
                                </ul>
                            </li>-->

                            <li>
                                <a href="javascript: void(0);">
                                    <i class="mdi mdi-doctor"></i>
                                    <span> Dokter </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_add_employee.php">Tambahkan Dokter</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_view_employee.php">Lihat Dokter</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_employee.php">Kelola Dokter</a>
                                    </li>
                                    <hr>
                                    <li>
                                        <a href="his_admin_assaign_dept.php">Data Polik</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_transfer_employee.php">Transfer Polik</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);">
                                    <i class="mdi mdi-pill"></i>
                                    <span> Data Berobat </span>
                                    <?php if($pending_count > 0): ?>
                                        <span class="badge badge-danger float-right" style="background-color: red; color: white; border-radius: 50%; padding: 2px 8px;">
                                            <?php echo $pending_count; ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_manage_pharm_cat.php">Data Berobat Pasien</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_view_pharm_cat.php">Riwayat Pendaftaran</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_report.php">Laporan</a>
                                    </li>
                                </ul>
                            </li>

                            <!--<li>
                                <a href="javascript: void(0);">
                                    <i class="mdi mdi-cash-multiple "></i>
                                    <span> Accounting </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_add_acc.payable.php">Add Acc. Payable</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_acc_payable.php">Manage Acc. Payable</a>
                                    </li>
                                    <hr>
                                    <li>
                                        <a href="his_admin_add_acc_receivable.php">Add Acc. Receivable</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_acc_receivable.php">Manage Acc. Receivable</a>
                                    </li>
                                    <hr>
                                    
                                    
                                </ul>
                            </li>-->
                            <!--<li>
                                <a href="javascript: void(0);">
                                    <i class=" fas fa-funnel-dollar "></i>
                                    <span> Inventory </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                   
                                    <li>
                                        <a href="his_admin_pharm_inventory.php">Pharmaceuticals</a>
                                    </li>

                                    <li>
                                        <a href="his_admin_equipments_inventory.php">Assets</a>
                                    </li>
                                    
                                </ul>
                            </li>-->
                
                            <!--<li>
                                <a href="javascript: void(0);">
                                    <i class="fe-share"></i>
                                    <span> Laporan </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_inpatient_records.php">InPatient Records</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_outpatient_records.php">OutPatient Records</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_employee_records.php">Employee Records</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_pharmaceutical_records.php">Pharmaceutical Records</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_accounting_records.php">Accounting Records</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_medical_records.php">Medical Records</a>
                                    </li>
                                    
                                </ul>
                            </li>-->

                            <!--<li>
                                <a href="javascript: void(0);">
                                    <i class="fe-file-text"></i>
                                    <span> Rekam Medis </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_add_medical_record.php">Tambah Rekam Medis</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_medical_record.php">Kelola Data Rekam Medis</a>
                                    </li>
                                    
                                </ul>
                            </li>-->

                            <!--<li>
                                <a href="javascript: void(0);">
                                    <i class="mdi mdi-flask"></i>
                                    <span> Laboratory </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_patient_lab_test.php">Patient Lab Tests</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_patient_lab_result.php">Patient Lab Results</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_patient_lab_vitals.php">Patient Vitals</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_employee_lab_vitals.php">Employee Vitals</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_lab_report.php">Lab Reports</a>
                                    </li>
                                    <hr>
                                    <li>
                                        <a href="his_admin_add_lab_equipment.php">Add Lab Equipment</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_lab_equipment.php">Manage Lab Equipments</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);">
                                    <i class="mdi mdi-scissors-cutting "></i>
                                    <span> Surgical / Theatre </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_add_equipment.php">Add Equipment</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_equipment.php">Manage Equipments</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_add_theatre_patient.php">Add Patient</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_theatre_patient.php">Manage Patients</a>
                                    </li>

                                    <li>
                                        <a href="his_admin_surgery_records.php">Surgery Records</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);">
                                    <i class="mdi mdi-cash-refund "></i>
                                    <span> Payrolls </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_add_payroll.php">Add Payroll</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_payrolls.php">Manage Payrolls</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_generate_payrolls.php">Generate Payrolls</a>
                                    </li>
                                </ul>
                            </li>-->

                            <!--<li>
                                <a href="javascript: void(0);">
                                    <i class="fas fa-user-tag"></i>
                                    <span> Vendors </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_add_vendor.php">Add Vendor</a>
                                    </li>
                                    <li>
                                        <a href="his_admin_manage_vendor.php">Manage Vendors</a>
                                    </li>
                                    
                                </ul>
                            </li>-->
                          <!--<li>
                                <a href="javascript: void(0);">
                                    <i class="fas fa-lock"></i>
                                    <span> Password Resets </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li>
                                        <a href="his_admin_manage_password_resets.php">Manage</a>
                                    </li>
                                                                        
                                </ul>
                            </li>-->

                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>